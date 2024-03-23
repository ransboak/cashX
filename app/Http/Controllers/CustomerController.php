<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function addCustomer(Request $request){
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.Customer::class],
            'contact' => ['required', 'max:10', 'min:10']
        ]);

        $user = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'contact' => $request->contact,
        ]);

        if($user){
            return redirect()->back()->with('success', "$user->name added successfully.");
        }else{
            return redirect()->back()->with('error', 'Unable to add customer.');
        }
    }
}
