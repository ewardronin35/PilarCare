<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  ...$roles
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();

        // Convert roles to lowercase for case-insensitive comparison
        $roles = array_map('strtolower', $roles);
        $userRole = strtolower($user->role);

        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        // Optionally, you can redirect or abort with a 403 error
        abort(403, 'Unauthorized action.');
    }
}
