<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
{
    $credentials = $request->only('email', 'password');
    Log::info('Login attempt for email: ' . $credentials['email']);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        $user = Auth::user();
        Log::info('User logged in: ' . $user->email);

        if (!$user->hasVerifiedEmail()) {
            Auth::logout();
            Log::warning('User tried to login without verifying email: ' . $user->email);
            return redirect()->route('verification.notice')->withErrors(['email' => 'You need to verify your email address.']);
        }

        return $this->redirectTo($user);
    }

    Log::warning('Login failed for email: ' . $credentials['email']);
    return back()->withErrors(['email' => 'The provided credentials do not match our records.']);
}

    protected function redirectTo($user)
    {
        switch ($user->role) {
            case 'Student':
                return redirect()->route('student.dashboard');
            case 'Parent':
                return redirect()->route('parent.dashboard');
            case 'Teacher':
                return redirect()->route('teacher.dashboard');
            case 'Staff':
                return redirect()->route('staff.dashboard');
            case 'Admin':
                return redirect()->route('admin.dashboard');
            default:
                return redirect('/');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
