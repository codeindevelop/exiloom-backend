<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{

    // get user profile
    public function profile(): JsonResponse
    {
        $user = Auth::user();
        $role = $user->getRoleNames();
        $personalInfo = $user->personalInfos()->get();

        return response()->json([
            'user' => $user,
            'personalInfo' => $personalInfo,
            'role' => $role,
        ]);
    }
}
