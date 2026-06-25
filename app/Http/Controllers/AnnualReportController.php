<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AnnualReportController extends Controller
{
    public function index()
    {
        // Return view or response for annual reports
        return view('annual-report');
    }
}