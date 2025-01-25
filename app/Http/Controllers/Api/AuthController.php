<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\ContactAdminEmail;
use App\Mail\ContactEmail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

use App\Models\ContactUs;
use App\Models\User;


use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{

    public function signup(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'image_1' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|min:8|confirmed',
            'dob' => 'required|date',
            'phone' => 'required|string|max:15',
            'street' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip_province_code' => 'required|string|max:10',
            'country' => 'required|string|max:255',
            'preferred_lang' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $profilePath = public_path('/uploads/profiles_pic');

        if (!file_exists($profilePath)) {
            if (!mkdir($profilePath, 0755, true) && !is_dir($profilePath)) {
                Log::error('Failed to create directory: ' . $profilePath);
                return response()->json(['error' => 'Failed to create upload directory'], 500);
            }
        }

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

        try {
            $user = User::create([
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'profile_image' => $filePath1,
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'dob' => $request->input('dob'),
                'phone' => $request->input('phone'),
                'street' => $request->input('street'),
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
                'created_date' => now(),

            ]);

            // Return success response
            return response()->json([
                'message' => 'User created successfully!',
                'user' => $user,
            ], 201);
        } catch (\Exception $e) {
            // Log error and return response
            Log::error('User creation error: ' . $e->getMessage());
            return response()->json(['error' => 'User creation failed'], 500);
        }
    }

    public function user_update(Request $request, $id)
    {
        // Find the user
        $details = User::find($id);
    
        if (!$details) {
            return response()->json([
                'error' => 'User not found.'
            ], 404);
        }
    
        // Profile image upload
        $profilePath = public_path('/uploads/profiles_pic');
    
        if (!file_exists($profilePath)) {
            if (!mkdir($profilePath, 0755, true) && !is_dir($profilePath)) {
                Log::error('Failed to create directory: ' . $profilePath);
                return response()->json(['error' => 'Failed to create upload directory.'], 500);
            }
        }
    
        $filePath1 = $details->profile_image; // Keep the existing profile image
    
        if ($request->hasFile('image_1')) {
            try {
                $file1 = $request->file('image_1');
                $filename1 = time() . '_1.' . $file1->getClientOriginalExtension();
                $file1->move($profilePath, $filename1);
                $filePath1 = 'uploads/profiles_pic/' . $filename1;
    
                // Log success
                Log::info('File uploaded successfully: ' . $filePath1);
    
                // Optional: Delete the old profile image if it exists
                if ($details->profile_image && file_exists(public_path($details->profile_image))) {
                    unlink(public_path($details->profile_image));
                }
            } catch (\Exception $e) {
                Log::error('File upload error: ' . $e->getMessage());
                return response()->json(['error' => 'File upload failed.'], 500);
            }
        }
    
        // Update user details
        $details->first_name = $request->input('first_name', $details->first_name);
        $details->last_name = $request->input('last_name', $details->last_name);
        $details->email = $request->input('email', $details->email);
        $details->dob = $request->input('dob', $details->dob);
        $details->phone = $request->input('phone', $details->phone);
        $details->street = $request->input('street', $details->street);
        $details->city = $request->input('city', $details->city);
        $details->state = $request->input('state', $details->state);
        $details->zip_province_code = $request->input('zip_province_code', $details->zip_province_code);
        $details->country = $request->input('country', $details->country);
        $details->profile_image = $filePath1;
    
        $details->save();
    
        return response()->json([
            'message' => 'User updated successfully!',
            'user' => $details,
        ], 200);
    }
    



    public function login(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        // dump('check');

        // dump($request->input('email'));
        // echo '<pre>';
        // print_r($user);
        // echo '</pre>';


        // Find the user by email
        $user = User::where('email', $request->input('email'))->first();


        // echo '<pre>';
        // print_r($user);
        // echo '</pre>';

        // exit();
        // Check if the user exists
        if (!$user) {
            return response()->json(['error' => 'Unauthorized User '], 401);
        }

        // Authentication
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['error' => 'Incorrect password'], 401);
        }

        // Re-authenticate the user to get the updated user instance
        $user = Auth::user();

        // Check user status and deletion status
        if ($user->is_deleted) {
            return response()->json(['error' => 'Your account has been deleted. Please contact admin.'], 403);
        }

        if ($user->status != 1) {
            return response()->json(['error' => 'Your account is inactive. Please contact admin.'], 403);
        }


        $userDetails = $user->makeHidden([
            'email_verified_at',
            'status',
            'is_deleted',
            'created_date',
            'created_by',
            'updated_by',
            'updated_date',
            'status_changed_by',
            'deleted_by',
            'created_at',
            'updated_at'
        ]);

        // Generate token
        $token = $user->createToken('Personal Access Token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful!',
            'token' => $token,
            'user_details' => $userDetails,
        ], 200);
    }


    // public function getToken(): JsonResponse
    // {
    //     return response()->json(['csrfToken' => csrf_token()]);
    // }

    public function getToken(): JsonResponse
    {
        // Define a dummy security key (sample key)
        $dummyKey = 'mmY9oVQiSX7sQO5UXl2upBvOkSKMk0XMXMretwv3'; // Sample key

        // Return only the dummy key
        return response()->json([
            'csrfToken' => $dummyKey,
        ]);
    }


    // public function store(Request $request)
    // {
    //     try {
    //         // Validate the request
    //         $rules = [
    //             'first_name' => 'required|string|max:255',
    //             'last_name' => 'required|string|max:255',
    //             'email' => 'required|email|max:255',
    //             'phone' => 'required|string|max:20',
    //             'message' => 'required|string',
    //         ];

    //         $validator = Validator::make($request->all(), $rules);

    //         if ($validator->fails()) {
    //             return response()->json([
    //                 'errors' => $validator->errors()
    //             ], 422);
    //         }

    //         // Get the logged-in user ID if authenticated
    //         $userId = Auth::check() ? Auth::id() : null;

    //         // if (Auth::check()) {
    //         //     $userId = Auth::id();
    //         // } else {
    //         //     $userId = null;
    //         // }

    //         // Log userId for debugging
    //         // \Log::info('User ID:', ['user_id' => $userId]);


    //         // Store the contact us data
    //         $contact = ContactUs::create([
    //             'user_id' => $userId,
    //             'first_name' => $request->input('first_name'),
    //             'last_name' => $request->input('last_name'),
    //             'email' => $request->input('email'),
    //             'phone' => $request->input('phone'),
    //             'message' => $request->input('message'),
    //         ]);

    //         return response()->json([
    //             'message' => 'Contact message sent successfully!',
    //             'contact' => $contact
    //         ], 201);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'error' => 'An error occurred: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }

    public function store(Request $request)
    {
        try {
            // Validate the request
            $rules = [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'message' => 'required|string',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }

            // Get the logged-in user ID if authenticated, otherwise null
            $userId = Auth::check() ? Auth::id() : null;

            // Log userId for debugging
            //\Log::info('User ID:', ['user_id' => $userId]);

            // Store the contact us data
            $contact = ContactUs::create([
                'user_id' => $userId,
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'message' => $request->input('message'),
            ]);

            return response()->json([
                'message' => 'Contact message sent successfully!',
                'contact' => $contact
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }


    public function logout(Request $request)
    {
        // Revoke all tokens for the authenticated user
        $user = $request->user();
        $user->tokens()->delete();

        return response()->json([
            'message' => 'All tokens have been revoked.'
        ], 200);
    }


    public function store_contact(Request $request)
    {


        $fname = $request->input('first_name');
        $lname = $request->input('last_name');
        $email = $request->input('email');
        $phone = $request->input('phone');
        $msg = $request->input('message');


        //    $fname='test';
        //    $lname='test';
        //    $email='test@gmail.com';
        //    $phone='12345678';
        //    $msg= 'test_msg';


        // Store the contact us data
        $contact = ContactUs::create([
            // 'user_id' => $userId,  from react login user id
            'first_name' => $fname,
            'last_name' => $lname,
            'email' => $email,
            'phone' =>  $phone,
            'message' =>  $msg


        ]);
        try {
            // Send email to the client
            Mail::to($contact->email)->send(new ContactEmail([
                'name' => $contact->first_name,
                'email' => $contact->email,
                'phone' => $contact->phone,
                'comments' => $contact->message,
            ]));

            // Send email to admin
            Mail::to('barathkrishnamoorthy17@gmail.com')->send(new ContactAdminEmail([
                'name' => $contact->first_name,
                'email' => $contact->email,
                'phone' => $contact->phone,
                'comments' => $contact->message,

            ]));
        } catch (\Exception $e) {
            // Log any email sending errors
            Log::error('Mail failed: ' . $e->getMessage());
        }
        return response()->json([
            'message' => 'Contact message sent successfully!',
            'contact' => $contact
        ], 201);
    }
}
