<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\Models\City;
use App\Models\Clientreview;
use App\Models\InclusivePackages;
use App\Models\Review;
use App\Models\User;
use App\Models\StagReview;

class ClientreviewController extends Controller
{
    public function list(Request $request)
    {
        
        $title = 'Client Review List';
        $review_dts = Review::with('package','user') // Eager load the related theme
            ->where('is_deleted', '0')
            ->get();
        $stay_reviews = StagReview::with(['stag', 'user'])
        ->where('is_deleted', '0')
        ->orderBy('id', 'desc')
        ->get();
     
       
        return view('admin.client_review.client_reviewlist', compact('title', 'review_dts','stay_reviews'));
    }

//     public function list(Request $request)
// {
//     $title = 'All Reviews';

//     // Fetch client reviews
//     $client_reviews = Review::with(['package', 'user'])
//         ->where('is_deleted', '0')
//         ->orderBy('id', 'desc')
//         ->get();

//     // Fetch stay (stag) reviews
//     $stay_reviews = StagReview::with(['stag', 'user'])
//         ->where('is_deleted', '0')
//         ->orderBy('id', 'desc')
//         ->get();

//     return view('admin.reviews.all_reviews', compact('title', 'client_reviews', 'stay_reviews'));
// }

    public function review_list(Request $request)
    {
        
        $title = 'Client Review List';
        $review_dts = Review::with('package' , 'user') // Eager load the related theme
            ->where('is_deleted', '0')
            ->orderBy('created_at', 'desc')->get();
       
            return view('admin.review.reviewlist', compact('title', 'review_dts'));
    }

    public function review_delete(Request $request)
    {
        // Retrieve the request data
        $record_id = $request->input('record_id');

        // Find the admin record by ID
        $review = review::find($record_id);
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
        $title = 'Add ClientReview';
        $users = User::where('status', "1")->where('is_deleted', "0")->get();
        $program_dts = InclusivePackages::where('status', "1")->where('is_deleted', "0")->pluck('title', 'id');
        return view('admin.client_review.client_reviewadd', compact('title','program_dts','users'));
    }

    public function insert(Request $request)
    {
       
        //    print_r($_POST);die;

        // dd($request->all());
        $credentials = $request->validate([
            'program_name' => 'required',
            'client_name' => 'required',
            // 'client_role' => 'required',
            'client_review' => 'required',
            // 'review_dt' => 'required',
            'rating' => 'required',
        ]);

        $client_picPath = public_path('/uploads/client_pic');
        if (!file_exists($client_picPath)) {
            mkdir($client_picPath, 0755, true);
        }

        $filePath1 = null;
        if ($request->hasFile('image_1')) {
            $file1 = $request->file('image_1');
            $customFileName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $request->input('upload_image_name'));
            $filename1 = $customFileName . '.' . $file1->getClientOriginalExtension();
            $file1->move( $client_picPath, $filename1);
            $filePath1 = 'uploads/client_pic/' . $filename1;
        }
        $client_review = new Review;
        $client_review->package_id = $request->input('program_name');
        $client_review->user_id = $request->input('client_name');
        // $client_review->client_role = $request->input('client_role');
        $client_review->alternate_name = $request->input('alternate_image_name') ?? '';
        $client_review->upload_image_name = $request->input('upload_image_name') ?? '';

        $client_review->comment = $request->input('client_review');
        $client_review->review_dt = $request->input('review_dt') ?: null;
        $client_review->rating = $request->input('rating');
        $client_review->client_pic = $filePath1;
        $client_review->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';
        $client_review->created_date = date('Y-m-d H:i:s');
        $client_review->created_by = auth('admin')->user()->email;
        $client_review->is_deleted = '0';
        $client_review->updated_at = null;
        $client_review->save();

        return redirect()->route('admin.client_review_list')
            ->with('success', 'Client Review created successfully.');
    }

    public function edit_form(Request $request, $id)
    {
        $client_details = Review::find($id);
        $program_dts = InclusivePackages::where('status', "1")->where('is_deleted', "0")->pluck('title', 'id');
        $selectedprogramId = $client_details->program_id;
        $users = User::where('status', "1")->where('is_deleted', "0")->get();

        $title = 'Destination Edit';
        return view('admin.client_review.client_reviewedit', compact('client_details', 'title','selectedprogramId','program_dts','users'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'program_name' => 'required',
            'client_name' => 'required',
            // 'client_role' => 'required',
            'client_review' => 'required',
            // 'review_dt' => 'required',
            'rating' => 'required',
        ]);

        $client_picPath = public_path('/uploads/client_pic');
        if (!file_exists($client_picPath)) {
            mkdir($client_picPath, 0755, true);
        }

        $client_review = Review::find($id);
        if (!$client_review) {
            return redirect()->route('admin.client_review_list')
                ->with('error', 'Client Review not found.');
        }

        if ($request->hasFile('image_1')) {
            $file1 = $request->file('image_1');
            $customFileName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $request->input('upload_image_name'));
            $filename1 = $customFileName . '.' . $file1->getClientOriginalExtension();
            $file1->move($client_picPath, $filename1);
            $filePath1 = 'uploads/client_pic/' . $filename1;
            $client_review->client_pic = $filePath1; // Only update if a new file is uploaded
        }
        


        $client_review->package_id = $request->input('program_name');
        $client_review->user_id = $request->input('client_name');
        // $client_review->client_role = $request->input('client_role');
        $client_review->alternate_name = $request->input('alternate_image_name'); // Save alternate name
        $client_review->upload_image_name = $request->input('upload_image_name');
    
        $client_review->comment = $request->input('client_review');
        $client_review->review_dt = $request->input('review_dt') ?: null;
        $client_review->rating = $request->input('rating');
        $client_review->updated_date = date('Y-m-d H:i:s');
        $client_review->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';
        $client_review->updated_by = 'admin';
        $client_review->save();

        return redirect()->route('admin.client_review_list')
            ->with('success', 'Client Review updated successfully');
    }


    public function change_status(Request $request)
    {
        // Retrieve the request data
        $record_id = $request->input('record_id');
        $mode = $request->input('mode');

        // Find the admin record by ID
        $client_review = Review::find($record_id);

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
        $client_review = Review::find($record_id);
        if ($client_review) {
            // Update the is_deleted field to 1
            $client_review->is_deleted = "1";

            // Set the updated_date field
            $client_review->updated_date = date('Y-m-d H:i:s');
            $client_review->deleted_by = 'admin';
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
