<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\Parents;
use App\Models\Staff;
use App\Models\Teacher;
use App\Models\Nurse;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RegisteredUserController extends Controller
{
    /**
     * Show the registration form.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        Log::info('Accessed registration form');
        return view('auth.register');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        Log::info('Validating registration data', [
            'id_number' => $data['id_number'], 
            'email' => $data['email']
        ]);
    
        return Validator::make($data, [
            'id_number' => [
                'required', 
                'string', 
                'max:7', // 1 letter + 6 numbers
                'regex:/^[A-Za-z]{1}[0-9]{6}$/', 
                'unique:users,id_number'
            ],
            'email' => [
                'required', 
                'string', 
                'email', 
                'max:255', 
                'unique:users,email'
            ],
            'password' => [
                'required', 
                'string', 
                'min:8', 
                'confirmed'
            ],
            // Removed reCAPTCHA validation rules
        ], [
            'id_number.regex' => 'The ID number must start with a letter followed by 6 numbers.',
            'id_number.unique' => 'The ID number is already registered.',
            'email.unique' => 'The email address is already in use.',
            'password.confirmed' => 'The password confirmation does not match.',
            // Removed reCAPTCHA error messages
        ]);
    }
    

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @param  string  $role
     * @param  bool  $approved
     * @return \App\Models\User
     */
    protected function createUser(array $data, $role, $approved)
    {
        Log::info('Creating user', ['id_number' => $data['id_number'], 'role' => $role]);
        return User::create([
            'id_number' => $data['id_number'],
            'email' => strtolower($data['email']),
            'password' => Hash::make($data['password']),
            'role' => $role,
            'approved' => $approved,
        ]);
    }

    /**
     * Determine the role and approval status based on the ID number.
     *
     * @param  string  $idNumber
     * @return array|null
     */
    protected function determineRoleAndApproval($idNumber)
    {
        Log::info('Determining role and approval for ID number', ['id_number' => $idNumber]);
        $models = [
            'Student' => Student::class,
            'Parent' => Parents::class,
            'Staff' => Staff::class,
            'Teacher' => Teacher::class,
            'Nurse' => Nurse::class,
            'Doctor' => Doctor::class,
        ];

        foreach ($models as $role => $model) {
            $record = $model::where('id_number', $idNumber)->first();
            if ($record) {
                Log::info('Role found for ID number', ['id_number' => $idNumber, 'role' => $role]);
                return ['role' => $role, 'approved' => $record->approved];
            }
        }

        Log::warning('No matching role found for ID number', ['id_number' => $idNumber]);
        return null;
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
{
    Log::info('Registration attempt', ['id_number' => $request->id_number, 'email' => $request->email]);

    $validator = $this->validator($request->all());
    if ($validator->fails()) {
        Log::warning('Validation failed', ['errors' => $validator->errors()]);
        return response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 422);
    }

    $roleAndApproval = $this->determineRoleAndApproval($request->id_number);

    if (!$roleAndApproval) {
        Log::warning('Role determination failed', ['id_number' => $request->id_number]);
        return response()->json([
            'success' => false,
            'errors' => ['id_number' => 'The ID number does not match any records. Please check your information.']
        ], 422);
    }

    $user = $this->createUser($request->all(), $roleAndApproval['role'], $roleAndApproval['approved']);
    event(new Registered($user)); // This already triggers the email notification

    Log::info('User registered successfully', ['user_id' => $user->id, 'role' => $user->role]);

    Auth::login($user);

    return response()->json([
        'success' => true,
        'message' => 'Registration successful! Please verify your email.'
    ], 200);
}

}
