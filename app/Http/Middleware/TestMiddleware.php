<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TestMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        try {
            // Log initial request data to verify middleware activation
            Log::info('TestMiddleware has been triggered with the following request data:', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'headers' => $request->headers->all(),
                'input' => $request->all(),
            ]);

            // Continue to the next middleware/route
            $response = $next($request);

            // Log the response status code and content
            Log::info('TestMiddleware response:', [
                'status' => $response->status(),
                'content' => $response->getContent(),
            ]);

            // Return the response
            return $response;

        } catch (\Exception $e) {
            // Log the exception details
            Log::error('Exception in TestMiddleware:', [
                'error_message' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
            ]);

            // Return a JSON response with the error details
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ], 500);
        }
    }
}
