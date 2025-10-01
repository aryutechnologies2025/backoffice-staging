<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProgramEvents;
use App\Models\EventRegistration;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;


class ProgramEventController extends Controller
{
    public function list(Request $request)
    {
        try {
            $programdetails = ProgramEvents::withCount(['registration'])->where('is_deleted', '0')->where('status', '1')->orderBy('id', 'desc')->get();

            $currentDateTime = now(); // Current date and time

            $programdetails->transform(function ($program) use ($currentDateTime) {
                $startDateTime = \Carbon\Carbon::createFromFormat('Y-m-d h:i A', $program->start_datetime);
                $endDateTime = \Carbon\Carbon::createFromFormat('Y-m-d h:i A', $program->end_datetime);

                // Determine the type based on current date/time
                if ($currentDateTime->between($startDateTime, $endDateTime)) {
                    $program->type = 'live';
                } elseif ($currentDateTime->gt($endDateTime)) {
                    $program->type = 'past';
                } else {
                    $program->type = 'upcoming';
                }

                return $program;
            });

            return response()->json([
                'status' => 'success',
                'data' => [
                    'programdetails' => $programdetails
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching program details.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function viewevents(Request $request, $id)
    {
        try {
            $programdetails = ProgramEvents::withCount(['registration'])->where('id', $id)->where('is_deleted', '0')->orderBy('id', 'desc')->first();
            return response()->json([
                'status' => 'success',
                'data' => [
                    'programdetails' => $programdetails
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching program details.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function registration(Request $request)
    {
        try {

            // Check for duplicate email for this event
            // $existingRegistration = EventRegistration::where('email', $request->email)
            //     ->where('event_id', $request->event_id)
            //     ->where('is_deleted', '0')
            //     ->first();

            // if ($existingRegistration) {
            //     return response()->json([
            //         'status' => 'error',
            //         'message' => 'Validation failed',
            //         'errors' => [
            //             'email' => ['This email is already registered for this event.']
            //         ],
            //     ], 422);
            // }


            // $existingRegistration = EventRegistration::where('phone', $request->phone)
            //     ->where('event_id', $request->event_id)
            //     ->where('is_deleted', '0')
            //     ->first();

            // if ($existingRegistration) {
            //     return response()->json([
            //         'status' => 'error',
            //         'message' => 'Validation failed',
            //         'errors' => [
            //             'phone' => ['This phone is already registered for this event.']
            //         ],
            //     ], 422);
            // }

            // Check for duplicate email and phone for this event
            $errors = [];

            $existingEmail = EventRegistration::where('email', $request->email)
                ->where('event_id', $request->event_id)
                ->where('is_deleted', '0')
                ->first();

            if ($existingEmail) {
                $errors['email'] = ['This email is already registered for this event.'];
            }

            $existingPhone = EventRegistration::where('phone', $request->phone)
                ->where('event_id', $request->event_id)
                ->where('is_deleted', '0')
                ->first();

            if ($existingPhone) {
                $errors['phone'] = ['This phone number is already registered for this event.'];
            }

            // If there are any errors, return them together
            if (!empty($errors)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $errors,
                ], 422);
            }

            $user = new EventRegistration;
            $user->event_id = $request->input('event_id');
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->phone = $request->input('phone');
            $user->status = '1';
            $user->created_date = date('Y-m-d H:i:s');
            $user->is_deleted = '0';
            $user->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Registration created successfully!',
                'data' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching program details.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function vieweventregistration(Request $request, $id)
    {
        try {
            $programdetails = EventRegistration::select('id', 'name')->where('event_id', $id)->where('is_deleted', '0')->orderBy('id', 'desc')->get();
            return response()->json([
                'status' => 'success',
                'data' => [
                    'programdetails' => $programdetails
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching program details.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
