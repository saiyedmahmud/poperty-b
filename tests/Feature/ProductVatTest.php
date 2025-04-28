<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductVatTest extends TestCase
{
    public function test_create_single_product_vat(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/product-vat', [
            "title" => "test",
            "percentage" => 5
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'title',
                'percentage',
                'updatedAt',
                'createdAt',
                'id'
            ]);
    }

    public function test_create_many_product_vat(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/product-vat?query=createmany', [
            [
                "title" => "test2",
                "percentage" => 6
            ],
            [
                "title" => "test3",
                "percentage" => 10
            ],
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'count'
            ]);
    }

    public function test_get_all_product_vat(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product-vat?query=all');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'title',
                    'percentage',
                    'status',
                    'createdAt',
                    'updatedAt'
                ]
            ]);
    }

    public function test_get_product_vat_statement(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product-vat/statement');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'totalVatGiven',
                'totalVatReceived',
                'totalVat'
            ]);
    }

    public function test_get_all_product_vat_paginated_true(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product-vat?status=true&page=1&count=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllProductVat' => [
                    '*' => [
                        'id',
                        'title',
                        'percentage',
                        'status',
                        'createdAt',
                        'updatedAt'
                    ]
                ],
                'totalProductVat'
            ]);
    }

    public function test_get_all_product_vat_paginated_false(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product-vat?status=false&page=1&count=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllProductVat' => [
                    '*' => [
                        'id',
                        'title',
                        'percentage',
                        'status',
                        'createdAt',
                        'updatedAt'
                    ]
                ],
                'totalProductVat'
            ]);
    }

    public function test_get_all_product_vat_info(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product-vat?query=info');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '_count' => [
                    'id'
                ]
            ]);
    }

    public function test_update_product_vat(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->put('/product-vat/1', [
            "title" => "test updated",
            "percentage" => 2
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_single_product_vat(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->patch('/product-vat/2', [
            "status" => "false"
        ]);

        $response->assertStatus(200);
    }
}
