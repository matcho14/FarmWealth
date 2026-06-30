<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cycle;

class AnnualReportController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->input('year', date('Y'));

        // Get completed cycles for the specified year
        $cycles = Cycle::with(['shed', 'financialRecords'])
            ->whereYear('end_date', $year)
            ->where('status', 'completed')
            ->get();

        $totalExpenses = $cycles->sum('total_expenses');
        $totalRevenues = $cycles->sum('total_revenues');
        $netProfit = $cycles->sum('net_profit');

        return view('financial-records.annual-report', compact(
            'year',
            'cycles',
            'totalExpenses',
            'totalRevenues',
            'netProfit'
        ));
    }
}