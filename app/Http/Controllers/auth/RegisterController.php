<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Jobs\Auth\ProcessUserRegister;
use App\Jobs\User\ProcessUserActive;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{

    // Loockup and check exist Email
    public function emailLookup(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => ['required']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'err' => $validator->errors()->first()
            ], 400);
        } else {

            $targetUser =  User::where('email', $request->email)->first();

            if ($targetUser === null) {
                return response()->json([
                    'message' => "Email Can register",
                    'email' => $request->email
                ], 200);
            } else {
                return response()->json([
                    'message' => "email has exist",
                    'email' => $request->email
                ], 200);
            }
        }
    }

    public function userSignup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed'],
        ]);

        // Check Validator Error
        if ($validator->fails()) {
            return response()->json([
                'err' => $validator->errors()->first()
            ], 400);

            // If Validator Has No Error
        } else {

            // Create user with Requests
            $user = new User([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'activation_token' => Str::random(60),
                'register_ip' => $request->getClientIp()
            ]);

            // Save User Data
            $user->save();


            // Dispatch User Process job for create some tables for user
            ProcessUserRegister::dispatchAfterResponse($user);

            // set log for user
            activity()
                ->withProperties(['user_id' => $user->id, 'ip' => $request->getClientIp()])
                ->log('Signup user');

            // Assign Registered User Role
            $user->assignRole('user');

            // Create Access Token For user After Register
            $tokenResult = $user->createToken('accessToken');
            $token = $tokenResult->token;
            $token->expires_at = Carbon::now()->addSeconds(config("auth.password_timeout"));
            $token->save();


            // Return Json Response User and AccessToken 
            return response()->json([
                'message' => 'User has ben registered successful',
                'accessToken' => $tokenResult->accessToken,
                'expires_at' => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString(),
                'user' => $user,
                'role' => $user->getRoleNames()
            ], 201);
        }
    }

    // Register User in system
    public function register(Request $request): JsonResponse
    {
        // check can register users from auth settings - if can not !
        if (DB::table('auth_settings')->where('id', 1)->value('can_register') != 1) {
            return response()->json(['message' => 'Register Has ben disabled by admin.'], 200);

            // If users can be register
        } else {

            // Check auth setting for register type - full
            if (DB::table('auth_settings')->where('id', 1)->value('register_type') == 'full') {

                // Validate Requested fields
                $validator = Validator::make($request->all(), [

                    'first_name' => ['required', 'string'],
                    'last_name' => ['required', 'string'],
                    'mobile_number' => ['required', 'string', 'unique:users'],
                    'email' => ['required', 'email', 'max:255', 'unique:users'],
                    'password' => ['required', 'confirmed'],
                ]);

                // Create user with Requests
                $user = new User([

                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'mobile_number' => $request->mobile_number,
                    'email' => $request->email,
                    'password' => bcrypt($request->password),
                    'activation_token' => Str::random(60),
                    'mobile_token' => "",
                    'register_ip' => $request->ip()
                ]);


                // Check auth setting for register type - Email
            } elseif (DB::table('auth_settings')->where('id', 1)->value('register_type') == 'email') {

                // Validate Requested fields
                $validator = Validator::make($request->all(), [

                    'email' => ['required', 'email', 'max:255', 'unique:users'],
                    'password' => ['required', 'confirmed', 'min:8'],
                ]);

                // Create user with Requests
                $user = new User([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'password' => bcrypt($request->password),
                    'activation_token' => Str::random(60),
                    'register_ip' => $request->ip()
                ]);

                // Check auth setting for register type - Mobile
            }

            // Check Validator Error
            if ($validator->fails()) {
                return response()->json([
                    'err' => $validator->errors()->first()
                ], 400);

                // If Validator Has No Error
            } else {

                // Save User Data
                $user->save();


                // Dispatch User Process job for create some tables for user
                ProcessUserRegister::dispatchAfterResponse($user);

                // set log for user
                activity()
                    ->withProperties(['user_id' => $user->id, 'ip' => $request->getClientIp()])
                    ->log('Signup user');

                // Assign Registered User Role
                $user->assignRole('user');


                return response()->json(['message' => 'User has ben registered successful'], 201);
            }
        }
    }


    // Active user after click on email activation
    public function signupActive($token, Request $request): JsonResponse
    {
        $user = User::where('activation_token', $token)->first();
        if (!$user) {
            return response()->json([
                'message' => 'This activation token is invalid.'
            ], 404);
        }
        $user->active = true;
        $user->email_verified_at = Carbon::now();
        $user->activation_token = '';
        $user->save();

        $ip = $request->ip();

        // Dispatch Process User Active Job
        ProcessUserActive::dispatchSync($user, $ip);

        return response()->json(['message' => 'User has ben activate!'], 200);
    }

    public function storeMobileNumber(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'mobile_number' => ['required', 'unique:users']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 200);
        } else {
            // Get Current User
            $user = Auth::user();

            // Create Random Code for user
            $code = str_pad(mt_rand(625, 999999), 6, '753', STR_PAD_LEFT);

            // save token in DB for validate
            $user->update([
                'mobile_number' => $request->mobile_number,
                'sms_token' => $code
            ]);

            // Send OTP code to user
            $apikey = env('SMS_API_KEY');
            $client = new \GuzzleHttp\Client([
                'headers' => ['Content-Type' => 'application/json', 'Authorization' => "AccessKey {$apikey}"]
            ]);

            // Values to send
            $patternValues = [
                "code" => $code,
            ];

            // Send 6 Digit Code for user
            // $client->post(
            //     'http://rest.ippanel.com/v1/messages/patterns/send',
            //     ['body' => json_encode(
            //         [
            //             'pattern_code' => "",
            //             'originator' => "+98",
            //             'recipient' => $request->mobile_number,
            //             'values' => $patternValues,
            //         ]
            //     )]
            // );

            return response()->json([
                'message' => 'code has ben send to user'
            ], 200);
        }
    }

    // Verify OTP Code
    public function verifyMobileToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'confirm_code' => ['required']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'err' => $validator->errors()->first()
            ], 400);
        } else {
            // Get Current User

            $user = User::where('sms_token', $request->confirm_code)->first();

            if ($user !== null) {

                $user->update([
                    'mobile_verified_at' => Carbon::now(),
                    'sms_token' => null,
                ]);

                // set log for user
                activity()
                    ->withProperties(['user_id' => $user->id, 'ip' => $request->getClientIp()])
                    ->log('user mobile verified');

                return response()->json([
                    'message' => 'mobile confirmed success',
                ], 200);
            } else {
                return response()->json([
                    'message' => 'code has ben expired',
                ], 400);
            }
        }
    }

    // Get OTP Code Again
    public function getMobileConfirmCodeAgain(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile_number' => ['required']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'err' => $validator->errors()->first()
            ], 400);
        } else {

            // Get Current User
            $user = Auth::user();

            // Create Random Code for user
            $code = str_pad(mt_rand(625, 999999), 6, '753', STR_PAD_LEFT);

            // save token in DB for validate
            $user->update([
                'sms_token' => $code
            ]);

            return response()->json([
                'message' => 'code has ben created'
            ], 200);
        }
    }
}
