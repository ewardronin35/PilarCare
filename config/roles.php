<?php

return [
    'roles' => [
        'student' => App\Models\Student::class,
        'parent'  => App\Models\Parents::class, // Renamed to ParentModel to avoid conflict with PHP's reserved keyword
        'staff'   => App\Models\Staff::class,
        'teacher' => App\Models\Teacher::class,
        'nurse'   => App\Models\Nurse::class,
        'doctor'  => App\Models\Doctor::class,
        'admin'   => null, // Admin might not require an additional model
    ],
];
