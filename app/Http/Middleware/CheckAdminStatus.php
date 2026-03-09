<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdminStatus
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('admin')->check()) {
            $admin = Auth::guard('admin')->user();
            
            if ($admin->status == '0' || $admin->is_deleted == '1') {
                Auth::guard('admin')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                return redirect()->route('admin.login')
                    ->with('error', 'Your account has been deactivated or deleted. Please contact administrator.');
            }
        }
        
        return $next($request);
    }
}
