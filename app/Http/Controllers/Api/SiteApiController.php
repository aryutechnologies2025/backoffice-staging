<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;



use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

use App\Models\Podcast;
use App\Models\Settings;
use App\Models\FAQ;


class SiteApiController extends Controller
{

    public function getheader_dts()
    {
        $settings = Settings::first(); // Or use where('type', 'header')->first() if you categorize settings

        // Check if settings were found
        if (!$settings) {
            return response()->json([
                'message' => 'Header Settings not found'
            ], 404); // Not Found
        }

        // Return the settings data
        return response()->json([
            'app_name' => $settings->app_name,
            'site_logo' => $settings->site_logo,
            'fav_icon' => $settings->fav_icon,
            'meta_title' => $settings->meta_title,
            'meta_keywords' => $settings->meta_keywords,
            'meta_desc' => $settings->meta_desc,
            'meta_desc' => $settings->meta_desc,
        ], 200);
    }

    public function getfooter_dts(){
        $settings = Settings::first(); // Or use where('type', 'header')->first() if you categorize settings

        // Check if settings were found
        if (!$settings) {
            return response()->json([
                'message' => 'Footer Settings not found'
            ], 404); // Not Found
        }

        // Return the settings data
        return response()->json([
            'footer_logo' => $settings->footer_logo,
            'official_footer_logo' => $settings->official_logo,
            'contact_email' => $settings->contact_email,
            'contact_number' => $settings->contact_number,
            'contact_address' => $settings->contact_address,
            'copyright' => $settings->copyright,
            'android_link' => $settings->android_link,
            'ios_link' => $settings->ios_link,
            'facebook' => $settings->facebook,
            'instagram' => $settings->instagram,
            'twitter_x' => $settings->twitter_x,
            'linkedin' => $settings->linkedin,
            'youtube_url' => $settings->youtube_url,
            'pinterest' => $settings->pinterest,
        ], 200);
    }


    public function getSettings()
    {
        // Retrieve settings from the database
        $settings = Settings::first(); // Or use where('type', 'header')->first() if you categorize settings

        // Check if settings were found
        if (!$settings) {
            return response()->json([
                'message' => 'Settings not found'
            ], 404); // Not Found
        }

        // Return the settings data
        return response()->json([
            'app_name' => $settings->app_name,
            'site_logo' => $settings->site_logo,
            'footer_logo' => $settings->footer_logo,
            'fav_icon' => $settings->fav_icon,
            'contact_email' => $settings->contact_email,
            'contact_number' => $settings->contact_number,
            'contact_address' => $settings->contact_address,
            'copyright' => $settings->copyright,
            'android_link' => $settings->android_link,
            'ios_link' => $settings->ios_link,
            'facebook' => $settings->facebook,
            'instagram' => $settings->instagram,
            'twitter_x' => $settings->twitter_x,
            'linkedin' => $settings->linkedin,
            'youtube_url' => $settings->youtube_url,
            'pinterest' => $settings->pinterest,
            'meta_title' => $settings->meta_title,
            'meta_keywords' => $settings->meta_keywords,
            'meta_desc' => $settings->meta_desc,
            'meta_desc' => $settings->meta_desc,
        ], 200);
    }

    public function getFaq()
    {
        try {
            // Retrieve FAQs from the database
            // $faqs = Faq::all(); // Adjust this if you need specific data or filtering
            $faqs = Faq::where('status', "1")
                ->where('is_deleted', "0")
                ->get(['question', 'answer']);
            // Check if FAQs were found
            if ($faqs->isEmpty()) {
                return response()->json([
                    'message' => 'No FAQ content found'
                ], 404); // Not Found
            }

            // Return the FAQ data
            return response()->json([
                'faqs' => $faqs
            ], 200);
        } catch (ModelNotFoundException $e) {
            // Handle case where model is not found
            return response()->json([
                'message' => 'FAQs not found'
            ], 404); // Not Found
        } catch (Exception $e) {
            // Handle any other exceptions
            return response()->json([
                'message' => 'An error occurred while fetching FAQ content',
                'error' => $e->getMessage()
            ], 500); // Internal Server Error
        }
    }

    // public function getPodcastDetails($id) {
    //     // Fetch the podcast by ID
    //     $podcast = Podcast::find($id);
    
    //     // Check if the podcast was found
    //     if (!$podcast) {
    //         return response()->json([
    //             'message' => 'Podcast not found'
    //         ], 404); // Not Found
    //     }
    
    //     // Return the podcast data
    //     return response()->json([
    //         'id'           => $podcast->id,
    //         'title'        => $podcast->title,
    //         'description'  => $podcast->description,
    //         'cover_image'  => $podcast->cover_image,
    //         'audio_url'    => $podcast->audio_url,
    //         'published_at' => $podcast->published_at->toIso8601String(), // Format as ISO 8601
    //     ], 200); // OK
    // }

    public function getPodcasts() {
        // Fetch all podcasts from the database
        // $podcasts = Podcast::all();
        $podcasts = Podcast::where('status', "1")
                        ->where('is_deleted', "0")
                        ->get();

        // Check if there are podcasts
        if ($podcasts->isEmpty()) {
            return response()->json([
                'message' => 'No podcasts found'
            ], 404); // Not Found
        }
    
        // Return the list of podcasts
        return response()->json([
            'data' => $podcasts->map(function ($podcast) {
                return [
                    'id'           => $podcast->id,
                    'podcast_link'        => $podcast->podcast_link,
                    'podcast_thumb_pic'  => $podcast->podcast_thumb_pic,
                ];
            }),
        ], 200); // OK
    }
    
}

