<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Analytics\Facades\Analytics;
use Spatie\Analytics\Period;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

use Google\Auth\Credentials\ServiceAccountCredentials;

class GoogleAnalyticsController extends Controller
{

    // Add this method to your AnalyticsController
    protected function setupAnalytics()
    {
        try {
            $propertyId = config('app.property_id');

            $this->setAnalyticsProperty($propertyId);

            return $propertyId;
        } catch (\Exception $e) {
            \Log::error('Error setting up analytics: ' . $e->getMessage());
        }
    }

    public function getindex(Request $request = null)
    {
        $request = $request ?? request();

        /**
         * 2️⃣ GA PROPERTY SETUP
         */
        $propertyId = config('app.property_id');

        $dateRangeParam = $request->get('date_range', 'today');
        $customDate = $request->get('date', '');

        if ($dateRangeParam === 'custom_range' && $customDate) {
            $period = $this->getPeriodFromRange($customDate);
            $selectedRange = $customDate;
            $dateRange = 'custom_range';
        } else {
            $period = $this->getPeriodFromRange($dateRangeParam);
            $selectedRange = $this->getRangeDisplayText($dateRangeParam);
            $dateRange = $dateRangeParam;
        }

        try {
            $user = $this->setAnalyticsProperty($propertyId);
            $totaluniqueVisitorsData = Analytics::get($period, ['activeUsers']);
        } catch (\Exception $e) {
            return view('admin.googleanalytics.googleAnalytics', [
                'selectedRange'  => 'Today',
                'dateRange'      => 'today',
                'currentVisitors' => 0,
                'error'          => 'Google Analytics Configuration Error: ' . $e->getMessage()
            ]);
        }

        $currentVisitors = 0;
        try {
            $rt = $this->getRealtimeStats($propertyId);
            $currentVisitors = $rt['active_users_now'];
        } catch (\Exception $e) {
            return view('admin.googleanalytics.googleAnalytics', [
                'selectedRange'  => $selectedRange,
                'dateRange'      => $dateRange,
                'currentVisitors' => 0,
                'user' => $user,
                'error'          => $e->getMessage()

            ]);
        }

        return view('admin.googleanalytics.googleAnalytics', compact(
            'selectedRange',
            'dateRange',
            'currentVisitors'
        ));
    }

    private function getRangeDisplayText($range)
    {
        $rangeTexts = [
            'today' => 'Today',
            'yesterday' => 'Yesterday',
            'realtime' => 'Realtime',
            'last_7_days' => 'Last 7 days',
            'last_28_days' => 'Last 28 days',
            'last_91_days' => 'Last 90 Days',
            'month_to_date' => 'This Month',
            'last_month' => 'Last Month',
            'year_to_date' => 'This Year',
            'last_12_months' => 'Last 12 months',
            'all_time' => 'All Time',
            'custom' => 'Custom Range'
        ];

        return $rangeTexts[$range] ?? 'Today';
    }


    public function getChannels(Request $request)
    {
        try {
            $websiteId = $request->get('website_id');
            $propertyId = config('app.property_id');
            $propertyId = $this->setupAnalytics($propertyId);
            $dateRange = $request->get('date_range', 'today');
            $customrange = $request->get('date');

            $period = ($dateRange === 'custom_range' && $customrange)
                ? $this->getPeriodFromRange($customrange)
                : $this->getPeriodFromRange($dateRange);

            $filters = json_decode($request->filters ?? '{}', true);
            $hasFilters = !empty($filters) && count($filters) > 0;

            // Use only sessionDefaultChannelGroup for base channels
            $dimensions = $hasFilters ? [
                'sessionDefaultChannelGroup',
                'sessionSource',
                'country',
                'browser',
                'operatingSystem',
                'screenResolution',
                'region',
                'city'
            ] : ['sessionDefaultChannelGroup'];

            $metrics = ['sessions', 'screenPageViews']; // Use sessions to match GA4

            // Fetch Analytics
            $response = Analytics::get($period, $metrics, $dimensions);

            if ($hasFilters) {
                $response = $this->applyDetailedFilters($response, $filters);
            }

            // Group sessions by channel
            $grouped = [];
            foreach ($response as $row) {
                $channel = $row['sessionDefaultChannelGroup'] ?? '(not set)';
                $grouped[$channel]['sessions'] = ($grouped[$channel]['sessions'] ?? 0) + ($row['sessions'] ?? 0);
                $grouped[$channel]['pageViews'] = ($grouped[$channel]['pageViews'] ?? 0) + ($row['screenPageViews'] ?? 0);
            }

            $totalSessions = array_sum(array_column($grouped, 'sessions'));

            $channels = [];
            foreach ($grouped as $name => $data) {
                $sessions = $data['sessions'] ?? 0;
                $pageViews = $data['pageViews'] ?? 0;
                $percentage = $totalSessions > 0 ? round(($sessions / $totalSessions) * 100, 1) : 0;

                $channels[] = [
                    'name' => $name,
                    'sessions' => $sessions,
                    'pageViews' => $pageViews,
                    'percentage' => $percentage . '%',
                    'value' => $percentage
                ];
            }

            // Sort by sessions descending
            $channels = collect($channels)->sortByDesc('sessions')->take(5)->values();

            return response()->json($channels);
        } catch (\Exception $e) {
            \Log::error('Channels data error: ' . $e->getMessage());
            return response()->json([]);
        }
    }

    private function applyDetailedFilters($data, $filters)
    {
        return $data->filter(function ($row) use ($filters) {
            foreach ($filters as $filterType => $filterValues) {
                if (!empty($filterValues)) {
                    $rowValue = $this->getDetailedRowValue($row, $filterType);

                    // Check if this row matches ANY of the filter values for this type
                    $matches = false;
                    foreach ($filterValues as $filterValue) {
                        if (stripos($rowValue, $filterValue) !== false) {
                            $matches = true;
                            break;
                        }
                    }

                    if (!$matches) {
                        return false;
                    }
                }
            }
            return true;
        });
    }

    private function getDetailedRowValue($row, $filterType)
    {
        // Map frontend filter types to Google Analytics dimensions
        $filterMap = [
            'channel' => 'sessionDefaultChannelGroup',
            'source' => 'sessionSource',
            'page' => 'pageTitle',
            'country' => 'country',
            'browser' => 'browser',
            'operatingSystem' => 'operatingSystem',
            'screenSize' => 'screenResolution',
            'region' => 'region',
            'city' => 'city'
        ];

        return $row[$filterMap[$filterType] ?? ''] ?? null;
    }

    public function getSources(Request $request)
    {
        try {
            $websiteId = $request->get('website_id');
            $propertyId = $this->setupAnalytics($websiteId);
            // $period = $this->getPeriodFromRange($request->get('date_range', 'last_7_days'));
            $dateRange = $request->get('date_range', 'today');
            $customrange = $request->get('date');

            if ($dateRange === 'custom_range' && $customrange) {
                // Use the custom date range
                $period = $this->getPeriodFromRange($customrange);
            } else {
                // Use the predefined date range
                $period = $this->getPeriodFromRange($dateRange);
            }

            $filters = json_decode($request->filters ?? '{}', true);
            $hasFilters = !empty($filters) && count($filters) > 0;

            // DIMENSIONS BASED ON FILTERS
            $basicDimensions = [
                'sessionSource'
            ];

            $allDimensions = [
                'sessionDefaultChannelGroup',
                'sessionSource',
                'pageTitle',
                'country',
                'browser',
                'operatingSystem',
                'screenResolution',
                'region',
                'city'
            ];

            // If filters applied → use all dimensions
            $dimensions = $hasFilters ? $allDimensions : $basicDimensions;
            // Fetch Analytics
            $response = Analytics::get(
                $period,
                ['sessions', 'totalUsers', 'screenPageViews'],
                $dimensions
            );

            // Apply filters if present
            if ($hasFilters) {
                $response = $this->applyDetailedFilters($response, $filters);
            }


            $totalVisitors = $response->sum('totalUsers');

            $sources = $response->map(function ($row) use ($totalVisitors) {
                $visitors = $row['totalUsers'] ?? 0;
                $sessions = $row['sessions'] ?? 0;
                $pageViews = $row['screenPageViews'] ?? 0;
                $percentage = $totalVisitors > 0 ? round(($visitors / $totalVisitors) * 100, 1) : 0;

                return [
                    'name' => $row['sessionSource'] ?? '(not set)',
                    'visitors' => (int) $visitors,
                    'percentage' => $percentage . '%',
                    'sessions' => (int) $sessions,
                    'pageViews' => (int) $pageViews,
                    'value' => $percentage // For compatibility with existing frontend
                ];
            })
                ->sortByDesc('visitors')
                // ->take(5)
                ->values();

            return response()->json($sources);
        } catch (\Exception $e) {
            \Log::error('Sources data error: ' . $e->getMessage(), [
                'website_id' => $websiteId ?? 'unknown'
            ]);
        }
    }

    public function getTopPages(Request $request)
    {
        try {
            $websiteId = $request->get('website_id');
            $propertyId = config('app.property_id');
            $propertyId = $this->setupAnalytics($propertyId);

            $dateRange = $request->get('date_range', 'today');
            $customrange = $request->get('date');
            $filters = json_decode($request->filters ?? '{}', true);
            $hasFilters = !empty($filters) && count($filters) > 0;

            $period = ($dateRange === 'custom_range' && $customrange)
                ? $this->getPeriodFromRange($customrange)
                : $this->getPeriodFromRange($dateRange);

            // Set dimensions
            $basicDimensions = ['pageTitle'];
            $allDimensions = [
                'sessionDefaultChannelGroup',
                'sessionSource',
                'pageTitle',
                'country',
                'browser',
                'operatingSystem',
                'screenResolution',
                'region',
                'city'
            ];

            $dimensions = $hasFilters ? $allDimensions : $basicDimensions;

            // Fetch analytics data
            $response = Analytics::get(
                $period,
                ['screenPageViews', 'totalUsers'],
                $dimensions
            );

            // Apply filters if any
            if ($hasFilters) {
                $response = $this->applyDetailedFilters($response, $filters);
            }

            // Group by pageTitle and sum metrics
            $grouped = [];

            foreach ($response as $row) {
                $pageName = $row['pageTitle'] ?: '(not set)';

                if (!isset($grouped[$pageName])) {
                    $grouped[$pageName] = [
                        'page' => $pageName,
                        'pageViews' => 0,
                        'visitors' => 0,
                    ];
                }

                $grouped[$pageName]['pageViews'] += (int)($row['screenPageViews'] ?? 0);
                $grouped[$pageName]['visitors'] += (int)($row['totalUsers'] ?? 0);
            }

            $grouped = collect($grouped)->values();

            // Calculate percentages
            $totalPageViews = $grouped->sum('pageViews');
            $pages = $grouped->map(function ($row) use ($totalPageViews) {
                $percentage = $totalPageViews > 0
                    ? round(($row['pageViews'] / $totalPageViews) * 100, 1)
                    : 0;

                return [
                    'page' => $row['page'],
                    'visits' => number_format($row['pageViews']),
                    'percentage' => $percentage . '%',
                ];
            })
                ->sortByDesc('visits')
                ->values();

            return response()->json($pages);
        } catch (\Exception $e) {
            \Log::error('Error fetching top pages: ' . $e->getMessage());
            return response()->json([]);
        }
    }




    public function getEntryPages(Request $request)
    {
        try {
            $websiteId = $request->get('website_id');
            $this->setupAnalytics($websiteId);

            $dateRange = $request->get('date_range', 'today');
            $customrange = $request->get('date');

            $period = ($dateRange === 'custom_range' && $customrange)
                ? $this->getPeriodFromRange($customrange)
                : $this->getPeriodFromRange($dateRange);

            $filters = json_decode($request->filters ?? '{}', true);
            $hasFilters = !empty($filters);

            // GA4 ENTRY PAGES NEED landingPage
            $basicDimensions = ['landingPage', 'pagePath', 'pageTitle'];
            $allDimensions = [
                'sessionDefaultChannelGroup',
                'sessionSource',
                'landingPage',
                'pagePath',
                'pageTitle',
                'country',
                'browser',
                'operatingSystem',
                'screenResolution',
                'region',
                'city'
            ];


            $dimensions = $hasFilters ? $allDimensions : $basicDimensions;
            $dimensions = array_unique($dimensions);

            // Get sessions and bounceRate
            $response = Analytics::get($period, ['sessions', 'bounceRate'], $dimensions);

            if ($hasFilters) {
                $response = $this->applyDetailedFilters($response, $filters);
            }

            // ----------------------------------------
            // FIX: GROUP BY landingPage (correct GA4)
            // ----------------------------------------
            $grouped = [];

            foreach ($response as $row) {

                // landingPage defines entry page
                $pageKey = $row['landingPage'] ?: '(not set)';
                $pageName = $row['pageTitle'] ?: $pageKey;

                if (!isset($grouped[$pageKey])) {
                    $grouped[$pageKey] = [
                        'page'       => $pageName,
                        'sessions'   => 0,
                        'bounceRate' => 0,
                        'count'      => 0,
                    ];
                }

                $grouped[$pageKey]['sessions']   += (int)($row['sessions'] ?? 0);
                $grouped[$pageKey]['bounceRate'] += (float)($row['bounceRate'] ?? 0);
                $grouped[$pageKey]['count']++;
            }

            $grouped = collect($grouped);

            // Build final results
            $pages = $grouped->map(function ($row) {

                $avgBounce =
                    $row['count'] > 0
                    ? $row['bounceRate'] / $row['count']
                    : 0;

                return [
                    'page'       => $row['page'],
                    'visits'     => number_format($row['sessions']),
                    'bounceRate' => round($avgBounce, 1) . '%',
                ];
            })
                ->sortByDesc('visits')
                ->values();

            return response()->json($pages);
        } catch (\Exception $e) {
            \Log::error('Error fetching entry pages: ' . $e->getMessage());
            return response()->json([]);
        }
    }

    public function getExitPages(Request $request)
    {
        try {
            $websiteId = $request->get('website_id');
            $propertyId = config('app.property_id');
            $propertyId = $this->setupAnalytics($propertyId);

            $dateRange = $request->get('date_range', 'today');
            $customrange = $request->get('date');

            $period = ($dateRange === 'custom_range' && $customrange)
                ? $this->getPeriodFromRange($customrange)
                : $this->getPeriodFromRange($dateRange);

            $filters = json_decode($request->filters ?? '{}', true);
            $hasFilters = !empty($filters);

            // GA4-compatible dimensions
            $basicDimensions = ['pagePath', 'pageTitle'];
            $allDimensions = [
                'sessionDefaultChannelGroup',
                'sessionSource',
                'pagePath',
                'pageTitle',
                'country',
                'browser',
                'operatingSystem',
                'screenResolution',
                'region',
                'city'
            ];

            $dimensions = $hasFilters ? array_merge($basicDimensions, $allDimensions) : $basicDimensions;
            $dimensions = array_unique($dimensions);

            // GA4-compatible metric
            $metrics = ['sessions']; // instead of 'exits'

            // Fetch analytics
            $response = Analytics::get($period, $metrics, $dimensions);

            if ($hasFilters) {
                $response = $this->applyDetailedFilters($response, $filters);
            }

            // Group by pagePath
            $grouped = [];

            foreach ($response as $row) {
                $pageKey = $row['pagePath'] ?? '(not set)';
                $pageName = $row['pageTitle'] ?? $pageKey;

                if (!isset($grouped[$pageKey])) {
                    $grouped[$pageKey] = [
                        'page'     => $pageName,
                        'sessions' => 0
                    ];
                }

                $grouped[$pageKey]['sessions'] += (int)($row['sessions'] ?? 0);
            }

            $grouped = collect($grouped);

            $totalSessions = $grouped->sum('sessions');

            $pages = $grouped->map(function ($row) use ($totalSessions) {
                $exitRate = $totalSessions > 0
                    ? round(($row['sessions'] / $totalSessions) * 100, 1)
                    : 0;

                return [
                    'page'     => $row['page'],
                    'exits'    => number_format($row['sessions']), // approximate exits
                    'exitRate' => $exitRate . '%'
                ];
            })
                ->sortByDesc('exits')
                ->values();

            return response()->json($pages);
        } catch (\Exception $e) {
            \Log::error('Error fetching exit pages: ' . $e->getMessage());
            return response()->json([
                'pages' => [],
                'error' => 'Unable to fetch exit pages'
            ]);
        }
    }


    private function setAnalyticsProperty($propertyId)
    {

        $tempFilePath = storage_path('app/google-analytics-credentials.json');
        // Set configuration
        config(['analytics.property_id' => $propertyId]);
        config(['analytics.service_account_credentials_json' => $tempFilePath]);

        // Reinitialize Analytics facade
        app()->forgetInstance('laravel-analytics');
        app()->register(\Spatie\Analytics\AnalyticsServiceProvider::class);
    }

    public function getBrowserData(Request $request)
    {
        try {
            $propertyId = config('app.property_id');
            $propertyId = $this->setupAnalytics($propertyId);

            // Check if propertyId is valid
            if (!$propertyId) {
                return response()->json([]); // Return empty data if no propertyId found
            }
            // $period    = $this->getPeriodFromRange($request->get('date_range', 'today'));
            $dateRange = $request->get('date_range', 'today');
            $customrange = $request->get('date');

            if ($dateRange === 'custom_range' && $customrange) {
                $period = $this->getPeriodFromRange($customrange);
            } else {
                $period = $this->getPeriodFromRange($dateRange);
            }

            $filters = json_decode($request->filters ?? '{}', true);
            $hasFilters = !empty($filters) && count($filters) > 0;

            // DIMENSIONS BASED ON FILTERS
            $basicDimensions = [
                'browser'
            ];

            $allDimensions = [
                'sessionDefaultChannelGroup',
                'sessionSource',
                'pageTitle',
                'country',
                'browser',
                'operatingSystem',
                'screenResolution',
                'region',
                'city'
            ];

            // If filters applied → use all dimensions
            $dimensions = $hasFilters ? $allDimensions : $basicDimensions;

            $response = Analytics::get(
                $period,
                ['activeUsers'],
                $dimensions
            );
            // Apply filters if any
            if ($hasFilters) {
                $response = $this->applyDetailedFilters($response, $filters);
            }

            $total = $response->sum('activeUsers');

            $data = $response->map(function ($row) use ($total) {
                return [
                    'name' => $row['browser'] ?? '(not set)',
                    'sessions' => number_format($row['activeUsers']),
                    'percentage' => $total > 0 ? round(($row['activeUsers'] / $total) * 100) . '%' : '0%'
                ];
            })->take(5);

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([]);
        }
    }
    public function getOSData(Request $request)
    {
        try {
            $propertyId = config('app.property_id');
            $propertyId = $this->setupAnalytics($propertyId);

            // Check if propertyId is valid
            if (!$propertyId) {
                return response()->json([]); // Return empty data if no propertyId found
            }
            // $period    = $this->getPeriodFromRange($request->get('date_range', 'today'));
            $dateRange = $request->get('date_range', 'today');
            $customrange = $request->get('date');

            if ($dateRange === 'custom_range' && $customrange) {
                $period = $this->getPeriodFromRange($customrange);
            } else {
                $period = $this->getPeriodFromRange($dateRange);
            }

            $filters = json_decode($request->filters ?? '{}', true);
            $hasFilters = !empty($filters) && count($filters) > 0;

            // DIMENSIONS BASED ON FILTERS
            $basicDimensions = [
                'operatingSystem'
            ];

            $allDimensions = [
                'sessionDefaultChannelGroup',
                'sessionSource',
                'pageTitle',
                'country',
                'browser',
                'operatingSystem',
                'screenResolution',
                'region',
                'city'
            ];

            // If filters applied → use all dimensions
            $dimensions = $hasFilters ? $allDimensions : $basicDimensions;

            $response = Analytics::get(
                $period,
                ['activeUsers'],
                $dimensions
            );

            // Apply filters if any
            if ($hasFilters) {
                $response = $this->applyDetailedFilters($response, $filters);
            }


            $total = $response->sum('activeUsers');

            $data = $response->map(function ($row) use ($total) {
                return [
                    'os' => $row['operatingSystem'] ?? '(not set)',
                    'sessions' => number_format($row['activeUsers']),
                    'percentage' => $total > 0 ? round(($row['activeUsers'] / $total) * 100) . '%' : '0%'
                ];
            })->take(5);

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([]);
        }
    }

    public function getDeviceSizeData(Request $request)
    {
        try {
            $propertyId = config('app.property_id');
            $propertyId = $this->setupAnalytics($propertyId);

            if (!$propertyId) {
                return response()->json([]);
            }

            $dateRange = $request->get('date_range', 'today');
            $customrange = $request->get('date');

            if ($dateRange === 'custom_range' && $customrange) {
                $period = $this->getPeriodFromRange($customrange);
            } else {
                $period = $this->getPeriodFromRange($dateRange);
            }

            $filters = json_decode($request->filters ?? '{}', true);
            $hasFilters = !empty($filters) && count($filters) > 0;

            // DIMENSIONS BASED ON FILTERS
            $basicDimensions = [
                'screenResolution'
            ];

            $allDimensions = [
                'sessionDefaultChannelGroup',
                'sessionSource',
                'pageTitle',
                'country',
                'browser',
                'operatingSystem',
                'screenResolution',
                'region',
                'city'
            ];

            // If filters applied → use all dimensions
            $dimensions = $hasFilters ? $allDimensions : $basicDimensions;

            $response = Analytics::get(
                $period,
                ['activeUsers'],
                $dimensions,
                10
            );

            // Apply filters if any
            if ($hasFilters) {
                $response = $this->applyDetailedFilters($response, $filters);
            }


            // // Alternative approach if above doesn't work
            // if (empty($response) || $response->isEmpty()) {
            //     $response = Analytics::performQuery(
            //         $period,
            //         'sessions',
            //         ['dimensions' => 'screenResolution']
            //     );
            // }

            $total = $response->sum('activeUsers');

            $data = $response->map(function ($row) use ($total) {
                $deviceCategory = $row['screenResolution'] ?? '(not set)';
                $sessions = $row['activeUsers'] ?? 0;

                return [
                    'device' => ucfirst($deviceCategory),
                    'sessions' => number_format($sessions),
                    'percentage' => $total > 0 ? round(($sessions / $total) * 100) . '%' : '0%'
                ];
            })->take(5);

            return response()->json($data);
        } catch (\Exception $e) {
            \Log::error('Device Size Data Error: ' . $e->getMessage());
            return response()->json([]);
        }
    }

    public function getChanneldetails(Request $request)
    {
        try {
            $type = $request->get('type', 'channels'); // 'channels' or 'sources'
            $propertyId = config('app.property_id');
            $propertyId = $this->setupAnalytics($propertyId);

            $dateRange = $request->get('date_range', 'today');
            $customrange = $request->get('date');

            $period = ($dateRange === 'custom_range' && $customrange)
                ? $this->getPeriodFromRange($customrange)
                : $this->getPeriodFromRange($dateRange);

            $filters = json_decode($request->filters ?? '{}', true);
            $hasFilters = !empty($filters) && count($filters) > 0;

            // Determine main dimension
            $dimension = $type === 'sources' ? 'sessionSource' : 'sessionDefaultChannelGroup';

            // Dimensions to fetch
            $allDimensions = [
                'sessionDefaultChannelGroup',
                'sessionSource',
                'pageTitle',
                'country',
                'browser',
                'operatingSystem',
                'screenResolution',
                'region',
                'city'
            ];

            $dimensions = $hasFilters ? $allDimensions : [$dimension];

            // Fetch analytics
            $response = Analytics::get($period, ['sessions', 'screenPageViews'], $dimensions, 10000);

            if ($hasFilters) {
                $response = $this->applyDetailedFilters($response, $filters);
            }

            // Aggregate sessions and pageviews per dimension
            $grouped = [];
            foreach ($response as $row) {
                $key = $row[$dimension] ?? 'Direct';
                $grouped[$key]['sessions'] = ($grouped[$key]['sessions'] ?? 0) + ($row['sessions'] ?? 0);
                $grouped[$key]['pageViews'] = ($grouped[$key]['pageViews'] ?? 0) + ($row['screenPageViews'] ?? 0);
            }

            $totalSessions = array_sum(array_column($grouped, 'sessions'));

            // Format final data
            $data = [];
            foreach ($grouped as $name => $values) {
                $sessions = $values['sessions'] ?? 0;
                $pageViews = $values['pageViews'] ?? 0;
                $percentage = $totalSessions > 0 ? round(($sessions / $totalSessions) * 100, 1) : 0;

                $data[] = [
                    'name' => $name,
                    'sessions' => $sessions,
                    'pageViews' => $pageViews,
                    'percentage' => $percentage . '%',
                    'value' => $percentage
                ];
            }

            // Sort by sessions descending
            $data = collect($data)->sortByDesc('sessions')->values();

            return response()->json($data);
        } catch (\Exception $e) {
            \Log::error('Channel details error: ' . $e->getMessage());
            return response()->json([]);
        }
    }


    public function getPagedetails(Request $request)
    {
        try {
            $websiteId = $request->get('website_id');
            $type = $request->get('type', 'top-pages');
            $propertyId = config('app.property_id');
            $propertyId = $this->setupAnalytics($propertyId);

            $dateRange = $request->get('date_range', 'today');
            $customrange = $request->get('date');

            $period = ($dateRange === 'custom_range' && $customrange)
                ? $this->getPeriodFromRange($customrange)
                : $this->getPeriodFromRange($dateRange);

            $filters = json_decode($request->filters ?? '{}', true);
            $hasFilters = !empty($filters);

            // Dimensions
            $basicDimensions = ['landingPage', 'pagePath', 'pageTitle'];
            $allDimensions = [
                'sessionDefaultChannelGroup',
                'sessionSource',
                'landingPage',
                'pagePath',
                'pageTitle',
                'country',
                'browser',
                'operatingSystem',
                'screenResolution',
                'region',
                'city'
            ];


            $dimensions = $hasFilters ? $allDimensions : $basicDimensions;

            // Metrics based on page type
            if ($type === 'entry-pages') {
                $dimensions[] = 'landingPage';
                $dimensions[] = 'pagePath';
                $metrics = ['sessions', 'bounceRate'];
            } else {
                $metrics = ['screenPageViews', 'totalUsers'];
            }

            $dimensions = array_unique($dimensions);

            // Fetch Data
            $response = Analytics::get($period, $metrics, $dimensions);

            if ($hasFilters) {
                $response = $this->applyDetailedFilters($response, $filters);
            }

            // ------------------------------------------
            // 🔥 FIXED: Grouping logic SAME as getTopPages
            // ------------------------------------------
            if ($type === 'top-pages') {

                $grouped = [];

                foreach ($response as $row) {
                    $pageName = $row['pageTitle'] ?: '(not set)';

                    if (!isset($grouped[$pageName])) {
                        $grouped[$pageName] = [
                            'pageViews' => 0,
                            'visitors' => 0,
                        ];
                    }

                    $grouped[$pageName]['pageViews'] += (int)($row['screenPageViews'] ?? 0);
                    $grouped[$pageName]['visitors'] += (int)($row['totalUsers'] ?? 0);
                }

                $grouped = collect($grouped);
                $totalPageViews = $grouped->sum('pageViews');

                // SAME percentage formula as getTopPages()
                $pages = $grouped->map(function ($row, $pageName) use ($totalPageViews) {
                    $percentage = $totalPageViews > 0
                        ? round(($row['pageViews'] / $totalPageViews) * 100, 1)
                        : 0;

                    return [
                        'page'       => $pageName,
                        'views'      => number_format($row['pageViews']),
                        'visitors'   => number_format($row['visitors']),
                        'percentage' => $percentage . '%'
                    ];
                })
                    ->sortByDesc('views')
                    ->values();

                return response()->json($pages);
            }

            // ------------------------------------------
            // ENTRY & EXIT PAGES LOGIC (unchanged)
            // ------------------------------------------

            if ($type === 'entry-pages') {

                $grouped = [];

                foreach ($response as $row) {

                    // Correct GA4 identification of entry page
                    $pageKey = $row['landingPage']
                        ?: ($row['pagePath'] ?? '(not set)');

                    $pageName = $row['pageTitle'] ?: $pageKey;

                    if (!isset($grouped[$pageKey])) {
                        $grouped[$pageKey] = [
                            'page'       => $pageName,
                            'sessions'   => 0,
                            'bounceRate' => 0,
                            'count'      => 0
                        ];
                    }

                    $grouped[$pageKey]['sessions']   += (int)($row['sessions'] ?? 0);
                    $grouped[$pageKey]['bounceRate'] += (float)($row['bounceRate'] ?? 0);
                    $grouped[$pageKey]['count']++;
                }

                $grouped = collect($grouped);

                $pages = $grouped->map(function ($row) {

                    $avgBounce = $row['count'] > 0
                        ? $row['bounceRate'] / $row['count']
                        : 0;

                    return [
                        'page'       => $row['page'],
                        'visits'     => number_format($row['sessions']),
                        'bounceRate' => round($avgBounce, 1) . '%',
                    ];
                })
                    ->sortByDesc('visits')
                    ->values();

                return response()->json($pages);
            } else if ($type === 'exit-pages') {
                // GA4-compatible dimensions
                $dimensions = ['pagePath', 'pageTitle'];

                // GA4-compatible metric
                $metrics = ['sessions']; // GA4 does not have 'exits'

                // Fetch analytics
                $response = Analytics::get($period, $metrics, $dimensions);

                // Group by pagePath
                $grouped = [];

                foreach ($response as $row) {
                    $pageKey = $row['pagePath'] ?? '(not set)';
                    $pageName = $row['pageTitle'] ?? $pageKey;

                    if (!isset($grouped[$pageKey])) {
                        $grouped[$pageKey] = [
                            'page'     => $pageName,
                            'sessions' => 0
                        ];
                    }

                    $grouped[$pageKey]['sessions'] += (int)($row['sessions'] ?? 0);
                }

                $grouped = collect($grouped);

                $totalSessions = $grouped->sum('sessions');

                $pages = $grouped->map(function ($row) use ($totalSessions) {
                    $exitRate = $totalSessions > 0
                        ? round(($row['sessions'] / $totalSessions) * 100, 1)
                        : 0;

                    return [
                        'page'     => $row['page'],
                        'exits'    => number_format($row['sessions']), // approximate exits
                        'exitRate' => $exitRate . '%'
                    ];
                })
                    ->sortByDesc('exits')
                    ->values();

                return response()->json($pages);
            }





            return response()->json([]);
        } catch (\Exception $e) {
            \Log::error('Page details error: ' . $e->getMessage());
            return response()->json([]);
        }
    }


    public function getDevicedetails(Request $request)
    {
        try {
            $websiteId = $request->get('website_id');
            $type = $request->get('type', 'browser');
            $propertyId = config('app.property_id');
            $propertyId = $this->setupAnalytics($propertyId);
            $dateRange = $request->get('date_range', 'today');
            $customrange = $request->get('date');

            if ($dateRange === 'custom_range' && $customrange) {
                $period = $this->getPeriodFromRange($customrange);
            } else {
                $period = $this->getPeriodFromRange($dateRange);
            }

            $filters = json_decode($request->filters ?? '{}', true);
            $hasFilters = !empty($filters) && count($filters) > 0;

            // Define basic and all dimensions (same as your working methods)
            $basicDimensions = [];

            $allDimensions = [
                'sessionDefaultChannelGroup',
                'sessionSource',
                'pageTitle',
                'country',
                'browser',
                'operatingSystem',
                'screenResolution',
                'region',
                'city'
            ];

            // Add specific dimensions based on type
            if ($type === 'browser') {
                $basicDimensions[] = 'browser';
            } else if ($type === 'os') {
                $basicDimensions[] = 'operatingSystem';
            } else {
                $basicDimensions[] = 'screenResolution';
            }

            // Use same dimension logic as your working methods
            $dimensions = $hasFilters ? $allDimensions : $basicDimensions;

            // Get devices data from Google Analytics based on type
            if ($type === 'browser') {
                $response = Analytics::get(
                    $period,
                    ['activeUsers', 'totalUsers'],
                    $dimensions,
                    10000
                );

                $devices = $response->map(function ($row) {
                    return [
                        'browser' => $row['browser'] ?? '(not set)',
                        'visitors' => (int) ($row['totalUsers'] ?? 0),
                        'percentage' => '0%' // You might want to calculate this
                    ];
                })->sortByDesc('visitors')->values();
            } else if ($type === 'os') {
                $response = Analytics::get(
                    $period,
                    ['activeUsers', 'totalUsers'],
                    $dimensions,
                    10000
                );

                $devices = $response->map(function ($row) {
                    return [
                        'os' => $row['operatingSystem'] ?? '(not set)',
                        'visitors' => (int) ($row['totalUsers'] ?? 0),
                        'percentage' => '0%'
                    ];
                })->sortByDesc('visitors')->values();
            } else if ($type === 'size') {
                $response = Analytics::get(
                    $period,
                    ['activeUsers', 'totalUsers'],
                    $dimensions,
                    10000
                );

                $devices = $response->map(function ($row) {
                    return [
                        'size' => $row['screenResolution'] ?? '(not set)',
                        'visitors' => (int) ($row['totalUsers'] ?? 0),
                        'percentage' => '0%'
                    ];
                })->sortByDesc('visitors')->values();
            }


            // Apply filters if any (same as your working methods)
            if ($hasFilters) {
                $response = $this->applyDetailedFilters($response, $filters);
            }

            // Calculate percentages if needed
            $totalVisitors = $devices->sum('visitors');
            $devices = $devices->map(function ($device) use ($totalVisitors) {
                if ($totalVisitors > 0) {
                    $device['percentage'] = round(($device['visitors'] / $totalVisitors) * 100, 1) . '%';
                }
                return $device;
            });

            // Limit to top 50 results
            $devices = $devices;

            return response()->json($devices);
        } catch (\Exception $e) {

            return response()->json([]);
        }
    }

    private function getPeriodFromRange($range)
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $now = Carbon::now();

        // Check if it's a custom date range first (contains " - ")
        if (strpos($range, ' - ') !== false) {
            list($startDate, $endDate) = explode(' - ', $range);
            return Period::create(
                Carbon::parse($startDate),
                Carbon::parse($endDate)
            );
        }

        switch ($range) {
            case 'today':
                return Period::create($today, $today);
            case 'yesterday':
                return Period::create($yesterday, $yesterday);
            case 'realtime':
                return Period::create($now->copy()->subMinutes(30), $now);

            case 'last_7_days':
                return Period::create($yesterday->copy()->subDays(6), $yesterday);

            case 'last_28_days':
                return Period::create($yesterday->copy()->subDays(27), $yesterday);

            case 'last_91_days':
                return Period::create($yesterday->copy()->subDays(89), $yesterday);

            case 'month_to_date':
                return Period::create($today->copy()->startOfMonth(), $today);

            case 'last_month':
                $lastMonth = $today->copy()->subMonth();
                $start = $lastMonth->copy()->startOfMonth();
                $end = $lastMonth->copy()->endOfMonth();
                return Period::create($start, $end);

            case 'year_to_date':
                return Period::create($today->copy()->startOfYear(), $today);

            case 'last_12_months':
                return Period::create($today->copy()->subMonths(12), $today);

            case 'all_time':
                return Period::create($today->copy()->subYears(2), $today);

            case 'custom_range':
                // For custom_range without actual dates, default to last 7 days
                return Period::create($yesterday->copy()->subDays(6), $yesterday);

            default: // last_7_days as default
                return Period::create($yesterday->copy()->subDays(6), $yesterday);
        }
    }

    private function getPeriodFromRangeChart($range)
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $now = Carbon::now();

        // Check if it's a custom date range first (contains " - ")
        if (strpos($range, ' - ') !== false) {
            list($startDate, $endDate) = explode(' - ', $range);
            return Period::create(
                Carbon::parse($startDate),
                Carbon::parse($endDate)
            );
        }

        switch ($range) {
            case 'today':
                // return Period::create($today, $today);
                return Period::create(
                    Carbon::today(),
                    Carbon::today()->endOfDay()
                );
            case 'yesterday':
                // return Period::create($yesterday, $yesterday);
                return Period::create(
                    $yesterday->copy()->startOfDay(),
                    $yesterday->copy()->endOfDay()
                );

            case 'realtime':
                // return Period::create($now->copy()->subMinutes(30), $now);
                return Period::create($now->copy()->subMinutes(30), $now);

            case 'last_7_days':
                return Period::create($yesterday->copy()->subDays(6), $yesterday);

            case 'last_28_days':
                return Period::create($yesterday->copy()->subDays(27), $yesterday);

            case 'last_91_days':
                return Period::create($yesterday->copy()->subDays(89), $yesterday);

            case 'month_to_date':
                return Period::create($today->copy()->startOfMonth(), $today);

            case 'last_month':
                $lastMonth = $today->copy()->subMonth();
                $start = $lastMonth->copy()->startOfMonth();
                $end = $lastMonth->copy()->endOfMonth();
                return Period::create($start, $end);

            case 'year_to_date':
                return Period::create($today->copy()->startOfYear(), $today);

            case 'last_12_months':
                return Period::create($today->copy()->subMonths(12), $today);

            case 'all_time':
                return Period::create($today->copy()->subYears(2), $today);

            case 'custom_range':
                // For custom_range without actual dates, default to last 7 days
                return Period::create($yesterday->copy()->subDays(6), $yesterday);

            default: // last_7_days as default
                return Period::create($yesterday->copy()->subDays(6), $yesterday);
        }
    }
    public function getChartDataApi(Request $request)
    {
        try {
            $websiteId = $request->get('website_id');
            $dateRange = $request->get('date_range', 'today');
            $customrange = $request->get('date');
            $granularity = $request->get('granularity', 'hours');
            $metric = $request->get('metric', 'unique_visitors');
            $filters = json_decode($request->get('filters', '{}'), true);

            $propertyId = config('app.property_id');
            // $this->setAnalyticsProperty($propertyId);
           
            $this->setAnalyticsProperty($propertyId);

            if (!$this->checkAnalyticsConfiguration()) {
                return response()->json($this->getEmptyChartDataForPeriod());
            }


            if ($dateRange === 'custom_range' && $customrange) {
                $period = $this->getPeriodFromRangeChart($customrange);
            } else {
                $period = $this->getPeriodFromRangeChart($dateRange);
            }

            $chartData = $this->getChartDataWithGranularityAndMetric($period, $granularity, $metric, $filters, $propertyId, $dateRange);

            return response()->json($chartData);
        } catch (\Exception $e) {

            \Log::error('Chart Data API Error: ' . $e->getMessage());

            return response()->json([
                'error' => $this->parseChartErrorMessage($e->getMessage()),
                'categories' => [],
                'visitor_data' => [],
                'y_max' => 100,
                'total_unique_visitors' => 0
            ], 500);
        }
    }

    private function getHourlyData(Period $period, $metrics = ['activeUsers'], $metricType = 'unique_visitors', $filters = [])
    {
        try {
            // Add dimensions if filters are present
            $dimensions = ['dateHour'];
            if (!empty($filters)) {
                foreach ($filters as $filterType => $filterValues) {
                    if (!empty($filterValues)) {
                        $dimensions[] = $this->getDimensionByFilterType($filterType);
                    }
                }
            }


            $response = Analytics::get(
                $period,
                $metrics,
                $dimensions
            );

            // Apply filters if present
            if (!empty($filters)) {
                $response = $this->applyDetailedFilters($response, $filters);
            }

            $categories = [];
            $visitorData = [];
            $formattedData = [];

            // Process the response data
            foreach ($response as $item) {
                if (isset($item['dateHour'])) {
                    $dateHour = $item['dateHour'];
                    $date = substr($dateHour, 0, 10);
                    $hour = substr($dateHour, 8, 2);
                    $value = $this->calculateMetricValue($item, $metricType, $metrics);
                    $formattedData[$dateHour] = $value;
                }
            }

            // Fill in missing hours and create final arrays
            $currentDateTime = $period->startDate->copy();
            while ($currentDateTime <= $period->endDate) {
                $dateHourKey = $currentDateTime->format('YmdH');
                $categoryLabel = $currentDateTime->format('H:00');

                $categories[] = $categoryLabel;
                $value = $formattedData[$dateHourKey] ?? 0;
                $visitorData[] = (float)$value;

                $currentDateTime->addHour();
            }

            $total = array_sum($visitorData);
            $maxValue = max($visitorData);
            $yMax = $maxValue > 0 ? $this->calculateAppropriateYMax($maxValue) : 100;

            return $this->formatChartData($categories, $visitorData, $metricType);
        } catch (\Exception $e) {
            return $this->getEmptyChartDataForPeriod($period);
        }
    }

    private function formatChartData($categories, $data, $metricType)
    {
        $total = array_sum($data);

        // Calculate appropriate max value for Y-axis
        $maxValue = max($data);
        if ($maxValue <= 0) {
            $maxValue = 100; // Default max for empty data
        } else {
            // Add some padding to the max value (20% padding)
            $maxValue = $maxValue * 1.2;
        }

        $yMax = $this->calculateAppropriateYMax($maxValue);

        return [
            'categories' => $categories,
            'visitor_data' => $data,
            'y_max' => (int)$yMax,
            'total_unique_visitors' => $total,
            'metric_type' => $metricType
        ];
    }

    private function getMinuteData(Period $period, $metrics = ['activeUsers'], $metricType = 'unique_visitors', $filters = [])
    {
        try {
            // Add dimensions if filters are present
            $dimensions = ['dateHourMinute'];
            if (!empty($filters)) {
                foreach ($filters as $filterType => $filterValues) {
                    if (!empty($filterValues)) {
                        $dimensions[] = $this->getDimensionByFilterType($filterType);
                    }
                }
            }


            $response = Analytics::get(
                $period,
                $metrics,
                $dimensions
            );

            // Apply filters if present
            if (!empty($filters)) {
                $response = $this->applyDetailedFilters($response, $filters);
            }

            $categories = [];
            $visitorData = [];
            $formattedData = [];

            // Process the response data
            foreach ($response as $item) {
                if (isset($item['dateHourMinute'])) {
                    $dateTime = $item['dateHourMinute'];
                    $value = $this->calculateMetricValue($item, $metricType, $metrics);
                    $formattedData[$dateTime] = $value;
                }
            }

            // For minute data, we'll group by 15-minute intervals to avoid too many data points
            $currentDateTime = $period->startDate->copy();
            while ($currentDateTime <= $period->endDate) {
                $categoryLabel = $currentDateTime->format('H:i');
                $categories[] = $categoryLabel;

                // Sum values for this 15-minute interval
                $intervalStart = $currentDateTime->copy();
                $intervalEnd = $currentDateTime->copy()->addMinutes(14)->addSeconds(59);

                $intervalValue = 0;
                foreach ($formattedData as $dateTime => $value) {
                    $itemDateTime = Carbon::createFromFormat('YmdHi', $dateTime);
                    if ($itemDateTime >= $intervalStart && $itemDateTime <= $intervalEnd) {
                        $intervalValue += $value;
                    }
                }

                $visitorData[] = (float)$intervalValue;
                $currentDateTime->addMinutes(15);
            }

            $total = array_sum($visitorData);
            $maxValue = max($visitorData);
            $yMax = $maxValue > 0 ? $this->calculateAppropriateYMax($maxValue) : 100;

            return $this->formatChartData($categories, $visitorData, $metricType);
        } catch (\Exception $e) {
            return $this->getHourlyData($period, $metrics, $metricType, $filters);
        }
    }

    private function getWeeklyData(Period $period, $metrics = ['activeUsers'], $metricType = 'unique_visitors', $filters = [])
    {
        try {
            // Add dimensions if filters are present
            $dimensions = ['yearWeek'];
            if (!empty($filters)) {
                foreach ($filters as $filterType => $filterValues) {
                    if (!empty($filterValues)) {
                        $dimensions[] = $this->getDimensionByFilterType($filterType);
                    }
                }
            }


            $response = Analytics::get(
                $period,
                $metrics,
                $dimensions
            );

            // Apply filters if present
            if (!empty($filters)) {
                $response = $this->applyDetailedFilters($response, $filters);
            }

            $categories = [];
            $visitorData = [];
            $formattedData = [];

            // Process the response data
            foreach ($response as $item) {
                if (isset($item['yearWeek'])) {
                    $yearWeek = $item['yearWeek'];
                    $value = $this->calculateMetricValue($item, $metricType, $metrics);
                    $formattedData[$yearWeek] = $value;
                }
            }

            // Create weekly categories
            $currentDate = $period->startDate->copy()->startOfWeek();
            while ($currentDate <= $period->endDate) {
                $weekStart = $currentDate->copy()->format('M j');
                $weekEnd = $currentDate->copy()->endOfWeek()->format('M j');
                $yearWeekKey = $currentDate->format('oW'); // ISO year and week

                $categories[] = "{$weekStart} - {$weekEnd}";
                $value = $formattedData[$yearWeekKey] ?? 0;
                $visitorData[] = (float)$value;

                $currentDate->addWeek();
            }

            $total = array_sum($visitorData);
            $maxValue = max($visitorData);
            $yMax = $maxValue > 0 ? $this->calculateAppropriateYMax($maxValue) : 100;

            return $this->formatChartData($categories, $visitorData, $metricType);
        } catch (\Exception $e) {
            return $this->getDailyData($period, $metrics, $metricType, $filters);
        }
    }

    private function getMonthlyData(Period $period, $metrics = ['activeUsers'], $metricType = 'unique_visitors', $filters = [])
    {
        try {
            // $response = Analytics::get(
            //     $period,
            //     $metrics,
            //     ['yearMonth']
            // );
            $dimensions = ['yearMonth'];
            if (!empty($filters)) {
                foreach ($filters as $filterType => $filterValues) {
                    if (!empty($filterValues)) {
                        $dimensions[] = $this->getDimensionByFilterType($filterType);
                    }
                }
            }

            $response = Analytics::get(
                $period,
                $metrics,
                $dimensions
            );

            // Apply filters if present
            if (!empty($filters)) {
                $response = $this->applyDetailedFilters($response, $filters);
            }

            $categories = [];
            $visitorData = [];
            $formattedData = [];

            // Process the response data
            foreach ($response as $item) {
                if (isset($item['yearMonth'])) {
                    $yearMonth = $item['yearMonth'];
                    $value = $this->calculateMetricValue($item, $metricType, $metrics);
                    $formattedData[$yearMonth] = $value;
                }
            }

            // Create monthly categories
            $currentDate = $period->startDate->copy()->startOfMonth();
            while ($currentDate <= $period->endDate) {
                $monthLabel = $currentDate->format('M Y');
                $yearMonthKey = $currentDate->format('Ym');

                $categories[] = $monthLabel;
                $value = $formattedData[$yearMonthKey] ?? 0;
                $visitorData[] = (float)$value;

                $currentDate->addMonth();
            }

            $total = array_sum($visitorData);
            $maxValue = max($visitorData);
            $yMax = $maxValue > 0 ? $this->calculateAppropriateYMax($maxValue) : 100;

            return $this->formatChartData($categories, $visitorData, $metricType);
        } catch (\Exception $e) {
            return $this->getWeeklyData($period, $metrics, $metricType);
        }
    }

    private function getChartDataWithGranularityAndMetric(Period $period, $granularity, $metric, $filters = [], $propertyId, $dateRange)
    {
        try {
            // Determine the appropriate granularity
            $actualGranularity = $this->determineActualGranularity($period, $granularity);

            // Define metrics mapping
            $metricsMapping = [
                'unique_visitors' => ['activeUsers'],
                'total_visits' => ['sessions'],
                'total_pageviews' => ['screenPageViews'],
                'views_per_visit' => ['screenPageViews', 'sessions'],
                'bounce_rate' => ['bounceRate'],
                'avg_session_duration' => ['averageSessionDuration']
            ];

            $selectedMetrics = $metricsMapping[$metric] ?? ['activeUsers'];

            if ($dateRange === 'realtime') {
                return $this->getRealTimeMinuteData($period, $selectedMetrics, $metric, $dateRange, $propertyId);
            }

            switch ($actualGranularity) {
                case 'minutes':
                    return $this->getMinuteData($period, $selectedMetrics, $metric, $filters); // Missing $filters parameter
                case 'hours':
                    return $this->getHourlyData($period, $selectedMetrics, $metric, $filters); // Missing $filters parameter
                case 'day':
                case 'days':
                    return $this->getDailyData($period, $selectedMetrics, $metric, $filters); // Missing $filters parameter
                case 'weeks':
                    return $this->getWeeklyData($period, $selectedMetrics, $metric, $filters); // Missing $filters parameter
                case 'months':
                    return $this->getMonthlyData($period, $selectedMetrics, $metric, $filters); // Missing $filters parameter
                default:
                    return $this->getDailyData($period, $selectedMetrics, $metric, $filters); // Missing $filters parameter
            }
        } catch (\Exception $e) {
            return $this->getEmptyChartDataForPeriod($period);
        }
    }

    private function determineActualGranularity(Period $period, $requestedGranularity)
    {
        $start = Carbon::parse($period->startDate);
        $end = Carbon::parse($period->endDate);

        $days = $start->diffInDays($end);
        $hours = $start->diffInHours($end);

        // Normalize 'day' to 'days'
        if ($requestedGranularity === 'day') {
            $requestedGranularity = 'days';
        }

        // If user explicitly requests granularity and method exists, return it
        $availableGranularities = ['minutes', 'hours', 'days', 'weeks', 'months'];
        if (in_array($requestedGranularity, $availableGranularities)) {
            return $requestedGranularity;
        }

        // Auto decide granularity based on range
        if ($hours <= 24) {
            return 'hours';
        } elseif ($days <= 7) {
            return 'days';
        } elseif ($days <= 30) {
            return 'weeks';
        } else {
            return 'months';
        }
    }


    private function checkAnalyticsConfiguration()
    {
        try {
            // Test if analytics is properly configured
            $test = Analytics::get(
                Period::days(1),
                ['activeUsers']
            );
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    private function getDailyData(Period $period, $metrics = ['activeUsers'], $metricType = 'unique_visitors', $filters = [])
    {
        try {
            // Add dimensions if filters are present
            $dimensions = ['date'];
            if (!empty($filters)) {
                foreach ($filters as $filterType => $filterValues) {
                    if (!empty($filterValues)) {
                        $dimensions[] = $this->getDimensionByFilterType($filterType);
                    }
                }
            }

            // Fetch analytics data with date dimension
            $response = Analytics::get(
                $period,
                $metrics,
                $dimensions
            );

            // Apply filters if present
            if (!empty($filters)) {
                $response = $this->applyDetailedFilters($response, $filters);
            }

            $categories = [];
            $visitorData = [];
            $formattedData = [];

            // Process the response data
            foreach ($response as $item) {
                if (isset($item['date'])) {
                    $dateOnly = substr($item['date'], 0, 10);
                    $value = $this->calculateMetricValue($item, $metricType, $metrics);
                    $formattedData[$dateOnly] = $value;
                }
            }

            // Fill in missing dates and create final arrays
            $currentDate = $period->startDate->copy();
            while ($currentDate <= $period->endDate) {
                $dateStr = $currentDate->format('d M');
                $dateKey = $currentDate->format('Y-m-d');

                $categories[] = $dateStr;
                $value = $formattedData[$dateKey] ?? 0;
                $visitorData[] = (float)$value;

                $currentDate->addDay();
            }

            $totalUniqueVisitors = array_sum($visitorData);

            // Calculate max value for Y-axis based on actual data
            $maxValue = max($visitorData);
            $yMax = $maxValue > 0 ? $this->calculateAppropriateYMax($maxValue) : 100;

            return [
                'categories' => $categories,
                'visitor_data' => $visitorData,
                'y_max' => (int)$yMax,
                'total_unique_visitors' => $totalUniqueVisitors,
                'metric_type' => $metricType
            ];
        } catch (\Exception $e) {
            return $this->getEmptyChartDataForPeriod($period);
        }
    }

    // private function getRealTimeMinuteData(Period $period, $metrics = ['activeUsers'], $metricType = 'unique_visitors', $dateRange, $propertyId)
    // {
    //     try {

    //         $property = "properties/" . $propertyId;
    //         $accessToken = $this->getAccessToken();
    //         $apiUrl = "https://analyticsdata.googleapis.com/v1beta/{$property}:runRealtimeReport";

    //         // Build metrics list properly
    //         $metricList = array_map(fn($m) => ["name" => $m], $metrics);

    //         $request = [
    //             "metrics" => $metricList,
    //             "dimensions" => [
    //                 ["name" => "minutesAgo"]
    //             ],
    //             "limit" => 30
    //         ];

    //         $response = Http::withToken($accessToken)
    //             ->post($apiUrl, $request)
    //             ->json();

    //         $categories = [];
    //         $visitorData = [];
    //         $formattedData = [];

    //         // Parse rows
    //         foreach (($response['rows'] ?? []) as $row) {

    //             $minutesAgo = (int) $row['dimensionValues'][0]['value'];

    //             // turn minutesAgo → actual timestamp
    //             $timestamp = now()->subMinutes($minutesAgo)->format('YmdHi');

    //             // first metric (usually activeUsers)
    //             $value = (int) $row['metricValues'][0]['value'];

    //             $formattedData[$timestamp] = $value;
    //         }

    //         // Build minute-by-minute chart data
    //         $currentDateTime = $period->startDate->copy();

    //         while ($currentDateTime <= $period->endDate) {

    //             $label = $currentDateTime->format('H:i');
    //             $categories[] = $label;

    //             $key = $currentDateTime->format('YmdHi');

    //             $visitorData[] = $formattedData[$key] ?? 0;

    //             $currentDateTime->addMinute();
    //         }

    //         return $this->formatChartData($categories, $visitorData, $metricType);
    //     } catch (\Exception $e) {
    //         return $this->getHourlyData($period, $metrics, $metricType, $filters);
    //     }
    // } //final update

    private function getRealTimeMinuteData(Period $period, $metrics = ['activeUsers'], $metricType = 'unique_visitors', $dateRange, $propertyId)
    {
        try {
            $property = "properties/" . $propertyId;
            $accessToken = $this->getAccessToken();
            $apiUrl = "https://analyticsdata.googleapis.com/v1beta/{$property}:runRealtimeReport";

            // Build metrics list
            $metricList = array_map(fn($m) => ["name" => $m], $metrics);

            // Request last 30 minutes
            $request = [
                "metrics" => $metricList,
                "dimensions" => [
                    ["name" => "minutesAgo"]
                ],
                "limit" => 30
            ];

            $response = Http::withToken($accessToken)->post($apiUrl, $request)->json();

            // Prepare array (0..29 minutes)
            $values = array_fill(0, 30, 0);

            foreach (($response['rows'] ?? []) as $row) {
                $minutesAgo = (int)$row['dimensionValues'][0]['value'];
                $value      = (int)$row['metricValues'][0]['value'];

                if ($minutesAgo < 30) {
                    $values[$minutesAgo] = $value;
                }
            }

            // Build final chart arrays (29 → 1)
            $categories = [];
            $visitorData = [];

            for ($i = 29; $i >= 0; $i--) {

                $labelMinute = $i + 1;   // FIX: Shift labels correctly

                $categories[] = "{$labelMinute} mins";
                $visitorData[] = $values[$i] ?? 0;
            }


            return $this->formatChartData($categories, $visitorData, $metricType);
        } catch (\Exception $e) {
            return $this->getHourlyData($period, $metrics, $metricType, []);
        }
    }

    private function getDimensionByFilterType($filterType)
    {
        $map = [
            'channel' => 'sessionDefaultChannelGroup',
            'source' => 'sessionSource',
            'page' => 'pageTitle',
            'country' => 'country',
            'browser' => 'browser',
            'operatingSystem' => 'operatingSystem',
            'screenSize' => 'screenResolution',
            'region' => 'region',
            'city' => 'city'
        ];

        return $map[$filterType] ?? null;
    }


    private function calculateMetricValue($item, $metricType, $metrics)
    {
        try {
            switch ($metricType) {
                case 'views_per_visit':
                    $pageviews = $item['screenPageViews'] ?? 0;
                    $sessions = $item['sessions'] ?? 1;
                    return $sessions > 0 ? round($pageviews / $sessions, 2) : 0;

                case 'bounce_rate':
                    $bounceRate = $item['bounceRate'] ?? 0;
                    // Convert percentage to decimal if needed
                    return $bounceRate > 1 ? $bounceRate / 100 : $bounceRate;

                case 'avg_session_duration':
                    $seconds = $item['averageSessionDuration'] ?? 0;
                    return round($seconds / 60, 2); // Convert to minutes

                default:
                    // Return the first metric value
                    $firstMetric = $metrics[0] ?? 'activeUsers';
                    return $item[$firstMetric] ?? 0;
            }
        } catch (\Exception $e) {
            return 0;
        }
    }
    private function calculateAppropriateYMax($maxValue)
    {
        if ($maxValue <= 0) return 100;

        // Round up to nearest appropriate interval
        $magnitude = pow(10, floor(log10($maxValue)));
        $normalized = $maxValue / $magnitude;

        if ($normalized <= 2) return 2 * $magnitude;
        if ($normalized <= 5) return 5 * $magnitude;
        return 10 * $magnitude;
    }

    // FIXED: Improved empty data method
    private function getEmptyChartDataForPeriod($period = null, $granularity = 'days')
    {
        $categories = [];
        $data = [];

        if ($period) {
            $currentDate = $period->startDate->copy();

            switch ($granularity) {
                case 'minutes':
                    while ($currentDate <= $period->endDate) {
                        $categories[] = $currentDate->format('H:i');
                        $data[] = 0;
                        $currentDate->addMinutes(15);
                    }
                    break;
                case 'hours':
                    while ($currentDate <= $period->endDate) {
                        $categories[] = $currentDate->format('H:00');
                        $data[] = 0;
                        $currentDate->addHour();
                    }
                    break;
                case 'weeks':
                    $currentDate->startOfWeek();
                    while ($currentDate <= $period->endDate) {
                        $weekStart = $currentDate->copy()->format('M j');
                        $weekEnd = $currentDate->copy()->endOfWeek()->format('M j');
                        $categories[] = "{$weekStart} - {$weekEnd}";
                        $data[] = 0;
                        $currentDate->addWeek();
                    }
                    break;
                case 'months':
                    $currentDate->startOfMonth();
                    while ($currentDate <= $period->endDate) {
                        $categories[] = $currentDate->format('M Y');
                        $data[] = 0;
                        $currentDate->addMonth();
                    }
                    break;
                default: // days
                    while ($currentDate <= $period->endDate) {
                        $categories[] = $currentDate->format('d M');
                        $data[] = 0;
                        $currentDate->addDay();
                    }
            }
        } else {
            // Default to last 7 days
            for ($i = 0; $i < 7; $i++) {
                $date = Carbon::today()->subDays(6 - $i);
                $categories[] = $date->format('d M');
                $data[] = 0;
            }
        }

        return [
            'categories' => $categories,
            'visitor_data' => $data,
            'y_max' => 100,
            'total_unique_visitors' => 0,
            'metric_type' => 'unique_visitors'
        ];
    }
    public function getMapData(Request $request)
    {
        try {
            $websiteId = $request->get('website_id');
            $dateRange = $request->get('date_range', 'today');
            $customrange = $request->get('date');

            $propertyId = config('app.property_id');
            $propertyId = $this->setupAnalytics($propertyId);

            // Determine period
            if ($dateRange === 'custom_range' && $customrange) {
                $period = $this->getPeriodFromRange($customrange);
            } else {
                $period = $this->getPeriodFromRange($dateRange);
            }

            $filters = json_decode($request->filters ?? '{}', true);
            $hasFilters = !empty($filters) && count($filters) > 0;

            $basicDimensions = ['country'];
            $allDimensions = [
                'sessionDefaultChannelGroup',
                'sessionSource',
                'pageTitle',
                'country',
                'browser',
                'operatingSystem',
                'screenResolution',
                'region',
                'city'
            ];

            $dimensions = $hasFilters ? $allDimensions : $basicDimensions;

            $response = Analytics::get(
                $period,
                ['activeUsers'],
                $dimensions
            );

            // Apply filters if any
            if ($hasFilters) {
                $response = $this->applyDetailedFilters($response, $filters);
            }

            $total = $response->sum('activeUsers');

            $data = $response->map(function ($row) use ($total) {
                return [
                    'country' => $row['country'] ?? '(not set)',
                    'visitors' => (int)($row['activeUsers'] ?? 0),
                    'percentage' => $total > 0 ? round(($row['activeUsers'] / $total) * 100) . '%' : '0%'
                ];
            })
                ->values(); // FIX: Reset array keys to start from 0

            return response()->json($data);
        } catch (\Exception $e) {
            \Log::error('Map data error: ' . $e->getMessage());
            return response()->json([]);
        }
    }

    public function getCountriesData(Request $request)
    {
        try {
            $websiteId = $request->get('website_id');
            $dateRange = $request->get('date_range', 'today');
            $customrange = $request->get('date');

            $propertyId = config('app.property_id');
            $propertyId = $this->setupAnalytics($propertyId);
            // Determine period
            if ($dateRange === 'custom_range' && $customrange) {
                $period = $this->getPeriodFromRange($customrange);
            } else {
                $period = $this->getPeriodFromRange($dateRange);
            }

            $filters = json_decode($request->filters ?? '{}', true);
            $hasFilters = !empty($filters) && count($filters) > 0;

            // DIMENSIONS BASED ON FILTERS
            $basicDimensions = [
                'country'
            ];

            $allDimensions = [
                'sessionDefaultChannelGroup',
                'sessionSource',
                'pageTitle',
                'country',
                'browser',
                'operatingSystem',
                'screenResolution',
                'region',
                'city'
            ];

            // If filters applied → use all dimensions
            $dimensions = $hasFilters ? $allDimensions : $basicDimensions;

            // Fetch country data
            $response = Analytics::get(
                $period,
                ['activeUsers'],
                $dimensions
            );

            // Apply filters if any
            if ($hasFilters) {
                $response = $this->applyDetailedFilters($response, $filters);
            }


            $total = $response->sum('activeUsers');

            $data = $response->map(function ($row) use ($total) {
                $sessions = $row['activeUsers'] ?? 0;
                return [
                    'country' => $row['country'] ?? '(not set)',
                    'visitors' => number_format($sessions),
                    'percentage' => $total > 0 ? round(($sessions / $total) * 100) . '%' : '0%'
                ];
            });

            return response()->json($data);
        } catch (\Exception $e) {
            \Log::error('Countries data error: ' . $e->getMessage());
            return response()->json([]);
        }
    }

    public function getRegionsData(Request $request)
    {
        try {
            $websiteId = $request->get('website_id');
            $dateRange = $request->get('date_range', 'today');
            $customrange = $request->get('date');
            $propertyId = config('app.property_id');
            $propertyId = $this->setupAnalytics($propertyId);
            // Determine period
            if ($dateRange === 'custom_range' && $customrange) {
                $period = $this->getPeriodFromRange($customrange);
            } else {
                $period = $this->getPeriodFromRange($dateRange);
            }

            $filters = json_decode($request->filters ?? '{}', true);
            $hasFilters = !empty($filters) && count($filters) > 0;

            // DIMENSIONS BASED ON FILTERS
            $basicDimensions = [
                'region'
            ];

            $allDimensions = [
                'sessionDefaultChannelGroup',
                'sessionSource',
                'pageTitle',
                'country',
                'browser',
                'operatingSystem',
                'screenResolution',
                'region',
                'city'
            ];

            // If filters applied → use all dimensions
            $dimensions = $hasFilters ? $allDimensions : $basicDimensions;
            // Fetch region data
            $response = Analytics::get(
                $period,
                ['activeUsers'],
                $dimensions
            );

            // Apply filters if any
            if ($hasFilters) {
                $response = $this->applyDetailedFilters($response, $filters);
            }


            $total = $response->sum('activeUsers');

            $data = $response->map(function ($row) use ($total) {
                $sessions = $row['activeUsers'] ?? 0;
                return [
                    'region' => $row['region'] ?? '(not set)',
                    'visitors' => number_format($sessions),
                    'percentage' => $total > 0 ? round(($sessions / $total) * 100) . '%' : '0%'
                ];
            });

            return response()->json($data);
        } catch (\Exception $e) {
            \Log::error('Regions data error: ' . $e->getMessage());
            return response()->json([]);
        }
    }

    public function getCitiesData(Request $request)
    {
        try {
            $websiteId = $request->get('website_id');
            $dateRange = $request->get('date_range', 'today');
            $customrange = $request->get('date');

            $propertyId = config('app.property_id');
            $propertyId = $this->setupAnalytics($propertyId);
            // Determine period
            if ($dateRange === 'custom_range' && $customrange) {
                $period = $this->getPeriodFromRange($customrange);
            } else {
                $period = $this->getPeriodFromRange($dateRange);
            }

            $filters = json_decode($request->filters ?? '{}', true);
            $hasFilters = !empty($filters) && count($filters) > 0;

            // DIMENSIONS BASED ON FILTERS
            $basicDimensions = [
                'city'
            ];

            $allDimensions = [
                'sessionDefaultChannelGroup',
                'sessionSource',
                'pageTitle',
                'country',
                'browser',
                'operatingSystem',
                'screenResolution',
                'region',
                'city'
            ];

            // If filters applied → use all dimensions
            $dimensions = $hasFilters ? $allDimensions : $basicDimensions;

            // Fetch city data
            $response = Analytics::get(
                $period,
                ['activeUsers'],
                $dimensions
            );

            // Apply filters if any
            if ($hasFilters) {
                $response = $this->applyDetailedFilters($response, $filters);
            }


            $total = $response->sum('activeUsers');

            $data = $response->map(function ($row) use ($total) {
                $sessions = $row['activeUsers'] ?? 0;
                return [
                    'city' => $row['city'] ?? '(not set)',
                    'visitors' => number_format($sessions),
                    'percentage' => $total > 0 ? round(($sessions / $total) * 100) . '%' : '0%'
                ];
            });

            return response()->json($data);
        } catch (\Exception $e) {
            \Log::error('Cities data error: ' . $e->getMessage());
            return response()->json([]);
        }
    }

    public function getLocationdetails(Request $request)
    {
        try {
            $websiteId = $request->get('website_id');
            $type = $request->get('type', 'countries');
            $dateRange = $request->get('date_range', 'today');
            $customrange = $request->get('date');
            $propertyId = config('app.property_id');
            $propertyId = $this->setupAnalytics($propertyId);
            // Determine period
            $period = ($dateRange === 'custom_range' && $customrange)
                ? $this->getPeriodFromRange($customrange)
                : $this->getPeriodFromRange($dateRange);

            // Decode filter JSON
            $filters = json_decode($request->filters ?? '{}', true);
            if (!is_array($filters)) {
                $filters = [];
            }

            $hasFilters = !empty($filters) && count($filters) > 0;

            // Set dimension based on type
            switch ($type) {
                case 'countries':
                    $primaryDimension = 'country';
                    break;
                case 'regions':
                    $primaryDimension = 'region';
                    break;
                case 'cities':
                    $primaryDimension = 'city';
                    break;
                case 'map':
                    $primaryDimension = 'country';
                    break;
                default:
                    $primaryDimension = 'country';
            }

            // Define all possible dimensions needed for filtering
            $allDimensions = [
                'sessionDefaultChannelGroup',
                'sessionSource',
                'pageTitle',
                'country',
                'browser',
                'operatingSystem',
                'screenResolution',
                'region',
                'city'
            ];

            // If filters are applied, use all dimensions, otherwise just the primary one
            $dimensions = $hasFilters ? $allDimensions : [$primaryDimension];

            // Fetch GA data with appropriate dimensions (ensure metric is activeUsers)
            $response = Analytics::get($period, ['activeUsers'], $dimensions, 10000);

            // Apply filters if any
            if ($hasFilters) {
                $response = $this->applyDetailedFilters($response, $filters);
            }

            // Group by primary dimension and sum activeUsers (use ints)
            $groupedData = [];
            foreach ($response as $row) {
                $key = $row[$primaryDimension] ?? '(not set)';

                if ($key === 'Unknown' || $key === '(not set)' || empty($key)) {
                    $key = '(not set)';
                }

                if (!isset($groupedData[$key])) {
                    $groupedData[$key] = [
                        $primaryDimension => $key,
                        'visitors' => 0
                    ];
                }

                // Important: cast to int here
                $groupedData[$key]['visitors'] += (int)($row['activeUsers'] ?? 0);
            }

            // Convert to collection and sort by visitors (raw ints)
            $locations = collect($groupedData)
                ->sortByDesc('visitors')
                ->values();

            // Calculate percentages (use raw integer totals)
            if ($locations->isNotEmpty()) {
                // totalVisitors is integer sum of raw visitors (no formatting)
                $totalVisitors = $locations->sum('visitors');

                $locations = $locations->map(function ($location) use ($totalVisitors) {
                    $rawVisitors = (int)$location['visitors'];

                    $percentage = $totalVisitors > 0
                        ? round(($rawVisitors / $totalVisitors) * 100, 1) // 1 decimal like GA
                        : 0;

                    // attach raw visitors (we'll format later if needed)
                    $location['percentage'] = $percentage . '%';
                    $location['visitors'] = $rawVisitors;
                    return $location;
                });
            }

            // Format visitors for UI for non-map types AFTER calculating percentages
            if ($type !== 'map') {
                $locations = $locations->map(function ($location) {
                    $location['visitors'] = number_format($location['visitors']);
                    return $location;
                });
            }

            return response()->json($locations);
        } catch (\Exception $e) {
            \Log::error("Location details error for type {$type}: " . $e->getMessage());
            return response()->json([]);
        }
    }

    public function getWebsiteStats(Request $request)
    {
        try {
            $websiteId = $request->get('website_id');
            $dateRange = $request->get('date_range', 'today');
            $customrange = $request->get('date');
            $propertyId = config('app.property_id');
            $propertyId = $this->setupAnalytics($propertyId);

            // ------------------- REALTIME MODE --------------------
            if ($dateRange === 'realtime') {

                $rt = $this->getRealtimeStats($propertyId);

                return response()->json([
                    'analytics' => [
                        'unique_visitors'      => $this->formatNumber($rt['unique_visitors_30min']),
                        'total_pageviews'      => $this->formatNumber($rt['pageviews_30min']),
                        'total_visits'         => 0,
                        'views_per_visit'      => 0,
                        'bounce_rate'          => 0,
                        'avg_session_duration' => 0,
                        'date_range' => 'realtime',
                        'website_id' => $websiteId,
                    ],
                    'date_range' => 'realtime',
                    'website_id' => $websiteId,
                    'filters_applied' => null
                ]);
            }
            // ---------------- END REALTIME MODE -------------------


            // ------------------- NORMAL GA4 MODE ------------------
            if ($dateRange === 'custom_range' && $customrange) {
                $period = $this->getPeriodFromRange($customrange);
            } else {
                $period = $this->getPeriodFromRange($dateRange);
            }

            $filters = json_decode($request->filters ?? '{}', true);
            $hasFilters = !empty($filters);

            $dimensions = $hasFilters ? [
                'sessionDefaultChannelGroup',
                'sessionSource',
                'pageTitle',
                'country',
                'browser',
                'operatingSystem',
                'screenResolution',
                'region',
                'city'
            ] : [];

            $totaluniqueVisitorsData = Analytics::get($period, ['activeUsers'], $dimensions);
            $totalVisitsData = Analytics::get($period, ['sessions'], $dimensions);
            $totalPageviewsData = Analytics::get($period, ['screenPageViews'], $dimensions);
            $bounceRateData = Analytics::get($period, ['bounceRate'], $dimensions);
            $avgSessionDurationData = Analytics::get($period, ['averageSessionDuration'], $dimensions);

            if ($hasFilters) {
                $totaluniqueVisitorsData = $this->applyDetailedFilters($totaluniqueVisitorsData, $filters);
                $totalVisitsData = $this->applyDetailedFilters($totalVisitsData, $filters);
                $totalPageviewsData = $this->applyDetailedFilters($totalPageviewsData, $filters);
                $bounceRateData = $this->applyDetailedFilters($bounceRateData, $filters);
                $avgSessionDurationData = $this->applyDetailedFilters($avgSessionDurationData, $filters);
            }

            $uniqueVisitors = $totaluniqueVisitorsData->sum('activeUsers') ?? 0;
            $totalVisits = $totalVisitsData->sum('sessions') ?? 0;
            $totalPageviews = $totalPageviewsData->sum('screenPageViews') ?? 0;

            $bounceRate = $this->calculateBounceRate($period);
            $avgSessionDuration = $this->getAvgSessionDuration($period);

            $viewsPerVisit = $totalVisits > 0 ? round($totalPageviews / $totalVisits, 2) : 0;

            $analytics = [
                'unique_visitors' => $this->formatNumber($uniqueVisitors),
                'total_visits' => $this->formatNumber($totalVisits),
                'total_pageviews' => $this->formatNumber($totalPageviews),
                'views_per_visit' => $viewsPerVisit,
                'bounce_rate' => $this->formatPercentage($bounceRate),
                'avg_session_duration' => $this->formatDuration($avgSessionDuration),
            ];

            return response()->json([
                'analytics' => $analytics,
                'date_range' => $dateRange,
                'website_id' => $websiteId,
                'filters_applied' => $hasFilters ? $filters : null
            ]);
        } catch (\Exception $e) {
            \Log::error('Analytics API Error: ' . $e->getMessage());
            return response()->json([
                'error' => $e->getMessage(),
                'analytics' => $this->getEmptyAnalyticsData()
            ], 500);
        }
    }


    public function getRealtimeStats($propertyId)
    {
        $property = "properties/" . $propertyId;
        $accessToken = $this->getAccessToken();

        $apiUrl = "https://analyticsdata.googleapis.com/v1beta/{$property}:runRealtimeReport";

        $request = [
            "metrics" => [
                ["name" => "screenPageViews"],
                ["name" => "activeUsers"]
            ],
            "dimensions" => [
                ["name" => "minutesAgo"]
            ],
            "limit" => 30
        ];

        $response = Http::withToken($accessToken)
            ->post($apiUrl, $request)
            ->json();

        $pageviews = 0;
        $activeUsersNow = 0;
        $activeUsersTimeline = [];

        if (!empty($response["rows"])) {

            foreach ($response["rows"] as $row) {

                $minutesAgo = (int)$row["dimensionValues"][0]["value"];
                $pageviews += (int)$row["metricValues"][0]["value"];

                $activeUsers = (int)$row["metricValues"][1]["value"];

                // Save for timeline chart
                $activeUsersTimeline[$minutesAgo] = $activeUsers;

                // Active users at this exact moment
                if ($minutesAgo === 0) {
                    $activeUsersNow = $activeUsers;
                }
            }
        }

        ksort($activeUsersTimeline);

        return [
            "pageviews_30min"         => $pageviews,        // Total last 30 minutes
            "active_users_now"        => $activeUsersNow,   // Right now
            "active_users_timeline"   => $activeUsersTimeline, // minute-by-minute
        ];
    }

    private function getAccessToken()
    {
        $path = storage_path('app/google-analytics-credentials.json');

        $scopes = ['https://www.googleapis.com/auth/analytics.readonly'];

        $creds = new ServiceAccountCredentials($scopes, $path);

        $token = $creds->fetchAuthToken();

        return $token['access_token'];
    }


    function formatK($num)
    {
        if ($num >= 1000) {
            return round($num / 1000, 1) . 'K';
        }
        return $num;
    }

    private function formatDurationFromSeconds($seconds): string
    {
        if (!$seconds) {
            return '0s';
        }

        $minutes = floor($seconds / 60);
        $remainingSeconds = $seconds % 60;

        if ($minutes > 0) {
            return $minutes . 'm ' . $remainingSeconds . 's';
        } else {
            return $remainingSeconds . 's';
        }
    }

    private function formatNumber($number): string
    {
        return number_format($number);
    }
    private function formatPercentage($percentage): string
    {
        return $percentage . '%';
    }


    /**
     * Format duration for existing string format
     */
    private function formatDuration($durationString): string
    {
        if (strpos($durationString, ':') !== false) {
            $parts = explode(':', $durationString);
            if (count($parts) === 3) {
                $hours = (int)$parts[0];
                $minutes = (int)$parts[1];
                $seconds = (int)$parts[2];
                $totalSeconds = ($hours * 3600) + ($minutes * 60) + $seconds;
                return $this->formatDurationFromSeconds($totalSeconds);
            }
        }

        return $durationString;
    }

    private function calculateBounceRate(Period $period): float
    {
        $data = Analytics::get($period, ['bounceRate']);
        return isset($data[0]['bounceRate'])
            ? round((float)$data[0]['bounceRate'] * 100, 2)
            : 0.0;
    }

    private function getAvgSessionDuration(Period $period): string
    {
        $data = Analytics::get($period, ['averageSessionDuration']);

        $seconds = isset($data[0]['averageSessionDuration'])
            ? (int)$data[0]['averageSessionDuration']
            : 0;

        return $this->formatDurationFromSeconds($seconds);
    }

    private function getEmptyAnalyticsData()
    {
        return [
            'unique_visitors' => '0',
            'total_visits' => '0',
            'total_pageviews' => '0',
            'views_per_visit' => 0,
            'bounce_rate' => '0%',
            'avg_session_duration' => '0s'
        ];
    }

    public function getRealtimeActivity(Request $request)
    {
        try {
            $propertyId = config('app.property_id');
            $propertyId = $this->setupAnalytics($propertyId);

            // GA4 Realtime Endpoint
            $apiUrl = "https://analyticsdata.googleapis.com/v1beta/properties/{$propertyId}:runRealtimeReport";

            // PAYLOAD (Last 30 minutes → 29 minutes ago to now)
            $payload = [
                "dimensions" => [
                    ["name" => "country"],
                    ["name" => "deviceCategory"],
                    ["name" => "unifiedScreenName"],
                    ["name" => "minutesAgo"],
                ],
                "metrics" => [
                    ["name" => "activeUsers"]
                ],
                "minuteRanges" => [
                    [
                        "name" => "last30minutes",
                        "startMinutesAgo" => 29,
                        "endMinutesAgo" => 0
                    ]
                ]
            ];

            $accessToken = $this->getAccessToken();

            $response = Http::withToken($accessToken)->post($apiUrl, $payload);

            if (!$response->successful()) {
                return response()->json([
                    "error" => $response->json(),
                    "activities" => []
                ]);
            }

            $data = $response->json();

            $activities = [];

            if (!empty($data['rows'])) {
                foreach ($data['rows'] as $row) {
                    $dimensions = $row['dimensionValues'];
                    $metrics = $row['metricValues'];
                    $minutesAgo = (int)($dimensions[3]['value'] ?? 0);

                    $activities[] = [
                        "country"     => $dimensions[0]['value'] ?? "--",
                        "device_type" => $dimensions[1]['value'] ?? "--",
                        "page"        => $dimensions[2]['value'] ?? "--",
                        "minutesAgo"  => $this->formatMinutesAgo($dimensions[3]['value'] ?? 0),
                        "time"        => $this->convertMinutesAgoToTime($minutesAgo),
                        "activeUsers" => $metrics[0]['value'] ?? 0
                    ];
                }
            }

            return response()->json([
                'activities' => $activities
            ]);
        } catch (\Exception $e) {
            \Log::error('Realtime Data Error: ' . $e->getMessage());
            return response()->json([
                'error' => $e->getMessage(),
                'activities' => []
            ], 500);
        }
    }

    private function formatMinutesAgo($minutes)
    {
        if ($minutes == 0) return "just now";
        if ($minutes == 1) return "1 minute ago";
        return "{$minutes} minutes ago";
    }

    private function convertMinutesAgoToTime($minutesAgo)
    {
        $time = now()->subMinutes($minutesAgo);
        return $time->format('M d, h:i A'); // Example: Dec 01, 12:10 PM
    }
}
