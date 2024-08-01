<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Parents;
use App\Models\User;
use App\Imports\ParentImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Validators\ValidationException;

class ParentController extends Controller
{
    public function showUploadForm()
    {
        $parents = Parent::all();
        Log::info('Parents:', $parents->toArray());
        return view('admin.enrolledparents', compact('parents'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        try {
            $import = new ParentImport;
            Excel::import($import, $request->file('file'));

            $parents = Parent::all();
            $duplicates = $import->getDuplicates();

            if (count($duplicates) > 0) {
                $duplicateMessages = [];
                foreach ($duplicates as $duplicate) {
                    $duplicateMessages[] = "Duplicate entry for ID Number: {$duplicate->id_number}";
                }
                return response()->json(['success' => false, 'errors' => $duplicateMessages]);
            }

            return response()->json(['success' => true, 'message' => 'Parents imported successfully.', 'parents' => $parents]);
        } catch (ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = 'Row ' . $failure->row() . ': ' . implode(', ', $failure->errors());
            }
            return response()->json(['success' => false, 'errors' => $errorMessages]);
        } catch (\Exception $e) {
            Log::error('Error importing parents: ' . $e->getMessage());
            return response()->json(['success' => false, 'errors' => ['There was a problem importing the parents.']]);
        }
    }

    public function toggleApproval(Request $request, $id)
    {
        $parent = Parent::findOrFail($id);
        $parent->approved = $request->input('approved');
        $parent->save();

        $user = User::where('id_number', $parent->id_number)->first();
        if ($user) {
            $user->approved = $parent->approved;
            $user->save();
        }

        return response()->json(['success' => true, 'message' => 'Parent status updated successfully.', 'parent' => $parent]);
    }

    public function enrolledParents()
    {
        $parents = Parent::all();
        return response()->json($parents);
    }
}
