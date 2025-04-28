<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Quote;

class QuoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $quote = [
            [
                'quoteOwnerId' => 1,
                'quoteName' => 'hello',
                'quoteDate' => '2023-04-24 14:21:00',
                'opportunityId' => 1,
                'companyId' => 1,
                'contactId' => 1,
                'expirationDate' => '2023-06-24 14:21:00',
                'quoteStageId' => 2,
                'termsAndConditions' => 'test',
                'description' => 'test',
                'discount' => 20,
            ],
            [
                'quoteOwnerId' => 1,
                'quoteName' => 'hello',
                'quoteDate' => '2023-04-24 14:21:00',
                'opportunityId' => 1,
                'companyId' => 1,
                'contactId' => 1,
                'expirationDate' => '2023-06-24 14:21:00',
                'quoteStageId' => 2,
                'termsAndConditions' => 'test',
                'description' => 'test',
                'discount' => 20,
            ]
        ];

        foreach ($quote as $key => $value) {
            Quote::create($value);
        }
    }
}
