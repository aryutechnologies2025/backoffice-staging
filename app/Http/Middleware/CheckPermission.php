<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
 public function handle(Request $request, Closure $next, $module, $action = null)
{
    $permissions = session('permissions', []);

    // If module not found in permissions → deny
     // ❌ Empty permissions → deny (secure by default)
    if (empty($permissions)) {
        abort(403, 'Unauthorized');
    }

    if (!isset($permissions[$module])) {
        abort(403, 'Unauthorized');
    }

    // If no action specified → module access is enough
    if ($action === null) {
        return $next($request);
    }

    // Check if specific action is allowed
    if (
        !isset($permissions[$module][$action]) ||
        $permissions[$module][$action] !== true
    ) {
        abort(403, 'Unauthorized');
    }

    return $next($request);
}
}