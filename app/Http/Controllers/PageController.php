<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    //
    public function allCollections(){
        if(Auth::user()){
            $collections = Collection::all();
            return view('backend.pages.all-collections', compact('collections'));
        }else{
            return redirect()->back();
        }
    }
    public function generalReport(){
        if(Auth::user()){
            $customers = Customer::all();
            return view('backend.pages.general-report', compact('customers'));
        }else{
            return redirect()->back();
        }
    }

    public function getUsers(){
        if(Auth::user()){
            $users = User::all();
            return view('backend.pages.users', compact('users'));
        }else{
            return redirect()->back();
        }
    }

    public function getCollections(Request $request){
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        if(Auth::user()){
            $customer = Customer::where('id', $request->customer_id)->first();
            $collections = Collection::where('customer_id', $request->customer_id)->whereDate('created_at', '>=', $request->start_date)->whereDate('created_at', '<=', $request->end_date)->get();
            return view('backend.pages.collection-date-specific', compact('customer', 'collections', 'start_date', 'end_date'));
        }else{
            return redirect()->back();
        }
    }

    // public function reportDate(){
    //     if(Auth::user()){
    //         $customers = Customer::all();
    //         return view('backend.pages.report-date-specific', compact('customers'));
    //     }else{
    //         return redirect()->back();
    //     }
    // }

    public function customers(){
        if(Auth::user()){
            $customers = Customer::all();
            return view('backend.pages.customers', compact('customers'));
        }else{
            return redirect()->back();
        }
    }

    public function customerCollections($id){
        if(Auth::user()){
            $collections = Collection::where('customer_id', $id);
            $customer = Customer::find($id);
            return view('backend.pages.collections', compact('collections', 'customer'));
        }else{
            return redirect()->back();
        }
    }
}
