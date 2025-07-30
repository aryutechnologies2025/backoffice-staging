<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\Models\City;
use App\Models\StagReview;
use App\Models\InclusivePackages;
use App\Models\stays_destination_details;
use App\Models\User;

class StayreviewController extends Controller
{
    public function list(Request $request)
    {
        
        $title = 'Stay Review List';
        $review_dts = StagReview::with('stag','user')
            ->where('is_deleted', '0')
            ->orderBy('id', 'desc')
            ->get();
       
            return view('admin.stay_review.stay_reviewlist', compact('title', 'review_dts'));
    }
  
    public function review_delete(Request $request)
    {
        // Retrieve the request data
        $record_id = $request->input('record_id');

        // Find the admin record by ID
        $review = StagReview::find($record_id);
        if ($review) {
            // Update the is_deleted field to 1
            $review->is_deleted = "1";

           
            $review->save();

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

    public function add_form()
    {
        $title = 'Add StayReview';
        $users = User::where('status', "1")->where('is_deleted', "0")->get();
        $program_dts = stays_destination_details::where('status', "1")->where('is_deleted', "0")->pluck('stay_title', 'id');
        return view('admin.stay_review.stay_reviewadd', compact('title','program_dts','users'));
    }

    public function insert(Request $request)
    {
       
        //    print_r($_POST);die;

        // dd($request->all());
        $credentials = $request->validate([
            'program_name' => 'required',
            'client_name' => 'required',
            'client_review' => 'required',
            'rating' => 'required',
        ]);

      
        $client_review = new StagReview;
        $client_review->stag_id = $request->input('program_name');
        $client_review->user_id = $request->input('client_name');
        $client_review->review = $request->input('client_review');
        $client_review->rating = $request->input('rating');
        $client_review->created_by = 'admin';
        $client_review->is_deleted = '0';
        $client_review->save();

        return redirect()->route('admin.stay_review_list')
            ->with('success', 'Stay Review created successfully.');
    }

    public function edit_form(Request $request, $id)
    {
        $client_details = StagReview::find($id);
        $program_dts = stays_destination_details::where('status', "1")->where('is_deleted', "0")->pluck('stay_title', 'id');
        $selectedprogramId = $client_details->program_id;
        $users = User::where('status', "1")->where('is_deleted', "0")->get();

        $title = 'Destination Edit';
        return view('admin.stay_review.stay_reviewedit', compact('client_details', 'title','selectedprogramId','program_dts','users'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'program_name' => 'required',
            'client_name' => 'required',
            // 'client_role' => 'required',
            'client_review' => 'required',
            'rating' => 'required',
        ]);

        $client_review = StagReview::find($id);
        $client_review->stag_id = $request->input('program_name');
        $client_review->user_id = $request->input('client_name');
        $client_review->review = $request->input('client_review');
        $client_review->rating = $request->input('rating');
        $client_review->save();

        return redirect()->route('admin.stay_review_list')
            ->with('success', 'Stay Review updated successfully');
    }

    public function change_status(Request $request)
    {
        // Retrieve the request data
        $record_id = $request->input('record_id');
        $mode = $request->input('mode');

        // Find the admin record by ID
        $client_review = StagReview::find($record_id);

        if ($client_review) {
            // Update the status based on the mode value
            if ($mode == 0) {
                $client_review->status = "0";
            } else {
                $client_review->status = "1";
            }
            // Update the updated_date field
            $client_review->updated_date = date('Y-m-d H:i:s');
            $client_review->status_changed_by = 'admin';
            $client_review->save();

            // Prepare the response
            $response = [
                'status' => '1',
                'response' => 'Client Review changed successfully.'
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

    public function delete(Request $request)
    {
        // Retrieve the request data
        $record_id = $request->input('record_id');

        // Find the admin record by ID
        $client_review = StagReview::find($record_id);
        if ($client_review) {
            // Update the is_deleted field to 1
            $client_review->is_deleted = "1";

            // Set the updated_date field
            // $client_review->updated_date = date('Y-m-d H:i:s');
            // $client_review->deleted_by = 'admin';
            // Save the changes
            $client_review->save();

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
