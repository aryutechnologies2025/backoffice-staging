<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


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
            'image_1' => 'required',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|min:8|confirmed',
            'dob' => 'required|date',
            'phone' => 'required|string|max:15', // Adjusted to handle various phone formats
            'street' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip_province_code' => 'required|string|max:10',
            'country' => 'required|string|max:255',
            'preferred_lang' => 'required|string|max:255',
            // 'newsletter_sub' => 'required|boolean',
            // 'terms_condition' => 'required|boolean',
        ]);
    
        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }
    
        // Handle profile image upload
      
        // if ($request->hasFile('image_1')) {
        //     $image = $request->file('image_1');
        //     $imageName = time() . '.' . $image->getClientOriginalExtension();
        //     $destinationPath = public_path('/uploads/profiles_pic');
        //     if (!file_exists($destinationPath)) {
        //         mkdir($destinationPath, 0755, true);
        //     }
        //     $image->move($destinationPath, $imageName);
        // }
        $profilePath = public_path('/uploads/profiles_pic');
        if (!file_exists($profilePath)) {
            mkdir($profilePath, 0755, true);
        }

        if ($request->hasFile('image_1')) {
            $file1 = $request->file('image_1');
            $filename1 = time() . '_1.' . $file1->getClientOriginalExtension();
            $file1->move($profilePath, $filename1);
            $filePath1 = 'uploads/profiles_pic/' . $filename1;
        }
        // Create user record
        $user = User::create([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
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
            'created_date' => now(), // Using Laravel's helper
            'profile_image' => $filePath1, // Save the uploaded image name
        ]);
    
        // Return response
        return response()->json([
            'message' => 'User created successfully!',
            'user' => $user, // Include user data in the response
        ], 201);
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
      

           $fname=$request->input('first_name');
           $lname=$request->input('last_name');
           $email=$request->input('email');
           $phone=$request->input('phone');
           $msg= $request->input('message');

           
            //    $fname='test';
            //    $lname='test';
            //    $email='test@gmail.com';
            //    $phone='12345678';
            //    $msg= 'test_msg';


            // Store the contact us data
            $contact = ContactUs::create([
               // 'user_id' => $userId,  from react login user id
                'first_name' =>$fname,
                'last_name' =>$lname,
                'email' =>$email,
                'phone' =>  $phone,
                'message' =>  $msg
            ]);

            return response()->json([
                'message' => 'Contact message sent successfully!',
                'contact' => $contact
            ], 201);


       
    }
}


