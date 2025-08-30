<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class DatabaseConnectionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Check if connection exists before disconnecting
        try {
            if (DB::connection()->getPdo()) {
                DB::disconnect();
            }
        } catch (\Exception $e) {
            // Log error but don't throw exception
            \Log::warning('Database disconnect warning: ' . $e->getMessage());
        }

        return $response;
    }
}
