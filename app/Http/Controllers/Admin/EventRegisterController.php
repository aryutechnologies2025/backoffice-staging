<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EventRegistration;
use App\Models\ProgramEvents;
use Illuminate\Support\Facades\Validator;


class EventRegisterController extends Controller
{
    public function list(Request $request)
    {
        $title = 'Events Registration';

        $fromdate = isset($_REQUEST["from_date"]) && !empty($_REQUEST["from_date"]) ? $_REQUEST["from_date"] : date("Y-m-d");
        $todate = isset($_REQUEST["to_date"]) && !empty($_REQUEST["to_date"]) ? $_REQUEST["to_date"] : date("Y-m-d");

        $event_name = isset($_REQUEST["event_name"]) && !empty($_REQUEST["event_name"]) ? $_REQUEST["event_name"] : 'all';

        $programdetails = EventRegistration::with(['event'])->whereDate('created_date', [$fromdate, $todate])->where('is_deleted', '0')->orderBy('id', 'desc');


        if (isset($event_name) && $event_name != 'all') {
            $programdetails = $programdetails->where('event_id', $event_name);
        }

        $programdetails = $programdetails->get();

        $eventNames = ProgramEvents::where('is_deleted', '0')
            ->where('status', '1')
            ->pluck('event_name', 'id')
            ->toArray();

        // dd($eventNames);

        return view('admin.eventregister.eventRegisterList', compact('title', 'programdetails', 'eventNames', 'fromdate', 'todate', 'event_name'));
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

    public function eventview(Request $request)
    {
        return view('admin.eventregister.eventRegisterview');
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

    public function view_form(Request $request, $id)
    {
        $user_details = EventRegistration::find($id);

        // dd($user_details);
        $title = 'View Details';
        return view('admin.home_enquiry.eventenquiryview', compact('user_details', 'title'));
    }

    public function updateEventNotes(Request $request)
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'eventuserid' => 'required|exists:event_registrations,id',
                'notes' => 'required|string|max:1000'
            ], [
                'eventuserid.required' => 'User ID is required.',
                'eventuserid.exists' => 'The selected event registration does not exist.',
                'notes.required' => 'Notes are required.',
                'notes.max' => 'Notes cannot exceed 1000 characters.'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            // Find and update the event registration
            $eventRegistration = EventRegistration::findOrFail($request->eventuserid);
            $eventRegistration->notes = $request->notes;
            $eventRegistration->save();

            return response()->json([
                'success' => true,
                'message' => 'Notes updated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update notes: ' . $e->getMessage()
            ], 500);
        }
    }
}
