<?php

use Carbon\Carbon;
use Database\TruncateTable;
use Illuminate\Database\Seeder;
use Database\DisableForeignKeys;

/**
 * Class PermissionTableSeeder.
 */
class PermissionTableSeeder extends Seeder
{
    use DisableForeignKeys, TruncateTable;

    /**
     * Run the database seed.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();
        $this->truncateMultiple([config('access.permissions_table'), config('access.permission_role_table')]);

        /**
         * Don't need to assign any permissions to administrator because the all flag is set to true
         * in RoleTableSeeder.php.
         */

        /**
         * Misc Access Permissions.
         */
        $permission_model = config('access.permission');
        $viewBackend = new $permission_model();
        $viewBackend->name = 'view-backend';
        $viewBackend->display_name = 'View Backend';
        $viewBackend->sort = 1;
        $viewBackend->created_at = Carbon::now();
        $viewBackend->updated_at = Carbon::now();
        $viewBackend->save();

        /**
         * Access Permissions.
         */
        $permission_model = config('access.permission');
        $manageUsers = new $permission_model();
        $manageUsers->name = 'manage-users';
        $manageUsers->display_name = 'Manage Users';
        $manageUsers->sort = 2;
        $manageUsers->created_at = Carbon::now();
        $manageUsers->updated_at = Carbon::now();
        $manageUsers->save();

        $permission_model = config('access.permission');
        $manageRoles = new $permission_model();
        $manageRoles->name = 'manage-roles';
        $manageRoles->display_name = 'Manage Roles';
        $manageRoles->sort = 3;
        $manageRoles->created_at = Carbon::now();
        $manageRoles->updated_at = Carbon::now();
        $manageRoles->save();

        /**
         * Event Permissions.
         */
        $permission_model = config('access.permission');
        $viewBackend = new $permission_model();
        $viewBackend->name = 'manage-all-events';
        $viewBackend->display_name = 'Manage All Events';
        $viewBackend->sort = 4;
        $viewBackend->created_at = Carbon::now();
        $viewBackend->updated_at = Carbon::now();
        $viewBackend->save();

        $permission_model = config('access.permission');
        $viewBackend = new $permission_model();
        $viewBackend->name = 'manage-own-events';
        $viewBackend->display_name = 'Manage Own Events';
        $viewBackend->sort = 5;
        $viewBackend->created_at = Carbon::now();
        $viewBackend->updated_at = Carbon::now();
        $viewBackend->save();

        $permission_model = config('access.permission');
        $viewBackend = new $permission_model();
        $viewBackend->name = 'view-events';
        $viewBackend->display_name = 'View Events';
        $viewBackend->sort = 6;
        $viewBackend->created_at = Carbon::now();
        $viewBackend->updated_at = Carbon::now();
        $viewBackend->save();

        $permission_model = config('access.permission');
        $viewBackend = new $permission_model();
        $viewBackend->name = 'can-be-invited';
        $viewBackend->display_name = 'Can Be Invited';
        $viewBackend->sort = 7;
        $viewBackend->created_at = Carbon::now();
        $viewBackend->updated_at = Carbon::now();
        $viewBackend->save();

        /**
         * Can update own profile.
         */
        $permission_model = config('access.permission');
        $viewBackend = new $permission_model();
        $viewBackend->name = 'update-profile';
        $viewBackend->display_name = 'Update Own Profile';
        $viewBackend->sort = 8;
        $viewBackend->created_at = Carbon::now();
        $viewBackend->updated_at = Carbon::now();
        $viewBackend->save();

        // Host permission
        $permission_model = config('access.permission');
        $viewBackend = new $permission_model();
        $viewBackend->name = 'host-event';
        $viewBackend->display_name = 'Can Host Event';
        $viewBackend->sort = 7;
        $viewBackend->created_at = Carbon::now();
        $viewBackend->updated_at = Carbon::now();
        $viewBackend->save();

        $this->enableForeignKeys();
    }
}
