<?php

namespace App\Http\Controllers;
use PDF;  // Make sure the Barryvdh/Dompdf package is installed
use App\Models\HealthExamination;
use Illuminate\Http\Request;

class PDFController extends Controller
{
    public function downloadPDF()
    {
        // Retrieve the health examination record for the authenticated user
        $userId = auth()->id();
        $healthExamination = HealthExamination::where('user_id', $userId)->first();

        if (!$healthExamination) {
            // If no record is found, you can redirect or show an error message
            return redirect()->back()->with('error', 'No health examination record found.');
        }

        // Load the view and pass the health examination data
        $pdf = PDF::loadView('pdf.health-examination', compact('healthExamination'));

        // Return the generated PDF for download
        return $pdf->download('health-examination.pdf');
    }
}
