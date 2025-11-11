<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProgramEvents;
use App\Models\EventRegistration;
use App\Models\EventRegistrationDetails;
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
            $user_id = $request->query('user_id');

            $programdetails = ProgramEvents::withCount(['registration'])
            ->with(['eventdetails' => function($query) use ($user_id) {
                $query->where('user_id', $user_id);
            }])->where('id', $id)->where('is_deleted', '0')->orderBy('id', 'desc')->first();
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
            //latest hide
            // $errors = [];

            // $existingEmail = EventRegistration::where('email', $request->email)
            //     ->where('event_id', $request->event_id)
            //     ->where('is_deleted', '0')
            //     ->first();

            // if ($existingEmail) {
            //     $errors['email'] = ['This email is already registered for this event.'];
            // }

            // $existingPhone = EventRegistration::where('phone', $request->phone)
            //     ->where('event_id', $request->event_id)
            //     ->where('is_deleted', '0')
            //     ->first();

            // if ($existingPhone) {
            //     $errors['phone'] = ['This phone number is already registered for this event.'];
            // }

            // // If there are any errors, return them together
            // if (!empty($errors)) {
            //     return response()->json([
            //         'status' => 'error',
            //         'message' => 'Validation failed',
            //         'errors' => $errors,
            //     ], 422);
            // }

            // $user = new EventRegistration;
            // $user->event_id = $request->input('event_id');
            // $user->name = $request->input('name');
            // $user->email = $request->input('email');
            // $user->phone = $request->input('phone');
            // $user->status = '1';
            // $user->created_date = date('Y-m-d H:i:s');
            // $user->is_deleted = '0';
            // $user->save();

            $profilePath = public_path('/uploads/profiles_pic');

            if (!file_exists($profilePath)) {
                if (!mkdir($profilePath, 0755, true) && !is_dir($profilePath)) {
                    Log::error('Failed to create directory: ' . $profilePath);
                    return response()->json(['error' => 'Failed to create upload directory'], 500);
                }
            }

            $filePath1 = $request->input('profile_image');
            if ($request->hasFile('image_1')) {
                try {
                    $file1 = $request->file('image_1');
                    $filename1 = time() . '_1.' . $file1->getClientOriginalExtension();
                    $file1->move($profilePath, $filename1);
                    $filePath1 = 'uploads/profiles_pic/' . $filename1;

                    // Log success
                    Log::info('File uploaded successfully: ' . $filePath1);
                } catch (\Exception $e) {
                    // Log error and return response
                    Log::error('File upload error: ' . $e->getMessage());
                    return response()->json(['error' => 'File upload failed'], 500);
                }
            }

            $user = EventRegistration::create([
                'event_id' => $request->input('event_id'),
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'profile_image' => $filePath1,
                'email' => $request->input('email'),
                'dob' => $request->input('dob'),
                'phone' => $request->input('phone'),
                'street' => $request->input('street'),
                'anniversary_date' => $request->input('anniversary_date'),
                'city' => $request->input('city'),
                'state' => $request->input('state'),
                'zip_province_code' => $request->input('zip_province_code'),
                'country' => $request->input('country'),
                'preferred_lang' => $request->input('preferred_lang'),
                'newsletter_sub' => (bool) $request->input('newsletter_sub'),
                'terms_condition' => (bool) $request->input('terms_condition'),
                'status' => "1",
                'is_deleted' => "0",
                'created_by' => "user",
                'created_date' => date('Y-m-d'),

            ]);

            $details = new EventRegistrationDetails();
            $details->event_id = $request->input('event_id');
            $details->user_id = $request->input('id');
            $details->registration_id = $user->id;
            $details->save();

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
            $programdetails = EventRegistration::select('id', 'first_name', 'last_name')->where('event_id', $id)->where('is_deleted', '0')->orderBy('id', 'desc')->get();
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
