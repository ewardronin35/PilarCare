<?php
namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:staff',
            'contact_number' => 'required|string|max:12',
            'gender' => 'required|string',
            'id_number' => 'required|string|unique:staff',
            'staff_role' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        Staff::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'contact_number' => $request->contact_number,
            'gender' => $request->gender,
            'id_number' => $request->id_number,
            'staff_role' => $request->staff_role,
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('home')->with('success', 'Staff registered successfully.');
    }
}
