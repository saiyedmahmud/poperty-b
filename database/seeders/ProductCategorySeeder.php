<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $productCategory1 = new ProductCategory();
        $productCategory1->name = 'product-category-1';
        $productCategory1->save();

        $productCategory2 = new ProductCategory();
        $productCategory2->name = 'product-category-2';
        $productCategory2->save();

        $productCategory3 = new ProductCategory();
        $productCategory3->name = 'product-category-3';
        $productCategory3->save();

        $productCategory4 = new ProductCategory();
        $productCategory4->name = 'product-category-4';
        $productCategory4->save();
    }
}
