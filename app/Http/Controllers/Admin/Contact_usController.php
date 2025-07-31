<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactUs;

class Contact_usController extends Controller
{
    public function list(Request $request)
    {
        $title = 'Contact-Us List';
        $contact_dts = ContactUs::with('user')->where('is_deleted', '0')->orderBy('created_at', 'desc')->get();

        return view('admin.contact_us.contactlist', compact('title', 'contact_dts'));
    }

    public function delete(Request $request)
    {
        // Retrieve the request data
        $record_id = $request->input('record_id');

        // Find the admin record by ID
        $user = ContactUs::find($record_id);
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
