<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade; // Add this line
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::component('layouts.app', 'app-layout');
        Blade::component('components.Adminsidebar', 'adminsidebar');
        Blade::component('components.Parentsidebar', 'parentsidebar');
        Blade::component('components.Studentsidebar', 'studentsidebar');
        Blade::component('components.Teachersidebar', 'teachersidebar');
        Blade::component('components.Staffsidebar', 'staffsidebar');
    }
}
