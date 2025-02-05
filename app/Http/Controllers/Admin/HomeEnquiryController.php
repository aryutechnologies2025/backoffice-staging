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
        ]);

        // Optional: Delete after transfer
        $enquiry->delete();

        return response()->json(['success' => true, 'message' => 'Data transferred successfully.']);
    }

    return response()->json(['success' => false, 'message' => 'Follow-up status not selected.']);
}
}