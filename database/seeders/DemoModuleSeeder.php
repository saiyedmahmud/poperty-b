<?php

namespace Database\Seeders;

use App\Models\DemoModule;
use Illuminate\Database\Seeder;

class DemoModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 20; $i++) {
            $demoModule = new DemoModule();
            $demoModule->name = 'Demo Module ' . $i;
            $demoModule->description = 'This is the demo module number ' . $i . ' with sample description';
            $demoModule->status = 'true';
            $demoModule->save();
        }
    }
}
