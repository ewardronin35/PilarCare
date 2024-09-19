<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        // Fetch only the necessary fields: first_name, last_name, role, and id_number
        $students = User::select('first_name', 'last_name', 'role', 'id_number')
                        ->where('role', 'student')
                        ->get();

        return view('admin.profiles', compact('students'));
    }

    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'id_number' => 'required|string|max:255|unique:users',
        ]);

        // Create a new user
        User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'role' => $request->role,
            'id_number' => $request->id_number,
            // Add other fields as necessary
        ]);

        // Redirect or return a response
        return redirect()->route('admin.profiles')->with('success', 'User created successfully.');
    }
}
