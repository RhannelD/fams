<?php

namespace App\Http\Controllers;

use App\Models\User;
use Barryvdh\DomPDF\PDF;
use App\Models\Scholarship;
use Illuminate\Http\Request;

class ScholarListController extends Controller
{
    public function scholar_list(Scholarship $scholarship)
    {
        $this->authorize('view', $scholarship);

        $scholars = User::whereScholarOf($scholarship->id)
            ->orderBy('lastname')
            ->get();

        // return view("/pdf/scholar-list")->with("scholars", $scholars)->with('scholarship', $scholarship);

        view()->share('scholars', $scholars);
        view()->share('scholarship', $scholarship);
        $pdf = PDF::loadView('/pdf/scholar-list', $scholars);
        
        return $pdf->stream('pdf_file.pdf', array("Attachment" => false));
    }
}
