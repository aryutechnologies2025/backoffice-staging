<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EnquiryDetail;
use App\Models\FollowUp;

class EnquiryController extends Controller
{
    public function list(Request $request)
    {
        $title = 'Booking List';
        $enquiry_dts = EnquiryDetail::with('package')->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.enquiry.enquirylist', compact('title', 'enquiry_dts'));
    }

    public function addFollowUp(Request $request, $enquiryId)
    {
        $validated = $request->validate([
            'follow_up_date' => 'required|date',
            
            'lead_source' => 'nullable|string',
            'lead_status' => 'nullable|string',
            'follow_up_notes' => 'nullable|string',
            'action_required' => 'nullable|string',
            'deal_value' => 'nullable|numeric',
            'assigned_to' => 'nullable|string',
            'interest_prospect' => 'nullable|string',
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
    
   
}
