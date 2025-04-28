<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use App\Models\TicketCategory;
use App\Models\TicketComment;
use App\Models\TicketStatus;
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
                awardSeeder::class,
                ShiftSeeder::class,
                EmploymentSeeder::class,
                DesignationSeeder::class,
                DepartmentSeeder::class,
                UsersSeeder::class,
                PermissionSeeder::class,
                RolePermissionSeeder::class,
                CurrencySeeder::class,
                AppSettingSeeder::class,
                AccountSeeder::class,
                SubAccountSeeder::class,
                EmailConfigSeeder::class,
                customerSeeder::class,
                ProductCategorySeeder::class,
                ProductSeeder::class,
                PageSizeSeeder::class,
                DiscountSeeder::class,
                PaymentMethodSeeder::class,
                QuickLinksSeeder::class,
                IndustrySeeder::class,
                CompanyTypeSeeder::class,
                CompanySeeder::class,
                ContactSourceSeeder::class,
                ContactStageSeeder::class,
                ContactSeeder::class,
                OpportunitySourceSeeder::class,
                OpportunityTypeSeeder::class,
                OpportunityStageSeeder::class,
                OpportunitySeeder::class,
                TicketCategorySeeder::class,
                TicketStatusSeeder::class,
                QuoteStageSeeder::class,
                crmTaskStatusSeeder::class,
                crmTaskTypeSeeder::class,
                PrioritySeeder::class,
                TicketCategorySeeder::class,
                TicketStatusSeeder::class,
            ]);
        } else {
            $this->call([
                RoleSeeder::class,
                awardSeeder::class,
                ShiftSeeder::class,
                EmploymentSeeder::class,
                DesignationSeeder::class,
                DepartmentSeeder::class,
                UsersSeeder::class,
                PermissionSeeder::class,
                RolePermissionSeeder::class,
                CurrencySeeder::class,
                AppSettingSeeder::class,
                AccountSeeder::class,
                SubAccountSeeder::class,
                EmailConfigSeeder::class,
                customerSeeder::class,
                ProductCategorySeeder::class,
                ProductSeeder::class,
                PageSizeSeeder::class,
                DiscountSeeder::class,
                PaymentMethodSeeder::class,
                QuickLinksSeeder::class,
                PrioritySeeder::class,
                TicketStatusSeeder::class,

            ]);
        }

    }
}
