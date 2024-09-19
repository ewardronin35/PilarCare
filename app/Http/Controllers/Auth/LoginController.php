<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use App\Models\Student;
use App\Models\Parents;
use App\Models\Staff;
use App\Models\Teacher;
use App\Models\Nurse;
use App\Models\Doctor;

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

                // Check role-specific validations for different user types
                switch (strtolower($user->role)) {
                    case 'student':
                        $student = Student::where('id_number', $user->id_number)->first();
                        if (!$student) {
                            Auth::logout();
                            Log::warning('User tried to login without being enrolled: ' . $user->id_number);
                            return response()->json([
                                'success' => false,
                                'errors' => ['enrolled' => 'You are not enrolled as a student. Please contact the administrator.'],
                            ], 422);
                        }
                        break;

                    case 'parent':
                        $parent = Parents::where('id_number', $user->id_number)->first();
                        if (!$parent) {
                            Auth::logout();
                            Log::warning('Parent login attempt without matching record: ' . $user->id_number);
                            return response()->json([
                                'success' => false,
                                'errors' => ['parent' => 'Parent record not found. Please contact the administrator.'],
                            ], 422);
                        }
                        break;

                    case 'staff':
                        $staff = Staff::where('id_number', $user->id_number)->first();
                        if (!$staff) {
                            Auth::logout();
                            Log::warning('Staff login attempt without matching record: ' . $user->id_number);
                            return response()->json([
                                'success' => false,
                                'errors' => ['staff' => 'Staff record not found. Please contact the administrator.'],
                            ], 422);
                        }
                        break;

                    case 'teacher':
                        $teacher = Teacher::where('id_number', $user->id_number)->first();
                        if (!$teacher) {
                            Auth::logout();
                            Log::warning('Teacher login attempt without matching record: ' . $user->id_number);
                            return response()->json([
                                'success' => false,
                                'errors' => ['teacher' => 'Teacher record not found. Please contact the administrator.'],
                            ], 422);
                        }
                        break;

                    case 'nurse':
                        $nurse = Nurse::where('id_number', $user->id_number)->first();
                        if (!$nurse) {
                            Auth::logout();
                            Log::warning('Nurse login attempt without matching record: ' . $user->id_number);
                            return response()->json([
                                'success' => false,
                                'errors' => ['nurse' => 'Nurse record not found. Please contact the administrator.'],
                            ], 422);
                        }
                        break;

                    case 'doctor':
                        $doctor = Doctor::where('id_number', $user->id_number)->first();
                        if (!$doctor) {
                            Auth::logout();
                            Log::warning('Doctor login attempt without matching record: ' . $user->id_number);
                            return response()->json([
                                'success' => false,
                                'errors' => ['doctor' => 'Doctor record not found. Please contact the administrator.'],
                            ], 422);
                        }
                        break;

                    default:
                        Auth::logout();
                        Log::warning('Invalid role for login: ' . $user->id_number);
                        return response()->json([
                            'success' => false,
                            'errors' => ['role' => 'Invalid role for login. Please contact the administrator.'],
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
            case 'nurse':
                return route('nurse.dashboard');
            case 'doctor':
                return route('doctor.dashboard');
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
