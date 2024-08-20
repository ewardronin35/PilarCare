<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'parent_name' => 'required|string|max:255',
            'parent_contact' => 'required|string|max:15',
            'birthdate' => 'required|date',
            'profile_picture' => 'nullable|image|max:2048',
        ]);

        $user = Auth::user();
        $user->parent_name = $request->parent_name;
        $user->parent_contact = $request->parent_contact;
        $user->birthdate = $request->birthdate;

        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture) {
                Storage::delete($user->profile_picture);
            }
            $filePath = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->profile_picture = $filePath;
        }

        $user->save();

        return response()->json(['success' => true]);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Store the user's profile picture.
     */
    public function storeProfilePicture(Request $request): RedirectResponse
    {
        $request->validate([
            'profile_picture' => 'required|image|max:2048',
        ]);

        $user = Auth::user();

        if ($request->hasFile('profile_picture')) {
            $filePath = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->profile_picture = $filePath;
            $user->save();
        }

        return Redirect::route('profile.edit')->with('status', 'profile-picture-updated');
    }
}
