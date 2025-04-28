<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //get all permissions
        $permissions = Permission::all();

        //create rolePermission for role id 1
        for ($i = 1; $i <= count($permissions); $i++) {
            $roleId = 1;
            $permissionId = $i;

            // Assuming you have a model for rolePermission with proper relationships
            $rolePermission = new RolePermission();
            $rolePermission->role()->associate($roleId);
            $rolePermission->permission()->associate($permissionId);
            $rolePermission->save();
        }

        //TODO:: need to remove after testing
        //create rolePermission for role id 1
        for ($i = 1; $i <= count($permissions); $i++) {
            $roleId = 2;
            $permissionId = $i;

            // Assuming you have a model for rolePermission with proper relationships
            $rolePermission = new RolePermission();
            $rolePermission->role()->associate($roleId);
            $rolePermission->permission()->associate($permissionId);
            $rolePermission->save();
        }
        //create rolePermission for role id 3
        // $customer =
        // [
        //     1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60
        // ];

        // for ($i = 0; $i < count($customer); $i++) {
        //     $roleId = 3;
        //     $permissionId = $customer[$i];

        //     // Assuming you have a model for rolePermission with proper relationships
        //     $rolePermission = new RolePermission();
        //     $rolePermission->role()->associate($roleId);
        //     $rolePermission->permission()->associate($permissionId);
        //     $rolePermission->save();
        // }

        //get all role id
        $roles = Role::all();
        $permissionId = Permission::where('name', 'readSingle-rolePermission')->first()->id;

        //role id 1 is skip
        for ($i = 2; $i <= count($roles); $i++) {
            $rolePermission = new RolePermission();
            $rolePermission->roleId = $i;
            $rolePermission->permissionId = $permissionId;
            $rolePermission->save();
        }
    }
}
