<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InventoryHousesTest extends TestCase
{
    public function test_create_single_inventory_house(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/inventory-house', [
            "name" => "test",
            "address" => "test",
            "type" => "BRANCH",
            "note" => "eita ekta note"
        ]);

        $response->assertStatus(201);
    }

    public function test_create_many_inventory_house(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/inventory-house?query=createmany', [
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

    public function test_get_all_inventory_house(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/inventory-house?query=all');

        $response->assertStatus(200);
    }

    public function test_get_all_inventory_house_paginated_true(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/inventory-house?page=1&count=4&status=true');

        $response->assertStatus(200);
    }

    public function test_get_all_inventory_house_paginated_false(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/inventory-house?page=1&count=20&status=false');

        $response->assertStatus(200);
    }

    public function test_get_search_inventory_house(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/inventory-house?query=search&key=test1&page=1&count=5');

        $response->assertStatus(200);
    }

    public function test_get_filtered_inventory_house_by_branch(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/inventory-house?type=BRANCH&status=false,true&page=1&count=20');

        $response->assertStatus(200);
    }

    public function test_get_filtered_inventory_house_by_address(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/inventory-house?address=test&status=false,true&page=1&count=20');

        $response->assertStatus(200);
    }

    public function test_get_single_inventory_house(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/inventory-house/H38FJQKT5Q1KEIZ');

        $response->assertStatus(200);
    }

    public function test_update_inventory_house(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->put('/inventory-house/H38FJQKT5Q1KEIZ', [
            "note" => "dfsdfsfsdfdsfrwef"
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_single_inventory_house(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->patch('/inventory-house/H38FJQKT5Q1KEIZ', [
            "status" => "false"
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_many_inventory_house(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->post('/inventory-house?query=deletemany', [
            "SLSWIQCJ0MQPJ43",
            "QO40KIV1MID4ZDR"
        ]);

        $response->assertStatus(200);
    }
}
