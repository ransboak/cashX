<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\Customer;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CollectionController extends Controller
{
    //
    public function addCollection(Request $request)
    {


        $user_id = Auth::user()->id;
        $user = User::find($user_id)->first();
        $user_name = $user->name;
        $formatted_amount = number_format($request->amount, 2, '.', ',');
        $customer = Customer::where('id', $request->customer_id)->first();

        $request->validate([
            'station_name' => 'required',
            'customer_id' => 'required',
            'station_officer' => 'required',
            'amount' => 'required|gt:0|numeric',
        ]);

        if (Auth::user()) {
            $collection = Collection::create([
                'station_name' => $request->station_name,
                'station_officer' => $request->station_officer,
                'amount' => $request->amount,
                'user_id' => $user_id,
                'customer_id' => $request->customer_id
            ]);

            $time = \Carbon\Carbon::parse($collection->created_at)->format('H:i');

            if ($collection) {
                (new NotificationController)->SMS_wirepick($customer->contact, "Dear Customer,
This is to acknowledge receipt of your cash deposit received at $request->station_name paid by $request->station_officer for an amount of GHS$formatted_amount.
Your account will be credited when the cash is lodged at the bank.

Thank you
Truly dependable..........
");
                $email = $customer->email;

                (new NotificationController)->Email_nalo($email, $request->station_name, $request->station_officer, $formatted_amount);

                // $content = 'testmessage';
                // $client = new Client(['verify' => false]);
                // $endpoint = 'https://email.nalosolutions.com/smsbackend/clientapi/Resl_Prud/send-email/';
                // $headers = [
                //     'Content-Type' => 'application/json'
                // ];
                // $maildata = [
                //     "username" => "prudentialbankgh",
                //     "password" => "Prudentialbankgh@2022",
                //     "emailTo" => [$email],
                //     "emailFrom" => "alert@prudentialbank.com.gh",
                //     "emailBody" => "<p>Dear Customer,</p>
                //     <p>This is to acknowledge receipt of your cash deposit received at $request->station_name paid by $request->station_officer for an amount of GHS$formatted_amount. </p>
                //     <p>Your account will be credited when the cash is lodged at the bank.</p>
                //     <p>Thank you</p>
                //     <p>Truly dependable..........</p>",
                //     "senderName" => "Prudential Bank Ltd",
                //     "subject" => "AlertWise Portal",
                //     "callBackUrl" => ""
                // ];

                // try {
                //     $response =  $client->request('POST', $endpoint, ['json' => $maildata, 'headers' =>  $headers]);
                //     if ($response == '') {
                //         abort(503);
                //     }
                // } catch (\Exception $e) {
                //     //api server down
                //     abort(503);
                // }

                // $data = $response->getBody();
                // $jason = json_decode($data, true);

                // return redirect()->back()->with('success', 'Collection Recorded Successfully');
            } else {
                return redirect()->back()->with('error', 'Failed to record collection');
            }
        } else {
            return redirect()->back();
        }
    }
}