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
        // Increase PHP settings temporarily for this request
        ini_set('post_max_size', '3072M');        // 3GB
        ini_set('upload_max_filesize', '3072M');  // 3GB
        ini_set('max_execution_time', 0);         // unlimited (or set a high value e.g., 3600 seconds)
        ini_set('max_input_time', 0);             // unlimited
        ini_set('memory_limit', '3072M');         // 3GB

        return $next($request);
    }
}
