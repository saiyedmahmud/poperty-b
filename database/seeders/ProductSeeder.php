<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductProductAttributeValue;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $product1 = new Product();
        $product1->name = 'product-red';
        $product1->description = 'This is a red product';
        $product1->rate = 100.00;
        $product1->unit = 10;
        $product1->productCategoryId = 1;
        $product1->save();

        $product2 = new Product();
        $product2->name = 'product-blue';
        $product2->description = 'This is a blue product';
        $product2->rate = 200.00;
        $product2->unit = 20;
        $product2->productCategoryId = 2;
        $product2->save();

        $product3 = new Product();
        $product3->name = 'product-green';
        $product3->description = 'This is a green product';
        $product3->rate = 300.00;
        $product3->unit = 3;
        $product3->productCategoryId = 3;
        $product3->save();

        $product4 = new Product();
        $product4->name = 'product-yellow';
        $product4->description = 'This is a yellow product';
        $product4->rate = 400.00;
        $product4->unit = 4;
        $product4->productCategoryId = 4;
        $product4->save();
    }
}
