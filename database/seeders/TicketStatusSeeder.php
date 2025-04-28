<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use \App\Models\TicketStatus;

class TicketStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $ticketStatus = [
        //     "pending",
        //     "in-progress",
        //     "need information",
        //     "resolved"
        //   ];

        //   foreach($ticketStatus as $item){
        //     $ticketStatus = new TicketStatus();
        //     $ticketStatus->ticketStatusName = $item;
        //     $ticketStatus->save();
        //   }

        $ticketStatus = new TicketStatus();
        $ticketStatus->ticketStatusName = 'pending';
        $ticketStatus->save();
    }
}
