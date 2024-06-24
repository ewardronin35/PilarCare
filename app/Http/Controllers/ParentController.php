<?php
namespace App\Http\Controllers;

use App\Models\Parents;
use Illuminate\Http\Request;

class ParentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:parents',
            'contact_number' => 'required|string|max:12',
            'gender' => 'required|string',
            'id_number' => 'required|string|unique:parents',
            'password' => 'required|string|min:8|confirmed',
        ]);

        Parent::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'contact_number' => $request->contact_number,
            'gender' => $request->gender,
            'id_number' => $request->id_number,
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('home')->with('success', 'Parent registered successfully.');
    }
}
