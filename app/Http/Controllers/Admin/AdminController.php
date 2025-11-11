<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\FollowUp;
use App\Models\HomeEnquiryDetail;
use App\Models\EnquiryDetail;
use App\Models\Review;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class AdminController extends Controller
{
    public function index()
    {

        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        $title = 'Admin';
        $megatitle = 'Login';
        return view('admin.page.admin_login', compact('title', 'megatitle'));
        // return view('admin.pages.login');
    }

    public function signup_form()
    {
        $megatitle = 'Signup';
        $title = 'Admin sign up';
        return view('admin.page.sign_up', compact('title', 'megatitle'));
    }

    public function register(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:admin,email',
            'phone' => 'required',
            'password' => 'required',
        ]);

        $admin = new Admin();
        $admin->name = $request->input('name');
        $admin->email = $request->input('email');
        $admin->phone = $request->input('phone');
        $admin->password = bcrypt($request->input('password')); // Encrypt password as needed

        $admin->save();
        return redirect()->route('admin.login')
            ->withSuccess('Admin registered successfully. Please log in.');
        // return redirect()->route('admin.login')->with('success', 'Admin created successfully. Please log in.');
    }

    public function check_login(Request $request)
    {

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $email = $request->input('email');
        $password = $request->input('password');
        $admin = Admin::where('email', $email)->first();

        if (Auth::guard('admin')->attempt(['email' => $email, 'password' => $password])) {
            $request->session()->regenerate();
            $adminName = $admin->first_name . ' ' . $admin->last_name;
            $request->session()->put('admin_email', $admin->email);
            $request->session()->put('admin_name', $adminName);
            $request->session()->put('admin_profile_pic', $admin->profile_pic);
            return redirect()->route('admin.dashboard')
                ->withSuccess('You have successfully logged in!');
        }

        return back()->withErrors([
            'email' => 'Your provided credentials do not match in our records.',
        ])->onlyInput('email');
    }


    public function dashboard()
    {
        $title = 'Dashboard';

        $programCount = Cache::remember('program_count', 120, function () {
            return DB::table('inclusive_package_details')->where('is_deleted', "0")->count();
        });

        $userRegister = DB::table('users')->where('is_deleted', "0")->where('login_type', 'login')->count();

        $today = date('Y-m-d');
        $enquiryCount = EnquiryDetail::with('package')
        ->where('is_deleted', '0')
        ->whereDate('travel_date', '>=', $today)
        ->orderBy('travel_date', 'asc')
        ->count();
        $enquiryHomeCount = DB::table('home_enquiry_details')
            ->where('is_deleted', '0')
            ->count();

        $clientReview = Review::with('package','user') // Eager load the related theme
            ->where('is_deleted', '0')
            ->count();

      

        $userRegisterGoogle = DB::table('users')->where('is_deleted', "0")->where('login_type', 'google')->count();

        $current_date = date('Y-m-d');
        $followupIds = FollowUp::where('next_follow_up_date', $current_date)
            ->orderBy('id', 'desc')
            ->pluck('home_id')
            ->toArray();

        if (!empty($followupIds)) {
            $enquiry_dts = HomeEnquiryDetail::whereIn('id', $followupIds)
                ->orderBy('created_at', 'desc')
                ->where('is_deleted', '0')
                ->get();
        } else {
            $enquiry_dts = collect(); // Empty collection
        }

        $followupCount = count($followupIds);


        return view('dashboard.dashboard', compact('title', 'programCount', 'userRegister', 'enquiryCount','enquiryHomeCount', 'clientReview', 'userRegisterGoogle', 'followupCount', 'followupIds'));
    }


    // public function getInclusivePackagesCount()
    // {
    //     // Get the count of inclusive packages
    //     $programCount = DB::table('inclusive_package_details')->where('is_deleted', "0")->count();

    //     // Return view with the count
    //     return view('dashboard.dashboard', compact('programCount'));
    // }

    public function logout(Request $request)
    {

        Auth::guard('admin')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
