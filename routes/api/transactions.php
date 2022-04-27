<?php

use App\Http\Controllers\API\Transactions\TransactionsController;
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

        // Get All User Transaction
        Route::get('transactions', [TransactionsController::class, 'getAllTransactions']);

        // Get UserTransaction by transaction id
        Route::post('transaction', [TransactionsController::class, 'getTransactionDetail']);
    });
});
