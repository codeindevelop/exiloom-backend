<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Jobs\User\ProcessUserLogin;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{

    // Loockup and check exist account
    public function accountLookup(Request $request)
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
                    'message' => "couldn't find user account"
                ], 200);
            } else {
                return response()->json([
                    'message' => "user has exist",
                    'email' => $request->email
                ], 200);
            }
        }
    }



    // Login to system function
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean',
            '2fa_code' => 'sometimes|numeric'
        ]);
        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 400);
        }

        $user = Auth::user();

        if (!$user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email not verified'], 401);
        }


        $tokenResult = $user->createToken('Login Token');
        $token = $tokenResult->token;
        $userIp = $request->getClientIp();

        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        } else {
            $token->expires_at = Carbon::now()->addSeconds(config("auth.password_timeout"));
        }
        $token->save();

        ProcessUserLogin::dispatchAfterResponse($user, $userIp);


        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),
            'user' => $user,
            'role' => $user->getRoleNames()
        ]);
    }



    /**
     * Logout user (Revoke the token)
     *
     * @param Request $request
     * @return JsonResponse [string] message
     */
    public function logout(Request $request): JsonResponse
    {
        $user = Auth::user();

        $nowTime = Verta(Carbon::now())->format('H:i');
        $nowDate = Verta(Carbon::now())->format('j-m-Y');

        $request->user()->token()->revoke();

        activity()
            ->withProperties(['ip' => $request->ip()])
            ->log('logout from system');


        return response()->json([
            'message' => 'Successfully logged out'
        ], 200);
    }
}
