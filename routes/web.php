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

Route::get('/', function () {
    return view('auth.login');
})->name('home');

// Authentication Routes
Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
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
    Route::get('/dashboard', function () {
        return view('student.StudentDashboard');
    })->name('dashboard');

    Route::get('/complaint', [ComplaintController::class, 'index'])->name('complaint');
    Route::post('/complaint/store', [ComplaintController::class, 'store'])->name('complaint.store');
    Route::post('/complaint/add', [ComplaintController::class, 'add'])->name('complaint.add');

    // Health Examination Routes
    Route::get('/submit-health-exam', [HealthExaminationController::class, 'create'])->name('submit-health-exam');
    Route::post('/health-examination/store', [HealthExaminationController::class, 'store'])->name('health-examination.store');
    
    // Adding the upload-pictures route
    Route::get('/upload-pictures', [HealthExaminationController::class, 'create'])->name('upload-pictures');
    
    // Routes for pending approval
    Route::get('/pending-approval', function () {
        return view('pending-approval');
    })->name('pending-approval');
    
    // Ensure health examination approval before accessing medical and dental records
    Route::middleware('check.approval')->group(function () { // Check approval middleware
        Route::get('/medical-record', [MedicalRecordController::class, 'create'])->name('medical-record.create');
        Route::post('/medical-record/store', [MedicalRecordController::class, 'store'])->name('medical-record.store');
        Route::get('/dental-record', [DentalRecordController::class, 'create'])->name('dental-record.create');
        Route::post('/dental-record/store', [DentalRecordController::class, 'store'])->name('dental-record.store');
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
    Route::post('/medical-record/store', [MedicalRecordController::class, 'store'])->name('medical-record.store');
    Route::get('/appointment', [AppointmentController::class, 'index'])->name('appointment');
    Route::post('/appointment/add', [AppointmentController::class, 'add'])->name('appointment.add');
    Route::put('/appointment/update/{id}', [AppointmentController::class, 'update'])->name('appointment.update');
    Route::delete('/appointment/delete/{id}', [AppointmentController::class, 'delete'])->name('appointment.delete');
});

// Group admin routes
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/complaint', [ComplaintController::class, 'index'])->name('complaint');
    Route::get('/complaint/add', [ComplaintController::class, 'addComplaint'])->name('complaint.add');
    Route::get('/complaint/{id}', [ComplaintController::class, 'show'])->name('complaint.show');
    Route::post('/complaint/add', [ComplaintController::class, 'store'])->name('complaint.store');
    Route::post('/complaint/update-status/{id}', [ComplaintController::class, 'updateStatus'])->name('complaint.update-status');

    Route::get('students/upload', [StudentController::class, 'showUploadForm'])->name('students.upload');
    Route::post('students/import', [StudentController::class, 'import'])->name('students.import');
    
    // Define the medical-record route to use the HealthExaminationController
    Route::get('/medical-record', [HealthExaminationController::class, 'medicalRecord'])->name('medical-record.index');
    Route::post('/medical-record/store', [HealthExaminationController::class, 'store'])->name('medical-record.store');
    Route::post('/medical-record/{id}/approve', [HealthExaminationController::class, 'approve'])->name('medical-record.approve');
    Route::post('/medical-record/{id}/reject', [HealthExaminationController::class, 'reject'])->name('medical-record.reject');
    
    Route::get('/appointment', [AppointmentController::class, 'index'])->name('appointment');
    Route::post('/appointment/add', [AppointmentController::class, 'add'])->name('appointment.add');
    Route::put('/appointment/update/{id}', [AppointmentController::class, 'update'])->name('appointment.update');
    Route::delete('/appointment/delete/{id}', [AppointmentController::class, 'delete'])->name('appointment.delete');

    // Inventory Routes
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory');
    Route::post('/inventory/add', [InventoryController::class, 'add'])->name('inventory.add');
    Route::post('/inventory/update/{id}', [InventoryController::class, 'update'])->name('inventory.update');
    Route::delete('/inventory/delete/{id}', [InventoryController::class, 'delete'])->name('inventory.delete');

    // Enrolled Students
    Route::get('/enrolledstudents', [StudentController::class, 'enrolledStudents'])->name('enrolledstudents');
    Route::get('/monitoring-report-log', function () {
        return view('admin.monitoring-report-log');
    })->name('monitoring-report-log');

    Route::get('/pending-approvals', [AdminDashboardController::class, 'pendingApprovals'])->name('pendingApproval');

    // Notification routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications', [NotificationController::class, 'store'])->name('notifications.store');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
});

// Routes for password reset
Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
Route::get('reset-password/{token}', [PasswordController::class, 'create'])->name('password.reset');
Route::post('reset-password', [PasswordController::class, 'store'])->name('password.update');

// Grouped routes for profile with middleware for auth
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile-picture/store', [ProfileController::class, 'storeProfilePicture'])->name('profile-picture.store');
});
