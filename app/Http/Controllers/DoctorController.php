<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\User;
use App\Imports\DoctorImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Validators\ValidationException;

class DoctorController extends Controller
{
    public function showUploadForm()
    {
        $doctors = Doctor::all();
        Log::info('Doctor:', $doctors->toArray());
        return view('admin.enrolleddoctor', compact('doctors'));
    }

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

            if (count($duplicates) > 0) {
                $duplicateMessages = [];
                foreach ($duplicates as $duplicate) {
                    $duplicateMessages[] = "Duplicate entry for ID Number: {$duplicate->id_number}";
                }
                return response()->json(['success' => false, 'errors' => $duplicateMessages]);
            }

            return response()->json(['success' => true, 'message' => 'Doctors imported successfully.', 'doctors' => $doctors]);
        } catch (ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = 'Row ' . $failure->row() . ': ' . implode(', ', $failure->errors());
            }
            return response()->json(['success' => false, 'errors' => $errorMessages]);
        } catch (\Exception $e) {
            Log::error('Error importing doctors: ' . $e->getMessage());
            return response()->json(['success' => false, 'errors' => ['There was a problem importing the doctors.']]);
        }
    }

    public function toggleApproval(Request $request, $id)
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->approved = $request->input('approved');
        $doctor->save();

        $user = User::where('id_number', $doctor->id_number)->first();
        if ($user) {
            $user->approved = $doctor->approved;
            $user->save();
        }

        return response()->json(['success' => true, 'message' => 'Doctor status updated successfully.', 'doctor' => $doctor]);
    }

    public function enrolledDoctors()
    {
        $doctors = Doctor::all();
        return response()->json($doctors);
    }

    public function addLateDoctor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'late-id_number' => 'required|string|max:255',
            'late-first_name' => 'required|string|max:255',
            'late-last_name' => 'required|string|max:255',
            'late-specialization' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->all(),
            ]);
        }

        $doctor = new Doctor();
        $doctor->id_number = $request->input('late-id_number');
        $doctor->first_name = $request->input('late-first_name');
        $doctor->last_name = $request->input('late-last_name');
        $doctor->specialization = $request->input('late-specialization');
        $doctor->approved = false;
        $doctor->save();

        Log::info('Late doctor added:', $doctor->toArray());

        return response()->json([
            'success' => true,
            'message' => 'Late Doctor added successfully.',
        ]);
    }

    public function downloadDoctor()
    {
        $filePath = 'templates/doctor_template.xlsx';

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

        return Storage::download($filePath, 'doctor_template.xlsx');
    }

    public function edit(Request $request, $id)
    {
        $doctor = Doctor::findOrFail($id);

        $request->validate([
            'id_number' => 'required|max:10',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
        ]);

        $doctor->id_number = $request->input('id_number');
        $doctor->first_name = $request->input('first_name');
        $doctor->last_name = $request->input('last_name');
        $doctor->specialization = $request->input('specialization');
        $doctor->save();

        $user = User::where('id_number', $doctor->id_number)->first();
        if ($user) {
            $user->first_name = $doctor->first_name;
            $user->last_name = $doctor->last_name;
            $user->save();
        }

        return response()->json(['success' => true, 'message' => 'Doctor details updated successfully.', 'doctor' => $doctor]);
    }

    public function delete($id)
    {
        $doctor = Doctor::findOrFail($id);

        $user = User::where('id_number', $doctor->id_number)->first();
        if ($user) {
            $user->delete();
        }

        $doctor->delete();

        return response()->json(['success' => true, 'message' => 'Doctor deleted successfully.']);
    }

    public function show($id)
    {
        $doctor = Doctor::find($id);

        if (!$doctor) {
            return response()->json(['message' => 'Doctor not found'], 404);
        }

        return response()->json($doctor);
    }
}
