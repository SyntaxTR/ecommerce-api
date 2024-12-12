<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Log the request
        Log::info('Request:', [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'data' => $request->all(),
            'headers' => $request->headers->all(),
        ]);

        // Get the response
        $response = $next($request);

        // Log the response
        Log::info('Response:', [
            'status' => $response->status(),
            'content' => $response->getContent(),
        ]);

        return $response;
    }
}