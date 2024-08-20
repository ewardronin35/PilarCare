<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\HealthExamination;

class CheckApproval
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Check if the health examination is approved
        $healthExamination = HealthExamination::where('user_id', $user->id)
                                              ->where('is_approved', true)
                                              ->first();

        // If the health examination is not approved, redirect to upload pictures page
        if (!$healthExamination) {
            return redirect()->route('student.upload-pictures')
                             ->with('error', 'Your health examination must be approved to access this section.');
        }

        // Otherwise, proceed with the request
        return $next($request);
    }
}
