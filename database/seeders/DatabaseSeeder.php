<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        if (env('APP_DEBUG') === true) {
            $this->call([
                RoleSeeder::class,
                UsersSeeder::class,
                PermissionSeeder::class,
                RolePermissionSeeder::class,
                AppSettingSeeder::class,
                AccountSeeder::class,
                SubAccountSeeder::class,
                EmailConfigSeeder::class,
                customerSeeder::class,
                DemoModuleSeeder::class,
            ]);
        } else {
            $this->call([
                RoleSeeder::class,
                UsersSeeder::class,
                PermissionSeeder::class,
                RolePermissionSeeder::class,
                AppSettingSeeder::class,
                AccountSeeder::class,
                SubAccountSeeder::class,
                EmailConfigSeeder::class,
                customerSeeder::class,
            ]);
        }
    }
}
