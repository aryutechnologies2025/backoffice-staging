<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $module, $action)
    {
        $permissions = session('permissions', []);

        // Empty permissions = super admin, allow all
        if (empty($permissions)) {
            return $next($request);
        }

        if (!isset($permissions[$module]) || empty($permissions[$module][$action])) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
            abort(403, 'You do not have permission to perform this action.');
        }

        return $next($request);
    }
}
