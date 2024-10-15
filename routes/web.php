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
use App\Events\LowStockNotification;
use App\Http\Controllers\ReportLogsController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\StaffDashboardController;
use App\Http\Controllers\TeacherDashboardController;
use App\Http\Controllers\MedicalHistoryController;
use App\Http\Controllers\DoctorDashboardController;
use App\Http\Controllers\NurseDashboardController;
use App\Http\Controllers\MedicineIntakeController;
use App\Http\Controllers\ParentDashboardController;
use App\Http\Middleware\CheckRoleMiddleware;
use App\Http\Middleware\TestMiddleware;
use App\Http\Middleware\BasicTestMiddleware;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request; // Make sure you have this line at the top of your file
use App\Http\Controllers\Auth\CustomLogoutController;

use App\Events\LowStockEvent;





Route::get('/', function () {
    return view('auth.login');
})->name('home');

// Authentication Routes
Route::get('login', [LoginController::class, 'create'])->name('login');
Route::post('login', [LoginController::class, 'login'])->name('login.perform'); // Changed the name to avoid conflict

// Registration and Email Verification Routes
Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('register', [RegisteredUserController::class, 'store'])->name('register.store');
Route::middleware('auth')->group(function () {
    // Email Verification Notice
    Route::get('email/verify', [EmailVerificationPromptController::class, '__invoke'])
        ->name('verification.notice');

    // Resend Verification Email
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
});

Route::get('email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
->middleware(['signed', 'throttle:6,1']) // Note: No 'auth' middleware
->name('verification.verify');

Route::get('email/verified', function () {
    return view('auth.email-verified'); // This view displays a success message
})->name('verification.verified');


    Route::post('/custom-logout', [CustomLogoutController::class, 'logout'])->name('custom.logout');

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
        case 'Nurse':
            return redirect()->route('nurse.dashboard');
        case 'Doctor':
            return redirect()->route('doctor.dashboard');
        default:
            return redirect('/');
    }
})->name('dashboard');
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', function (Request $request) {
        Auth::logout(); // Logs out the user
        $request->session()->invalidate(); // Invalidate the session
        $request->session()->regenerateToken(); // Regenerate CSRF token

        return response()->json(['success' => true, 'message' => 'Logged out successfully.']);
    })->name('logout');
});



// Redirect to specific dashboard based on user role
// Group parent routes
Route::middleware(['auth', 'verified', CheckRoleMiddleware::class . ':parent'])->prefix('parent')->name('parent.')->group(function () {
    Route::get('/dashboard', [ParentDashboardController::class, 'index'])->name('dashboard');


    Route::get('/complaint', [ComplaintController::class, 'index'])->name('complaint');
    Route::post('/complaint/store', [ComplaintController::class, 'store'])->name('complaint.store');
    Route::post('/complaint/update-status/{id}', [ComplaintController::class, 'updateStatus'])->name('complaint.update-status');
    Route::get('/medical-record', [MedicalRecordController::class, 'create'])->name('medical-record');
    Route::post('/medical-record/store', [MedicalRecordController::class, 'store'])->name('medical-record.store');
    Route::get('/appointment', [AppointmentController::class, 'index'])->name('appointment');
    Route::post('/appointment/store', [AppointmentController::class, 'store'])->name('appointment.store');
    Route::put('/appointment/update/{id}', [AppointmentController::class, 'update'])->name('appointment.update');
    Route::delete('/appointment/delete/{id}', [AppointmentController::class, 'destroy'])->name('appointment.delete');

    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::put('/profile-image', [SettingsController::class, 'updateImage'])->name('profile.update.image');
    Route::delete('/settings/delete', [SettingsController::class, 'destroy'])->name('settings.delete');
    Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit');
});

// Group student routes

Route::middleware(['auth', 'verified', CheckRoleMiddleware::class . ':student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
        Route::get('/submit-health-exam', [HealthExaminationController::class, 'create'])->name('submit-health-exam');
        Route::get('/medical-record/index', [MedicalRecordController::class, 'index'])->name('medical-record');
        Route::get('/dental-record/index', [DentalRecordController::class, 'index'])->name('dental-record');
        Route::post('/health-examination/store', [HealthExaminationController::class, 'store'])->name('health-examination.store');
        Route::put('/health-examination/update', [HealthExaminationController::class, 'update'])->name('health-examination.update');
        Route::get('/health-examination', [HealthExaminationController::class, 'index'])->name('health-examination.index');

        Route::get('/health-examination/status', [HealthExaminationController::class, 'checkApprovalStatus'])->name('health-examination.status');
        Route::get('/health-examination/{id}/details', [HealthExaminationController::class, 'getDetails'])->name('health-examination.details');
        
        Route::get('/upload-pictures', [HealthExaminationController::class, 'create'])->name('upload-pictures');
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications', [NotificationController::class, 'store'])->name('notifications.store');
        Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
        Route::get('/dental-record/pdf/{id_number}', [App\Http\Controllers\DentalRecordController::class, 'generatePdf'])->name('dentalRecord.pdf');
        Route::put('/profile-image', [SettingsController::class, 'updateImage'])->name('profile.update.image');
        Route::delete('/settings/delete', [SettingsController::class, 'destroy'])->name('settings.delete');
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('profile/store', [StudentDashboardController::class, 'storeProfile'])->name('profile.store');
    Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit');
    Route::put('/settings/update', [SettingsController::class, 'update'])->name('settings.update');
    Route::delete('/settings/delete', [SettingsController::class, 'delete'])->name('settings.delete');

    Route::put('settings/updateAdditional', [SettingsController::class, 'updateAdditional'])->name('settings.updateAdditional');



    Route::get('/complaint', [ComplaintController::class, 'index'])->name('complaint');
    Route::post('/complaint/store', [ComplaintController::class, 'store'])->name('complaint.store');
    Route::get('/complaint/{id}', [ComplaintController::class, 'show'])->name('complaint.show');
    Route::put('/physical-exam/{id}', [PhysicalExaminationController::class, 'update'])->name('physical-exam.update');
    Route::post('/physical-exam/store', [PhysicalExaminationController::class, 'store'])->name('physical-exam.store');
    Route::get('/physical-exam/{id}/edit', [PhysicalExaminationController::class, 'edit'])->name('student.physical-exam.edit');
    Route::get('medical-records', [StudentController::class, 'showMedicalRecords'])->name('medical-records');

    Route::get('update-records/{id_number}', [StudentController::class, 'showUpdateRecords'])->name('update-records');

    Route::post('/complaint/add', [ComplaintController::class, 'add'])->name('complaint.add');
    Route::get('/health-examination/{id}', [HealthExaminationController::class, 'show'])->name('health-examination.show');
    Route::get('/health-examination/{id}/download-pdf', [HealthExaminationController::class, 'downloadPdf'])->name('health-examination.downloadPdf');

    Route::get('/dental-examination/history', [DentalRecordController::class, 'history'])->name('dental-examination.history');
    Route::get('/tooth-history', [DentalRecordController::class, 'toothHistory'])->name('tooth-history');
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
        Route::post('/physical-exam/store', [MedicalRecordController::class, 'storePhysicalExamination'])->name('physical-exam.store');
        Route::get('/physical-exam/bmi-data/{id_number}', [MedicalRecordController::class, 'getBMIData'])->name('physical-exam.bmiData');
        Route::post('/medical-history/store', [MedicalHistoryController::class, 'store'])->name('medical-history.store');
        Route::get('/medical-record/approval-status', [MedicalRecordController::class, 'checkApprovalStatus'])->name('medical-record.approval-status');
        Route::get('/medical-history/{id_number}', [MedicalHistoryController::class, 'show'])->name('medical-history.show');
        Route::post('/medicine-intake/store', [MedicineIntakeController::class, 'store'])->name('medicine-intake.store');
        Route::get('/medicine-intake/{id_number}', [MedicineIntakeController::class, 'show'])->name('medicine-intake.show');

    });

    Route::get('/appointment', [AppointmentController::class, 'indexs'])->name('appointment');
    Route::post('/appointment/add', [AppointmentController::class, 'add'])->name('appointment.add');
    Route::put('/appointment/update/{id}', [AppointmentController::class, 'update'])->name('appointment.update');
    Route::get('/appointments/by-date', [AppointmentController::class, 'getAppointmentsByDate'])->name('appointments.by-date');
    Route::post('/appointments/month', [AppointmentController::class, 'getAppointmentsByMonth'])->name('appointments.by-month');
    Route::delete('/appointment/delete/{id}', [AppointmentController::class, 'delete'])->name('appointment.delete');
});

// Group teacher routes
Route::middleware(['auth', 'verified', CheckRoleMiddleware::class . ':teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');
    Route::get('/submit-health-exam', [HealthExaminationController::class, 'create'])->name('submit-health-exam');
    Route::get('/medical-record/index', [MedicalRecordController::class, 'index'])->name('medical-record');
    Route::get('/dental-record/index', [DentalRecordController::class, 'index'])->name('dental-record');
    Route::post('/health-examination/store', [HealthExaminationController::class, 'store'])->name('health-examination.store');
    Route::get('/health-examination/status', [HealthExaminationController::class, 'checkApprovalStatus'])->name('health-examination.status');
    Route::get('/upload-pictures', [HealthExaminationController::class, 'create'])->name('upload-pictures');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications', [NotificationController::class, 'store'])->name('notifications.store');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::get('/dental-record/pdf/{id_number}', [App\Http\Controllers\DentalRecordController::class, 'generatePdf'])->name('dentalRecord.pdf');
    Route::put('/profile-image', [SettingsController::class, 'updateImage'])->name('profile.update.image');
    Route::delete('/settings/delete', [SettingsController::class, 'destroy'])->name('settings.delete');
Route::post('profile/store', [StudentDashboardController::class, 'storeProfile'])->name('profile.store');
Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit');
Route::put('/settings/update', [SettingsController::class, 'update'])->name('settings.update');
Route::put('/settings/update-image', [SettingsController::class, 'updateImage'])->name('settings.updateImage');
Route::delete('/settings/delete', [SettingsController::class, 'delete'])->name('settings.delete');

Route::put('settings/updateAdditional', [SettingsController::class, 'updateAdditional'])->name('settings.updateAdditional');



Route::get('/complaint', [ComplaintController::class, 'index'])->name('complaint');
Route::post('/complaint/store', [ComplaintController::class, 'store'])->name('complaint.store');
Route::get('/complaint/{id}', [ComplaintController::class, 'show'])->name('complaint.show');
Route::put('/physical-exam/{id}', [PhysicalExaminationController::class, 'update'])->name('physical-exam.update');
Route::post('/physical-exam/store', [PhysicalExaminationController::class, 'store'])->name('physical-exam.store');
Route::get('/physical-exam/{id}/edit', [PhysicalExaminationController::class, 'edit'])->name('student.physical-exam.edit');
Route::get('medical-records', [StudentController::class, 'showMedicalRecords'])->name('medical-records');

Route::get('update-records/{id_number}', [StudentController::class, 'showUpdateRecords'])->name('update-records');

Route::post('/complaint/add', [ComplaintController::class, 'add'])->name('complaint.add');
Route::get('/health-examination/{id}', [HealthExaminationController::class, 'show'])->name('health-examination.show');
Route::get('/health-examination/{id}/download-pdf', [HealthExaminationController::class, 'downloadPdf'])->name('health-examination.downloadPdf');


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
    Route::post('/physical-exam/store', [MedicalRecordController::class, 'storePhysicalExamination'])->name('physical-exam.store');
    Route::get('/physical-exam/bmi-data/{id_number}', [MedicalRecordController::class, 'getBMIData'])->name('physical-exam.bmiData');
    Route::post('/medical-history/store', [MedicalHistoryController::class, 'store'])->name('medical-history.store');
    Route::get('/medical-record/approval-status', [MedicalRecordController::class, 'checkApprovalStatus'])->name('medical-record.approval-status');
    Route::get('/medical-history/{id_number}', [MedicalHistoryController::class, 'show'])->name('medical-history.show');
    Route::post('/medicine-intake/store', [MedicineIntakeController::class, 'store'])->name('medicine-intake.store');
    Route::get('/medicine-intake/{id_number}', [MedicineIntakeController::class, 'show'])->name('medicine-intake.show');

});

Route::get('/appointment', [AppointmentController::class, 'indexs'])->name('appointment');
Route::post('/appointment/add', [AppointmentController::class, 'add'])->name('appointment.add');
Route::put('/appointment/update/{id}', [AppointmentController::class, 'update'])->name('appointment.update');
Route::get('/appointments/by-date', [AppointmentController::class, 'getAppointmentsByDate'])->name('appointments.by-date');
Route::post('/appointments/month', [AppointmentController::class, 'getAppointmentsByMonth'])->name('appointments.by-month');
Route::delete('/appointment/delete/{id}', [AppointmentController::class, 'delete'])->name('appointment.delete');
});

// Group staff routes
Route::middleware(['auth', 'verified', CheckRoleMiddleware::class . ':staff'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('dashboard');
    Route::get('/submit-health-exam', [HealthExaminationController::class, 'create'])->name('submit-health-exam');
    Route::get('/medical-record/index', [MedicalRecordController::class, 'index'])->name('medical-record');
    Route::get('/dental-record/index', [DentalRecordController::class, 'index'])->name('dental-record');
    Route::post('/health-examination/store', [HealthExaminationController::class, 'store'])->name('health-examination.store');
    Route::get('/health-examination/status', [HealthExaminationController::class, 'checkApprovalStatus'])->name('health-examination.status');
    Route::get('/upload-pictures', [HealthExaminationController::class, 'create'])->name('upload-pictures');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications', [NotificationController::class, 'store'])->name('notifications.store');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::get('/dental-record/pdf/{id_number}', [App\Http\Controllers\DentalRecordController::class, 'generatePdf'])->name('dentalRecord.pdf');
    Route::put('/profile-image', [SettingsController::class, 'updateImage'])->name('profile.update.image');
    Route::delete('/settings/delete', [SettingsController::class, 'destroy'])->name('settings.delete');
    Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit');
Route::post('profile/store', [StudentDashboardController::class, 'storeProfile'])->name('profile.store');
Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit');
Route::put('/settings/update', [SettingsController::class, 'update'])->name('settings.update');
Route::put('/settings/update-image', [SettingsController::class, 'updateImage'])->name('settings.updateImage');
Route::delete('/settings/delete', [SettingsController::class, 'delete'])->name('settings.delete');

Route::put('settings/updateAdditional', [SettingsController::class, 'updateAdditional'])->name('settings.updateAdditional');



Route::get('/complaint', [ComplaintController::class, 'index'])->name('complaint');
Route::post('/complaint/store', [ComplaintController::class, 'store'])->name('complaint.store');
Route::get('/complaint/{id}', [ComplaintController::class, 'show'])->name('complaint.show');
Route::put('/physical-exam/{id}', [PhysicalExaminationController::class, 'update'])->name('physical-exam.update');
Route::post('/physical-exam/store', [PhysicalExaminationController::class, 'store'])->name('physical-exam.store');
Route::get('/physical-exam/{id}/edit', [PhysicalExaminationController::class, 'edit'])->name('student.physical-exam.edit');
Route::get('medical-records', [StudentController::class, 'showMedicalRecords'])->name('medical-records');

Route::get('update-records/{id_number}', [StudentController::class, 'showUpdateRecords'])->name('update-records');

Route::post('/complaint/add', [ComplaintController::class, 'add'])->name('complaint.add');
Route::get('/health-examination/{id}', [HealthExaminationController::class, 'show'])->name('health-examination.show');
Route::get('/health-examination/{id}/download-pdf', [HealthExaminationController::class, 'downloadPdf'])->name('health-examination.downloadPdf');


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
    Route::post('/physical-exam/store', [MedicalRecordController::class, 'storePhysicalExamination'])->name('physical-exam.store');
    Route::get('/physical-exam/bmi-data/{id_number}', [MedicalRecordController::class, 'getBMIData'])->name('physical-exam.bmiData');
    Route::post('/medical-history/store', [MedicalHistoryController::class, 'store'])->name('medical-history.store');
    Route::get('/medical-record/approval-status', [MedicalRecordController::class, 'checkApprovalStatus'])->name('medical-record.approval-status');
    Route::get('/medical-history/{id_number}', [MedicalHistoryController::class, 'show'])->name('medical-history.show');
    Route::post('/medicine-intake/store', [MedicineIntakeController::class, 'store'])->name('medicine-intake.store');
    Route::get('/medicine-intake/{id_number}', [MedicineIntakeController::class, 'show'])->name('medicine-intake.show');

});

Route::get('/appointment', [AppointmentController::class, 'indexs'])->name('appointment');
Route::post('/appointment/add', [AppointmentController::class, 'add'])->name('appointment.add');
Route::put('/appointment/update/{id}', [AppointmentController::class, 'update'])->name('appointment.update');
Route::get('/appointments/by-date', [AppointmentController::class, 'getAppointmentsByDate'])->name('appointments.by-date');
Route::post('/appointments/month', [AppointmentController::class, 'getAppointmentsByMonth'])->name('appointments.by-month');
Route::delete('/appointment/delete/{id}', [AppointmentController::class, 'delete'])->name('appointment.delete');
});

// Group admin routes (only accessible to admins)
Route::middleware(['auth', 'verified', CheckRoleMiddleware::class . ':admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard Route
    Route::get('/medical-records/history', [MedicalRecordController::class, 'history'])->name('medical-records.history');
    Route::post('physical-examination/store', [MedicalRecordController::class, 'storePhysicalExamination'])->name('physical-examination.store');
    Route::put('/physical-exam/{id}', [PhysicalExaminationController::class, 'update'])->name('physical-exam.update');
    Route::post('/inventory/report/generate', [InventoryController::class, 'generateStatisticsReport'])->name('inventory.generateReport');
    Route::get('/complaint/report/{role}', [ComplaintController::class, 'generatePdfReport'])->name('complaint.report');
    Route::get('/fetch-profiles', [ProfileController::class, 'fetchProfiles'])->name('profiles.fetch');
    Route::get('/profiles', [ProfileController::class, 'index'])->name('profiles.index');
    Route::post('/profiles/store', [UserController::class, 'store'])->name('profiles.store');
    Route::get('/complaint/statistics', [ComplaintController::class, 'getStatistics'])->name('complaint.statistics');



    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/export-students', [StudentController::class, 'exportStudents']);
    Route::get('/download-student', [App\Http\Controllers\StudentController::class, 'downloadStudent'])->name('download.templates');
    Route::get('/download-teacher', [App\Http\Controllers\TeacherController::class, 'downloadTeacher'])->name('download.teacher');
    Route::get('/download-nurse', [App\Http\Controllers\NurseController::class, 'downloadNurse'])->name('download.nurse');
    Route::get('/download-doctor', [App\Http\Controllers\DoctorController::class, 'downloadDoctor'])->name('download.doctor');
    Route::get('/download-template', [App\Http\Controllers\StaffController::class, 'downloadTemplates'])->name('download.staffs');
    Route::get('/download-parents-template', [ParentController::class, 'downloadTemplate'])->name('download.parents-template');


    // Complaint Routes
    Route::get('/complaint', [ComplaintController::class, 'index'])->name('complaint');
    Route::get('/complaint/add', [ComplaintController::class, 'addComplaint'])->name('complaint.add');
    Route::post('/complaint/store', [ComplaintController::class, 'store'])->name('complaint.store');
    Route::post('/complaint/update-status/{id}', [ComplaintController::class, 'updateStatus'])->name('complaint.update-status');
    Route::get('/complaint/{id}', [ComplaintController::class, 'show'])->name('complaint.show');
    Route::get('/complaint/person/{id}', [ComplaintController::class, 'fetchPersonData']);
    Route::get('/upload-health-examination', [HealthExaminationController::class, 'viewAllRecords'])->name('uploadHealthExamination');
    Route::get('/upload-medical-docu', [MedicalRecordController::class, 'viewAllRecords'])->name('uploadMedicalDocu');
    Route::get('/upload-pictures', [HealthExaminationController::class, 'create'])->name('upload-pictures');
    Route::post('/tooth/{id}/approve', [DentalRecordController::class, 'approveTooth'])->name('tooth.approve');
    Route::post('/tooth/{id}/reject', [DentalRecordController::class, 'rejectTooth'])->name('tooth.reject');
    Route::get('/upload-dental-docu', [DentalRecordController::class, 'viewAllDentalRecords'])->name('uploadDentalDocu');

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
    Route::get('/parents/list', [ParentController::class, 'list'])->name('parents.list');
    Route::post('/parents/{id}/edit', [ParentController::class, 'edit'])->name('parents.edit');


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
    Route::post('/dental-record/{id}/approve', [DentalRecordController::class, 'approveTooth'])->name('dental-record.approve');
    Route::post('/dental-record/{id}/reject', [DentalRecordController::class, 'rejectTooth'])->name('dental-record.reject');

    Route::get('/dental-record', [DentalRecordController::class, 'index'])->name('dental-record.index');
    Route::get('/dental-records', [DentalRecordController::class, 'fetchDentalRecords'])->name('dental-records');
    Route::get('/dental-record/create', [DentalRecordController::class, 'create'])->name('dental-record.create');
    Route::post('/dental-record/store', [DentalRecordController::class, 'store'])->name('dental-record.store');
    Route::post('/dental-record/store-tooth', [DentalRecordController::class, 'storeAdminTooth'])->name('dental-record.store-tooth');
    Route::post('/dental-examination/store', [DentalExaminationController::class, 'store'])->name('dental-examination.store');
    Route::get('/get-tooth-status', [DentalRecordController::class, 'getToothStatus'])->name('getToothStatus');
    Route::get('/search-dental-record', [DentalRecordController::class, 'searchRecords'])->name('searchRecords');
    Route::get('/appointments/statisticsReport', [AppointmentController::class, 'generateStatisticsReport'])->name('appointments.statisticsReport');

    Route::patch('/physical_examinations/{physicalExamination}/approve', [PhysicalExaminationController::class, 'approve'])->name('physical_examinations.approve');
    Route::get('/physical-examinations', [PhysicalExaminationController::class, 'index'])->name('physical_examinations.index');
    Route::get('/physical-examinations/create', [PhysicalExaminationController::class, 'create'])->name('physical-examinations.create');
    Route::post('/physical-examinations/store', [PhysicalExaminationController::class, 'store'])->name('physical-examinations.store');
    
    // Profiles View
    
    // Update settings route
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::put('/settings/update', [SettingsController::class, 'update'])->name('settings.updateAdditional');
    Route::put('/settings/image', [SettingsController::class, 'update'])->name('settings.updateImage');

    Route::put('/profile-image', [SettingsController::class, 'updateImage'])->name('profile.update.image');
    Route::delete('/settings/delete', [SettingsController::class, 'destroy'])->name('settings.delete');
    Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit');


    // Medical Record Routes
Route::get('/search-medical-record', [MedicalRecordController::class, 'search'])->name('medical-record.search');

    Route::get('/medical-records', [MedicalRecordController::class, 'index'])->name('medical-record.index');
    Route::get('/pending-examinations', [HealthExaminationController::class, 'medicalRecord'])->name('medical-records.pending');
    Route::post('/medical-record/store', [HealthExaminationController::class, 'store'])->name('medical-record.store');
    Route::get('/health-examinations', [HealthExaminationController::class, 'Admin'])->name('health-examinations');
    Route::post('/reset-school-year', [HealthExaminationController::class, 'resetSchoolYear'])->name('resetSchoolYear');
    Route::post('/health-examination/{id}/approve', [HealthExaminationController::class, 'approve'])->name('health-examination.approve');
    Route::get('/health-examinations/pending', [HealthExaminationController::class, 'viewAllRecords'])->name('health-examinations.pending.view');
    Route::get('/health-examinations/pending-data', [HealthExaminationController::class, 'getPendingExaminations'])->name('health-examinations.pending.data');

    Route::post('/health-examination/{id}/reject', [HealthExaminationController::class, 'reject'])->name('health-examination.reject');
    Route::get('/get-student-info/{id}', [StudentController::class, 'getStudentInfo'])->name('get-student-info');
    Route::post('/medical-record/{id}/approve', [MedicalRecordController::class, 'approve'])->name('medical-record.approve');
    Route::post('/medical-record/{id}/reject', [MedicalRecordController::class, 'reject'])->name('medical-record.reject');
    
    // Appointment Routes
    Route::get('/appointment', [AppointmentController::class, 'index'])->name('appointment');
    Route::post('/appointment/confirm/{id}', [AppointmentController::class, 'confirm'])->name('appointment.confirm');

    Route::post('/appointment/add', [AppointmentController::class, 'add'])->name('appointment.add');
    Route::put('/appointment/update/{id}', [AppointmentController::class, 'update'])->name('appointment.update');
    Route::delete('/appointment/delete/{id}', [AppointmentController::class, 'delete'])->name('appointment.delete');
    Route::get('/appointment/fetch-patient-name/{id}', [AppointmentController::class, 'fetchPatientName'])->name('appointment.fetch-patient-name'); // Added this route
    Route::get('/appointments/by-month', [AppointmentController::class, 'getAppointmentsByMonth'])->name('appointments.by-month');
    Route::get('/appointments/by-date', [AppointmentController::class, 'getAppointmentsByDate'])->name('appointments.by-date');
    Route::get('/appointment/get-doctors', [AppointmentController::class, 'getApprovedDoctors'])->name('appointment.getApprovedDoctors');

    // Inventory Routes
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory');
    Route::post('/inventory/add', [InventoryController::class, 'add'])->name('inventory.add');
    Route::post('/inventory/update/{id}', [InventoryController::class, 'update'])->name('inventory.update');
    Route::delete('/inventory/delete/{id}', [InventoryController::class, 'delete'])->name('inventory.delete');
    Route::get('/inventory/available-medicines', [InventoryController::class, 'getAvailableMedicines'])->name('inventory.available-medicines');
    Route::post('/inventory/update-quantity', [InventoryController::class, 'updateQuantity'])->name('inventory.update-quantity');


    // Monitoring and Report Log
    Route::get('/report-logs', [ReportLogsController::class, 'index'])->name('monitoring-report-log');

    // Pending Approvals
    Route::get('/pending-approvals', [AdminDashboardController::class, 'pendingApprovals'])->name('pendingApproval');

    // Notification Routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications', [NotificationController::class, 'store'])->name('notifications.store');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
});

Route::middleware(['auth', 'verified', CheckRoleMiddleware::class . ':doctor'])->prefix('doctor')->name('doctor.')->group(function () {
    // Dashboard Route
    Route::get('/dashboard', [DoctorDashboardController::class, 'index'])->name('dashboard');
    Route::get('/appointments', [DoctorDashboardController::class, 'appointments'])->name('appointments');

// Complaints
Route::get('/complaints', [DoctorDashboardController::class, 'complaints'])->name('complaints');

// Medical Records
Route::get('/medical-records', [DoctorDashboardController::class, 'medicalRecords'])->name('medicalRecords');

// Dental Records
Route::get('/dental-records', [DoctorDashboardController::class, 'dentalRecords'])->name('dentalRecords');

    Route::get('/medical-records/history', [MedicalRecordController::class, 'history'])->name('medical-records.history');

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
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::put('/profile-image', [SettingsController::class, 'updateImage'])->name('profile.update.image');
    Route::delete('/settings/delete', [SettingsController::class, 'destroy'])->name('settings.delete');
    Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit');

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
    Route::get('/physical-examinations/create', [PhysicalExaminationController::class, 'create'])->name('physical-examinations.create');
    Route::post('/physical-examinations/store', [PhysicalExaminationController::class, 'store'])->name('physical-examinations.store');
    
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
    Route::delete('/appointment/delete/{id}', [AppointmentController::class, 'delete'])->name('appointment.delete');
    Route::get('/appointment/fetch-patient-name/{id}', [AppointmentController::class, 'fetchPatientName'])->name('appointment.fetch-patient-name');
    Route::put('/appointment/update/{id}', [AppointmentController::class, 'update'])->name('appointment.update');
    Route::post('/appointment/confirm/{id}', [AppointmentController::class, 'confirm'])->name('appointment.confirm');
    Route::get('/appointments-by-month-doctor', [AppointmentController::class, 'getAppointmentsByMonthDoctor'])->name('appointments.by-month-doctor');
    Route::get('/appointments-by-date', [AppointmentController::class, 'getAppointmentsByDate'])->name('appointments.by-date');
    Route::get('/appointments/statisticsReport', [AppointmentController::class, 'generateStatisticsReport'])->name('appointments.statisticsReport');

    Route::get('/inventory/available-medicines', [InventoryController::class, 'getAvailableMedicines'])->name('inventory.available-medicines');


    // Notification Routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications', [NotificationController::class, 'store'])->name('notifications.store');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
});

Route::middleware(['auth', 'verified', CheckRoleMiddleware::class . ':nurse'])->prefix('nurse')->name('nurse.')->group(function () {
    // Dashboard Route
    Route::get('/dashboard', [NurseDashboardController::class, 'index'])->name('dashboard');

    Route::get('/medical-records/history', [MedicalRecordController::class, 'history'])->name('medical-records.history');
    Route::post('physical-examination/store', [MedicalRecordController::class, 'storePhysicalExamination'])->name('physical-examination.store');
    Route::put('/physical-exam/{id}', [PhysicalExaminationController::class, 'update'])->name('physical-exam.update');


    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/export-students', [StudentController::class, 'exportStudents']);
    Route::get('/download-student', [App\Http\Controllers\StudentController::class, 'downloadStudent'])->name('download.templates');
    Route::get('/download-teacher', [App\Http\Controllers\TeacherController::class, 'downloadTeacher'])->name('download.teacher');
    Route::get('/download-template', [App\Http\Controllers\StaffController::class, 'downloadTemplates'])->name('download.staffs');
    Route::get('/download-parents-template', [ParentController::class, 'downloadTemplate'])->name('download.parents-template');


    // Complaint Routes
    Route::get('/complaint', [ComplaintController::class, 'index'])->name('complaint');
    Route::get('/complaint/add', [ComplaintController::class, 'addComplaint'])->name('complaint.add');
    Route::post('/complaint/store', [ComplaintController::class, 'store'])->name('complaint.store');
    Route::post('/complaint/update-status/{id}', [ComplaintController::class, 'updateStatus'])->name('complaint.update-status');
    Route::get('/complaint/{id}', [ComplaintController::class, 'show'])->name('complaint.show');
    Route::get('/complaint/person/{id}', [ComplaintController::class, 'fetchPersonData']);
    Route::get('/upload-health-examination', [HealthExaminationController::class, 'viewAllRecords'])->name('uploadHealthExamination');
    Route::get('/upload-medical-docu', [MedicalRecordController::class, 'viewAllRecords'])->name('uploadMedicalDocu');
    Route::get('/upload-pictures', [HealthExaminationController::class, 'create'])->name('upload-pictures');
    Route::post('/tooth/{id}/approve', [DentalRecordController::class, 'approveTooth'])->name('tooth.approve');
    Route::post('/tooth/{id}/reject', [DentalRecordController::class, 'rejectTooth'])->name('tooth.reject');
    Route::get('/upload-dental-docu', [DentalRecordController::class, 'viewAllDentalRecords'])->name('uploadDentalDocu');

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
    Route::get('/parents/list', [ParentController::class, 'list'])->name('parents.list');
    Route::post('/parents/{id}/edit', [ParentController::class, 'edit'])->name('parents.edit');


    // Teacher Routes
    Route::get('teachers/enrolled', [TeacherController::class, 'enrolledTeachers'])->name('teachers.enrolled');
    Route::post('teachers/add', [TeacherController::class, 'addLateTeacher'])->name('teachers.add');
    Route::get('teachers/upload', [TeacherController::class, 'showUploadForm'])->name('teachers.upload');
    Route::post('teachers/import', [TeacherController::class, 'import'])->name('teachers.import');
    Route::post('teachers/{id}/toggle-approval', [TeacherController::class, 'toggleApproval'])->name('teachers.toggle-approval');
    Route::post('teachers/{id}/edit', [TeacherController::class, 'edit'])->name('teachers.edit');
    Route::delete('teachers/{id}', [TeacherController::class, 'delete'])->name('teachers.delete');
    Route::get('teachers/{id}', [TeacherController::class, 'show'])->name('teachers.show');

    Route::get('doctors/enrolled', [DoctorController::class, 'enrolledDoctors'])->name('doctors.enrolled');
    Route::post('doctors/add', [DoctorController::class, 'addLateDoctor'])->name('doctors.add');
    Route::get('doctors/upload', [DoctorController::class, 'showUploadForm'])->name('doctors.upload');
    Route::post('doctors/import', [DoctorController::class, 'import'])->name('doctors.import');
    Route::post('doctors/{id}/toggle-approval', [DoctorController::class, 'toggleApproval'])->name('doctors.toggle-approval');
    Route::post('doctors/{id}/edit', [DoctorController::class, 'edit'])->name('doctors.edit');
    Route::delete('doctors/{id}', [DoctorController::class, 'delete'])->name('doctors.delete');
    Route::get('doctors/{id}', [DoctorController::class, 'show'])->name('doctors.show');


    // Dental Record Routes
    Route::post('/dental-record/{id}/approve', [DentalRecordController::class, 'approveTooth'])->name('dental-record.approve');
    Route::post('/dental-record/{id}/reject', [DentalRecordController::class, 'rejectTooth'])->name('dental-record.reject');

    Route::get('/dental-record', [DentalRecordController::class, 'index'])->name('dental-record.index');
    Route::get('/dental-records', [DentalRecordController::class, 'viewAllRecords'])->name('dental-records');
    Route::get('/dental-record/create', [DentalRecordController::class, 'create'])->name('dental-record.create');
    Route::post('/dental-record/store', [DentalRecordController::class, 'store'])->name('dental-record.store');
    Route::post('/dental-record/store-tooth', [DentalRecordController::class, 'storeAdminTooth'])->name('dental-record.store-tooth');
    Route::post('/dental-examination/store', [DentalExaminationController::class, 'store'])->name('dental-examination.store');
    Route::get('/get-tooth-status', [DentalRecordController::class, 'getToothStatus'])->name('getToothStatus');
    Route::get('/search-dental-record', [DentalRecordController::class, 'searchRecords'])->name('searchRecords');

    Route::patch('physical_examinations/{physicalExamination}/approve', [PhysicalExaminationController::class, 'approve'])->name('physical_examinations.approve');
    Route::get('/physical-examinations', [PhysicalExaminationController::class, 'index'])->name('physical_examinations.index');
    Route::get('/physical-examinations/create', [PhysicalExaminationController::class, 'create'])->name('physical-examinations.create');
    Route::post('/physical-examinations/store', [PhysicalExaminationController::class, 'store'])->name('physical-examinations.store');
    
    // Profiles View
    
    // Update settings route
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::put('/profile-image', [SettingsController::class, 'updateImage'])->name('profile.update.image');
    Route::delete('/settings/delete', [SettingsController::class, 'destroy'])->name('settings.delete');
    Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit');


    // Medical Record Routes
Route::get('/search-medical-record', [MedicalRecordController::class, 'search'])->name('medical-record.search');

    Route::get('/medical-records', [MedicalRecordController::class, 'index'])->name('medical-record.index');
    Route::get('/pending-examinations', [HealthExaminationController::class, 'medicalRecord'])->name('medical-records.pending');
    Route::post('/medical-record/store', [HealthExaminationController::class, 'store'])->name('medical-record.store');
    Route::post('/health-examination/{id}/approve', [HealthExaminationController::class, 'approve'])->name('health-examination.approve');
    Route::post('/health-examination/{id}/reject', [HealthExaminationController::class, 'reject'])->name('health-examination.reject');
    Route::get('/get-student-info/{id}', [StudentController::class, 'getStudentInfo'])->name('get-student-info');
    Route::post('/medical-record/{id}/approve', [MedicalRecordController::class, 'approve'])->name('medical-record.approve');
    Route::post('/medical-record/{id}/reject', [MedicalRecordController::class, 'reject'])->name('medical-record.reject');
    
    // Appointment Routes
    Route::get('/appointment', [AppointmentController::class, 'index'])->name('appointment');
    Route::post('/appointment/confirm/{id}', [AppointmentController::class, 'confirm'])->name('appointment.confirm');

    Route::post('/appointment/add', [AppointmentController::class, 'add'])->name('appointment.add');
    Route::put('/appointment/update/{id}', [AppointmentController::class, 'update'])->name('appointment.update');
    Route::delete('/appointment/delete/{id}', [AppointmentController::class, 'delete'])->name('appointment.delete');
    Route::get('/appointment/fetch-patient-name/{id}', [AppointmentController::class, 'fetchPatientName'])->name('appointment.fetch-patient-name'); // Added this route
    Route::get('/appointments/by-month', [AppointmentController::class, 'getAppointmentsByMonth'])->name('appointments.by-month');
    Route::get('/appointments/by-date', [AppointmentController::class, 'getAppointmentsByDate'])->name('appointments.by-date');
    Route::get('/appointment/get-doctors', [AppointmentController::class, 'getApprovedDoctors'])->name('appointment.getApprovedDoctors');

    // Inventory Routes
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory');
    Route::post('/inventory/add', [InventoryController::class, 'add'])->name('inventory.add');
    Route::post('/inventory/update/{id}', [InventoryController::class, 'update'])->name('inventory.update');
    Route::delete('/inventory/delete/{id}', [InventoryController::class, 'delete'])->name('inventory.delete');
    Route::get('/inventory/available-medicines', [InventoryController::class, 'getAvailableMedicines'])->name('inventory.available-medicines');
    Route::post('/inventory/update-quantity', [InventoryController::class, 'updateQuantity'])->name('inventory.update-quantity');


    // Monitoring and Report Log
    Route::get('/report-logs', [ReportLogsController::class, 'index'])->name('monitoring-report-log');

    // Pending Approvals
    Route::get('/pending-approvals', [AdminDashboardController::class, 'pendingApprovals'])->name('pendingApproval');

    // Notification Routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications', [NotificationController::class, 'store'])->name('notifications.store');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
});
// Routes for password reset
// Password Reset Routes

// Password Reset Routes
Route::prefix('password')->name('password.')->group(function () {
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('email');
    Route::get('reset-password/{token}', [PasswordController::class, 'create'])->name('reset');
    Route::post('reset-password', [PasswordController::class, 'store'])->name('update');

    // Catch-all GET route for /password/reset-password without token
    Route::get('reset-password', function () {
        return redirect()->route('password.request')->with('error', 'Invalid password reset link.');
    })->name('reset.invalid');
});
Route::prefix('notifications')->name('notifications.')->middleware(['auth'])->group(function () {
    Route::get('/', [NotificationController::class, 'index'])->name('index');
    Route::post('/', [NotificationController::class, 'store'])->name('store');
    Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
    Route::post('/{id}/mark-as-opened', [NotificationController::class, 'markAsOpened'])->name('markAsOpened');
    Route::post('/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('markAllAsRead');
    Route::get('/count', [NotificationController::class, 'count'])->name('notifications.count');

});

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
Route::middleware(TestMiddleware::class)->get('/test', function () {
    return response()->json(['message' => 'Middleware Test Successful!'], 200);
});

Route::middleware(BasicTestMiddleware::class)->get('/test-basic', function () {
    Log::info('Test route reached!');
    return 'Route reached successfully!';
});

Route::get('/test-broadcast', function () {
    event(new LowStockEvent([
        'title' => 'Test Alert',
        'message' => 'This is a test notification.',
        'expiry_date' => '2024-12-31',
    ]));

    return 'Test broadcast sent!';
});