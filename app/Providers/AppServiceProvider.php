<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade; // Add this line
use Illuminate\Support\ServiceProvider;
use App\Models\HealthExamination;
use App\Models\SchoolYear;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Auth;

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
        // Register your components as before
        Blade::component('layouts.app', 'app-layout');
        Blade::component('components.Adminsidebar', 'adminsidebar');
        Blade::component('components.Parentsidebar', 'parentsidebar');
        Blade::component('components.Studentsidebar', 'studentsidebar');
        Blade::component('components.Teachersidebar', 'teachersidebar');
        Blade::component('components.Staffsidebar', 'staffsidebar');
        
        Relation::morphMap([
            'student' => \App\Models\Student::class,
            'teacher' => \App\Models\Teacher::class,
            'staff' => \App\Models\Staff::class,
            'parent' => \App\Models\Parents::class,
            'admin' => \App\Models\Admin::class,
        ]);
        // View composer to pass health examination approval data to the student sidebar
        view()->composer('components.Studentsidebar', function ($view) {
            $user = Auth::user();
        
            if ($user) {
                // Get the current school year
                $currentSchoolYear = SchoolYear::where('is_current', true)->first();
        
                if ($currentSchoolYear) {
                    // Check if there is an approved health examination for the current school year
                    $healthExamination = HealthExamination::where('id_number', $user->id_number)
                        ->where('school_year', $currentSchoolYear->year)
                        ->where('is_approved', 1)
                        ->first();
                } else {
                    $healthExamination = null;
                }
            } else {
                $healthExamination = null;
            }
        
            // Pass the approval status to the sidebar
            $view->with('healthExamination', $healthExamination);
        });
        view()->composer('components.Teachersidebar', function ($view) {
            $user = Auth::user();
        
            if ($user) {
                // Get the current school year
                $currentSchoolYear = SchoolYear::where('is_current', true)->first();
        
                if ($currentSchoolYear) {
                    // Check if there is an approved health examination for the current school year
                    $healthExamination = HealthExamination::where('id_number', $user->id_number)
                        ->where('school_year', $currentSchoolYear->year)
                        ->where('is_approved', 1)
                        ->first();
                } else {
                    $healthExamination = null;
                }
            } else {
                $healthExamination = null;
            }
        
            // Pass the approval status to the sidebar
            $view->with('healthExamination', $healthExamination);
        });
        
        view()->composer('components.Staffsidebar', function ($view) {
            $user = Auth::user();
        
            if ($user) {
                // Get the current school year
                $currentSchoolYear = SchoolYear::where('is_current', true)->first();
        
                if ($currentSchoolYear) {
                    // Check if there is an approved health examination for the current school year
                    $healthExamination = HealthExamination::where('id_number', $user->id_number)
                        ->where('school_year', $currentSchoolYear->year)
                        ->where('is_approved', 1)
                        ->first();
                } else {
                    $healthExamination = null;
                }
            } else {
                $healthExamination = null;
            }
        
            // Pass the approval status to the sidebar
            $view->with('healthExamination', $healthExamination);
        });
    }
}    
