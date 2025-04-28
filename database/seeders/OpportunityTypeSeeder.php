<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use \App\Models\OpportunityType;

class OpportunityTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        define('opportunityType', [
            "New Business",
            "Existing Customer - upgraded",
            "Existing Customer - Renewal",
        ]);

        foreach(opportunityType as $TypeName){
            $opportunityType = new OpportunityType();
            $opportunityType->opportunityTypeName = $TypeName;
            $opportunityType->save();
        }
    }
}
