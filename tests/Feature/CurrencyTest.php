<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CurrencyTest extends TestCase
{
    public function test_create_single_currency(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/currency', [
            "currencyName" => "taka",
            "currencySymbol" => "&#x09F3;"
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'currencyName',
                'currencySymbol',
                'updatedAt',
                'createdAt',
                'id',
            ]);
    }

    public function test_create_many_currency(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/currency?query=createmany', [
            [
                "currencyName" => "DOLLAR",
                "currencySymbol" => "&#36;"
            ],
            [
                "currencyName" => "EURO",
                "currencySymbol" => "&#8364;"
            ],
            [
                "currencyName" => "taka",
                "currencySymbol" => "&#x09F3;"
            ],
        ]);

        $response->assertStatus(201);
    }

    public function test_get_all_currency(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/currency?query=all');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'currencyName',
                    'currencySymbol',
                    'status',
                    'createdAt',
                    'updatedAt'
                ]
            ]);
    }

    public function test_get_paginated_currency(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/currency?count=10&status=true&page=1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllCurrency' => [
                    '*' => [
                        'id',
                        'currencyName',
                        'currencySymbol',
                        'status',
                        'createdAt',
                        'updatedAt'
                    ]
                ],
                'totalCurrency'
            ]);
    }

    public function test_get_single_currency(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/currency/2');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'currencyName',
                'currencySymbol',
                'status',
                'createdAt',
                'updatedAt'
            ]);
    }

    public function test_update_currency(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->put('/currency/1', [
            "currencySymbol" => "sdg"
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_single_currency(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->patch('/currency/1', [
            "status" => "false"
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_many_currency(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->patch('/currency?query=deletemany', [
            1, 2
        ]);

        $response->assertStatus(200);
    }
}
