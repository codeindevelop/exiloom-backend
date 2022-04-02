<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $roles = Role::all();
        $roleCount = $roles->count();

        return response()->json([
            'roles' => $roles,
            'rolesCount' => $roleCount
        ]);
    }



    public function getUsersByRoles(): \Illuminate\Http\JsonResponse
    {
        $userRoles = User::with('roles')->get();


        return response()->json([
            'userRoles' => $userRoles
        ]);
    }

    public function getRolesPermission(): \Illuminate\Http\JsonResponse
    {
        $role_permissions = Role::with('permissions')->get();
        $roleCounts = $role_permissions->count();


        return response()->json([
            'role_permissions' => $role_permissions,
            'rolesCount' => $roleCounts

        ]);
    }

    public function getRolesPermissionsById($id): \Illuminate\Http\JsonResponse
    {


        $targetRole = Role::where('id', $id)->with('permissions')->get();

        return response()->json([
            'role_permissions' => $targetRole
        ]);
    }


    public function assignRoleToUser(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [

            'user_id' => ['required'],
            'rolenames' => ['required'],

        ]);


        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first(), 'status' => false], 500);
        } else {

            if ($user->hasRole('super-admin')) {

                // Give user id by request
                $userid = User::findOrFail($request->user_id);

                $userid->assignRole($request->rolenames);

                return response()->json([
                    'message' => 'Role Has ben assigned to user'
                ], 201);
            } else {
                return response()->json([
                    'message' => 'You don have permission to assign Role to user!'
                ], 500);
            }
        }
    }

    public function removeRoleToUser(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [

            'user_id' => ['required'],
            'rolename' => ['required'],

        ]);


        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first(), 'status' => false], 500);
        } else {

            if ($user->hasRole(3)) {

                // Give user id by request
                $userid = User::findOrFail($request->user_id);

                $userid->removeRole($request->rolename);

                return response()->json([
                    'message' => 'Role Has ben Removed to user'
                ], 201);
            } else {
                return response()->json([
                    'message' => 'You don have permission to Remove User Role!'
                ], 500);
            }
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [

            'role_name' => ['required'],

        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first(), 'status' => false], 500);
        } else {

            if ($user->hasRole(3)) {

                Role::create(['guard_name' => 'api', 'name' => $request->role_name]);

                return response()->json([
                    'message' => 'Role Has ben created!'
                ], 201);
            } else {
                return response()->json([
                    'message' => 'You don have permission to create a Role!'
                ], 500);
            }
        }
    }

    // Role Give A permission By ID
    public function roleGivPermission(Request $request)
    {

        $user = Auth::user();


        $validator = Validator::make($request->all(), [

            'permission' => ['required'],
            'role_id' => ['required'],

        ]);

        $role_id = $request->role_id;
        $permission = $request->permission;


        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first(), 'status' => false], 500);
        } else {

            if ($user->hasRole(3)) {

                $role = Role::findById($role_id);

                $role->givePermissionTo($permission);

                return response()->json([
                    'message' => 'Role Has Ben Updated'
                ], 201);
            } else {
                return response()->json([
                    'message' => 'You don have permission to Update Role Permissions'
                ], 500);
            }
        }
    }

    // Role Revoke A permission By ID
    public function roleRevokePermission(Request $request)
    {

        $user = Auth::user();


        $validator = Validator::make($request->all(), [

            'permission_id' => ['required'],
            'role_id' => ['required'],

        ]);

        $role_id = $request->role_id;
        $permission = $request->permission_id;


        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first(), 'status' => false], 500);
        } else {

            if ($user->hasRole(3)) {

                $role = Role::findById($role_id);

                $role->revokePermissionTo($permission);

                return response()->json([
                    'message' => 'Permission Has Ben revoked from Role'
                ], 201);
            } else {
                return response()->json([
                    'message' => 'You don have permission to Update Role Permissions'
                ], 500);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $role = Role::findOrFail($id);

        if ($user->hasRole(3)) {
            $role->delete();

            return response()->json(['message' => 'Role has ben Deleted!']);
        } else {
            return response()->json([
                'message' => 'You don have permission to Delete Role!'
            ], 500);
        }
    }
}
