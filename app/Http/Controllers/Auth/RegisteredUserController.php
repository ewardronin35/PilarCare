<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'contact_number' => ['required', 'string', 'max:12'],
            'gender' => ['required', 'string'],
            'role' => ['required', 'string', 'in:Parent,Teacher,Student,Staff'],
            'id_number' => ['required', 'string', 'unique:users'],
            'g-recaptcha-response' => ['required'],
        ]);
    }

    protected function createUser(array $data)
    {
        return User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'contact_number' => $data['contact_number'],
            'gender' => $data['gender'],
            'role' => $data['role'],
            'id_number' => $data['id_number'],
            'parent_id' => $data['parent_id'] ?? null,
            'student_type' => $data['student_type'] ?? null,
            'program' => $data['program'] ?? null,
            'year_level' => $data['year_level'] ?? null,
            'bed_type' => $data['bed_type'] ?? null,
            'section' => $data['section'] ?? null,
            'grade' => $data['grade'] ?? null,
            'teacher_type' => $data['teacher_type'] ?? null,
            'staff_role' => $data['staff_role'] ?? null,
        ]);
    }

    public function store(Request $request)
    {
        $this->validator($request->all())->validate();

        $recaptchaResponse = $request->input('g-recaptcha-response');
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.nocaptcha.secret'),
            'response' => $recaptchaResponse,
            'remoteip' => $request->ip(),
        ]);

        $responseBody = json_decode($response->body());
        Log::info('reCAPTCHA response', ['responseBody' => $responseBody]);

        if (!$responseBody->success) {
            Log::error('reCAPTCHA verification failed', ['response' => $responseBody]);
            return redirect()->back()->withErrors(['g-recaptcha-response' => 'reCAPTCHA verification failed. Please try again.'])->withInput();
        }

        $user = $this->createUser($request->all());
        event(new Registered($user));
        Log::info('User created', ['user' => $user]);
        $user->sendEmailVerificationNotification();
        Log::info('Verification email sent', ['user' => $user]);
        Auth::login($user);
        Log::info('User logged in', ['user' => $user]);

        return redirect()->route('verification.notice');
    }
}
