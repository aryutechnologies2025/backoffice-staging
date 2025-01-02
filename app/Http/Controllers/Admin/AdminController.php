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
    /**
     * Show the admin login page.
     */
    public function index()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        $title = 'Admin';
        $megatitle = 'Login';
        return view('admin.page.admin_login', compact('title', 'megatitle'));
    }

    /**
     * Show the admin signup form.
     */
    public function signup_form()
    {
        $megatitle = 'Signup';
        $title = 'Admin sign up';
        return view('admin.page.sign_up', compact('title', 'megatitle'));
    }

    /**
     * Handle admin registration.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:admin,email',
            'phone' => 'required',
            'password' => 'required|min:8',
        ]);

        $admin = new Admin();
        $admin->name = $request->input('name');
        $admin->email = $request->input('email');
        $admin->phone = $request->input('phone');
        $admin->password = bcrypt($request->input('password')); // Encrypt password

        $admin->save();

        return redirect()->route('admin.login')
            ->withSuccess('Admin registered successfully. Please log in.');
    }

    /**
     * Handle admin login.
     */
    public function check_login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        $email = $request->input('email');
        $password = $request->input('password');

        // Cache admin data to reduce queries
        $admin = Cache::remember("admin_user_{$email}", 3600, function () use ($email) {
            return Admin::where('email', $email)->first();
        });

        if ($admin && Auth::guard('admin')->attempt(['email' => $email, 'password' => $password])) {
            $request->session()->regenerate();
            $adminName = $admin->name;
            $request->session()->put('admin_email', $admin->email);
            $request->session()->put('admin_name', $adminName);

            return redirect()->route('admin.dashboard')
                ->withSuccess('You have successfully logged in!');
        }

        return back()->withErrors([
            'email' => 'Your provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Show the admin dashboard with cached counts.
     */
    public function dashboard()
    {
        $title = 'Dashboard';

        $dashboardData = Cache::remember('dashboard_data', 120, function () {
            return [
                'programCount' => DB::table('inclusive_package_details')->where('is_deleted', "0")->count(),
                'userRegister' => DB::table('users')->where('is_deleted', "0")->count(),
                'enquiryCount' => DB::table('enquiry_details')->count(),
                'clientReview' => DB::table('client_review')->where('is_deleted', "0")->count(),
            ];
        });

        return view('dashboard.dashboard', array_merge(['title' => $title], $dashboardData));
    }

    /**
     * Handle admin logout.
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
