<?php

namespace Database\Seeders;


use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        define('endpoints', [

            //  lead  //
            ['name' => 'customer', 'type' => 'lead'],
            ['name' => 'lead', 'type' => 'lead'],
            ['name' => 'leadSource', 'type' => 'lead'],
            //  lead  //




            //   opportunity  //
            ['name' => 'opportunitySource', 'type' => 'opportunity'],
            ['name' => 'opportunityType', 'type' => 'opportunity'],
            ['name' => 'opportunityStage', 'type' => 'opportunity'],
            ['name' => 'opportunity', 'type' => 'opportunity'],
            //   opportunity  //

    

            //  user  //
            ['name' => 'user', 'type' => 'user'],
            ['name' => 'rolePermission', 'type' => 'user'],
            ['name' => 'permission', 'type' => 'user'],
            ['name' => 'role', 'type' => 'user'],
            ['name' => 'designation', 'type' => 'user'],
            ['name' => 'shift', 'type' => 'user'],
            ['name' => 'award', 'type' => 'user'],
            ['name' => 'awardHistory', 'type' => 'user'],
            ['name' => 'department', 'type' => 'user'],
            ['name' => 'designationHistory', 'type' => 'user'],
            ['name' => 'education', 'type' => 'user'],
            ['name' => 'salaryHistory', 'type' => 'user'],
            ['name' => 'employmentStatus', 'type' => 'user'],
            ['name' => 'announcement', 'type' => 'user'],
            //  user  //


            //  media  //
            ['name' => 'media', 'type' => 'media'],
            // media  //


            // account  //
            ['name' => 'transaction', 'type' => 'account'],
            ['name' => 'account', 'type' => 'account'],
            //  account  //

            // other //
            ['name' => 'note', 'type' => 'other'],
            ['name' => 'attachment', 'type' => 'other'],
            ['name' => 'email', 'type' => 'other'],
            ['name' => 'emailConfig', 'type' => 'other'],
            // other //

            ['name' => 'dashboard', 'type' => 'dashboard'],

            //  settings  //
            ['name' => 'setting', 'type' => 'settings'],
            ['name' => 'quickLink', 'type' => 'settings'],
            //  settings  //

            // demoModule //
            ['name' => 'demoModule', 'type' => 'demoModule'],
            // demoModule //

        ]);


        define('PERMISSIONSTYPES', [
            'create',
            'readAll',
            "readSingle",
            'update',
            'delete',
        ]);

        foreach (endpoints as $endpoint) {
            $countedId = 0;

            foreach (PERMISSIONSTYPES as $permissionType) {
                $countedId += 1;

                $permission = new Permission();
                $permission->id = $endpoint['name'] . $countedId;
                $permission->name = $permissionType . "-" . $endpoint['name'];
                $permission->type = $endpoint['type'];
                $permission->save();
            }
        }
    }
}
