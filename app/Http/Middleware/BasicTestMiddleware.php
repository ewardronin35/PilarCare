<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BasicTestMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Log to confirm the middleware is being triggered
        \Log::info('BasicTestMiddleware has been triggered.');

        // Return a simple test response
        return response('BasicTestMiddleware is working!', 200);

        // Uncomment the following line to continue to the next middleware/route
        // return $next($request);
    }
}
