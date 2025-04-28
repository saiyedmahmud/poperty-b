<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CourierMediumTest extends TestCase
{
    public function test_create_single_courier_medium(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/courier-medium', [
            "courierMediumName" => "abd",
            "address" => "dhaka",
            "phone" => "12345",
            "email" => "abd@gmail.com",
            "type" => "courier" // courier, deliveryBoy
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'courierMediumName',
                'address',
                'phone',
                'email',
                'type',
                'subAccountId',
                'updatedAt',
                'createdAt',
                'id',
            ]);
    }

    public function test_create_many_courier_medium(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/courier-medium?query=createmany', [
            [
                "courierMediumName" => "abd",
                "address" => "dhaka",
                "phone" => "12345",
                "email" => "abd@gmail.com",
                "type" => "courier" // courier, deliveryBoy
            ],
            [
                "courierMediumName" => "Mr. xyz",
                "address" => "dhaka",
                "phone" => "123456",
                "email" => "xyz@gmail.com",
                "type" => "deliveryBoy" // courier, deliveryBoy
            ]
        ]);

        $response->assertStatus(201);
    }

    public function test_get_all_courier_medium(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/courier-medium?query=all');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'courierMediumName',
                    'address',
                    'phone',
                    'email',
                    'type',
                    'subAccountId',
                    'status',
                    'createdAt',
                    'updatedAt',
                ],
            ]);
    }

    public function test_get_search_courier_medium(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/courier-medium?query=search&key=abd&page=1&count=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllCourierMedium' => [
                    '*' => [
                        'id',
                        'courierMediumName',
                        'address',
                        'phone',
                        'email',
                        'type',
                        'subAccountId',
                        'status',
                        'createdAt',
                        'updatedAt',
                    ],
                ],
                'totalCourierMedium',
            ]);
    }

    public function test_get_paginated_courier_medium(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/courier-medium?page=1&count=10&status=true');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllCourierMedium' => [
                    '*' => [
                        'id',
                        'courierMediumName',
                        'address',
                        'phone',
                        'email',
                        'type',
                        'subAccountId',
                        'status',
                        'createdAt',
                        'updatedAt',
                    ],
                ],
                'totalCourierMedium',
            ]);
    }

    public function test_get_single_courier_medium(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/courier-medium/1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'courierMediumName',
                'address',
                'phone',
                'email',
                'type',
                'subAccountId',
                'status',
                'createdAt',
                'updatedAt',
                'subAccount',
            ]);
    }

    public function test_get_single_courier_medium_by_courier_medium(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/courier-medium/1?page=1&count=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'courierMediumName',
                'address',
                'phone',
                'email',
                'type',
                'subAccountId',
                'status',
                'createdAt',
                'updatedAt',
                'totalCartOrder',
                'cartOrder',
            ]);
    }

    public function test_update_courier_medium(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->put('/courier-medium/1', [
            "email" => "notun@gmail.com"
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_single_courier_medium(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->patch('/courier-medium/1', [
            "status" => "false"
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_many_courier_medium(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->delete('/courier-medium?query=deletemany', [
            1, 2
        ]);

        $response->assertStatus(200);
    }
}
