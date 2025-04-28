<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DiscountTest extends TestCase
{
    public function test_create_single_discount(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/discount', [
            "value" => "50",
            "type" => "percentage",
            "startDate" => "2018-09-01",
            "endDate" => "2018-10-01"
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'value',
                'type',
                'startDate',
                'endDate',
                'updatedAt',
                'createdAt',
                'id',
            ]);
    }

    public function test_create_many_discount(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/discount?query=createmany', [
            [
                "value" => "20",
                "type" => "percentage",
                "startDate" => "2024-01-01",
                "endDate" => "2024-10-10"
            ],
            [
                "value" => "10",
                "type" => "percentage",
                "startDate" => "2023-01-01",
                "endDate" => "2023-10-10"
            ]
        ]);

        $response->assertStatus(201);
    }

    public function test_get_all_discount(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/discount?query=all');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'value',
                    'type',
                    'status',
                    'startDate',
                    'endDate',
                    'createdAt',
                    'updatedAt',
                ],
            ]);
    }

    public function test_get_all_discount_paginated(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/discount?page=1&count=5&status=true');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllDiscount' => [
                    '*' => [
                        'id',
                        'value',
                        'type',
                        'status',
                        'startDate',
                        'endDate',
                        'createdAt',
                        'updatedAt',
                    ],
                ],
                'totalDiscount',
            ]);
    }

    public function test_get_single_discount(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/discount/1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'value',
                'type',
                'status',
                'startDate',
                'endDate',
                'createdAt',
                'updatedAt',
            ]);
    }

    public function test_update_discount(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->put('/discount/1', [
            "value" => "80"
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_single_discount(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->patch('/discount/1', [
            "status" => "false"
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_many_discount(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->delete('/discount?query=deletemany', [
            1
        ]);

        $response->assertStatus(200);
    }
}
