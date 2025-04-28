<?php

namespace Database\Seeders;

use App\Models\Priority;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PrioritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $taskPriority = new Priority();
        $taskPriority->name = 'High';
        $taskPriority->colourValue = '#FF0000';
        $taskPriority->save();


        $taskPriority = new Priority();
        $taskPriority->name = 'Highest';
        $taskPriority->colourValue = '#FF0000';
        $taskPriority->save();


        $taskPriority = new Priority();
        $taskPriority->name = 'Low';
        $taskPriority->colourValue = '#FF0000';
        $taskPriority->save();


        $taskPriority = new Priority();
        $taskPriority->name = 'Lowest';
        $taskPriority->colourValue = '#FF0000';
        $taskPriority->save();

        $taskPriority = new Priority();
        $taskPriority->name = 'Normal';
        $taskPriority->colourValue = '#FF0000';
        $taskPriority->save();


       
    }
}
