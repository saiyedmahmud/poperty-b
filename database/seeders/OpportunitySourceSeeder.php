<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use \App\Models\OpportunitySource;

class OpportunitySourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        define('opportunitySource', [
            'Website',
            'social media',
            "Email Campaign",
            "Events",
            "Webinars",
            "Referrals",
            "Cold Calling",
            "Paid Advertising",
            "Organic Search",
            "Content Marketing",
            "Partner Programs",
            "Direct Mail",
            "Third-Party Lists",
            "Offline Networking",

        ]);

        foreach(opportunitySource as $sourceName){
            $opportunitySource = new OpportunitySource();
            $opportunitySource->opportunitySourceName = $sourceName;
            $opportunitySource->save();
        }
    }
}
