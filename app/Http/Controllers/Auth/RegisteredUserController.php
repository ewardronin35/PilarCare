<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\Parents;
use App\Models\Staff;
use App\Models\Teacher;
use App\Models\Nurse;  // Include Nurse model
use App\Models\Doctor;  // Include Doctor model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'id_number' => ['required', 'string', 'max:7', 'regex:/^[A-Za-z]{1}[0-9]{6}$/'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'g-recaptcha-response' => ['required'],
        ]);
    }

    protected function createUser(array $data, $role, $approved)
    {
        return User::create([
            'id_number' => $data['id_number'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $role,
            'approved' => $approved,
        ]);
    }

    protected function determineRoleAndApproval($idNumber, $firstName, $lastName)
    {
        // Check if the student's first and last name matches the database record
        if (Schema::hasColumn('students', 'id_number')) {
            $student = Student::where('id_number', $idNumber)
                              ->where('first_name', $firstName)
                              ->where('last_name', $lastName)
                              ->first();
            if ($student) {
                return ['role' => 'Student', 'approved' => $student->approved];
            }
        }

        // Check if the parent's first and last name matches the database record
        if (Schema::hasColumn('parents', 'id_number')) {
            $parent = Parents::where('id_number', $idNumber)
                             ->where('first_name', $firstName)
                             ->where('last_name', $lastName)
                             ->first();
            if ($parent) {
                return ['role' => 'Parent', 'approved' => $parent->approved];
            }
        }

        // Check if the staff's first and last name matches the database record
        if (Schema::hasColumn('staff', 'id_number')) {
            $staff = Staff::where('id_number', $idNumber)
                          ->where('first_name', $firstName)
                          ->where('last_name', $lastName)
                          ->first();
            if ($staff) {
                return ['role' => 'Staff', 'approved' => $staff->approved];
            }
        }

        // Check if the teacher's first and last name matches the database record
        if (Schema::hasColumn('teachers', 'id_number')) {
            $teacher = Teacher::where('id_number', $idNumber)
                              ->where('first_name', $firstName)
                              ->where('last_name', $lastName)
                              ->first();
            if ($teacher) {
                return ['role' => 'Teacher', 'approved' => $teacher->approved];
            }
        }

        // Check if the nurse's first and last name matches the database record
        if (Schema::hasColumn('nurses', 'id_number')) {
            $nurse = Nurse::where('id_number', $idNumber)
                          ->where('first_name', $firstName)
                          ->where('last_name', $lastName)
                          ->first();
            if ($nurse) {
                return ['role' => 'Nurse', 'approved' => $nurse->approved];
            }
        }

        // Check if the doctor's first and last name matches the database record
        if (Schema::hasColumn('doctors', 'id_number')) {
            $doctor = Doctor::where('id_number', $idNumber)
                            ->where('first_name', $firstName)
                            ->where('last_name', $lastName)
                            ->first();
            if ($doctor) {
                return ['role' => 'Doctor', 'approved' => $doctor->approved];
            }
        }

        return null; // Return null if no matching role is found
    }

    public function store(Request $request)
    {
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
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
            return response()->json([
                'success' => false,
                'errors' => ['g-recaptcha-response' => 'reCAPTCHA verification failed. Please try again.']
            ], 422);
        }

        // Determine the role and approval status based on the ID number, first name, and last name
        $roleAndApproval = $this->determineRoleAndApproval(
            $request->id_number, 
            $request->first_name, 
            $request->last_name
        );

        if (!$roleAndApproval) {
            return response()->json([
                'success' => false,
                'errors' => ['id_number' => 'The ID number, first name, and last name do not match any records. Please check your information.']
            ], 422);
        }

        $user = $this->createUser($request->all(), $roleAndApproval['role'], $roleAndApproval['approved']);
        event(new Registered($user));
        $user->sendEmailVerificationNotification();
        Auth::login($user);

        return response()->json([
            'success' => true,
            'message' => 'Registration successful! Please verify your email.'
        ], 200);
    }
}
