<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductBrandTest extends TestCase
{
    public function test_create_single_product_brand(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/product-brand', [
            "name" => "Rich Man"
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'name',
                'updatedAt',
                'createdAt',
                'id'
            ]);
    }

    public function test_create_many_product_brand(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/product-brand?query=createmany', [
            [
                "name" => "Blue Dream"
            ],
            [
                "name" => "Easy"
            ],
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'count'
            ]);
    }

    public function test_get_all_product_brand(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product-brand?query=all');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'status',
                    'createdAt',
                    'updatedAt',
                    'product' => []
                ]
            ]);
    }

    public function test_get_all_product_brand_paginated_true(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product-brand?page=1&count=4&status=true');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllProductBrand' => [
                    '*' => [
                        'id',
                        'name',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'product' => []
                    ]
                ],
                'totalProductBrand'
            ]);
    }

    public function test_get_all_product_brand_paginated_false(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product-brand?page=1&count=20&status=false');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllProductBrand' => [
                    '*' => [
                        'id',
                        'name',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'product' => []
                    ]
                ],
                'totalProductBrand'
            ]);
    }

    public function test_get_all_product_brand_public(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product-brand/public');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'status',
                    'createdAt',
                    'updatedAt'
                ]
            ]);
    }

    public function test_get_all_product_brand_search(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product-brand?query=search&key=rich&page=1&count=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllProductBrand' => [
                    '*' => [
                        'id',
                        'name',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'product' => []
                    ]
                ],
                'totalProductBrand'
            ]);
    }

    public function test_get_single_product_brand(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product-brand/1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'status',
                'createdAt',
                'updatedAt',
                'product' => []
            ]);
    }

    public function test_update_product_brand(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->put('/product-brand/1', [
            "name" => "Rich Man2"
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_single_product_brand(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->patch('/product-brand/3', [
            "status" => "false"
        ]);

        $response->assertStatus(200);
    }
}
