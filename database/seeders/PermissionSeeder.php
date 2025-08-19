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

            //  contact  //
            ['name' => 'contact', 'type' => 'contact'],
            ['name' => 'contactSource', 'type' => 'contact'],
            ['name' => 'contactStage', 'type' => 'contact'],
            ['name' => 'company', 'type' => 'contact'],
            ['name' => 'companyType', 'type' => 'contact'],
            ['name' => 'industry', 'type' => 'contact'],
            //  contact  //

            //  product  //
            ['name' => 'product', 'type' => 'product'],
            ['name' => 'productCategory', 'type' => 'product'],
            ['name' => 'supplier', 'type' => 'product'],
            //  product  //


            //  sales //
            ['name' => 'quote', 'type' => 'sales'],
            ['name' => 'quoteStage', 'type' => 'sales'],
            ['name' => 'saleInvoice', 'type' => 'sales'],
            ['name' => 'paymentMethod', 'type' => 'sales'],
            ['name' => 'manualPayment', 'type' => 'sales'],
            ['name' => 'termsAndCondition', 'type' => 'sales'],
            ['name' => 'saleCommission', 'type' => 'sales'],
            ['name' => 'stockTransfer', 'type' => 'sales'],
            ['name' => 'paymentSaleInvoice', 'type' => 'sales'],
            //  sales //

            //   opportunity  //
            ['name' => 'opportunitySource', 'type' => 'opportunity'],
            ['name' => 'opportunityType', 'type' => 'opportunity'],
            ['name' => 'opportunityStage', 'type' => 'opportunity'],
            ['name' => 'opportunity', 'type' => 'opportunity'],
            //   opportunity  //

            //  task //
            ['name' => 'task', 'type' => 'task'],
            ['name' => 'taskType', 'type' => 'task'],
            //  task //

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

            //  ticket  //
            ['name' => 'ticket', 'type' => 'ticket'],
            ['name' => 'ticketComment', 'type' => 'ticket'],
            ['name' => 'ticketCategory', 'type' => 'ticket'],
            ['name' => 'ticketStatus', 'type' => 'ticket'],
            //  ticket  //


            //  media  //
            ['name' => 'media', 'type' => 'media'],
            // media  //


            // project  //
            ['name' => 'project', 'type' => 'project'],
            ['name' => 'priority', 'type' => 'project'],
            ['name' => 'team', 'type' => 'project'],
            ['name' => 'projectTask', 'type' => 'project'],
            ['name' => 'taskStatus', 'type' => 'project'],
            ['name' => 'milestone', 'type' => 'project'],
            //  project  //

            //taskStatus, milestone,

            // account  //
            ['name' => 'transaction', 'type' => 'account'],
            ['name' => 'account', 'type' => 'account'],
            //  account  //

            // other //
            ['name' => 'note', 'type' => 'other'],
            ['name' => 'attachment', 'type' => 'other'],
            ['name' => 'email', 'type' => 'other'],
            ['name' => 'emailConfig', 'type' => 'other'],
            ['name' => 'crmEmail', 'type' => 'other'],
            // other //

            ['name' => 'dashboard', 'type' => 'dashboard'],

            //  settings  //
            ['name' => 'setting', 'type' => 'settings'],
            ['name' => 'quickLink', 'type' => 'settings'],

            //  settings  //

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
