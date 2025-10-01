<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EventRegistration;


class EventRegisterController extends Controller
{
    public function list(Request $request)
    {
        $title = 'Events Registation';
        $programdetails = EventRegistration::with(['event'])->where('is_deleted', '0')->orderBy('id', 'desc')->get();
        return view('admin.eventregister.eventRegisterList', compact('title', 'programdetails'));
    }


    public function change_status(Request $request)
    {
        // Retrieve the request data
        $record_id = $request->input('record_id');
        $mode = $request->input('mode');

        // Find the admin record by ID
        $City = EventRegistration::find($record_id);

        if ($City) {
            // Update the status based on the mode value
            if ($mode == 0) {
                $City->status = "0";
            } else {
                $City->status = "1";
            }

            $City->save();

            // Prepare the response
            $response = [
                'status' => '1',
                'response' => 'Register status changed successfully.'
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
        $City = EventRegistration::find($record_id);
        if ($City) {
            // Update the is_deleted field to 1
            $City->is_deleted = "1";
            $City->save();

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
