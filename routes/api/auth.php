<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\auth\OtpAuthController;
use App\Http\Controllers\auth\PasswordResetController;
use App\Http\Controllers\auth\PermissionController;
use App\Http\Controllers\auth\ProfileController;
use App\Http\Controllers\auth\RegisterController;
use App\Http\Controllers\auth\RoleController;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Authentication API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Authentication API routes for your application.
*/


Route::prefix('v1')->group(function () {

    Route::post('check-user-register', [OtpAuthController::class, 'checkUserRegister']);
    Route::post('otp', [OtpAuthController::class, 'OTP']);
    Route::post('verify-otp', [OtpAuthController::class, 'verifyOTP']);


    // account loockup for login API
    Route::post('account-loockup', [LoginController::class, 'accountLookup']);

    //  Email Loockup for Register API
    Route::post('email-loockup', [RegisterController::class, 'emailLookup']);

    Route::post('user-signup', [RegisterController::class, 'userSignup']);
    Route::get('register/activation/{token}', [RegisterController::class, 'signupActive']);


    Route::post('login', [LoginController::class, 'login']);
    Route::post('logout', [LoginController::class, 'logout'])->middleware('auth:api');


    Route::middleware("auth:api")->group(function () {

        // Register Mobile Number after signup | Get OTP Code
        Route::post('register-mobile', [RegisterController::class, 'storeMobileNumber']);
        Route::post('confirm-mobile-code', [RegisterController::class, 'verifyMobileToken']);
        Route::post('get-mobile-code', [RegisterController::class, 'getMobileConfirmCodeAgain']);

        // Get User Profile
        Route::get('profile', [ProfileController::class, 'profile']);

        // Users API Route
        Route::post('create-user', [AuthController::class, 'createUser']);
        Route::get('users', [AuthController::class, 'getAllUsers']);
        Route::get('trashed-users', [AuthController::class, 'getTrashedUsers']);
        Route::put('trashed-user/{id}', [AuthController::class, 'restoreTrashedUsers']);
        Route::get('getuser/{id}', [AuthController::class, 'getUserById']);
        Route::delete('user/{id}', [AuthController::class, 'destroy']);

        // important this api updated user by admin
        Route::put('update-user/{id}', [AuthController::class, 'update']);

        // important this api updated profile by ownuser
        Route::post('update-profile', [AuthController::class, 'UpdateProfile']);
        Route::get('users', [AuthController::class, 'getAllUsers']);



        // Roles Routes API
        Route::get('roles', [RoleController::class, 'index']);
        Route::post('role', [RoleController::class, 'store']);
        Route::get('role-permission/{id}', [RoleController::class, 'getRolesPermissionsById']);
        Route::delete('role/{id}', [RoleController::class, 'destroy']);
        Route::put('user-role/{id}',  [RoleController::class, 'changeUserRole']);
        Route::post('role-giv-permission', [RoleController::class, 'roleGivPermission']);
        Route::post('role-revoke-permission', [RoleController::class, 'roleRevokePermission']);
        Route::get('user-roles', [RoleController::class, 'getUsersByRoles']);
        Route::post('role-to-user', [RoleController::class, 'assignRoleToUser']);
        Route::post('role-remove-user', [RoleController::class, 'removeRoleToUser']);
        Route::get('role-permissions', [RoleController::class, 'getRolesPermission']);

        // Permissions Routes API
        Route::get('permissions', [PermissionController::class, 'index']);
        Route::post('permission', [PermissionController::class, 'store']);
        Route::delete('permission/{id}', [PermissionController::class, 'destroy']);
        Route::get('user-permissions', [PermissionController::class, 'getUsersByPermission']);
        Route::get('get-user-permission/{id}', [PermissionController::class, 'getUserPermissionById']);
    });


    //     Forgot password route group
    Route::group([
        'middleware' => 'api',
        'prefix' => 'password'
    ], function () {
        Route::post('create', [PasswordResetController::class, 'create']);
        Route::get('find/{token}', [PasswordResetController::class, 'find']);
        Route::post('reset', [PasswordResetController::class, 'reset']);
    });
});
