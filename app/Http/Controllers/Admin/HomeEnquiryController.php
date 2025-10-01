<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EnquiryDetail;
use Illuminate\Http\Request;
use App\Models\HomeEnquiryDetail;
use App\Models\stay_enquiry_details;

class HomeEnquiryController extends Controller
{
    public function list(Request $request)
    {
        $title = 'Enquiry List';
        $enquiry_dts = HomeEnquiryDetail::orderBy('created_at', 'desc')->where('is_deleted','0')->get();

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


    public function insert(Request $request)
    {
        $enquirydetails = new HomeEnquiryDetail();
        $enquirydetails->fill($request->all());
        $enquirydetails->save();
        return redirect()->route('admin.home_enquiry_list')->with('success', 'Enquiry added successfully!');
    }

    //add add_form function for enquirydetails
    public function add_form()
    {
        $title = 'Add Enquiry Form';
        return view('admin.home_enquiry.home_enquiryform', compact('title'));
    }

    public function stayList(Request $request)
    {
        $title = 'Stay Enquiry List';
        $enquiry_dts = stay_enquiry_details::orderBy('created_at', 'desc')->where('is_deleted','0')->get();

        return view('admin.stay_enquiry.stayenquirylist', compact('title', 'enquiry_dts'));
    }

    public function view_form(Request $request, $id)
    {
        $user_details = HomeEnquiryDetail::find($id);

        // dd($user_details);
        $title = 'View Details';
        return view('admin.home_enquiry.homeenquiryview', compact('user_details', 'title'));
    }

    public function delete(Request $request)
    {
        // Retrieve the request data
        $record_id = $request->input('record_id');

        // Find the admin record by ID
        $user = HomeEnquiryDetail::find($record_id);
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

    public function stay_view_form(Request $request, $id)
    {
        $user_details = stay_enquiry_details::find($id);
        $title = 'View Details';
        return view('admin.home_enquiry.stayenquiryview', compact('user_details', 'title'));
    }

    public function staydelete(Request $request)
    {
        // Retrieve the request data
        $record_id = $request->input('record_id');

        // Find the admin record by ID
        $user = stay_enquiry_details::find($record_id);
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
}
