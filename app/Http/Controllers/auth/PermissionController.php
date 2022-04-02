<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permissions = Permission::all();
        $Count = $permissions->count();

        return response()->json([
            'permissions' => $permissions,
            'permissionsCount' => $Count
        ]);
    }
    public function getUsersByPermission()
    {
        $permissions = User::with('permissions')->get();

        return response()->json([
            'user_permissions' => $permissions
        ]);
    }



    public function getUserPermissionById($id)
    {
        $user = User::findOrFail($id);

        $permissions = $user->getAllPermissions();

        return response()->json([
            'Permissions' => $permissions
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [

            'permission_name' => ['required'],

        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first(), 'status' => false], 500);
        } else {

            if ($user->hasRole(3)) {

                Permission::create(['name' => $request->permission_name]);

                return response()->json([
                    'message' => 'Permission Has ben created!'
                ], 201);
            } else {
                return response()->json([
                    'message' => 'You don have permission to create a Permission!'
                ], 500);
            }
        }
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $permission = Permission::findOrFail($id);

        if ($user->hasRole(3)) {
            $permission->delete();

            return response()->json(['message' => 'Permission has ben Deleted!']);
        } else {
            return response()->json([
                'message' => 'You don have permission to Delete Permission!'
            ], 500);
        }
    }
}
