<?php

use App\Http\Controllers\API\Wallet\WalletController;
use Illuminate\Support\Facades\Route;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;

/*
|--------------------------------------------------------------------------
| Authentication API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Authentication API routes for your application.
*/


Route::prefix('v1')->group(function () {

   


    Route::middleware("auth:api","verifiedMobile", EnsureEmailIsVerified::class)->group(function () {


        Route::get('user-balance', [WalletController::class, 'index']);
       
    });

});
