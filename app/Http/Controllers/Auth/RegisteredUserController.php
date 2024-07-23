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
        Log::info('Visited registration page.');
        return view('auth.register');
    }

    protected function validator(array $data)
    {
        $rules = [
            'id_number' => ['required', 'string', 'max:7', 'unique:users', 'regex:/^[A-Za-z]{1}[0-9]{6}$/'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', 'in:Student,Teacher,Staff'],
            'g-recaptcha-response' => ['required'],
        ];

        return Validator::make($data, $rules);
    }

    protected function createUser(array $data)
    {
        $role = $this->determineRole($data['id_number']);

        return User::create([
            'id_number' => $data['id_number'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $role,
        ]);
    }

    protected function determineRole($idNumber)
    {
        if ($idNumber) {
            if (preg_match('/^S[0-9]{6}$/', $idNumber)) {
                return 'Student';
            } elseif (preg_match('/^ST[0-9]{6}$/', $idNumber)) {
                return 'Staff';
            } elseif (preg_match('/^T[0-9]{6}$/', $idNumber)) {
                return 'Teacher';
            }
        }

        return 'Parent'; // Default role if none of the patterns match
    }

    public function store(Request $request)
    {
        Log::info('Store method called with request data: ', $request->all());

        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            Log::error('Validation failed: ', $validator->errors()->toArray());
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $recaptchaResponse = $request->input('g-recaptcha-response');
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.nocaptcha.secret'),
            'response' => $recaptchaResponse,
            'remoteip' => $request->ip(),
        ]);

        $responseBody = json_decode($response->body());
        if (!$responseBody->success) {
            Log::error('reCAPTCHA verification failed.');
            return response()->json([
                'success' => false,
                'errors' => ['g-recaptcha-response' => 'reCAPTCHA verification failed. Please try again.']
            ], 422);
        }

        $userData = $request->all();
        $user = $this->createUser($userData);
        event(new Registered($user));
        $user->sendEmailVerificationNotification();
        Auth::login($user);

        Log::info('User registered and logged in successfully.');
        return response()->json([
            'success' => true,
            'message' => 'Registration successful! Please verify your email.'
        ], 200);
    }
}
