<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\URL;
use App\Models\User;

class CustomEmailVerificationRequest extends FormRequest
{
    public function authorize()
    {
        // Retrieve the user based on the 'id' route parameter
        $user = User::find($this->route('id'));

        if (! $user) {
            return false;
        }

        // Verify the hash matches the user's email
        if (! hash_equals((string) $this->route('hash'), sha1(strtolower($user->getEmailForVerification())))) {
            return false;
        }

        // Verify the URL signature
        return URL::hasValidSignature($this);
    }

    public function rules()
    {
        return [];
    }

    // Rename the method to avoid conflict
    public function getRequestedUser()
    {
        return User::findOrFail($this->route('id'));
    }
}
