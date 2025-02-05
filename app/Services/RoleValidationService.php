<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RoleValidationService
{
    protected $roleModelMap;

    public function __construct()
    {
        $this->roleModelMap = config('roles.roles');
    }

    /**
     * Validate the authenticated user's role.
     *
     * @return array|null
     */
    public function validateUserRole()
    {
        $user = Auth::user();
        $role = strtolower($user->role);

        // If role is admin, no further validation is needed
        if ($role === 'admin') {
            return null;
        }

        // Check if the role exists in the mapping
        if (!array_key_exists($role, $this->roleModelMap)) {
            Log::warning('Invalid role for login: ' . $user->id_number);
            return ['role' => 'Invalid role for login. Please contact the administrator.'];
        }

        $modelClass = $this->roleModelMap[$role];

        // If no model is associated with the role, skip validation
        if (is_null($modelClass)) {
            return null;
        }

        // Check if the user has a corresponding record in the role-specific table
        $record = $modelClass::where('id_number', $user->id_number)->first();

        if (!$record) {
            Log::warning(ucfirst($role) . ' login attempt without matching record: ' . $user->id_number);
            $errorKey = $role === 'parent' ? 'parent' : strtolower($role);
            $errorMessage = ucfirst($role) . ' record not found. Please contact the administrator.';
            return [$errorKey => $errorMessage];
        }

        return null;
    }
}
