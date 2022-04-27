<?php

namespace App\Http\Controllers\Wallet;

use App\Http\Controllers\Controller;
use App\Models\Wallet\Wallet;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;


class WalletController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();

        $balance = $user->wallet()->get();

        return response()->json([
            'balance' => $balance
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();


        $validator = Validator::make($request->all(), [
            'irt_balance' => ['between:0,99.99'],
            'usd_balance' => ['between:0,99.99'],
            'cad_balance' => ['between:0,99.99'],
            'pound_balance' => ['between:0,99.99'],
            'btc_balance' => ['between:0,99.99'],
            'usdt_balance' => ['between:0,99.99'],
            'eth_balance' => ['between:0,99.99'],
            'bch_balance' => ['between:0,99.99'],
            'dash_balance' => ['between:0,99.99'],
            'ltc_balance' => ['between:0,99.99'],
            'imc_balance' => ['between:0,99.99'],
            'usdterc20_balance' => ['between:0,99.99'],
            'eth_balance' => ['between:0,99.99'],

        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first(), 'status' => false], 500);
        }


        $wallet = new Wallet([
            'user_id' => $user->id,
            'irt_balance' => $request->irt_balance,
            'usd_balance' => $request->usd_balance,
            'cad_balance' => $request->cad_balance,
            'pound_balance' => $request->pound_balance,
            'btc_balance' => $request->btc_balance,
            'usdt_balance' => $request->usdt_balance,
            'eth_balance' => $request->eth_balance,
            'bch_balance' => $request->bch_balance,
            'dash_balance' => $request->dash_balance,
            'ltc_balance' => $request->ltc_balance,
            'imc_balance' => $request->imc_balance,
            'usdterc20_balance' => $request->usdterc20_balance,
            'eth_balance' => $request->eth_balance,

        ]);


        // Check User Permission to store product
        if ($user->hasPermissionTo('verified')) {

            $user->wallets()->save($wallet);


            return response()->json([
                "message" => "Balance Has Ben Store",
            ]);
        } else {
            return response()->json([
                'message' => 'You don have permission to Store Balance'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
