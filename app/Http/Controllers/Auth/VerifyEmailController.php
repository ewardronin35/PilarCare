<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class VerifyEmailController extends Controller
{
    public function __invoke(Request $request)
    {
        // Retrieve the user ID and hash from the route
        $userId = $request->route('id');
        $hash = $request->route('hash');

        // Find the user by ID
        $user = User::findOrFail($userId);

        Log::info('User attempting email verification', ['user' => $user]);

        // Verify the hash matches the user's email
        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            Log::warning('Email verification hash mismatch', ['user' => $user]);

            // Optionally, you can redirect to an error page or show a message
            return redirect()->route('login')->with('error', 'Invalid verification link.');
        }

        // Check if the email is already verified
        if ($user->hasVerifiedEmail()) {
            Log::info('User already has verified email', ['user' => $user]);
            return redirect()->route('verification.verified');
        }

        // Mark the user's email as verified
        $user->markEmailAsVerified();
        event(new Verified($user));

        Log::info('User email verified', ['user' => $user]);

        // Redirect to a confirmation page
        return redirect()->route('verification.verified')->with('verified', true);
    }
}
