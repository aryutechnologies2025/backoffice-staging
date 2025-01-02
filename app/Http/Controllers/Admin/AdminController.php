<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;

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
    
        $programCount = Cache::remember('program_count', 60, function () {
            return DB::table('inclusive_package_details')->where('is_deleted', "0")->count();
        });
    
        $userRegister = Cache::remember('user_register_count', 60, function () {
            return DB::table('users')->where('is_deleted', "0")->count();
        });
    
        $enquiryCount = Cache::remember('enquiry_count', 60, function () {
            return DB::table('enquiry_details')->count();
        });
    
        $clientReview = Cache::remember('client_review_count', 60, function () {
            return DB::table('client_review')->where('is_deleted', "0")->count();
        });
    
        return view('dashboard.dashboard', compact('title', 'programCount', 'userRegister', 'enquiryCount', 'clientReview'));
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
