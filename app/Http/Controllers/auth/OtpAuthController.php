<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Jobs\Auth\ProcessOtpUserRegister;
use App\Jobs\Auth\ProcessUserRegister;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OtpAuthController extends Controller
{
    // check for user has ben registered or no
    public function checkUserRegister(Request $request)
    {

        // Find User by Mobile number
        $user = User::where('mobile_number', $request->mobile_number)->first();

        // check if user registered
        if ($user) {

            return response()->json([
                'message' => 'user has registered',
                'mobile_number' => $user->mobile_number,
                'register_date' => $user->created_at,

            ], 200);

            // if user has not be registered
        } else {

            return response()->json([
                'mobile_number' => $request->mobile_number,
                'message' => 'is not registered!!',
            ], 200);
        }
    }


    // send OTP for register or login
    public function OTP(Request $request)
    {
        // validate requirment fields
        $validate = Validator::make($request->all(), [
            'mobile_number' => ['required']

        ]);

        // If mobile number has no enter by user
        if ($validate->fails()) {
            return response()->json(['message' => $validate->errors()->first(), 'status' => false], 500);

            // If Validator Has No Error
        } else {

            // Check User by Mobile number
            $user = User::where('mobile_number', $request->mobile_number)->first();

            // If user has not registered
            if (!$user) {

                // Create Random Code for user
                $code = str_pad(mt_rand(625, 999999), 6, '753', STR_PAD_LEFT);


                $apikey = env('SMS_API_KEY');
                $client = new \GuzzleHttp\Client([
                    'headers' => ['Content-Type' => 'application/json', 'Authorization' => "AccessKey {$apikey}"]
                ]);

                // Values to send
                $patternValues = [
                    "code" => $code,
                ];

                // Send 6 Digit Code for user
                $client->post(
                    'http://rest.ippanel.com/v1/messages/patterns/send',
                    ['body' => json_encode(
                        [
                            'pattern_code' => "",
                            'originator' => "+98",
                            'recipient' => $request->mobile_number,
                            'values' => $patternValues,
                        ]
                    )]
                );




                // Create user with Requests
                $user = new User([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'mobile_number' => $request->mobile_number,
                    'mobile_token' => $code,
                    'register_ip' => $request->ip()
                ]);

                // Save User Data
                $user->save();


                return response()->json([
                    'message' => 'code has ben created to user'
                ], 201);

                // If Mobile number has ben exist
            } else {

                $code = str_pad(mt_rand(625, 999999), 6, '753', STR_PAD_LEFT);



                $apikey = env('SMS_API_KEY');
                $client = new \GuzzleHttp\Client([
                    'headers' => ['Content-Type' => 'application/json', 'Authorization' => "AccessKey {$apikey}"]
                ]);

                // Values to send
                $patternValues = [
                    "code" => $code,
                ];

                // Begin Post sms
                $client->post(
                    'http://rest.ippanel.com/v1/messages/patterns/send',
                    ['body' => json_encode(
                        [
                            'pattern_code' => "",
                            'originator' => "+98",
                            'recipient' => $request->mobile_number,
                            'values' => $patternValues,
                        ]
                    )]
                );

                $user->update([
                    'mobile_token' => $code,
                ]);

                return response()->json([
                    'message' => 'code has ben send to user'
                ], 200);
            }
        }
    }


    // Verify OTP Code
    public function verifyOTP(Request $request)
    {
        // $user = DB::table('users')->where('mobile_token', $request->mobile_token)->first();
        $user = User::where('mobile_token', $request->mobile_token)->first();
        // If mobile token has exist youser has ben verified
        if ($user) {

            // If User Has Not Registered - system send welcome sms to user
            if ($user->mobile_verified_at == null) {

                $user->update([
                    'mobile_verified_at' => Carbon::now(),
                    'mobile_token' => null,
                ]);

                // Assign Registered User Role
                $user->assignRole('registered-user');

                // Dispatch User Register Requirments
                ProcessOtpUserRegister::dispatch($user);

                // set log for user
                activity()
                    ->withProperties(['user_id' => $user->id, 'ip' => $request->ip()])
                    ->log('user has ben signup');
            } else {
                $user->update([
                    'mobile_token' => null
                ]);
            }



            $tokenResult = $user->createToken('Login Token');
            $token = $tokenResult->token;
            $userIp = $request->ip();

            $token->save();


            return response()->json([
                'register_date' => $user->created_at,
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString(),
                'user' => $user,
                'role' => $user->getRoleNames()
            ], 200);


            // if mobile token has ben exipired
        } else {
            return response()->json([
                'message' => 'your code has expired or wrong!'
            ], 400);
        }
    }
}
