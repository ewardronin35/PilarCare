<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use App\Http\Requests\ResetPasswordRequest;
use Illuminate\View\View; // Import the correct View class


class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }
    public function create($token): View
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => request()->email, // Pass the email if available
        ]);
    }
    public function store(ResetPasswordRequest $request): RedirectResponse
    {
        // The request is already validated at this point.

        // Attempt to reset the user's password.
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            // Redirect to login with success message
            return redirect()->route('login')->with('status', __('Your password has been reset successfully!'));
        } else {
            // Redirect back with error message
            return back()->withErrors(['email' => __($status)]);
        }
    }
}