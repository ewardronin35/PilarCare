<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use App\Models\Student;

class LoginController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'id_number' => ['required', 'string', 'regex:/^[A-Za-z][0-9]{6}$/'],
            'password' => ['required', 'string'],
            'g-recaptcha-response' => ['required', 'string'],
        ]);

        $recaptchaResponse = $request->input('g-recaptcha-response');
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.nocaptcha.secret'),
            'response' => $recaptchaResponse,
            'remoteip' => $request->ip(),
        ]);

        $responseBody = json_decode($response->body());

        if (!$responseBody->success) {
            throw ValidationException::withMessages([
                'g-recaptcha-response' => 'reCAPTCHA verification failed. Please try again.',
            ]);
        }

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

            if (strtolower($user->role) !== 'admin') {
                if (!$user->approved) {
                    Auth::logout();
                    Log::warning('User tried to login without approval: ' . $user->id_number);
                    return response()->json([
                        'success' => false,
                        'errors' => ['approved' => 'Your account is not approved. Please contact the administrator.'],
                    ], 422);
                }

                $student = Student::where('id_number', $user->id_number)->first();

                if (!$student) {
                    Auth::logout();
                    Log::warning('User tried to login without being enrolled: ' . $user->id_number);
                    return response()->json([
                        'success' => false,
                        'errors' => ['enrolled' => 'You are not enrolled as a student. Please contact the administrator.'],
                    ], 422);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Login successful.',
                'redirect' => $this->redirectTo($user),
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
        switch (strtolower($user->role)) {
            case 'student':
                return route('student.dashboard');
            case 'parent':
                return route('parent.dashboard');
            case 'teacher':
                return route('teacher.dashboard');
            case 'staff':
                return route('staff.dashboard');
            case 'admin':
                return route('admin.dashboard');
            default:
                return '/';
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
