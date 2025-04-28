<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use \App\Models\CompanyType;

class CompanyTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companyType = new CompanyType();
        $companyType->companyTypeName = 'Private';
        $companyType->save();

        $companyType = new CompanyType();
        $companyType->companyTypeName = 'Public';
        $companyType->save();

        $companyType = new CompanyType();
        $companyType->companyTypeName = 'Government';
        $companyType->save();

        $companyType = new CompanyType();
        $companyType->companyTypeName = 'NGO';
        $companyType->save();

        $companyType = new CompanyType();
        $companyType->companyTypeName = 'Others';
        $companyType->save();

    }
}
