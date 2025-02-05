<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EnquiryDetail;
use Illuminate\Http\Request;
use App\Models\HomeEnquiryDetail;

class HomeEnquiryController extends Controller
{
    public function list(Request $request)
    {
        $title = 'Enquiry List';
        $enquiry_dts = HomeEnquiryDetail::orderBy('created_at', 'desc')->get();

        return view('admin.home_enquiry.homeenquirylist', compact('title', 'enquiry_dts'));
    }


    public function markFollowUp(Request $request, $id)
    {
        $enquiry = HomeEnquiryDetail::findOrFail($id);
    
        // Check the dropdown value
        if ($request->followup === 'followup') {
            $enquiry->followup = true;
            $enquiry->save();
    
            // Transfer to enquirydetails
            EnquiryDetail::create([
                'name' => $enquiry->name,
                'email' => $enquiry->email,
                'phone' => $enquiry->phone,
                'location' => $enquiry->location,
                'travel_destination' => $enquiry->travel_destination,
                'comments' => $enquiry->comments,
                'days' => $enquiry->days,
                'budget_per_head' => $enquiry->budget_per_head,
                'cab_need' => $enquiry->cab_need,
                'total_count' => $enquiry->total_count,
                'male_count' => $enquiry->male_count,
                'female_count' => $enquiry->female_count,
                'travel_date' => $enquiry->travel_date,
                'rooms_count' => $enquiry->rooms_count,
                'child_count' => $enquiry->child_count,
            ]);
    
            // Optional: Delete after transfer
            $enquiry->delete();
    
            return response()->json(['success' => true, 'message' => 'Data transferred successfully.']);
        } elseif ($request->followup === 'unfollowup') {
            // Handle unfollowup case
            $enquiry->followup = false; // Set followup to false
            $enquiry->save();
    
            return response()->json(['success' => true, 'message' => 'Follow-up status set to unfollow-up.']);
        }
    
        return response()->json(['success' => false, 'message' => 'Invalid follow-up status selected.']);
    }
    
}