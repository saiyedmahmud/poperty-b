<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use \App\Models\Opportunity;

class OpportunitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $opportunity = new Opportunity();
        $opportunity->opportunityName = "Test Opportunity";
        $opportunity->opportunityTypeId = 1;
        $opportunity->opportunityStageId = 1;
        $opportunity->opportunitySourceId = 1;
        $opportunity->opportunityOwnerId = 1;
        $opportunity->contactId = 1;
        $opportunity->companyId = 1;
        $opportunity->amount = 1000;
        $opportunity->opportunityCreateDate = date('2023-01-01');
        $opportunity->opportunityCloseDate = date('2023-09-01');
        $opportunity->nextStep = "Next Step";
        $opportunity->competitors = "Competitors";
        $opportunity->description = "Description";
        $opportunity->save();
    }
}
