<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ContactSource;

class ContactSourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contactSource = [
            [
                'contactSourceName' => 'Website',
            ],
            [
                'contactSourceName' => 'social media',
            ],
            [
                'contactSourceName' => 'Email Campaign',
            ],
            [
                'contactSourceName' => 'Events',
            ],
            [
                'contactSourceName' => 'Webinars',
            ],
            [
                'contactSourceName' => 'Referrals',
            ],
            [
                'contactSourceName' => 'Cold Calling',
            ],
            [
                'contactSourceName' => 'Paid Advertising',
            ],
            [
                'contactSourceName' => 'Organic Search',
            ],
            [
                'contactSourceName' => 'Content Marketing',
            ],
            [
                'contactSourceName' => 'Partner Programs',
            ],
            [
                'contactSourceName' => 'Direct Mail',
            ],
            [
                'contactSourceName' => 'Third-Party Lists',
            ],
            [
                'contactSourceName' => 'Offline Networking',
            ],
        ];

        foreach ($contactSource as $key => $value) {
            ContactSource::create($value);
        }

    }
}
