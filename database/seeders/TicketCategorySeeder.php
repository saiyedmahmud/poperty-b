<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use \App\Models\TicketCategory;


class TicketCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $ticketCategory = [
            "Billing",
            "Assistance",
            "Technical",
            "Sales",
            "Others",
          ];

          foreach($ticketCategory as $item){
            $ticketCategory = new TicketCategory();
            $ticketCategory->ticketCategoryName = $item;
            $ticketCategory->save();
          }
    }
}
