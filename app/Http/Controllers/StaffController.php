<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff;
use App\Models\User;
use App\Imports\StaffImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Validators\ValidationException;

class StaffController extends Controller
{
    public function showUploadForm()
    {
        $staff = Staff::all();
        Log::info('Staff:', $staff->toArray());
        return view('admin.enrolledstaff', compact('staff'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        try {
            $import = new StaffImport;
            Excel::import($import, $request->file('file'));

            $staff = Staff::all();
            $duplicates = $import->getDuplicates();

            if (count($duplicates) > 0) {
                $duplicateMessages = [];
                foreach ($duplicates as $duplicate) {
                    $duplicateMessages[] = "Duplicate entry for ID Number: {$duplicate->id_number}";
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

    public function toggleApproval(Request $request, $id)
    {
        $staffMember = Staff::findOrFail($id);
        $staffMember->approved = $request->input('approved');
        $staffMember->save();
    
        $user = User::where('id_number', $staffMember->id_number)->first();
        if ($user) {
            $user->approved = $staffMember->approved;
            $user->save();
        }
    
        // Return 'staff' instead of 'staffMember'
        return response()->json(['success' => true, 'message' => 'Staff status updated successfully.', 'staff' => $staffMember]);
    }

    public function enrolledStaff()
    {
        $staff = Staff::all();
        return response()->json($staff);
    }
    public function downloadTemplates()
    {
        $filePath = 'templates/staff_template.xlsx';

        if (Storage::exists($filePath)) {
            try {
                $fileSize = Storage::size($filePath);
                Log::info('File size: ' . $fileSize);
            } catch (\Exception $e) {
                Log::error('Error retrieving file size: ' . $e->getMessage());
            }
        } else {
            Log::error('File not found: ' . $filePath);
        }
        
        // If file exists, proceed with download
        return Storage::download($filePath, 'staff_template.xlsx');
    }
    
    public function getDuplicates()
    {
        return $this->duplicates;
    }
    public function edit(Request $request, $id)
{
    $staffMember = Staff::findOrFail($id);
    
    // Validate the input
    $request->validate([
        'id_number' => 'required|max:10',
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
    ]);

    // Update staff member details
    $staffMember->id_number = $request->input('id_number');
    $staffMember->first_name = $request->input('first_name');
    $staffMember->last_name = $request->input('last_name');
    $staffMember->save();

    // Sync the changes with the user table
    $user = User::where('id_number', $staffMember->id_number)->first();
    if ($user) {
        $user->first_name = $staffMember->first_name;
        $user->last_name = $staffMember->last_name;
        $user->save();
    }

    return response()->json(['success' => true, 'message' => 'Staff details updated successfully.', 'staff' => $staffMember]);
}

public function delete($id)
{
    $staffMember = Staff::findOrFail($id);

    // Also delete the user if exists
    $user = User::where('id_number', $staffMember->id_number)->first();
    if ($user) {
        $user->delete();
    }

    // Delete the staff member
    $staffMember->delete();

    return response()->json(['success' => true, 'message' => 'Staff member deleted successfully.']);
}
public function show($id)
{
    // Find the staff member by ID
    $staff = Staff::find($id);

    // Check if the staff exists
    if (!$staff) {
        return response()->json(['message' => 'Staff not found'], 404);
    }

    // Return the staff data
    return response()->json($staff);
}

}
