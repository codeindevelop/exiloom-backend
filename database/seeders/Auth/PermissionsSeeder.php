<?php

namespace Database\Seeders\Auth;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Post Category permissions
        Permission::create(['guard_name' => 'api', 'name' => 'create post category']);
        Permission::create(['guard_name' => 'api', 'name' => 'edit post category']);
        Permission::create(['guard_name' => 'api', 'name' => 'delete post category']);
        Permission::create(['guard_name' => 'api', 'name' => 'update post category']);
        Permission::create(['guard_name' => 'api', 'name' => 'unpublished post category']);

        // Post permissions
        Permission::create(['guard_name' => 'api', 'name' => 'create post']);
        Permission::create(['guard_name' => 'api', 'name' => 'edit post']);
        Permission::create(['guard_name' => 'api', 'name' => 'delete post']);
        Permission::create(['guard_name' => 'api', 'name' => 'publish post']);
        Permission::create(['guard_name' => 'api', 'name' => 'unpublished post']);

        // Settings
        Permission::create(['guard_name' => 'api', 'name' => 'modify general settings']);
        Permission::create(['guard_name' => 'api', 'name' => 'modify seo settings']);
        Permission::create(['guard_name' => 'api', 'name' => 'modify auth settings']);
        Permission::create(['guard_name' => 'api', 'name' => 'modify social settings']);
        Permission::create(['guard_name' => 'api', 'name' => 'modify localization settings']);
        Permission::create(['guard_name' => 'api', 'name' => 'modify sms settings']);
        Permission::create(['guard_name' => 'api', 'name' => 'modify email settings']);

        // Digital Products Category permissions
        Permission::create(['guard_name' => 'api', 'name' => 'create digital product category']);
        Permission::create(['guard_name' => 'api', 'name' => 'edit digital product category']);
        Permission::create(['guard_name' => 'api', 'name' => 'delete digital product category']);
        Permission::create(['guard_name' => 'api', 'name' => 'update digital product category']);
        Permission::create(['guard_name' => 'api', 'name' => 'unpublished digital product category']);

        // Digital Products  permissions
        Permission::create(['guard_name' => 'api', 'name' => 'create digital product']);
        Permission::create(['guard_name' => 'api', 'name' => 'edit digital product']);
        Permission::create(['guard_name' => 'api', 'name' => 'delete digital product']);
        Permission::create(['guard_name' => 'api', 'name' => 'update digital product']);
        Permission::create(['guard_name' => 'api', 'name' => 'unpublished digital product']);

        // GiftCards Permission
        Permission::create(['guard_name' => 'api', 'name' => 'manage giftcards']);


        Permission::create(['guard_name' => 'api', 'name' => 'edit products']);
        Permission::create(['guard_name' => 'api', 'name' => 'delete products']);
        Permission::create(['guard_name' => 'api', 'name' => 'publish products']);
        Permission::create(['guard_name' => 'api', 'name' => 'unpublished products']);

        Permission::create(['guard_name' => 'api', 'name' => 'edit tickets']);
        Permission::create(['guard_name' => 'api', 'name' => 'delete tickets']);
        Permission::create(['guard_name' => 'api', 'name' => 'response tickets']);
        Permission::create(['guard_name' => 'api', 'name' => 'close tickets']);

        Permission::create(['guard_name' => 'api', 'name' => 'edit invoice']);
        Permission::create(['guard_name' => 'api', 'name' => 'delete invoice']);
        Permission::create(['guard_name' => 'api', 'name' => 'create invoice']);
        Permission::create(['guard_name' => 'api', 'name' => 'close invoice']);

        Permission::create(['guard_name' => 'api', 'name' => 'active user']);
        Permission::create(['guard_name' => 'api', 'name' => 'create user']);
        Permission::create(['guard_name' => 'api', 'name' => 'delete user']);
        Permission::create(['guard_name' => 'api', 'name' => 'deactivate user']);
        Permission::create(['guard_name' => 'api', 'name' => 'verify document']);
        Permission::create(['guard_name' => 'api', 'name' => 'view users']);

        Permission::create(['guard_name' => 'api', 'name' => 'manage portal']);
        Permission::create(['guard_name' => 'api', 'name' => 'manage pages']);
        Permission::create(['guard_name' => 'api', 'name' => 'manage eshop']);
        Permission::create(['guard_name' => 'api', 'name' => 'access to panel']);
        Permission::create(['guard_name' => 'api', 'name' => 'manage email templates']);
        Permission::create(['guard_name' => 'api', 'name' => 'view reseller']);
        Permission::create(['guard_name' => 'api', 'name' => 'create reseller package']);
        Permission::create(['guard_name' => 'api', 'name' => 'manage reseller package']);
        Permission::create(['guard_name' => 'api', 'name' => 'delete reseller package']);
        Permission::create(['guard_name' => 'api', 'name' => 'create reseller']);
        Permission::create(['guard_name' => 'api', 'name' => 'manage reseller']);
        Permission::create(['guard_name' => 'api', 'name' => 'delete reseller']);
        Permission::create(['guard_name' => 'api', 'name' => 'edit withdraw']);
        Permission::create(['guard_name' => 'api', 'name' => 'confirm withdraw']);

        // create roles and assign existing permissions
        $role1 = Role::create(['guard_name' => 'api', 'name' => 'writer']);
        $role1->givePermissionTo('edit post');
        $role1->givePermissionTo('delete post');

        $role2 = Role::create(['guard_name' => 'api', 'name' => 'admin']);
        $role2->givePermissionTo('publish post');
        $role2->givePermissionTo('unpublished post');
        $role2->givePermissionTo('view reseller');
        $role2->givePermissionTo('create reseller');

        $superadminrole = Role::create(['guard_name' => 'api', 'name' => 'super-admin']);
        $superadminrole->givePermissionTo('create post category');
        $superadminrole->givePermissionTo('edit post category');
        $superadminrole->givePermissionTo('delete post category');
        $superadminrole->givePermissionTo('update post category');
        $superadminrole->givePermissionTo('unpublished post category');

        $superadminrole->givePermissionTo('create post');
        $superadminrole->givePermissionTo('edit post');
        $superadminrole->givePermissionTo('delete post');
        $superadminrole->givePermissionTo('publish post');
        $superadminrole->givePermissionTo('unpublished post');


        $superadminrole->givePermissionTo('modify general settings');
        $superadminrole->givePermissionTo('modify seo settings');
        $superadminrole->givePermissionTo('modify auth settings');
        $superadminrole->givePermissionTo('modify social settings');
        $superadminrole->givePermissionTo('modify localization settings');
        $superadminrole->givePermissionTo('modify sms settings');
        $superadminrole->givePermissionTo('modify email settings');


        $superadminrole->givePermissionTo('create digital product category');
        $superadminrole->givePermissionTo('edit digital product category');
        $superadminrole->givePermissionTo('delete digital product category');
        $superadminrole->givePermissionTo('update digital product category');
        $superadminrole->givePermissionTo('unpublished digital product category');

        $superadminrole->givePermissionTo('create digital product');
        $superadminrole->givePermissionTo('edit digital product');
        $superadminrole->givePermissionTo('delete digital product');
        $superadminrole->givePermissionTo('update digital product');
        $superadminrole->givePermissionTo('unpublished digital product');

        $superadminrole->givePermissionTo('edit products');
        $superadminrole->givePermissionTo('delete products');
        $superadminrole->givePermissionTo('publish products');
        $superadminrole->givePermissionTo('unpublished products');
        $superadminrole->givePermissionTo('edit tickets');
        $superadminrole->givePermissionTo('delete tickets');
        $superadminrole->givePermissionTo('response tickets');
        $superadminrole->givePermissionTo('close tickets');
        $superadminrole->givePermissionTo('edit invoice');
        $superadminrole->givePermissionTo('delete invoice');
        $superadminrole->givePermissionTo('create invoice');
        $superadminrole->givePermissionTo('close invoice');
        $superadminrole->givePermissionTo('create user');
        $superadminrole->givePermissionTo('active user');
        $superadminrole->givePermissionTo('deactivate user');
        $superadminrole->givePermissionTo('delete user');
        $superadminrole->givePermissionTo('view users');
        $superadminrole->givePermissionTo('manage portal');
        $superadminrole->givePermissionTo('manage pages');
        $superadminrole->givePermissionTo('manage eshop');
        $superadminrole->givePermissionTo('manage email templates');
        $superadminrole->givePermissionTo('view reseller');
        $superadminrole->givePermissionTo('create reseller package');
        $superadminrole->givePermissionTo('manage reseller package');
        $superadminrole->givePermissionTo('delete reseller package');
        $superadminrole->givePermissionTo('create reseller');
        $superadminrole->givePermissionTo('manage reseller');
        $superadminrole->givePermissionTo('delete reseller');
        $superadminrole->givePermissionTo('edit withdraw');
        $superadminrole->givePermissionTo('confirm withdraw');
        $superadminrole->givePermissionTo('verify document');


        // Role For access to panel and (Un Verified)
        $userrole = Role::create(['guard_name' => 'api', 'name' => 'registered-user']);
        $userrole->givePermissionTo('access to panel');


        // Role for access to panel and User Uploaded our document
        $role5 = Role::create(['guard_name' => 'api', 'name' => 'uploaded-document']);
        $role5->givePermissionTo('access to panel');


        // Role for access to panel and User Uploaded our document but Rejected By Admin
        $role6 = Role::create(['guard_name' => 'api', 'name' => 'document-rejected']);
        $role6->givePermissionTo('access to panel');


        // Role for access to panel and (Verified)
        $role7 = Role::create(['guard_name' => 'api', 'name' => 'verified']);
        $role7->givePermissionTo('access to panel');



        // Role for Suspended User
        $role8 = Role::create(['guard_name' => 'api', 'name' => 'suspended']);


        //create super admin
        $user = \App\Models\User::factory()->create([

            // "user_name" => "super admin",
            "first_name" => "super",
            "last_name" => "admin",
            "email" => "super@admin.com",
            "email_verified_at" => Carbon::now(),
            "mobile_verified_at" => Carbon::now(),
            "password" => bcrypt("supersuper"),
            "active" => true,
            "activation_token" => "",
            "mobile_token" => "",
            "mobile_number" => "0912",
            "created_at" => Carbon::now()
        ]);
        $user->assignRole($superadminrole);



        // // create admin
        // $user = \App\Models\User::factory()->create([

        //     "first_name" => "admin",
        //     "last_name" => "Admin",
        //     "email" => "admin@example.com",
        //     "email_verified_at" => Carbon::now(),
        //     "mobile_verified_at" => Carbon::now(),
        //     "password" => bcrypt("adminadmin"),
        //     "active" => true,
        //     "activation_token" => "",
        //     "mobile_token" => "",
        //     "mobile_number" => "0900000",
        //     "created_at" => Carbon::now()
        // ]);
        // $user->assignRole($role2);

        // create demo users
        $user = \App\Models\User::factory()->create([
            // "user_name" => "userexample",
            "first_name" => "user",
            "last_name" => "example",
            "email" => "user@example.com",
            "email_verified_at" => Carbon::now(),
            "mobile_verified_at" => Carbon::now(),
            "password" => bcrypt("useruser"),
            "active" => true,
            "activation_token" => "",
            "mobile_token" => "",
            "mobile_number" => "090509",
            "created_at" => Carbon::now()
        ]);
        $user->assignRole($userrole);
    }
}