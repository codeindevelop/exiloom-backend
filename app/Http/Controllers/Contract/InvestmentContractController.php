<?php

namespace App\Http\Controllers\API\Contract;

use App\Http\Controllers\Controller;
use App\Models\Contract\InvestmentContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Hekmatinasser\Verta\Facades\Verta;
use Illuminate\Support\Facades\Validator;

class InvestmentContractController extends Controller
{
    // View All Contracts for Admins
    public function GetAllInvestmentContracts(Request $request,$limit)
    {
        $user = Auth::user();

        if ($user->hasPermissionTo('view invest contracts')) {

            $contracts = InvestmentContract::take($limit)->get();
            $count = $contracts->count();

            return response()->json([
                'contracts' => $contracts,
                'count' =>   $count,
            ], 200);
        } else {
            return response()->json([
                'message' => 'you dont have permission to view this',
            ], 401);
        }
    }

    // Get User Contracts By Auth Token
    public function GetUserInvestmentContracts(Request $request,$limit)
    {

        $user = Auth::user();

        $contracts = InvestmentContract::where('user_id', $user->id)->take($limit)->get();
        $count = $contracts->count();

        return response()->json([
            'investment-contracts' => $contracts,
            'count' =>   $count,
        ], 200);
    }


    public function CreateUserInvestmentContract(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'subject' => ['required'],
            'contract_text' => ['required'],
            'contract_month' => ['required'],
            'start_datetime' => ['required'],
            'end_datetime' => ['required'],
            'contract_amount' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
            ], 500);
        } else {

            $contract = new InvestmentContract([
                'user_id' => $user->id,
                'subject' => $request->subject,
                'contract_text' => $request->contract_text,
                'contract_month' => $request->contract_month,
                'start_datetime' => $request->start_datetime,
                'end_datetime' => $request->end_datetime,
                'creator' => 'کاربر',
                'contract_amount' => $request->contract_amount,
            ]);

            $contract->save();


            $apikey = env('SMS_API_KEY');
            $client = new \GuzzleHttp\Client([
                'headers' => ['Content-Type' => 'application/json', 'Authorization' => "AccessKey {$apikey}"]
            ]);
            

            // Values to send
            $patternValues = [
                "name" => "$user->first_name",
                "contract_id" => "$contract->id",
                "amount" => "$request->contract_amount",
                "month" => "$request->contract_month ماه",
                "start_date" => Verta($request->start_datetime)->format('j-n-Y'),
                "end_date" => Verta($request->end_datetime)->format('j-n-Y'),
            ];

            // Begin Post sms
            $client->post(
                'http://rest.ippanel.com/v1/messages/patterns/send',
                ['body' => json_encode(
                    [
                        'pattern_code' => "1t81qw9o69",
                        'originator' => "+983000505",
                        'recipient' =>  $user->mobile_number,
                        'values' => $patternValues,
                    ]
                )]
            );

            return response()->json([
                'message' => 'Contract Has Ben Created'
            ], 201);
        }
    }
}
