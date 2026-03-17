<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\FollowUp;
use App\Models\HomeEnquiryDetail;
use App\Models\EnquiryDetail;
use App\Models\Review;
use App\Models\ModulePermission;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

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
    }

    public function signup_form()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        $title = 'Register';
        $megatitle = 'Registration';
        return view('admin.page.admin_register', compact('title', 'megatitle'));
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|min:2|max:50',
            'last_name' => 'required|string|min:2|max:50',
            'email' => 'required|email|unique:admin,email',
            'phone' => 'required|unique:admin,phone|regex:/^[0-9]{10,}$/',
            'password' => 'required|min:8|confirmed',
        ], [
            'first_name.required' => 'First name is required',
            'last_name.required' => 'Last name is required',
            'email.unique' => 'This email is already registered',
            'phone.unique' => 'This phone number is already registered',
            'phone.regex' => 'Phone number must be at least 10 digits',
            'password.confirmed' => 'Passwords do not match',
        ]);

        $admin = new Admin();
        $admin->first_name = $request->first_name;
        $admin->last_name = $request->last_name;
        $admin->email = $request->email;
        $admin->phone = $request->phone;
        $admin->password = Hash::make($request->password);
        $admin->profile_pic = 'uploads/user_profile/default.png';
        $admin->status = '1';
        $admin->role_id = null;
        $admin->is_deleted = '0';
        $admin->created_by = 'self';
        $admin->save();

        return redirect()->route('admin.login')
            ->with('success', 'Registration successful! Please log in with your credentials.');
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
            
            // Load permissions based on admin's role
            $permissions = [];
            if ($admin->role_id) {
                $modulePermissions = ModulePermission::whereHas('permission', function($query) use ($admin) {
                    $query->where('role_id', $admin->role_id)->where('status', '1');
                })->get();
                
                foreach ($modulePermissions as $perm) {
                    $permissions[$perm->module] = [
                        'view' => (bool)$perm->is_view,
                        'create' => (bool)$perm->is_create,
                        'edit' => (bool)$perm->is_edit,
                        'delete' => (bool)$perm->is_delete,
                        'list' => (bool)$perm->is_list,
                    ];
                }
            }
            $request->session()->put('permissions', $permissions);
            
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

        $clientReview = Review::with('package','user')
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
            $enquiry_dts = collect();
        }

        $followupCount = count($followupIds);

        return view('dashboard.dashboard', compact('title', 'programCount', 'userRegister', 'enquiryCount','enquiryHomeCount', 'clientReview', 'userRegisterGoogle', 'followupCount', 'followupIds'));
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
