<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EnquiryDetail;
use App\Models\FollowUp;
use Illuminate\Support\Facades\Log;

class EnquiryController extends Controller
{
    public function list(Request $request)
    {
        $title = 'Booking List';
        $enquiry_dts = EnquiryDetail::with('package')->orderBy('created_at', 'desc')->get();
       
        return view('admin.enquiry.enquirylist', compact('title', 'enquiry_dts'));
    }

    public function addFollowUp(Request $request, $enquiryId)
    {
        $validated = $request->validate([
            'follow_up_date' => 'required|date',
            
            'lead_source' => 'required|string',
            'lead_status' => 'required|string',
            'follow_up_notes' => 'required|string',
            'action_required' => 'required|string',
            'deal_value' => 'required|numeric',
            'assigned_to' => 'required|string',
            'interest_prospect' => 'required|string',
            'next_follow_up_date' => 'required|date',
        ]);
    
        $validated['enquiry_id'] = $enquiryId;
        FollowUp::create($validated);
    
        return redirect()->route('admin.enquiry_list')->with('success', 'Follow-up added successfully!');
    }
    
    public function viewFollowUps($enquiryId)
    {
        $enquiry = EnquiryDetail::with('followUps')->findOrFail($enquiryId);
    
        return view('admin.enquiry.followups', compact('enquiry'));
    }
    
    public function showEnquiryForm($id)
    {
        $enquiry = EnquiryDetail::findOrFail($id); // Retrieve the enquiry by ID

        return view('admin.enquiry.form', compact('enquiry')); // Pass the enquiry to the view
    }


   


       
}
