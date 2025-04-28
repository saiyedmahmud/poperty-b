<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use \App\Models\OpportunityStage;

class OpportunityStageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        define('opportunityStage', [
            "Prospect",
            "Qualification",
            "Needs Assessment",
            "Proposal/Quote",
            "Negotiation/Review",
            "Closed Won",
            "Closed Lost",
        ]);

        foreach(opportunityStage as $StageName){
            $opportunityStage = new OpportunityStage();
            $opportunityStage->opportunityStageName = $StageName;
            $opportunityStage->save();
        }
    }
}
