<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('admin.login');
    }

    // protected function redirectTo(Request $request): ?string
    // {
    //     if ($request->expectsJson()) {
    //         return response()->json(['message' => 'Unauthorized. Please provide a valid access token.'], 401);
    //     }

    //     return route('admin.login');
    // }
}
