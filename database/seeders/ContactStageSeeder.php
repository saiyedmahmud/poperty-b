<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ContactStage;

class ContactStageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $contactStage = [
            [
                'contactStageName' => 'Prospect',
            ],
            [
                'contactStageName' => 'Lead',
            ],
            [
                'contactStageName' => 'Marketing Qualified Lead (MQL)',
            ],
            [
                'contactStageName' => 'Sales Qualified Lead (SQL)',
            ],
            [
                'contactStageName' => 'Opportunity',
            ],
            [
                'contactStageName' => 'Customer',
            ],
            [
                'contactStageName' => 'Repeat Customer',
            ],
            [
                'contactStageName' => 'Evangelist',
            ],
        ];

        foreach ($contactStage as $key => $value) {
            ContactStage::create($value);
        }
    }
}
