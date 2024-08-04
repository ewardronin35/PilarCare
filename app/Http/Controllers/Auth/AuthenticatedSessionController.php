<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if (!$user->hasVerifiedEmail()) {
                Auth::logout();
                return redirect()->route('verification.notice')->withErrors(['email' => 'You need to verify your email address.']);
            }
            return $this->redirectTo($user);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
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

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
