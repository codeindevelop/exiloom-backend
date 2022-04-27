<?php

namespace App\Http\Controllers\deposit;

use App\Models\Transaction\OnlineDepositTransaction;
use App\Http\Controllers\Controller;
use App\Jobs\Deposit\OnlineDeposit\ProcessStartOnlineDeposit;
use App\Jobs\Deposit\OnlineDeposit\ProcessVerifyOnlineDeposit;
use App\Models\Transaction\GatewayInvoice;
use App\Models\Invoice\Invoice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class OnlineDepositController extends Controller
{


    public function startOnlineDeposit(Request $request)
    {

        $user = Auth::user();

        $validator = Validator::make(
            $request->all(),
            ['amount' => 'required']
        );

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first(), 'status' => false], 400);
        } else {

            $invoice = new Invoice([
                'user_id' => $user->id,
                'title' => 'پرداخت آنلاین',
                'description' => 'پرداخت آنلاین توسط کاربر به مبلغ دلخواه',
                'amount' => $request->amount,
                'total_discounts' => $request->total_discounts,
                'total_paid' => $request->amount - $request->total_discounts,
                'tax_rate' => 0,
                'du_time' => Carbon::now()->addDays(5),
                'pay_time' => null,
                'invoice_creator' => 'توسط کاربر',
                'status' => 'پرداخت نشده',
            ]);

            $invoice->save();



            // Send Data To Gateway
            $GatewyRequest = Http::post('https://api.zarinpal.com/pg/v4/payment/request.json', [
                'merchant_id' => env('ZARINPAL_MERCHANT_ID'),
                "currency" => "IRT",
                'amount' => $request->amount,
                "callback_url" => env('GATEWAY_CALLBACK_URL'),
                "description" => "پرداخت فاکتور - $invoice->id - توسط کاربر",
                "metadata" => ["mobile" => $user->mobile_number, "email" => $user->email],
            ]);

            $gatewayInvoice = new GatewayInvoice([
                'user_id' => $user->id,
                'invoice_id' => $invoice->id,
                'authority_number' => $GatewyRequest->json()['data']["authority"],
                'amount' => $request->amount,
                'gateway_name' => 'زرین پال',
                'status' => 'پرداخت نشده',
            ]);

            $gatewayInvoice->save();

            $onlineDepositTransaction = new OnlineDepositTransaction([
                'user_id' => $user->id,
                'invoice_id' => $invoice->id,
                'gateway_id' => $gatewayInvoice->id,
                'transaction_type' => 'شارژ کیف پول',
                'amount' => $request->amount,
                'pay_time' => null,
                'tax' => 0,
                'total_discounts' => $request->total_discounts,
                'total_paid' => $request->amount,
                'status' => 'پرداخت نشده',
                'note' => $request->note,

            ]);

            $onlineDepositTransaction->save();


            $amountData = $request->amount;

            // Dispatch Create Invoice job for send email and sms to user
            ProcessStartOnlineDeposit::dispatch($user, $amountData, $invoice);


            // Return pay link to user
            if ($GatewyRequest->json()['data']['code'] == 100) {

                return response()->json([
                    'link' => ("https://www.zarinpal.com/pg/StartPay/" . $GatewyRequest->json()['data']["authority"]),
                    'authority' => substr($GatewyRequest->json()['data']["authority"], -9),
                    'gateway_invoice_number' => $gatewayInvoice->id,
                    'transaction_number' => $onlineDepositTransaction->id
                ], 200);
            }
        }
    }

    // Verify Deposit
    public function verifyOnlineDeposit(Request $request)
    {

        $user = Auth::user();


        $GatewayInvoice = GatewayInvoice::where('user_id', $user->id)->orderBy('created_at', 'desc')->first();

        // Get Last Authority number
        // $authority_number = DB::table('gateway_invoices')->where('user_id', $user->id)->where('amount', $request->amount)->latest()->value('authority_number');
        // $last_transaction_id = DB::table('gateway_invoices')->where('user_id', $user->id)->where('amount', $request->amount)->latest()->value('transaction_id');



        $response = Http::post('https://api.zarinpal.com/pg/v4/payment/verify.json', [
            'merchant_id' => env('ZARINPAL_MERCHANT_ID'),
            'amount' => $GatewayInvoice->amount,
            'authority' => $GatewayInvoice->authority_number,
        ]);


        $oldIrtBalance = DB::table('wallets')->where('user_id', $user->id)->value('irt_balance');


        // If Payment Has ben succsess
        if ($response->status() == 200) {

            if ($response->json()['data']) {
                if ($response->json()['data']['code'] == 100) {

                    // Update user IRT balance
                    DB::table('wallets')->where('user_id', $user->id)->update(array('irt_balance' => $oldIrtBalance + $GatewayInvoice->amount));

                    $invoice = Invoice::where('user_id', $user->id)->orderBy('created_at', 'desc')->first()->update([

                        'pay_time' => Carbon::now(),
                        'status' => 'پرداخت موفق',
                    ]);

                    $GatewayInvoice->update([
                        'status' => 'پرداخت موفق'
                    ]);


                    $invoice_id = Invoice::where('user_id', $user->id)->orderBy('created_at', 'desc')->first();
                    $amountData = $GatewayInvoice->amount;
                    ProcessVerifyOnlineDeposit::dispatch($user, $amountData, $invoice_id);

                    return $response->json();
                } else if ($response->json()['data']['code'] == 101) {
                    return $response->json();
                }
            }
        } else
            // Transaction Has Ben Canceled
            if ($response->status() == 400) {


                return $response->json();
            }
    }
}
