<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdminRoleId = Role::where('name', 'super-admin')->value('id');
        $adminRoleId = Role::where('name', 'admin')->value('id');
        $customerRoleId = Role::where('name', 'customer')->value('id');

        $allPermissions = Permission::pluck('id')->toArray();

        // customer specific permissions
        $customerIds = [
            "customer2",
            "customer3",
        ];

        //create rolePermission for super admin role
        foreach ($allPermissions as $permissionId) {
            $rolePermission = new RolePermission();
            $rolePermission->role()->associate($superAdminRoleId);
            $rolePermission->permission()->associate($permissionId);
            $rolePermission->save();
        }

        //create rolePermission for admin role
        foreach ($allPermissions as $permissionId) {
            $rolePermission = new RolePermission();
            $rolePermission->role()->associate($adminRoleId);
            $rolePermission->permission()->associate($permissionId);
            $rolePermission->save();
        }

        //create rolePermission for employee role
        foreach ($customerIds as $permissionId) {
            $rolePermission = new RolePermission();
            $rolePermission->role()->associate($customerRoleId);
            $rolePermission->permission()->associate($permissionId);
            $rolePermission->save();
        }
    }
}
