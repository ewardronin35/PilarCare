<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nurse;
use App\Models\User;
use App\Imports\NurseImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Validators\ValidationException;

class NurseController extends Controller
{
    public function showUploadForm()
    {
        $nurses = Nurse::all();
        Log::info('Nurse:', $nurses->toArray());
        return view('admin.enrollednurse', compact('nurses'));
    }

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

            if (count($duplicates) > 0) {
                $duplicateMessages = [];
                foreach ($duplicates as $duplicate) {
                    $duplicateMessages[] = "Duplicate entry for ID Number: {$duplicate->id_number}";
                }
                return response()->json(['success' => false, 'errors' => $duplicateMessages]);
            }

            return response()->json(['success' => true, 'message' => 'Nurses imported successfully.', 'nurses' => $nurses]);
        } catch (ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = 'Row ' . $failure->row() . ': ' . implode(', ', $failure->errors());
            }
            return response()->json(['success' => false, 'errors' => $errorMessages]);
        } catch (\Exception $e) {
            Log::error('Error importing nurses: ' . $e->getMessage());
            return response()->json(['success' => false, 'errors' => ['There was a problem importing the nurses.']]);
        }
    }

    public function toggleApproval(Request $request, $id)
    {
        $nurse = Nurse::findOrFail($id);
        $nurse->approved = $request->input('approved');
        $nurse->save();

        $user = User::where('id_number', $nurse->id_number)->first();
        if ($user) {
            $user->approved = $nurse->approved;
            $user->save();
        }

        return response()->json(['success' => true, 'message' => 'Nurse status updated successfully.', 'nurse' => $nurse]);
    }

    public function enrolledNurses()
    {
        $nurses = Nurse::all();
        return response()->json($nurses);
    }

    public function addLateNurse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'late-id_number' => 'required|string|max:255',
            'late-first_name' => 'required|string|max:255',
            'late-last_name' => 'required|string|max:255',
            'late-department' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->all(),
            ]);
        }

        $nurse = new Nurse();
        $nurse->id_number = $request->input('late-id_number');
        $nurse->first_name = $request->input('late-first_name');
        $nurse->last_name = $request->input('late-last_name');
        $nurse->department = $request->input('late-department');
        $nurse->approved = false;
        $nurse->save();

        Log::info('Late nurse added:', $nurse->toArray());

        return response()->json([
            'success' => true,
            'message' => 'Late Nurse added successfully.',
        ]);
    }

    public function downloadNurse()
    {
        $filePath = 'templates/nurse_template.xlsx';

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

        return Storage::download($filePath, 'nurse_template.xlsx');
    }

    public function edit(Request $request, $id)
    {
        $nurse = Nurse::findOrFail($id);

        $request->validate([
            'id_number' => 'required|max:10',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'department' => 'required|string|max:255',
        ]);

        $nurse->id_number = $request->input('id_number');
        $nurse->first_name = $request->input('first_name');
        $nurse->last_name = $request->input('last_name');
        $nurse->department = $request->input('department');
        $nurse->save();

        $user = User::where('id_number', $nurse->id_number)->first();
        if ($user) {
            $user->first_name = $nurse->first_name;
            $user->last_name = $nurse->last_name;
            $user->save();
        }

        return response()->json(['success' => true, 'message' => 'Nurse details updated successfully.', 'nurse' => $nurse]);
    }

    public function delete($id)
    {
        $nurse = Nurse::findOrFail($id);

        $user = User::where('id_number', $nurse->id_number)->first();
        if ($user) {
            $user->delete();
        }

        $nurse->delete();

        return response()->json(['success' => true, 'message' => 'Nurse deleted successfully.']);
    }

    public function show($id)
    {
        $nurse = Nurse::find($id);

        if (!$nurse) {
            return response()->json(['message' => 'Nurse not found'], 404);
        }

        return response()->json($nurse);
    }
}
