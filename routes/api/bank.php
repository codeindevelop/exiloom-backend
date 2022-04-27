
<?php

use App\Http\Controllers\API\Bank\BankController;
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


        // BankAccounts APIS
        Route::get('bank-account', [BankController::class, 'index']);
        Route::post('bank-account', [BankController::class, 'store']);
    });
});
