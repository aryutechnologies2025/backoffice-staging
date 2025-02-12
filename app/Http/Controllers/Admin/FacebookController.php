<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;

class FacebookController extends Controller
{
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->user();
            $admin = Admin::where('email', $facebookUser->getEmail())->first();

            if (!$admin) {
                $admin = Admin::create([
                    'name' => $facebookUser->getName(),
                    'email' => $facebookUser->getEmail(),
                    'password' => bcrypt('facebook_login'),
                ]);
            }

            Auth::guard('admin')->login($admin);
            session(['admin_email' => $admin->email, 'admin_name' => $admin->name, 'admin_profile_pic' => $admin->profile_pic]);

            return redirect()->route('admin.dashboard')->withSuccess('Logged in successfully using Facebook!');
        } catch (\Exception $e) {
            return redirect()->route('admin.login')->withErrors('Something went wrong! Please try again.');
        }
    }
}
