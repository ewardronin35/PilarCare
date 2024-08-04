<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Update Profile Information -->
            <div class="p-6 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl mx-auto">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-200 mb-4">Update Profile Information</h3>
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Update Password -->
            <div class="p-6 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl mx-auto">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-200 mb-4">Update Password</h3>
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete Account -->
            <div class="p-6 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl mx-auto">
                    <h3 class="text-lg font-medium text-red-600 dark:text-red-400 mb-4">Delete Account</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        Once your account is deleted, all of its resources and data will be permanently deleted. Please download any data or information that you wish to retain before deleting your account.
                    </p>
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>

    <style>
        body {
            font-family: 'Arial', sans-serif;
        }
    </style>
</x-app-layout>
