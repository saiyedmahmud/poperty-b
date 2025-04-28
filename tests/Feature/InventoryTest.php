<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InventoryTest extends TestCase
{
    public function test_create_single_inventory(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/inventory', [
            "inventoryHouseId" => "ILTX98BOLB6X7M7",
            "productId" => 1,
            "productQuantity" => 10,
            "productSalePrice" => 500,
            "productPurchasePrice" => 600,
            "reorderQuantity" => 0,
            "productPurchaseTaxId" => 1,
            "productSalesTaxId" => 1,
            "discountId" => 1,
            "expiryDate" => "2025-12-1",
            "shippingChargeComment" => "eita ekta comment!"
        ]);

        $response->assertStatus(201);
    }

    public function test_create_many_inventory(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/inventory?query=createmany', [
            [
                "name" => "test1",
                "address" => "test1",
                "type" => "BRANCH1",
                "note" => "eita ekta note1"
            ],
            [
                "name" => "test2",
                "address" => "test2",
                "type" => "BRANCH2",
                "note" => "eita ekta note2"
            ],
        ]);

        $response->assertStatus(201);
    }

    public function test_get_all_inventory(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/inventory?query=all');

        $response->assertStatus(200);
    }

    public function test_get_all_inventory_paginated_true(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/inventory?page=1&count=4&status=true');

        $response->assertStatus(200);
    }

    public function test_get_all_inventory_paginated_false(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/inventory?page=1&count=20&status=false');

        $response->assertStatus(200);
    }

    public function test_get_filtered_inventory_by_house_id(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/inventory?inventoryHouseId=H38FJQKT5Q1KEIZ&page=1&count=20');

        $response->assertStatus(200);
    }

    public function test_get_filtered_inventory_by_product_id(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/inventory?prodcutId=1&page=1&count=20');

        $response->assertStatus(200);
    }

    public function test_get_filtered_inventory_by_address(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/inventory?address=test&page=1&count=20');

        $response->assertStatus(200);
    }

    public function test_get_single_inventory(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/inventory/H38FJQKT5Q1KEIZ');

        $response->assertStatus(200);
    }

    public function test_update_inventory(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->put('/inventory/H38FJQKT5Q1KEIZ', [
            "productId" => 1,
            "productQuantity" => 100,
            "productSalePrice" => 5000,
            "productPurchasePrice" => 700,
            "reorderQuantity" => 4,
            "productPurchaseTaxId" => 1,
            "productSalesTaxId" => 1,
            "discountId" => 1,
            "expiryDate" => "2025-12-1",
            "shippingChargeComment" => "eita ekta commenttt!"
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_single_inventory(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->patch('/inventory/H38FJQKT5Q1KEIZ', [
            "status" => "false"
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_many_inventory(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->post('/inventory?query=deletemany', [
            "H38FJQKT5Q1KEIZ"
        ]);

        $response->assertStatus(200);
    }
}
