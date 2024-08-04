<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff;
use App\Models\User;
use App\Imports\StaffImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
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

        return response()->json(['success' => true, 'message' => 'Staff status updated successfully.', 'staffMember' => $staffMember]);
    }

    public function enrolledStaff()
    {
        $staff = Staff::all();
        return response()->json($staff);
    }
}
