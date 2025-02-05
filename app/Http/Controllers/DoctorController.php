<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\User;
use App\Imports\DoctorImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Str;
use Maatwebsite\Excel\Validators\ValidationException;

class DoctorController extends Controller
{
    /**
     * Show doctor management page.
     */
    public function showUploadForm()
    {
        $doctors = Doctor::all();
        Log::info('Fetching all doctors.', $doctors->toArray());
        return view('admin.enrolleddoctor', compact('doctors'));
    }

    /**
     * Handle the doctor import process.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        try {
            $import = new DoctorImport();
            Excel::import($import, $request->file('file'));

            $doctors = Doctor::all();
            $duplicates = $import->getDuplicates();

            if (!empty($duplicates)) {
                $duplicateMessages = array_map(fn($id) => "Duplicate entry for ID Number: {$id}", $duplicates);
                return response()->json(['success' => false, 'errors' => $duplicateMessages]);
            }

            return response()->json(['success' => true, 'message' => 'Doctors imported successfully.', 'doctors' => $doctors]);
        } catch (ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];

            foreach ($failures as $failure) {
                $errorMessages[] = "Row {$failure->row()}: " . implode(', ', $failure->errors());
            }

            return response()->json(['success' => false, 'errors' => $errorMessages]);
        } catch (\Exception $e) {
            Log::error('Error importing doctors: ' . $e->getMessage());
            return response()->json(['success' => false, 'errors' => ['There was a problem importing the doctors.']]);
        }
    }

    /**
     * Toggle doctor approval status.
     */
    public function toggleApproval(Request $request, $id)
    {
        $doctor = Doctor::findOrFail($id);

        $request->validate([
            'approved' => 'required|boolean',
        ]);

        $doctor->approved = $request->input('approved');
        $doctor->save();

        // Sync the approval status with the corresponding user account
        $user = User::where('id_number', $doctor->id_number)->first();
        if ($user) {
            $user->approved = $doctor->approved;
            $user->save();
        }

        return response()->json(['success' => true, 'message' => 'Doctor status updated successfully.', 'doctor' => $doctor]);
    }

    /**
     * Fetch all enrolled doctors.
     */
    public function enrolledDoctors()
    {
        return response()->json(Doctor::with('user')->get());
    }

    /**
     * Manually add a late doctor.
     */
    public function addLateDoctor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'late-id_number'       => 'required|string|max:10|unique:doctors,id_number',
            'late-first_name'      => 'required|string|max:255',
            'late-last_name'       => 'required|string|max:255',
            'late-specialization'  => 'required|string|max:255',
            'late-email'           => 'nullable|email|unique:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()->all(),
            ]);
        }

        $doctor = Doctor::create([
            'id_number'       => strtoupper(trim($request->input('late-id_number'))),
            'first_name'      => ucfirst(trim($request->input('late-first_name'))),
            'last_name'       => ucfirst(trim($request->input('late-last_name'))),
            'specialization'  => ucfirst(trim($request->input('late-specialization'))),
            'approved'        => true,
        ]);

        // If email is provided, create a user account
        if ($request->filled('late-email')) {
            $email    = strtolower(trim($request->input('late-email')));
            $password = Str::random(16);

            $user = User::create([
                'id_number' => $doctor->id_number,
                'email'     => $email,
                'password'  => Hash::make($password),
                'role'      => 'doctor',
                'approved'  => true,
            ]);

            Password::sendResetLink(['email' => $user->email]);
            Log::info("Password reset link sent to user: {$user->email}");
        } else {
            Log::warning("No email provided for doctor ID: {$doctor->id_number}. User account not created.");
        }

        Log::info('Late doctor added:', $doctor->toArray());

        return response()->json(['success' => true, 'message' => 'Late Doctor added successfully.']);
    }

    /**
     * Download the doctor Excel template.
     */
    public function downloadDoctor()
    {
        $filePath = 'templates/doctor_template.xlsx';

        if (Storage::exists($filePath)) {
            return Storage::download($filePath, 'doctor_template.xlsx');
        }

        Log::error('File not found: ' . $filePath);
        return response()->json(['success' => false, 'errors' => ['File not found.']], 404);
    }

    /**
     * Edit a doctor's details.
     */
    public function edit(Request $request, $id)
    {
        $doctor = Doctor::findOrFail($id);

        $request->validate([
            'id_number'      => 'required|string|max:10|unique:doctors,id_number,' . $doctor->id,
            'first_name'     => 'required|string|max:255',
            'last_name'      => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'email'          => 'nullable|email|unique:users,email,' . ($doctor->user->id ?? 'NULL'),
        ]);

        $doctor->update([
            'id_number'       => strtoupper(trim($request->input('id_number'))),
            'first_name'      => ucfirst(trim($request->input('first_name'))),
            'last_name'       => ucfirst(trim($request->input('last_name'))),
            'specialization'  => ucfirst(trim($request->input('specialization'))),
        ]);

        // Sync with user table
        $user = User::where('id_number', $doctor->id_number)->first();
        if ($user) {
            $user->update([
                'email'      => $request->input('email', $user->email),
                'first_name' => $doctor->first_name,
                'last_name'  => $doctor->last_name,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Doctor details updated successfully.', 'doctor' => $doctor]);
    }

    /**
     * Delete a doctor and corresponding user account.
     */
    public function delete($id)
    {
        $doctor = Doctor::findOrFail($id);

        // Delete associated user if exists
        User::where('id_number', $doctor->id_number)->delete();
        $doctor->delete();

        return response()->json(['success' => true, 'message' => 'Doctor deleted successfully.']);
    }

    /**
     * Show a specific doctor's details.
     */
    public function show($id)
    {
        $doctor = Doctor::with('user')->find($id);

        if (!$doctor) {
            return response()->json(['success' => false, 'message' => 'Doctor not found'], 404);
        }

        return response()->json(['success' => true, 'doctor' => $doctor]);
    }
}
