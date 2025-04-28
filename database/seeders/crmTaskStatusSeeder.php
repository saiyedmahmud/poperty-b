<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CrmTaskStatus;

class crmTaskStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //"todo", "in-progress", "done"
        $crmTaskStatus = [
            [
                'taskStatusName' => 'TO DO',
            ],
            [
                'taskStatusName' => 'IN-PROGRESS',
            ],
            [
                'taskStatusName' => 'DONE',
            ],
        ];

        foreach ($crmTaskStatus as $key => $value) {
            CrmTaskStatus::create($value);
        }
    }
}
