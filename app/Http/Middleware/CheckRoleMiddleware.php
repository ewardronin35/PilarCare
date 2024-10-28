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
            Log::warning('CheckRoleMiddleware received an empty roles array. Please check the middleware usage in routes.');
    
            // You can set a default role or return an error
            return $next($request);
        }
    
        Log::info('CheckRoleMiddleware triggered with roles: ' . json_encode($roles));

        // Check if the user is authenticated
        if (!Auth::check()) {
            return redirect('login');
        }

        // Get the authenticated user and their role
        $user = Auth::user();
        $userRole = strtolower($user->role);
        $roles = array_map('strtolower', $roles);

        // Check if the user's role matches any of the allowed roles
        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        // If the role doesn't match, return a custom view for unauthorized access
        return response()->view('errors.unauthorized', [
            'message' => "What are you doing? This is illegal!",
            'action' => $request->path(), // The requested action (e.g., student/dashboard)
        ], 403);
    }
}
