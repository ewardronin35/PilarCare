<?php

use Illuminate\Support\Facades\Broadcast;

// Public Channel Example
Broadcast::channel('public-channel', function ($user) {
    return true;
});

// Role-Based Private Channels
Broadcast::channel('notifications.role.{role}', function ($user, $role) {
    return strtolower($user->role) === strtolower($role);
});

// User-Specific Private Channels
Broadcast::channel('notifications.user.{userId}', function ($user, $userId) {
    return (int) $user->id_number === (int) $userId;
});
