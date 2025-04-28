<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use \App\Models\Industry;

class IndustrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $industry = [
            'Agriculture',
            'Automotive',
            'Banking and Finance',
            'Biotechnology',
            'Chemicals',
            'Construction',
            'Consumer Goods',
            'E-commerce',
            'Education',
            'Energy and Utilities',
            'Engineering',
            'Entertainment',
            'Environment and Sustainability',
            'Food and Beverage',
            'Government and Public Sector',
            'Healthcare and Pharmaceuticals',
            'Hospitality and Tourism',
            'Information Technology and Services',
            'Insurance',
            'Legal',
            'Manufacturing',
            'Marketing and Advertising',
            'Media and Communications',
            'Mining and Metals',
            'Nonprofit and Social Services',
            'Oil and Gas',
            'Professional Services',
            'Real Estate',
            'Retail',
            'Sports and Recreation',
            'Telecommunications',
            'Transportation and Logistics',
            'Wholesale Trade',
            'Others',
        ];

        foreach ($industry as $key => $value) {
            Industry::create(['industryName' => $value]);
        }
    }
}
