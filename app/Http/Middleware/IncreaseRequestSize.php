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
        ini_set('post_max_size', '5120M');         // 5GB = 5120 MB
        ini_set('upload_max_filesize', '5120M');   // 5GB = 5120 MB
        ini_set('max_execution_time', '0');        // Unlimited execution time (or set to something like 3600 for 1h)
        ini_set('max_input_time', '-1');           // No limit on input parsing time
        ini_set('memory_limit', '5120M');
        ini_set('max_input_vars', '200000'); s            // Set higher than upload size (e.g. 6GB)

        return $next($request);
    }
}
