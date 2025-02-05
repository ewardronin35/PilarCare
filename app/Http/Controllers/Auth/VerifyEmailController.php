<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomEmailVerificationRequest;
use Illuminate\Auth\Events\Verified;

class VerifyEmailController extends Controller
{
    public function __invoke(CustomEmailVerificationRequest $request)
    {
        $user = $request->getRequestedUser();

        // Check if the email is already verified
        if ($user->hasVerifiedEmail()) {
            return redirect()->route('verification.verified')->with('status', 'Email already verified.');
        }

        // Mark the user's email as verified
        $user->markEmailAsVerified();
        event(new Verified($user));

        return redirect()->route('verification.verified')->with('verified', true);
    }
}
