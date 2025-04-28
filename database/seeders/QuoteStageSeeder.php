<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\QuoteStage;

class QuoteStageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $quote = [
            [
                'quoteStageName' => 'Draft',
            ],
            [
                'quoteStageName' => 'Sent',
            ],
            [
                'quoteStageName' => 'Accepted',
            ],
            [
                'quoteStageName' => 'Rejected',
            ],
        ];

        foreach ($quote as $key => $value) {
            QuoteStage::create($value);
        }
    }
}
