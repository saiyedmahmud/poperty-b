<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class customerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customerRoleId = Role::where('name', 'customer')->value('id');

        $pass = "customer1";
        $password = Hash::make($pass);

        $customer = new Customer();
        $customer->roleId = $customerRoleId;
        $customer->username = 'Customer1';
        $customer->email = 'customer1@gmail.com';
        $customer->password = $password;
        $customer->phone = '1234567890';
        $customer->address = 'Dhaka, Bangladesh';
        $customer->save();
    }
}
