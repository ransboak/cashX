<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\Customer;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function filterReport(Request $request){
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date'
        ]);
        $customers = Customer::all();
       $start_date = $request->start_date;
       $end_date = $request->end_date;
        // $collections = Collection::whereDate('created_at', $request->date)->get();

        return view('backend.pages.report-date-specific', compact('customers', 'start_date', 'end_date'));
    }
}
