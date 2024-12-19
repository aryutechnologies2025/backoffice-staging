<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Podcast;

class PodcastController extends Controller
{
    public function list(Request $request)
    {
        $title = 'Podcast List';
        $podcast_dts = Podcast::where('is_deleted', '0')->paginate(10);
        return view('admin.podcast.podcastlist', compact('title', 'podcast_dts'));
    }

    public function add_form()
    {
        $title = 'Podcast Add';
        return view('admin.podcast.podcastadd', compact('title'));
    }

    public function insert(Request $request)
    {
        $credentials = $request->validate([
            'podcast_link' => 'required',
            'image_1' => 'required',
        ]);

        $destinationPath = public_path('/uploads/podcast_thumbnail_pic');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        if ($request->hasFile('image_1')) {
            $file1 = $request->file('image_1');
            $filename1 = time() . '_1.' . $file1->getClientOriginalExtension();
            $file1->move($destinationPath, $filename1);
            $filePath1 = 'uploads/podcast_thumbnail_pic/' . $filename1;
        }

        $podcast = new Podcast;
        $podcast->podcast_link = $request->input('podcast_link');
        $podcast->podcast_thumb_pic = $filePath1;
        $podcast->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';
        $podcast->created_date = date('Y-m-d H:i:s');
        $podcast->created_by = 'admin';
        $podcast->is_deleted = '0';
        $podcast->updated_at = null;
        $podcast->save();

        return redirect()->route('admin.podcastlist')
            ->with('success', 'Podcast created successfully.');
    }

    public function edit_form(Request $request, $id)
    {
        $podcast_details = Podcast::find($id);
        $title = 'Podcast Edit';
        return view('admin.podcast.podcastedit', compact('podcast_details', 'title'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'podcast_link' => 'required',

        ]);

        $destinationPath = public_path('/uploads/podcast_thumbnail_pic');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }



        $podcast = Podcast::find($id);
        if (!$podcast) {
            return redirect()->route('admin.podcastlist')
                ->with('error', 'Podcast not found.');
        }

        $podcast->podcast_link = $request->input('podcast_link');
        if ($request->hasFile('image_1')) {
            $file1 = $request->file('image_1');
            $filename1 = time() . '_1.' . $file1->getClientOriginalExtension();
            $file1->move($destinationPath, $filename1);
            $filePath1 = 'uploads/podcast_thumbnail_pic/' . $filename1;
            $podcast->podcast_thumb_pic = $filePath1;
        }

        $podcast->updated_date = date('Y-m-d H:i:s');
        $podcast->status = $request->has('status') && $request->input('status') === 'on' ? '1' : '0';
        $podcast->updated_by = 'admin';
        $podcast->save();

        return redirect()->route('admin.podcastlist')
            ->with('success', 'Podcast updated successfully');
    }

    public function change_status(Request $request)
    {
        // Retrieve the request data
        $record_id = $request->input('record_id');
        $mode = $request->input('mode');

        // Find the admin record by ID
        $podcast = Podcast::find($record_id);

        if ($podcast) {
            // Update the status based on the mode value
            if ($mode == 0) {
                $podcast->status = "0";
            } else {
                $podcast->status = "1";
            }
            $role = session('admin_role');
            // Update the updated_date field
            $podcast->updated_date = date('Y-m-d H:i:s');
            $podcast->status_changed_by = 'admin';
            $podcast->save();

            // Prepare the response
            $response = [
                'status' => '1',
                'response' => 'Podcast status changed successfully.'
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
        $podcast = Podcast::find($record_id);
        if ($podcast) {
            // Update the is_deleted field to 1
            $podcast->is_deleted = "1";

            // Set the updated_date field
            $podcast->updated_date = date('Y-m-d H:i:s');
            $podcast->deleted_by = 'admin';
            // Save the changes
            $podcast->save();

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
