<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
        $admin = null;
        $retryCount = 0;
        $maxRetries = 5;
        $retryDelay = 1000; // in milliseconds

        while ($retryCount < $maxRetries) {
            try {
                $admin = Admin::where('email', $email)->first();
                break; // Exit loop if successful
            } catch (\Illuminate\Database\QueryException $ex) {
                if ($ex->getCode() == 1226) { // MySQL error code for max_connections_per_hour exceeded
                    $retryCount++;
                    usleep($retryDelay * 1000); // Convert milliseconds to microseconds
                } else {
                    throw $ex; // Rethrow if it's a different error
                }
            }
        }

        if (!$admin) {
            return back()->withErrors([
                'email' => 'Unable to process your request at the moment. Please try again later.',
            ])->onlyInput('email');
        }

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
        return view('dashboard.dashboard', compact('title'));
    }
    public function logout(Request $request)
    {

        Auth::guard('admin')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
