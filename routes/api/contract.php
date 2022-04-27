<?php

use App\Http\Controllers\API\Contract\InvestmentContractController;

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

        // Get All Users Investment Contracts | for admin
        Route::get('investment-contracts/{limit}', [InvestmentContractController::class, 'GetAllInvestmentContracts']);

        // Get User Investment Contract
        Route::get('user-investment-contracts/{limit}', [InvestmentContractController::class, 'GetUserInvestmentContracts']);

        // Create User Investment Contract
        Route::post('create-user-investment-contracts', [InvestmentContractController::class, 'CreateUserInvestmentContract']);
      
    });
});
