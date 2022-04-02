<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Create user by admin
    public function createUser(Request $request)
    {

        $user = Auth::user();
        if ($user->can('create user')) {

            $validate = Validator::make($request->all(), [

                'first_name' => ['required', 'string'],
                'last_name' => ['required', 'string'],
                'mobile_number' => ['required', 'string', 'unique:users'],
                'email' => ['required', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'confirmed'],
            ]);
            if ($validate->fails()) {
                return response()->json(['message' => $validate->errors()->first(), 'status' => false], 500);
            }

            $newuser = new User([

                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'mobile_number' => $request->mobile_number,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'activation_token' => '',
                'active' => true,

            ]);

            if ($user->can('create user')) {
                $newuser->email_verified_at = Carbon::now();
                $newuser->save();

                $newuser->assignRole('user');

                return response()->json(['message' => 'User has ben Created succsessful!']);
            }
            // If admin dos not permission to create usre
        } else {
            return response()->json([
                'message' => 'You don have permission to Create User'
            ], 500);
        }
    }

    // Update user
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $targetUser = User::findOrFail($id);


        if ($user->can('deactivate user')) {

            $targetUser->update([
                'first_name' => $request->has('first_name') ? $request->first_name : $targetUser->first_name,
                'last_name' => $request->has('last_name') ? $request->last_name : $targetUser->last_name,
                'email' => $request->has('email') ? $request->email : $targetUser->email,
                'mobile_number' => $request->has('mobile_number') ? $request->mobile_number : $targetUser->mobile_number,
                'active' => $request->active,
                'password' => $request->password !=  ""  ? bcrypt($request->password) : $targetUser->password
            ]);

            return response()->json([
                'message' => 'User has ben Updated!',
                'updated_user' => $targetUser
            ], 202);
        } else {
            return response()->json([
                'message' => 'you dont have permission',

            ], 500);
        }
    }


    // Get All !not trashed users List
    public function getAllUsers(): JsonResponse
    {
        $user = Auth::user();

        $users = User::all();

        if ($user->can('view users')) {

            $users = User::with('roles')->get();
            $usersCount = $users->count();


            return response()->json([
                'users' => $users,
                'usersCounts' => $usersCount,

            ], 200);
        } else {
            return response()->json([
                'message' => 'You don have permission to see all Users'
            ], 500);
        }
    }

    // Get User By Id
    public function getUserById($id): JsonResponse
    {
        $user = Auth::user();

        $targetUser = User::where('id', $id)->get();
        if ($user->can('view users')) {

            return response()->json([
                'user' => $targetUser
            ], 200);
        } else {
            return response()->json([
                'message' => 'You don have permission'
            ], 500);
        }
    }
    // Get All trashed users List
    public function getTrashedUsers(): JsonResponse
    {
        $user = Auth::user();

        // Check Deleted Users
        $users = User::onlyTrashed()->get();

        if ($user->can('view users')) {

            $trashedUsersCount = $users->count();

            return response()->json([
                'users' => $users,
                'trashedUsersCount' => $trashedUsersCount,
            ], 200);
        } else {
            return response()->json([
                'message' => 'You don have permission to see Trashed Users'
            ], 500);
        }
    }
    // restore trashed user by ID
    public function restoreTrashedUsers($id): JsonResponse
    {
        $user = Auth::user();

        // Check Deleted User
        $targetUser = User::withTrashed()->find($id);

        if ($user->can('active user')) {

            $targetUser->restore();

            return response()->json([
                'message' => 'user has ben restored successful'
            ], 200);
        } else {
            return response()->json([
                'message' => 'You dont have permission'
            ], 500);
        }
    }

    // Delete User By Id
    public function destroy($id): JsonResponse
    {
        $user = Auth::user();

        if ($user->can('delete user')) {
            $targeted = User::findOrFail($id);
            $targeted->delete();

            return response()->json(['message' => 'User has ben Deleted!'], 200);
        } else {
            return response()->json([
                'message' => 'You don have permission to Delete User'
            ], 500);
        }
    }
}
