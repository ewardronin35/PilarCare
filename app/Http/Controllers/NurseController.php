<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nurse;
use App\Models\User;
use App\Imports\NurseImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use Maatwebsite\Excel\Validators\ValidationException;

class NurseController extends Controller
{
    /**
     * Show nurse management page.
     */
    public function showUploadForm()
    {
        $nurses = Nurse::all();
        Log::info('Fetching all nurses.', $nurses->toArray());
        return view('admin.enrollednurse', compact('nurses'));
    }

    /**
     * Handle the nurse import process.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        try {
            $import = new NurseImport();
            Excel::import($import, $request->file('file'));

            $nurses = Nurse::all();
            $duplicates = $import->getDuplicates();

            if (!empty($duplicates)) {
                $duplicateMessages = array_map(fn($id) => "Duplicate entry for ID Number: {$id}", $duplicates);
                return response()->json(['success' => false, 'errors' => $duplicateMessages]);
            }

            return response()->json(['success' => true, 'message' => 'Nurses imported successfully.', 'nurses' => $nurses]);
        } catch (ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];

            foreach ($failures as $failure) {
                $errorMessages[] = "Row {$failure->row()}: " . implode(', ', $failure->errors());
            }

            return response()->json(['success' => false, 'errors' => $errorMessages]);
        } catch (\Exception $e) {
            Log::error('Error importing nurses: ' . $e->getMessage());
            return response()->json(['success' => false, 'errors' => ['There was a problem importing the nurses.']]);
        }
    }

    /**
     * Toggle nurse approval status.
     */
    public function toggleApproval(Request $request, $id)
    {
        $nurse = Nurse::findOrFail($id);

        $request->validate([
            'approved' => 'required|boolean',
        ]);

        $nurse->approved = $request->input('approved');
        $nurse->save();

        // Sync the approval status with the corresponding user account
        $user = User::where('id_number', $nurse->id_number)->first();
        if ($user) {
            $user->approved = $nurse->approved;
            $user->save();
        }

        return response()->json(['success' => true, 'message' => 'Nurse status updated successfully.', 'nurse' => $nurse]);
    }

    /**
     * Fetch all enrolled nurses.
     */
    public function enrolledNurses()
    {
        return response()->json(Nurse::all());
    }

    /**
     * Manually add a late nurse.
     */
    public function addLateNurse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'late-id_number'  => 'required|string|max:10|unique:nurses,id_number',
            'late-first_name' => 'required|string|max:255',
            'late-last_name'  => 'required|string|max:255',
            'late-department' => 'required|string|max:255',
            'late-email'      => 'nullable|email|unique:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->all(),
            ]);
        }

        $nurse = Nurse::create([
            'id_number'  => strtoupper(trim($request->input('late-id_number'))),
            'first_name' => ucfirst(trim($request->input('late-first_name'))),
            'last_name'  => ucfirst(trim($request->input('late-last_name'))),
            'department' => ucfirst(trim($request->input('late-department'))),
            'approved'   => true,
        ]);

        // If email is provided, create a user account
        if ($request->filled('late-email')) {
            $email = strtolower(trim($request->input('late-email')));
            $password = Str::random(16);

            $user = User::create([
                'id_number' => $nurse->id_number,
                'email'     => $email,
                'password'  => Hash::make($password),
                'role'      => 'nurse',
                'approved'  => true,
            ]);

            Password::sendResetLink(['email' => $user->email]);
            Log::info("Password reset link sent to user: {$user->email}");
        }

        Log::info('Late nurse added:', $nurse->toArray());

        return response()->json(['success' => true, 'message' => 'Late Nurse added successfully.']);
    }

    /**
     * Download the nurse Excel template.
     */
    public function downloadNurse()
    {
        $filePath = 'templates/nurse_template.xlsx';

        if (Storage::exists($filePath)) {
            return Storage::download($filePath, 'nurse_template.xlsx');
        }

        Log::error('File not found: ' . $filePath);
        return response()->json(['success' => false, 'errors' => ['File not found.']], 404);
    }

    /**
     * Edit a nurse's details.
     */
    public function edit(Request $request, $id)
    {
        $nurse = Nurse::findOrFail($id);

        $request->validate([
            'id_number'  => 'required|string|max:10|unique:nurses,id_number,' . $nurse->id,
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'email'      => 'nullable|email|unique:users,email,' . ($nurse->user->id ?? 'NULL'),
        ]);

        $nurse->update([
            'id_number'  => strtoupper(trim($request->input('id_number'))),
            'first_name' => ucfirst(trim($request->input('first_name'))),
            'last_name'  => ucfirst(trim($request->input('last_name'))),
            'department' => ucfirst(trim($request->input('department'))),
        ]);

        // Sync with user table
        $user = User::where('id_number', $nurse->id_number)->first();
        if ($user) {
            $user->update([
                'email'      => $request->input('email', $user->email),
                'first_name' => $nurse->first_name,
                'last_name'  => $nurse->last_name,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Nurse details updated successfully.', 'nurse' => $nurse]);
    }

    /**
     * Delete a nurse and corresponding user account.
     */
    public function delete($id)
    {
        $nurse = Nurse::findOrFail($id);

        // Delete associated user if exists
        User::where('id_number', $nurse->id_number)->delete();
        $nurse->delete();

        return response()->json(['success' => true, 'message' => 'Nurse deleted successfully.']);
    }

    /**
     * Show a specific nurse's details.
     */
    public function show($id)
    {
        $nurse = Nurse::find($id);

        if (!$nurse) {
            return response()->json(['message' => 'Nurse not found'], 404);
        }

        return response()->json($nurse);
    }
}
