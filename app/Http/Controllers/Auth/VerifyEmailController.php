<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class VerifyEmailController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        Log::info('User attempting email verification', ['user' => $user]);

        if ($user->hasVerifiedEmail()) {
            Log::info('User already has verified email', ['user' => $user]);
            return redirect()->route('verification.verified');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
            Log::info('User email verified', ['user' => $user]);
            return redirect()->route('verification.verified')->with('verified', true);
        }

        Log::warning('User email verification failed', ['user' => $user]);
        return redirect()->route('auth.login');
    }
}
