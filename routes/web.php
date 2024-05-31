<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ComplaintController; // Add this line

// Route for login
Route::get('/', function () {
    return view('welcomepage'); // Correctly return the view
}); // Added missing semicolon here

// Route for register
Route::get('/register', function () {
    return view('register');
})->name('register');

// Route for dashboard with middleware for auth and email verification
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Route for medical record with middleware for auth
Route::get('/medical-record', function () {
    return view('medical-record');
})->middleware(['auth', 'verified'])->name('medical-record');

Route::get('/dental-record', function () {
    return view('dental-record');
})->middleware(['auth', 'verified'])->name('dental-record');

// Route for appointments
Route::get('/appointment', [AppointmentController::class, 'index'])->name('appointment');
Route::post('/appointment/add', [AppointmentController::class, 'add'])->name('appointment.add');
Route::put('/appointment/update/{id}', [AppointmentController::class, 'update'])->name('appointment.update');
Route::delete('/appointment/delete/{id}', [AppointmentController::class, 'delete'])->name('appointment.delete');

// Route for inventory
Route::get('/inventory', function () {
    return view('inventory');
})->middleware(['auth', 'verified'])->name('inventory');

Route::get('/notification', function () {
    return view('notification');
})->middleware(['auth', 'verified'])->name('notification');

Route::get('/monitoring-report-log', function () {
    return view('monitoring-report-log');
})->middleware(['auth', 'verified'])->name('monitoring-report-log');

// Route for complaints
Route::get('/complaint', [ComplaintController::class, 'index'])->name('complaint');
Route::post('/complaint/add', [ComplaintController::class, 'add'])->name('complaint.add');
Route::put('/complaint/update/{id}', [ComplaintController::class, 'update'])->name('complaint.update');
Route::delete('/complaint/delete/{id}', [ComplaintController::class, 'delete'])->name('complaint.delete');

// Grouped routes for profile with middleware for auth
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Routes for notifications
Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
Route::get('/notifications/create', [NotificationController::class, 'create'])->name('notifications.create');
Route::post('/notifications', [NotificationController::class, 'store'])->name('notifications.store');

// Inventory routes
Route::get('/inventory', [InventoryController::class, 'index'])->middleware(['auth', 'verified'])->name('inventory');
Route::post('/inventory/add', [InventoryController::class, 'add'])->middleware(['auth', 'verified'])->name('inventory.add');
Route::post('/inventory/update/{id}', [InventoryController::class, 'update'])->middleware(['auth', 'verified'])->name('inventory.update');
Route::post('/inventory/delete/{id}', [InventoryController::class, 'delete'])->middleware(['auth', 'verified'])->name('inventory.delete');

Route::post('/logout', function () {
    Auth::logout(); // Use Laravel's Auth facade to logout the user
    return redirect('/'); // Redirect to homepage or login page
})->name('logout');

require __DIR__.'/auth.php';
