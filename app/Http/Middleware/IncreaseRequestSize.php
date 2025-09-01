<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IncreaseRequestSize
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Set "unlimited" values
        ini_set('post_max_size', '-1');         // No limit
        ini_set('upload_max_filesize', '-1');   // No limit
        ini_set('max_execution_time', '0');     // Unlimited execution time
        ini_set('max_input_time', '-1');        // No limit on input parsing time
        ini_set('memory_limit', '-1');          // Unlimited memory

        return $next($request);
    }
}
