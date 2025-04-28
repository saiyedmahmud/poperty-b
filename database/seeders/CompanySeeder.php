<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use \App\Models\Company;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $company = new Company();
        $company->companyOwnerId = 1;
        $company->companyName = 'Company Name';
        $company->image = null;
        $company->companyTypeId = 1;
        $company->industryId = 1;
        $company->companySize = 10;
        $company->annualRevenue = 10000000;
        $company->website = 'www.company.com';
        $company->phone = '1234567890';
        $company->email = 'company@gmail.com';
        $company->linkedin = 'www.linkedin.com/company';
        $company->facebook = 'www.facebook.com/company';
        $company->twitter = 'www.twitter.com/company';
        $company->instagram = 'www.instagram.com/company';
        $company->billingStreet = 'Billing Street';
        $company->billingCity = 'Billing City';
        $company->billingState = 'Billing State';
        $company->billingZipCode = 'Billing Zip Code';
        $company->billingCountry = 'Billing Country';
        $company->shippingStreet = 'Shipping Street';
        $company->shippingCity = 'Shipping City';
        $company->shippingState = 'Shipping State';
        $company->shippingZipCode = 'Shipping Zip Code';
        $company->shippingCountry = 'Shipping Country';
        $company->save();

    }
}
