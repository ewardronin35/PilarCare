<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, $guard = null)
    {
        $authGuard = Auth::guard($guard);

        if ($authGuard->check()) {
            // Check if the email is verified
            if (!$authGuard->user()->hasVerifiedEmail()) {
                // Redirect to the email verification notice page
                return redirect()->route('verification.notice');
            }
            // Redirect to the intended dashboard if the user is verified
            return redirect('/auth.login');
        }

        return $next($request);
    }
}
