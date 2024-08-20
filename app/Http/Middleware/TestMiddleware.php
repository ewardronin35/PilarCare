<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TestMiddleware
{
    public function handle($request, Closure $next)
    {
        dd('TestMiddleware loaded and working!');
        return $next($request);
    }
}