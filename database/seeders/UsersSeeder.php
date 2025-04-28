<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Users;
use App\Models\Education;
use App\Models\SalaryHistory;
use Illuminate\Database\Seeder;
use App\Models\DesignationHistory;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $user = new Users();
        $user->firstName = 'John';
        $user->lastName = 'Doe';
        $user->username = 'demo';
        $user->email = 'admin@gmail.com';
        $user->phone = '01700000000';
        $user->street = 'Dhanmondi';
        $user->city = 'Dhaka';
        $user->state = 'Dhaka';
        $user->zipCode = '1205';
        $user->country = 'Bangladesh';
        $user->bloodGroup = 'A+';
        $user->password = Hash::make('5555');
        $user->roleId = 1;
        $user->employeeId = 1001;
        $user->save();


        $user = new Users();
        $user->firstName = 'Mr.';
        $user->lastName = 'Admin';
        $user->username = 'admin';
        $user->password = Hash::make('admin');
        $user->roleId = 2;
        $user->employeeId = 1002;
        $user->save();

        $user = new Users();
        $user->firstName = 'Mr.';
        $user->lastName = 'Customer';
        $user->username = 'customer';
        $user->password = Hash::make('customer');
        $user->roleId = 3;
        $user->employeeId = 1003;
        $user->save();

        $user = new Users();
        $user->firstName = 'Mrs.';
        $user->lastName = 'Manager';
        $user->username = 'manager';
        $user->password = Hash::make('manager');
        $user->roleId = 4;
        $user->employeeId = 1004;
        $user->save();

        $user = new Users();
        $user->firstName = 'Mr.';
        $user->lastName = 'Salesman';
        $user->username = 'salesman';
        $user->password = Hash::make('salesman');
        $user->roleId = 5;
        $user->employeeId = 1005;
        $user->save();

        $user = new Users();
        $user->firstName = 'Mrs.';
        $user->lastName = 'Delivery';
        $user->username = 'delivery';
        $user->password = Hash::make('delivery');
        $user->roleId = 6;
        $user->employeeId = 1006;
        $user->save();
    }
}
