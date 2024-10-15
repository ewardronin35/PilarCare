<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  ...$roles  // Accepts multiple roles
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        if (empty($roles) || count($roles) === 0) {
            // Log a warning to help debug why roles are empty
            \Log::warning('CheckRoleMiddleware received an empty roles array. Please check the middleware usage in routes.');
    
            // You can set a default role or return an error
            // For demonstration, we'll allow access if roles are empty (you can change this behavior)
            return $next($request);
        }
    
        // Original role checking logic...
        \Log::info('CheckRoleMiddleware triggered with roles: ' . json_encode($roles));
        if (!Auth::check()) {
            return redirect('login');
        }
    
        $user = Auth::user();
        $userRole = strtolower($user->role);
        $roles = array_map('strtolower', $roles);
    
        if (in_array($userRole, $roles)) {
            return $next($request);
        }
    
        return response()->json(['message' => 'Unauthorized action.'], 403);
    }
    
}
