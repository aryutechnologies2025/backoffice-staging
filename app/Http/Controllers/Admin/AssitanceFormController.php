<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\assitance;
use Illuminate\Http\Request;

class AssitanceFormController extends Controller
{
    public function list()
    {
        $title = 'Assitance Form List';
        $assitance = assitance::where('is_delected', '0')->orderBy('created_at', 'desc')->get();
        return view('admin.contact_us.assitance' , compact ('title', 'assitance'));
    }

      public function delete(Request $request)
    {
        // Retrieve the request data
        $record_id = $request->input('record_id');

        // Find the admin record by ID
        $user = assitance::find($record_id);
        if ($user) {
            // Update the is_deleted field to 1
            $user->is_delected = "1";

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
