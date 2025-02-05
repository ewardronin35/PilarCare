<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff;
use App\Models\User;
use App\Imports\StaffImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;

class StaffController extends Controller
{
    /**
     * Display the staff management view.
     */
    public function showUploadForm()
    {
        $staff = Staff::all();
        Log::info('Displaying staff:', $staff->toArray());
        return view('admin.enrolledstaff', compact('staff'));
    }

    /**
     * Handle the import of staff via Excel.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        try {
            $import = new StaffImport;
            Excel::import($import, $request->file('file'));

            // Fetch all staff after import
            $staff = Staff::all();

            // Check for duplicates (if any)
            $duplicates = $import->getDuplicates();

            if (count($duplicates) > 0) {
                $duplicateMessages = [];
                foreach ($duplicates as $duplicate) {
                    $duplicateMessages[] = "Duplicate or invalid entry for ID Number: {$duplicate->id_number}";
                }
                return response()->json(['success' => false, 'errors' => $duplicateMessages]);
            }

            return response()->json(['success' => true, 'message' => 'Staff imported successfully.', 'staff' => $staff]);
        } catch (ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = 'Row ' . $failure->row() . ': ' . implode(', ', $failure->errors());
            }
            return response()->json(['success' => false, 'errors' => $errorMessages]);
        } catch (\Exception $e) {
            Log::error('Error importing staff: ' . $e->getMessage());
            return response()->json(['success' => false, 'errors' => ['There was a problem importing the staff.']]);
        }
    }

    /**
     * Toggle the approval status of a staff member.
     */
    public function toggleApproval(Request $request, $id)
    {
        $staffMember = Staff::findOrFail($id);

        // Validate the 'approved' input
        $validator = Validator::make($request->all(), [
            'approved' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()->all()], 422);
        }

        // Update approval status
        $staffMember->approved = $request->input('approved');
        $staffMember->save();

        // Sync the approval status with the corresponding user account
        $user = User::where('id_number', $staffMember->id_number)->first();
        if ($user) {
            $user->approved = $staffMember->approved;
            $user->save();
        }

        return response()->json(['success' => true, 'message' => 'Staff approval status updated successfully.', 'staff' => $staffMember]);
    }

    /**
     * Fetch all enrolled staff members.
     */
    public function enrolledStaff()
    {
        $staff = Staff::all();
        return response()->json($staff);
    }

    /**
     * Download the staff Excel template.
     */
    public function downloadTemplates()
    {
        $filePath = 'templates/staff_template.xlsx';

        if (Storage::exists($filePath)) {
            // Optionally, log the download attempt
            Log::info('Downloading staff template.');

            return Storage::download($filePath, 'staff_template.xlsx');
        } else {
            Log::error('Staff template file not found: ' . $filePath);
            return redirect()->back()->withErrors(['error' => 'Template file not found.']);
        }
    }

    /**
     * Edit a staff member's details.
     */
    public function edit(Request $request, $id)
    {
        $staffMember = Staff::findOrFail($id);

        // Define validation rules
        $validator = Validator::make($request->all(), [
            'id_number'        => 'required|string|max:10|unique:staff,id_number,' . $staffMember->id,
            'first_name'       => 'required|string|max:255',
            'last_name'        => 'required|string|max:255',
            'position'         => 'required|string|max:255',
            'father_name'      => 'nullable|string|max:255',
            'mother_name'      => 'nullable|string|max:255',
            'contact_number'   => 'nullable|string|max:200',
            'address'          => 'nullable|string|max:255',
            'birthdate'        => 'nullable|date',
            'emergency_contact'=> 'nullable|string|max:200',
            'age'              => 'nullable|integer|min:0|max:150',
            'email'            => 'nullable|email|unique:users,email,' . ($staffMember->user->id ?? 'NULL'),
            'profile_picture'  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'approved'         => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()->all()], 422);
        }

        // Handle File Upload
        if ($request->hasFile('profile_picture')) {
            // Delete the old profile picture if exists
            if ($staffMember->profile_picture && Storage::disk('public')->exists(str_replace('storage/', '', $staffMember->profile_picture))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $staffMember->profile_picture));
            }

            $image      = $request->file('profile_picture');
            $imageName  = Str::uuid() . '.' . $image->getClientOriginalExtension();
            $path       = $image->storeAs('profile_pictures', $imageName, 'public');
            $profilePic = 'storage/' . $path;
        } else {
            $profilePic = $staffMember->profile_picture;
        }

        // Update Staff Member
        $staffMember->update([
            'id_number'        => strtoupper(trim($request->input('id_number'))),
            'first_name'       => ucfirst(trim($request->input('first_name'))),
            'last_name'        => ucfirst(trim($request->input('last_name'))),
            'position'         => ucfirst(trim($request->input('position'))),
            'father_name'      => isset($request->father_name) ? ucfirst(trim($request->input('father_name'))) : null,
            'mother_name'      => isset($request->mother_name) ? ucfirst(trim($request->input('mother_name'))) : null,
            'contact_number'   => $request->input('contact_number'),
            'address'          => $request->input('address'),
            'birthdate'        => $request->input('birthdate'),
            'emergency_contact'=> $request->input('emergency_contact'),
            'age'              => $request->input('age'),
            'profile_picture'  => $profilePic,
            'approved'         => $request->has('approved') ? $request->input('approved') : $staffMember->approved,
        ]);

        // Sync with User
        $user = User::where('id_number', $staffMember->id_number)->first();
        if ($user) {
            // Update user details
            $user->update([
                'email'     => $request->input('email') ?? $user->email,
                'first_name'=> ucfirst(trim($request->input('first_name'))),
                'last_name' => ucfirst(trim($request->input('last_name'))),
                'position'  => ucfirst(trim($request->input('position'))),
                'approved'  => $staffMember->approved,
            ]);

            // If email is updated, consider sending a verification email
            if ($request->has('email') && $request->input('email') !== $user->email) {
                $user->sendEmailVerificationNotification();
            }
        }

        return response()->json(['success' => true, 'message' => 'Staff details updated successfully.', 'staff' => $staffMember]);
    }

    /**
     * Delete a staff member and corresponding user account.
     */
    public function delete($id)
    {
        $staffMember = Staff::findOrFail($id);

        // Delete corresponding User
        $user = User::where('id_number', $staffMember->id_number)->first();
        if ($user) {
            // Delete profile picture if exists
            if ($user->profile_picture && Storage::disk('public')->exists(str_replace('storage/', '', $user->profile_picture))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $user->profile_picture));
            }

            $user->delete();
            Log::info("Deleted user account for staff ID: {$staffMember->id_number}");
        }

        // Delete profile picture if exists
        if ($staffMember->profile_picture && Storage::disk('public')->exists(str_replace('storage/', '', $staffMember->profile_picture))) {
            Storage::disk('public')->delete(str_replace('storage/', '', $staffMember->profile_picture));
        }

        // Delete the staff member
        $staffMember->delete();

        Log::info("Deleted staff member ID: {$staffMember->id_number}");

        return response()->json(['success' => true, 'message' => 'Staff member deleted successfully.']);
    }

    /**
     * Show a specific staff member's details.
     */
    public function show($id)
{
    // Find the staff member by ID
    $staff = Staff::find($id);

    // Check if the staff exists
    if (!$staff) {
        return response()->json(['message' => 'Staff not found'], 404);
    }

    // Optionally, include related user details
    $user = User::where('id_number', $staff->id_number)->first();

    // Combine staff and user data if needed
    $staffData = $staff->toArray();
    if ($user) {
        $staffData['user'] = $user->toArray();
    }

    // Return the staff data
    return response()->json(['staff' => $staffData]);
}
}
