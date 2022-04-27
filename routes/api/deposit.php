
<?php

use App\Http\Controllers\Bank\BankController;
use App\Http\Controllers\Deposit\OnlineDepositController;
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
        
        
        // Start Online Deposit
        Route::post('start-online-deposit', [OnlineDepositController::class, 'startOnlineDeposit']);
        
        // Verify Online Deposit Success of Faild
        Route::get('verify-online-deposit', [OnlineDepositController::class, 'verifyOnlineDeposit']);
       
    });
});
