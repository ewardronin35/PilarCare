<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\DentalRecordController;
use App\Http\Controllers\HealthExaminationController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\PhysicalExaminationController;
use App\Http\Controllers\DentalExaminationController;
use App\Http\Controllers\NurseController;
use App\Http\Controllers\DoctorController;


Route::get('/', function () {
    return view('auth.login');
})->name('home');

// Authentication Routes
Route::get('login', [LoginController::class, 'create'])->name('login');
Route::post('login', [LoginController::class, 'login'])->name('login.perform'); // Changed the name to avoid conflict
Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Registration and Email Verification Routes
Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('register', [RegisteredUserController::class, 'store'])->name('register.store');

Route::get('email/verify', [EmailVerificationPromptController::class, '__invoke'])
    ->middleware('auth')
    ->name('verification.notice');

Route::get('email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->middleware(['auth', 'signed'])
    ->name('verification.verify');

Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');

Route::get('email-verified', function () {
    return view('auth.email-verified');
})->name('verification.verified');

// Redirect to specific dashboard based on user role
Route::middleware(['auth', 'verified'])->get('/dashboard', function () {
    $user = Auth::user();
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
})->name('dashboard');

// Group parent routes
Route::middleware(['auth', 'verified'])->prefix('parent')->name('parent.')->group(function () {
    Route::get('/dashboard', function () {
        return view('parent.ParentDashboard');
    })->name('dashboard');

    Route::get('/complaint', [ComplaintController::class, 'index'])->name('complaint');
    Route::post('/complaint/store', [ComplaintController::class, 'store'])->name('complaint.store');
    Route::post('/complaint/update-status/{id}', [ComplaintController::class, 'updateStatus'])->name('complaint.update-status');
    Route::get('/medical-record', [MedicalRecordController::class, 'create'])->name('medical-record');
    Route::post('/medical-record/store', [MedicalRecordController::class, 'store'])->name('medical-record.store');
    Route::get('/appointment', [AppointmentController::class, 'index'])->name('appointment');
    Route::post('/appointment/store', [AppointmentController::class, 'store'])->name('appointment.store');
    Route::put('/appointment/update/{id}', [AppointmentController::class, 'update'])->name('appointment.update');
    Route::delete('/appointment/delete/{id}', [AppointmentController::class, 'destroy'])->name('appointment.delete');
});

// Group student routes

    Route::middleware(['auth', 'verified'])->prefix('student')->name('student.')->group(function () {
        Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
        Route::get('/submit-health-exam', [HealthExaminationController::class, 'create'])->name('submit-health-exam');
        Route::get('/medical-record/index', [MedicalRecordController::class, 'index'])->name('medical-record');
        Route::get('/dental-record/index', [DentalRecordController::class, 'index'])->name('dental-record');
        Route::post('/medical-record/store', [MedicalRecordController::class, 'store'])->name('medical-record.store');
        Route::post('/health-examination/store', [HealthExaminationController::class, 'store'])->name('health-examination.store');
        Route::get('/health-examination/status', [HealthExaminationController::class, 'checkApprovalStatus'])->name('health-examination.status');
        Route::get('/upload-pictures', [HealthExaminationController::class, 'create'])->name('upload-pictures');
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications', [NotificationController::class, 'store'])->name('notifications.store');
        Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
        Route::get('/dental-record/pdf/{id_number}', [App\Http\Controllers\DentalRecordController::class, 'generatePdf'])->name('dentalRecord.pdf');
    // Route::get('profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('profile/update', [ProfileController::class, 'update'])->name('profile.update');
    // Route::post('profile/picture', [ProfileController::class, 'storeProfilePicture'])->name('profile.picture');
    Route::post('profile/store', [StudentDashboardController::class, 'storeProfile'])->name('profile.store');

    Route::get('/complaint', [ComplaintController::class, 'index'])->name('complaint');
    Route::post('/complaint/store', [ComplaintController::class, 'store'])->name('complaint.store');
    Route::post('/complaint/add', [ComplaintController::class, 'add'])->name('complaint.add');
    Route::get('/health-examination/{id}', [HealthExaminationController::class, 'show'])->name('health-examination.show');
    Route::get('/health-examination/{id}/download-pdf', [HealthExaminationController::class, 'downloadPdf'])->name('health-examination.downloadPdf');
    
    // Adding the upload-pictures route
    Route::get('/upload-pictures', [HealthExaminationController::class, 'create'])->name('upload-pictures');
  
    // Ensure health examination approval before accessing medical and dental records
    Route::middleware([\App\Http\Middleware\CheckApproval::class])->group(function () {
        Route::get('/medical-record', [MedicalRecordController::class, 'create'])->name('medical-record.create');
        Route::post('/medical-record/store', [MedicalRecordController::class, 'store'])->name('medical-record.store');
        Route::get('/dental-record', [DentalRecordController::class, 'create'])->name('dental-record.create');
        Route::post('/dental-record/store', [DentalRecordController::class, 'store'])->name('dental-record.store');
        Route::post('/dental-record/store-tooth', [DentalRecordController::class, 'storeTooth'])->name('dental-record.store-tooth');
        Route::post('/teeth/store', [DentalRecordController::class, 'storeTooth'])->name('teeth.store');
        Route::get('/get-tooth-status', [DentalRecordController::class, 'getToothStatus'])->name('get-tooth-status');
        Route::get('/medical-record/download/{id}', [MedicalRecordController::class, 'downloadPdf'])->name('medical-record.downloadPdf');

    });

    Route::get('/appointment', [AppointmentController::class, 'index'])->name('appointment');
    Route::post('/appointment/add', [AppointmentController::class, 'add'])->name('appointment.add');
    Route::put('/appointment/update/{id}', [AppointmentController::class, 'update'])->name('appointment.update');
    Route::delete('/appointment/delete/{id}', [AppointmentController::class, 'delete'])->name('appointment.delete');
});

// Group teacher routes
Route::middleware(['auth', 'verified'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', function () {
        return view('teacher.TeacherDashboard');
    })->name('dashboard');
    Route::post('/complaint/update-status/{id}', [ComplaintController::class, 'updateStatus'])->name('complaint.update-status');
    Route::post('/complaint/store', [ComplaintController::class, 'store'])->name('complaint.store');
    Route::get('/complaint', [ComplaintController::class, 'index'])->name('complaint');
    Route::post('/complaint/add', [ComplaintController::class, 'add'])->name('complaint.add');
    Route::get('/medical-record', [MedicalRecordController::class, 'index'])->name('medical-record.index');
    Route::get('/dental-record', [DentalRecordController::class, 'index'])->name('dental-record.index');
    Route::get('/medical-record', [MedicalRecordController::class, 'create'])->name('medical-record');
    Route::post('/medical-record/store', [MedicalRecordController::class, 'store'])->name('teacher.medical-record.store');
    Route::get('/appointment', [AppointmentController::class, 'index'])->name('appointment');
    Route::post('/appointment/add', [AppointmentController::class, 'add'])->name('appointment.add');
    Route::put('/appointment/update/{id}', [AppointmentController::class, 'update'])->name('appointment.update');
    Route::delete('/appointment/delete/{id}', [AppointmentController::class, 'delete'])->name('appointment.delete');
});

// Group staff routes
Route::middleware(['auth', 'verified'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', function () {
        return view('staff.StaffDashboard');
    })->name('dashboard');

    Route::post('/complaint/update-status/{id}', [ComplaintController::class, 'updateStatus'])->name('complaint.update-status');
    Route::post('/complaint/store', [ComplaintController::class, 'store'])->name('complaint.store');
    Route::get('/complaint', [ComplaintController::class, 'index'])->name('complaint');
    Route::post('/complaint/add', [ComplaintController::class, 'add'])->name('complaint.add');
    Route::get('/medical-record', [MedicalRecordController::class, 'create'])->name('medical-record');
    Route::get('/medical-record', [MedicalRecordController::class, 'index'])->name('medical-record.index');
    Route::get('/dental-record', [DentalRecordController::class, 'index'])->name('dental-record.index');
    Route::post('/medical-record/store', [MedicalRecordController::class, 'store'])->name('medical-record.store');
    Route::get('/appointment', [AppointmentController::class, 'index'])->name('appointment');
    Route::post('/appointment/add', [AppointmentController::class, 'add'])->name('appointment.add');
    Route::put('/appointment/update/{id}', [AppointmentController::class, 'update'])->name('appointment.update');
    Route::delete('/appointment/delete/{id}', [AppointmentController::class, 'delete'])->name('appointment.delete');
});

// Group admin routes (only accessible to admins)
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard Route
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/export-students', [StudentController::class, 'exportStudents']);
    Route::get('/download-student', [App\Http\Controllers\StudentController::class, 'downloadStudent'])->name('download.templates');
    Route::get('/download-teacher', [App\Http\Controllers\TeacherController::class, 'downloadTeacher'])->name('download.teacher');
    Route::get('/download-nurse', [App\Http\Controllers\NurseController::class, 'downloadNurse'])->name('download.nurse');
    Route::get('/download-doctor', [App\Http\Controllers\DoctorController::class, 'downloadDoctor'])->name('download.doctor');
    Route::get('/download-template', [App\Http\Controllers\StaffController::class, 'downloadTemplates'])->name('download.staffs');

    // Complaint Routes
    Route::get('/complaint', [ComplaintController::class, 'index'])->name('complaint');
    Route::get('/complaint/add', [ComplaintController::class, 'addComplaint'])->name('complaint.add');
    Route::post('/complaint/store', [ComplaintController::class, 'store'])->name('complaint.store');
    Route::post('/complaint/update-status/{id}', [ComplaintController::class, 'updateStatus'])->name('complaint.update-status');
    Route::get('/complaint/{id}', [ComplaintController::class, 'show'])->name('complaint.show');
    Route::get('/complaint/person/{id}', [ComplaintController::class, 'fetchPersonData']);
    Route::get('/upload-health-examination', [HealthExaminationController::class, 'viewAllRecords'])->name('uploadHealthExamination');
    Route::get('/upload-pictures', [HealthExaminationController::class, 'create'])->name('upload-pictures');

    // Student Routes
    Route::get('students/enrolled', [StudentController::class, 'enrolledStudents'])->name('students.enrolled');
    Route::get('students/upload', [StudentController::class, 'showUploadForm'])->name('students.upload');
    Route::post('students/import', [StudentController::class, 'import'])->name('students.import');
    Route::post('students/add', [StudentController::class, 'addLateStudent'])->name('students.add');
    Route::post('students/{id}/toggle-approval', [StudentController::class, 'toggleApproval'])->name('students.toggle-approval');
    Route::delete('students/{id}', [StudentController::class, 'delete'])->name('students.delete');
    Route::post('students/{id}/edit', [StudentController::class, 'edit'])->name('students.edit');
    Route::get('students/{id}', [App\Http\Controllers\StudentController::class, 'show'])->name('students.show');

    // Profile Route
    Route::get('/profiles', [ProfileController::class, 'index'])->name('profiles');
    Route::post('/profiles/store', [UserController::class, 'store'])->name('profiles.store');

    // Staff Routes
    Route::get('staff/enrolled', [StaffController::class, 'enrolledStaff'])->name('staff.enrolled');
    Route::post('staff/add', [StaffController::class, 'addLatestaff'])->name('staff.add');
    Route::get('staff/upload', [StaffController::class, 'showUploadForm'])->name('staff.upload');
    Route::post('staff/import', [StaffController::class, 'import'])->name('staff.import');
    Route::post('staff/{id}/toggle-approval', [StaffController::class, 'toggleApproval'])->name('staff.toggle-approval');
    Route::post('staff/{id}/edit', [StaffController::class, 'edit'])->name('staff.edit');
    Route::delete('staff/{id}', [StaffController::class, 'delete'])->name('staff.delete');
    Route::get('staff/{id}', [StaffController::class, 'show'])->name('staff.show');

    // Parent Routes
    Route::get('parents/enrolled', [ParentController::class, 'enrolledParents'])->name('parents.enrolled');
    Route::post('parents/add', [ParentController::class, 'addLateParents'])->name('parents.add');

    Route::get('parents/upload', [ParentController::class, 'showUploadForm'])->name('parents.upload');
    Route::post('parents/import', [ParentController::class, 'import'])->name('parents.import');
    Route::post('parents/{id}/toggle-approval', [ParentController::class, 'toggleApproval'])->name('parents.toggle-approval');

    // Teacher Routes
    Route::get('teachers/enrolled', [TeacherController::class, 'enrolledTeachers'])->name('teachers.enrolled');
    Route::post('teachers/add', [TeacherController::class, 'addLateTeacher'])->name('teachers.add');
    Route::get('teachers/upload', [TeacherController::class, 'showUploadForm'])->name('teachers.upload');
    Route::post('teachers/import', [TeacherController::class, 'import'])->name('teachers.import');
    Route::post('teachers/{id}/toggle-approval', [TeacherController::class, 'toggleApproval'])->name('teachers.toggle-approval');
    Route::post('teachers/{id}/edit', [TeacherController::class, 'edit'])->name('teachers.edit');
    Route::delete('teachers/{id}', [TeacherController::class, 'delete'])->name('teachers.delete');
    Route::get('teachers/{id}', [TeacherController::class, 'show'])->name('teachers.show');

    Route::get('nurses/enrolled', [NurseController::class, 'enrolledNurses'])->name('nurses.enrolled');
    Route::post('nurses/add', [NurseController::class, 'addLateNurse'])->name('nurses.add');
    Route::get('nurses/upload', [NurseController::class, 'showUploadForm'])->name('nurses.upload');
    Route::post('nurses/import', [NurseController::class, 'import'])->name('nurses.import');
    Route::post('nurses/{id}/toggle-approval', [NurseController::class, 'toggleApproval'])->name('nurses.toggle-approval');
    Route::post('nurses/{id}/edit', [NurseController::class, 'edit'])->name('nurses.edit');
    Route::delete('nurses/{id}', [NurseController::class, 'delete'])->name('nurses.delete');
    Route::get('nurses/{id}', [NurseController::class, 'show'])->name('nurses.show');

    Route::get('doctors/enrolled', [DoctorController::class, 'enrolledDoctors'])->name('doctors.enrolled');
    Route::post('doctors/add', [DoctorController::class, 'addLateDoctor'])->name('doctors.add');
    Route::get('doctors/upload', [DoctorController::class, 'showUploadForm'])->name('doctors.upload');
    Route::post('doctors/import', [DoctorController::class, 'import'])->name('doctors.import');
    Route::post('doctors/{id}/toggle-approval', [DoctorController::class, 'toggleApproval'])->name('doctors.toggle-approval');
    Route::post('doctors/{id}/edit', [DoctorController::class, 'edit'])->name('doctors.edit');
    Route::delete('doctors/{id}', [DoctorController::class, 'delete'])->name('doctors.delete');
    Route::get('doctors/{id}', [DoctorController::class, 'show'])->name('doctors.show');


    // Dental Record Routes
    Route::get('/dental-record', [DentalRecordController::class, 'index'])->name('dental-record.index');
    Route::get('/dental-records', [DentalRecordController::class, 'viewAllRecords'])->name('dental-records');
    Route::get('/dental-record/create', [DentalRecordController::class, 'create'])->name('dental-record.create');
    Route::post('/dental-record/store', [DentalRecordController::class, 'store'])->name('dental-record.store');
    Route::post('/dental-record/store-tooth', [DentalRecordController::class, 'storeTooth'])->name('dental-record.store-tooth');
    Route::post('/dental-examination/store', [DentalExaminationController::class, 'store'])->name('dental-examination.store');
    Route::get('/get-tooth-status', [DentalRecordController::class, 'getToothStatus'])->name('getToothStatus');
    Route::get('/search-dental-record', [DentalRecordController::class, 'searchRecords'])->name('searchRecords');

    Route::patch('physical_examinations/{physicalExamination}/approve', [PhysicalExaminationController::class, 'approve'])->name('physical_examinations.approve');
    Route::get('/physical-examinations', [PhysicalExaminationController::class, 'index'])->name('physical_examinations.index');
    Route::get('/physical-examinations/create', [PhysicalExaminationController::class, 'create'])->name('physical_examinations.create');
    Route::post('/physical-examinations/store', [PhysicalExaminationController::class, 'store'])->name('physical_examinations.store');
    
    // Profiles View


    // Medical Record Routes
Route::get('/search-medical-record', [MedicalRecordController::class, 'search'])->name('medical-record.search');

    Route::get('/medical-records', [MedicalRecordController::class, 'index'])->name('medical-record.index');
    Route::get('/pending-examinations', [HealthExaminationController::class, 'medicalRecord'])->name('medical-records.pending');
    Route::post('/medical-record/store', [HealthExaminationController::class, 'store'])->name('medical-record.store');
    Route::post('/medical-record/{id}/approve', [HealthExaminationController::class, 'approve'])->name('medical-record.approve');
    Route::post('/medical-record/{id}/reject', [HealthExaminationController::class, 'reject'])->name('medical-record.reject');
    Route::get('/get-student-info/{id}', [StudentController::class, 'getStudentInfo'])->name('get-student-info');

    // Appointment Routes
    Route::get('/appointment', [AppointmentController::class, 'index'])->name('appointment');
    Route::post('/appointment/add', [AppointmentController::class, 'add'])->name('appointment.add');
    Route::put('/appointment/update/{id}', [AppointmentController::class, 'update'])->name('appointment.update');
    Route::delete('/appointment/delete/{id}', [AppointmentController::class, 'delete'])->name('appointment.delete');
    Route::get('/appointment/fetch-patient-name/{id}', [AppointmentController::class, 'fetchPatientName'])->name('appointment.fetch-patient-name'); // Added this route
    Route::get('/appointments-by-month', [AppointmentController::class, 'getAppointmentsByMonth'])->name('appointments.by-month');
    Route::get('/appointments-by-date', [AppointmentController::class, 'getAppointmentsByDate'])->name('appointments.by-date');

    // Inventory Routes
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory');
    Route::post('/inventory/add', [InventoryController::class, 'add'])->name('inventory.add');
    Route::post('/inventory/update/{id}', [InventoryController::class, 'update'])->name('inventory.update');
    Route::delete('/inventory/delete/{id}', [InventoryController::class, 'delete'])->name('inventory.delete');
    Route::get('/inventory/available-medicines', [InventoryController::class, 'getAvailableMedicines'])->name('inventory.available-medicines');
    Route::post('/inventory/update-quantity', [InventoryController::class, 'updateQuantity'])->name('inventory.update-quantity');


    // Monitoring and Report Log
    Route::get('/monitoring-report-log', function () {
        return view('admin.monitoring-report-log');
    })->name('monitoring-report-log');

    // Pending Approvals
    Route::get('/pending-approvals', [AdminDashboardController::class, 'pendingApprovals'])->name('pendingApproval');

    // Notification Routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications', [NotificationController::class, 'store'])->name('notifications.store');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
});

Route::middleware(['auth', 'verified'])->prefix('doctor')->name('doctor.')->group(function () {
    // Dashboard Route
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');



    // Complaint Routes
    Route::get('/complaint', [ComplaintController::class, 'index'])->name('complaint');
    Route::get('/complaint/add', [ComplaintController::class, 'addComplaint'])->name('complaint.add');
    Route::post('/complaint/store', [ComplaintController::class, 'store'])->name('complaint.store');
    Route::post('/complaint/update-status/{id}', [ComplaintController::class, 'updateStatus'])->name('complaint.update-status');
    Route::get('/complaint/{id}', [ComplaintController::class, 'show'])->name('complaint.show');
    Route::get('/complaint/person/{id}', [ComplaintController::class, 'fetchPersonData']);
    Route::get('/upload-health-examination', [HealthExaminationController::class, 'viewAllRecords'])->name('uploadHealthExamination');
    Route::get('/upload-pictures', [HealthExaminationController::class, 'create'])->name('upload-pictures');

    // Profile Route
    Route::get('/profiles', [ProfileController::class, 'index'])->name('profiles');
    Route::post('/profiles/store', [UserController::class, 'store'])->name('profiles.store');


    // Dental Record Routes
    Route::get('/dental-record', [DentalRecordController::class, 'index'])->name('dental-record.index');
    Route::get('/dental-records', [DentalRecordController::class, 'viewAllRecords'])->name('dental-records');
    Route::get('/dental-record/create', [DentalRecordController::class, 'create'])->name('dental-record.create');
    Route::post('/dental-record/store', [DentalRecordController::class, 'store'])->name('dental-record.store');
    Route::post('/dental-record/store-tooth', [DentalRecordController::class, 'storeTooth'])->name('dental-record.store-tooth');
    Route::post('/dental-examination/store', [DentalExaminationController::class, 'store'])->name('dental-examination.store');
    Route::get('/get-tooth-status', [DentalRecordController::class, 'getToothStatus'])->name('getToothStatus');
    Route::get('/search-dental-record', [DentalRecordController::class, 'searchRecords'])->name('searchRecords');

    Route::patch('physical_examinations/{physicalExamination}/approve', [PhysicalExaminationController::class, 'approve'])->name('physical_examinations.approve');
    Route::get('/physical-examinations', [PhysicalExaminationController::class, 'index'])->name('physical_examinations.index');
    Route::get('/physical-examinations/create', [PhysicalExaminationController::class, 'create'])->name('physical_examinations.create');
    Route::post('/physical-examinations/store', [PhysicalExaminationController::class, 'store'])->name('physical_examinations.store');
    
    // Profiles View


    // Medical Record Routes
Route::get('/search-medical-record', [MedicalRecordController::class, 'search'])->name('medical-record.search');

    Route::get('/medical-records', [MedicalRecordController::class, 'index'])->name('medical-record.index');
    Route::get('/pending-examinations', [HealthExaminationController::class, 'medicalRecord'])->name('medical-records.pending');
    Route::post('/medical-record/store', [HealthExaminationController::class, 'store'])->name('medical-record.store');
    Route::post('/medical-record/{id}/approve', [HealthExaminationController::class, 'approve'])->name('medical-record.approve');
    Route::post('/medical-record/{id}/reject', [HealthExaminationController::class, 'reject'])->name('medical-record.reject');
    Route::get('/get-student-info/{id}', [StudentController::class, 'getStudentInfo'])->name('get-student-info');

    // Appointment Routes
    Route::get('/appointment', [AppointmentController::class, 'index'])->name('appointment');
    Route::post('/appointment/add', [AppointmentController::class, 'add'])->name('appointment.add');
    Route::put('/appointment/update/{id}', [AppointmentController::class, 'update'])->name('appointment.update');
    Route::delete('/appointment/delete/{id}', [AppointmentController::class, 'delete'])->name('appointment.delete');
    Route::get('/appointment/fetch-patient-name/{id}', [AppointmentController::class, 'fetchPatientName'])->name('appointment.fetch-patient-name'); // Added this route
    Route::get('/appointments-by-month', [AppointmentController::class, 'getAppointmentsByMonth'])->name('appointments.by-month');
    Route::get('/appointments-by-date', [AppointmentController::class, 'getAppointmentsByDate'])->name('appointments.by-date');


    // Monitoring and Report Log
    Route::get('/monitoring-report-log', function () {
        return view('admin.monitoring-report-log');
    })->name('monitoring-report-log');

    // Pending Approvals
    Route::get('/pending-approvals', [AdminDashboardController::class, 'pendingApprovals'])->name('pendingApproval');

    // Notification Routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications', [NotificationController::class, 'store'])->name('notifications.store');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
});

Route::middleware(['auth', 'verified'])->prefix('nurse')->name('nurse.')->group(function () {
    // Dashboard Route
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');



    // Complaint Routes
    Route::get('/complaint', [ComplaintController::class, 'index'])->name('complaint');
    Route::get('/complaint/add', [ComplaintController::class, 'addComplaint'])->name('complaint.add');
    Route::post('/complaint/store', [ComplaintController::class, 'store'])->name('complaint.store');
    Route::post('/complaint/update-status/{id}', [ComplaintController::class, 'updateStatus'])->name('complaint.update-status');
    Route::get('/complaint/{id}', [ComplaintController::class, 'show'])->name('complaint.show');
    Route::get('/complaint/person/{id}', [ComplaintController::class, 'fetchPersonData']);
    Route::get('/upload-health-examination', [HealthExaminationController::class, 'viewAllRecords'])->name('uploadHealthExamination');

    // Student Routes

    // Profile Route
    Route::get('/profiles', [ProfileController::class, 'index'])->name('profiles');
    Route::post('/profiles/store', [UserController::class, 'store'])->name('profiles.store');



    // Dental Record Routes
    Route::get('/dental-record', [DentalRecordController::class, 'index'])->name('dental-record.index');
    Route::get('/dental-records', [DentalRecordController::class, 'viewAllRecords'])->name('dental-records');
    Route::get('/dental-record/create', [DentalRecordController::class, 'create'])->name('dental-record.create');
    Route::post('/dental-record/store', [DentalRecordController::class, 'store'])->name('dental-record.store');
    Route::post('/dental-record/store-tooth', [DentalRecordController::class, 'storeTooth'])->name('dental-record.store-tooth');
    Route::post('/dental-examination/store', [DentalExaminationController::class, 'store'])->name('dental-examination.store');
    Route::get('/get-tooth-status', [DentalRecordController::class, 'getToothStatus'])->name('getToothStatus');
    Route::get('/search-dental-record', [DentalRecordController::class, 'searchRecords'])->name('searchRecords');

    Route::patch('physical_examinations/{physicalExamination}/approve', [PhysicalExaminationController::class, 'approve'])->name('physical_examinations.approve');
    Route::get('/physical-examinations', [PhysicalExaminationController::class, 'index'])->name('physical_examinations.index');
    Route::get('/physical-examinations/create', [PhysicalExaminationController::class, 'create'])->name('physical_examinations.create');
    Route::post('/physical-examinations/store', [PhysicalExaminationController::class, 'store'])->name('physical_examinations.store');
    
    // Profiles View


    // Medical Record Routes
Route::get('/search-medical-record', [MedicalRecordController::class, 'search'])->name('medical-record.search');

    Route::get('/medical-records', [MedicalRecordController::class, 'index'])->name('medical-record.index');
    Route::get('/pending-examinations', [HealthExaminationController::class, 'medicalRecord'])->name('medical-records.pending');
    Route::post('/medical-record/store', [HealthExaminationController::class, 'store'])->name('medical-record.store');
    Route::post('/medical-record/{id}/approve', [HealthExaminationController::class, 'approve'])->name('medical-record.approve');
    Route::post('/medical-record/{id}/reject', [HealthExaminationController::class, 'reject'])->name('medical-record.reject');
    Route::get('/get-student-info/{id}', [StudentController::class, 'getStudentInfo'])->name('get-student-info');

    // Appointment Routes
    Route::get('/appointment', [AppointmentController::class, 'index'])->name('appointment');
    Route::post('/appointment/add', [AppointmentController::class, 'add'])->name('appointment.add');
    Route::put('/appointment/update/{id}', [AppointmentController::class, 'update'])->name('appointment.update');
    Route::delete('/appointment/delete/{id}', [AppointmentController::class, 'delete'])->name('appointment.delete');
    Route::get('/appointment/fetch-patient-name/{id}', [AppointmentController::class, 'fetchPatientName'])->name('appointment.fetch-patient-name'); // Added this route
    Route::get('/appointments-by-month', [AppointmentController::class, 'getAppointmentsByMonth'])->name('appointments.by-month');
    Route::get('/appointments-by-date', [AppointmentController::class, 'getAppointmentsByDate'])->name('appointments.by-date');

    // Inventory Routes
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory');
    Route::post('/inventory/add', [InventoryController::class, 'add'])->name('inventory.add');
    Route::post('/inventory/update/{id}', [InventoryController::class, 'update'])->name('inventory.update');
    Route::delete('/inventory/delete/{id}', [InventoryController::class, 'delete'])->name('inventory.delete');
    Route::get('/inventory/available-medicines', [InventoryController::class, 'getAvailableMedicines'])->name('inventory.available-medicines');
    Route::post('/inventory/update-quantity', [InventoryController::class, 'updateQuantity'])->name('inventory.update-quantity');


    // Monitoring and Report Log
    Route::get('/monitoring-report-log', function () {
        return view('admin.monitoring-report-log');
    })->name('monitoring-report-log');

    // Pending Approvals
    Route::get('/pending-approvals', [AdminDashboardController::class, 'pendingApprovals'])->name('pendingApproval');

    // Notification Routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications', [NotificationController::class, 'store'])->name('notifications.store');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
});
// Routes for password reset
Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
Route::get('reset-password/{token}', [PasswordController::class, 'create'])->name('password.reset');
Route::post('reset-password', [PasswordController::class, 'store'])->name('password.update');
Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
Route::post('/notifications', [NotificationController::class, 'store'])->name('notifications.store');
Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
Route::post('/notifications/{id}/mark-as-opened', [NotificationController::class, 'markAsOpened']);

// Grouped routes for profile with middleware for auth
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile-picture/store', [ProfileController::class, 'storeProfilePicture'])->name('profile-picture.store');
});
Route::get('/refresh-csrf', function () {
    return response()->json(['csrf_token' => csrf_token()]);
});
