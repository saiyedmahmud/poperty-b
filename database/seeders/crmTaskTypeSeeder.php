<?php

namespace Database\Seeders;

use App\Models\CrmTaskType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class crmTaskTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
//        "Call", "Email", "Event", "Todo"
        $crmTaskType = [
            [
                'taskTypeName' => 'Call',
            ],
            [
                'taskTypeName' => 'Email',
            ],
            [
                'taskTypeName' => 'Event',
            ],
            [
                'taskTypeName' => 'To Do',
            ],
        ];

        foreach ($crmTaskType as $key => $value) {
            CrmTaskType::create($value);
        }

    }
}
