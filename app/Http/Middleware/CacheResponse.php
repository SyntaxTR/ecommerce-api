<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class CacheResponse
{
    public function handle($request, Closure $next, $seconds = 10, $key = null)
    {
        $cacheKey = $key ?? md5($request->fullUrl());
        $response = Cache::remember($cacheKey, $seconds, function () use ($request, $next) {
            return $next($request);
        });

        return $response;
    }
}
