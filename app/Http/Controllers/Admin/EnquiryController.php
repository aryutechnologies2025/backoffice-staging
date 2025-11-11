<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EnquiryDetail;
use App\Models\EnquiryFollow_up;
use App\Models\FollowUp;
use App\Models\HomeEnquiryDetail;
use App\Models\MailTemplate;
use App\Models\Settings;
use App\Mail\BookingProcessingMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\JsonResponse;

class EnquiryController extends Controller
{
    public function list(Request $request)
    {
        $title = 'Booking List';
        $today = date('Y-m-d');

        // Initialize query
        $enquiry_dts = EnquiryDetail::with('package')
            ->where('is_deleted', '0');

        // Check if BOTH date filters are applied
        $filtersApplied = $request->filled('from_date') && $request->filled('to_date');

        if ($filtersApplied) {
            // Use provided dates (both are guaranteed to be filled)
            $fromdate = $request->from_date;
            $todate = $request->to_date;

            // Validate dates - if either date is invalid, use today for both
            if (!strtotime($fromdate) || !strtotime($todate)) {
                $fromdate = $today;
                $todate = $today;
            }

            $enquiry_dts->whereBetween('travel_date', [$fromdate, $todate]);
        } else {
            // First load or incomplete filters - show only future dates
            $fromdate = $today;
            $todate = $today;
            $enquiry_dts->whereDate('travel_date', '>=', $today);
        }

        $enquiry_dts = $enquiry_dts->orderBy('travel_date', 'asc')->get();

        return view('admin.enquiry.enquirylist', compact('title', 'enquiry_dts', 'fromdate', 'todate'));
    }

    public function downloadAll(Request $request)
    {
        $today = date('Y-m-d');
        try {
            $query = EnquiryDetail::with('package')
                ->where('is_deleted', '0');

            // Check if BOTH date filters are applied
            $filtersApplied = $request->filled('from_date') && $request->filled('to_date');

            if ($filtersApplied) {
                // Use provided dates (both are guaranteed to be filled)
                $fromdate = $request->from_date;
                $todate = $request->to_date;

                // Validate dates - if either date is invalid, use today for both
                if (!strtotime($fromdate) || !strtotime($todate)) {
                    $fromdate = $today;
                    $todate = $today;
                }

                $query->whereBetween('travel_date', [$fromdate, $todate]);
            } else {
                // First load or incomplete filters - show only future dates
                $query->whereDate('travel_date', '>=', $today);
            }

            // Apply search filters if provided
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%")
                        ->orWhere('phone', 'LIKE', "%{$search}%")
                        ->orWhere('program_title', 'LIKE', "%{$search}%");
                });
            }

            $query->orderBy('travel_date', 'asc');
            $allData = $query->get();

            return response()->json($allData);
        } catch (\Exception $e) {
            \Log::error('downloadAll error: ' . $e->getMessage());

            return response()->json([
                'status' => 0,
                'response' => 'Error retrieving data',
            ], 500);
        }
    }

    public function view_form(Request $request, $id)
    {
        $user_details = EnquiryDetail::find($id);
        $title = 'View User';
        return view('admin.enquiry.enquiryview', compact('user_details', 'title'));
    }

    public function addFollowUp(Request $request, $enquiryId)
    {
        $validated = $request->validate([
            'follow_up_date' => 'required|date',

            'lead_source' => 'required|string',
            'lead_status' => 'required|string',
            'follow_up_notes' => 'required|string',
            'deal_value' => 'required|numeric',
            'assigned_to' => 'required|string',
            'next_follow_up_date' => 'required|date',
        ]);

        $validated['home_id'] = $enquiryId;
        FollowUp::create($validated);

        // return redirect()->route('admin.home_enquiry_list')->with('success', 'Follow-up added successfully!');
        return redirect()->to('admin/home-enquiry/followups/' . $enquiryId)
            ->with('success', 'Follow-up added successfully!');
    }

    public function viewFollowUps($enquiryId)
    {
        $enquiry = HomeEnquiryDetail::with('followUps')->findOrFail($enquiryId);

        return view('admin.enquiry.followups', compact('enquiry'));
    }

    public function showEnquiryForm($id)
    {
        $enquiry = HomeEnquiryDetail::findOrFail($id); // Retrieve the enquiry by ID

        return view('admin.enquiry.form', compact('enquiry')); // Pass the enquiry to the view
    }



    public function addEnquiryFollowUp(Request $request, $enquiryId)
    {
        $data = $request->all();
        $data['enquiry_id'] = $enquiryId;
        EnquiryFollow_up::create($data);

        // return redirect()->route('admin.enquiry_list')->with('success', 'Follow-up added successfully!');

        return redirect()->to('admin/enquiry/followups/' . $enquiryId)
            ->with('success', 'Follow-up added successfully!');
    }

    public function viewEnquiryFollowUps($enquiryId)
    {
        $enquiry = EnquiryDetail::with('enquiryFollowUps')->findOrFail($enquiryId);

        $programdetails = DB::table('inclusive_package_details')->where('is_deleted', '0')->where('status', '1')->pluck('title', 'id');

        return view('admin.enquiry.enquiry_followup', compact('enquiry', 'programdetails'));
    }

    public function showEnquiryFollowUpForm($id)
    {
        $enquiry = EnquiryDetail::findOrFail($id); // Retrieve the enquiry by ID

        return view('admin.enquiry.form', compact('enquiry')); // Pass the enquiry to the view
    }

    //add store function for enquirydetails
    public function insert(Request $request)
    {
        $enquirydetails = new EnquiryDetail();
        $enquirydetails->fill($request->all());
        $enquirydetails->save();
        return redirect()->route('admin.enquiry_list')->with('success', 'Enquiry added successfully!');
    }

    //add add_form function for enquirydetails
    public function add_form()
    {
        $title = 'Add Book Enquiry';
        return view('admin.enquiry.add_enquiry', compact('title'));
    }


    public function change_status(Request $request)
    {
        // Find the enquiry
        $enquiry = EnquiryDetail::find($request->enquiry_id);

        if ($enquiry) {
            $enquiry->status = $request->status;
            $enquiry->save();

            return response()->json([
                'status'   => 1,
                'response' => 'Status updated successfully.',
            ]);
        }

        return response()->json([
            'status'   => 0,
            'response' => 'Enquiry not found.',
        ]);
    }

    public function delete(Request $request)
    {
        // Retrieve the request data
        $record_id = $request->input('record_id');

        // Find the admin record by ID
        $user = EnquiryDetail::find($record_id);
        if ($user) {
            // Update the is_deleted field to 1
            $user->is_deleted = "1";

            // Set the updated_date field
            $user->updated_date = date('Y-m-d H:i:s');
            $user->deleted_by = 'admin';
            // Save the changes
            $user->save();

            // Prepare the response
            $response = [
                'status' => '1',
                'response' => 'Record marked as deleted successfully.'
            ];
        } else {
            // Record not found
            $response = [
                'status' => '0',
                'response' => 'Record not found.'
            ];
        }

        // Return the response as JSON
        return response()->json($response);
    }

    public function mailtemplate(Request $request)
    {
        try {
            $enquiry_id = $request->enquiry_id;
            $emailtemplate = $request->status;

            // Validate request
            if (!$enquiry_id || !$emailtemplate) {
                return response()->json([
                    'status' => 0,
                    'response' => 'Enquiry ID and template type are required'
                ]);
            }

            // Get enquiry details with program relationship
            $enquirydetails = EnquiryDetail::with(['program'])->find($enquiry_id);
            if (!$enquirydetails) {
                return response()->json([
                    'status' => 0,
                    'response' => 'Enquiry not found'
                ]);
            }

            // Check if email exists
            if (empty($enquirydetails->email)) {
                return response()->json([
                    'status' => 0,
                    'response' => 'Customer email not found'
                ]);
            }

            // Get settings
            $settings = Settings::first();
            if (!$settings) {
                return response()->json([
                    'status' => 0,
                    'response' => 'Settings not found'
                ]);
            }

            // Get mail template
            $mailtemplate = MailTemplate::where('sub_title', $emailtemplate)
                ->where('is_deleted', '0')
                ->where('status', '1')
                ->first();

            if (!$mailtemplate) {
                return response()->json([
                    'status' => 0,
                    'response' => 'Mail template not found or inactive'
                ]);
            }

            // Calculate amounts
            $total_price = $enquirydetails->pricing ?? 0;
            $partical_amount = $total_price * 0.5;
            $balance_amount = $total_price * 0.5;

            // Format date range properly
            $date_range = '-';
            if (!empty($enquirydetails->travel_date)) {
                $start_date = date('d M, Y', strtotime($enquirydetails->travel_date));
                if (!empty($enquirydetails->travel_enddate)) {
                    $end_date = date('d M, Y', strtotime($enquirydetails->travel_enddate));
                    $date_range = $start_date . ' to ' . $end_date;
                } else {
                    $date_range = $start_date;
                }
            }

            // Get the mail template body
            $mail_body = $mailtemplate->mail_template;

            // Initialize replacements array
            $replacements = [];

            // Set replacements based on template type
            if ($emailtemplate == 'send_booking_process') {
                $replacements = [
                    '[total_count]' => $enquirydetails->total_count ?? '-',
                    '[event]' => $enquirydetails->program->title ?? 'No Program',
                    '[date]' => $date_range,
                    '[location]' => $enquirydetails->location ?? '-',
                    '[leader_name]' => $settings->leader_name ?? '-',
                    '[contact]' => $settings->leader_contact ?? '-',
                    '[total_price]' => 'Rs.' . number_format($total_price, 2) . '/-',
                    '[gpay_number]' => $settings->gpay_number ?? '-',
                    '[acc_name]' => $settings->ac_name ?? '-',
                    '[acc_number]' => $settings->ac_number ?? '-', // Fixed: was ac_number
                    '[ifsc_code]' => $settings->ifsc_code ?? '-',
                    '[bank_name]' => $settings->bank_name ?? '-',
                    '[branch_name]' => $settings->branch_name ?? '-',
                    '[partical_amount]' => 'Rs.' . number_format($partical_amount, 2) . '/-',
                    '[balance_amount]' => 'Rs.' . number_format($balance_amount, 2) . '/-', // Added balance amount
                ];
            } else if ($emailtemplate == 'advance_payment_completed') {
                $replacements = [
                    '[program_name]' => $enquirydetails->program->title ?? 'No Program',
                    '[date]' => $date_range,
                    '[total_payment]' => 'Rs.' . number_format($total_price, 2) . '/-',
                    '[advance_amount]' => 'Rs.' . number_format($partical_amount, 2) . '/-',
                    '[balance_amount]' => 'Rs.' . number_format($balance_amount, 2) . '/-',
                    // Add common fields that might be used
                    '[leader_name]' => $settings->leader_name ?? '-',
                    '[contact]' => $settings->leader_contact ?? '-',
                ];
            } else if ($emailtemplate == 'final_payment_completed') {
                $replacements = [
                    '[program_name]' => $enquirydetails->program->title ?? 'No Program',
                    '[date]' => $date_range,
                    '[total_payment]' => 'Rs.' . number_format($total_price, 2) . '/-',
                    // Add common fields that might be used
                    '[leader_name]' => $settings->leader_name ?? '-',
                    '[contact]' => $settings->leader_contact ?? '-',
                ];
            } else if ($emailtemplate == 'trip_cancelled') {
                $replacements = [
                    '[Trip Date]' => $date_range,
                ];
            }

            // Perform the replacements
            $final_mail_body = str_replace(
                array_keys($replacements),
                array_values($replacements),
                $mail_body
            );

            // Send email
            Mail::to($enquirydetails->email)->send(new BookingProcessingMail([
                'subject' => $mailtemplate->title ?? 'Booking Process Information',
                'body' => $final_mail_body
            ]));

            // Update enquiry record if needed (optional)
            $enquirydetails->update(['email_template' => $emailtemplate]);

            return response()->json([
                'status' => 1,
                'response' => 'Mail sent successfully to ' . $enquirydetails->email
            ]);
        } catch (\Exception $e) {
            \Log::error('Mail sending error: ' . $e->getMessage());
            return response()->json([
                'status' => 0,
                'response' => 'Error sending mail: ' . $e->getMessage()
            ]);
        }
    }
}
