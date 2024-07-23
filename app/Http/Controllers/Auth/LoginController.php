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
        $credentials = $request->only('id_number', 'password');
        Log::info('Login attempt for ID number: ' . $credentials['id_number']);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            Log::info('User logged in: ' . $user->id_number);

            if (!$user->hasVerifiedEmail()) {
                Auth::logout();
                Log::warning('User tried to login without verifying email: ' . $user->id_number);
                return response()->json([
                    'success' => false,
                    'errors' => ['email' => 'You need to verify your email address.'],
                ], 422);
            }

            return response()->json([
                'success' => true,
                'message' => 'Login successful.',
                'redirect' => route('dashboard'),
            ]);
        }

        Log::warning('Login failed for ID number: ' . $credentials['id_number']);
        return response()->json([
            'success' => false,
            'errors' => ['id_number' => 'The provided credentials do not match our records.'],
        ], 422);
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
