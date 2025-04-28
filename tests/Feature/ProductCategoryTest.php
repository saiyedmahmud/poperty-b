<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductCategoryTest extends TestCase
{
    public function test_create_single_product_category(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/product-category', [
            "name" => "category 10"
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'name',
                'updatedAt',
                'createdAt',
                'id'
            ]);
    }

    public function test_create_many_product_category(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/product-category?query=createmany', [
            [
                "name" => "category 101"
            ],
            [
                "name" => "category 102"
            ],
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'count',
            ]);
    }

    public function test_get_all_product_category(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product-category?query=all');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'status',
                    'createdAt',
                    'updatedAt',
                    'productSubCategory' => []
                ]
            ]);
    }

    public function test_get_all_product_category_paginated_true(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product-category?page=1&count=4&status=true');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllProductCategory' => [
                    '*' => [
                        'id',
                        'name',
                        'status',
                        'createdAt',
                        'updatedAt'
                    ]
                ],
                'totalProductCategory'
            ]);
    }

    public function test_get_all_product_category_paginated_false(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product-category?page=1&count=20&status=false');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllProductCategory' => [
                    '*' => [
                        'id',
                        'name',
                        'status',
                        'createdAt',
                        'updatedAt'
                    ]
                ],
                'totalProductCategory'
            ]);
    }

    public function test_get_all_product_category_public(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product-category/public');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'status',
                    'createdAt',
                    'updatedAt',
                    'productSubCategory' => [
                        '*' => [
                            'id',
                            'name',
                            'productCategoryId'
                        ]
                    ]
                ]
            ]);
    }

    public function test_get_all_product_category_info(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product-category?query=info');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '_count' => [
                    'id'
                ]
            ]);
    }

    public function test_get_single_product_category(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product-category/1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'status',
                'createdAt',
                'updatedAt',
                'productSubCategory' => [
                    '*' => [
                        'id',
                        'name',
                        'productCategoryId',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'product' => []
                    ]
                ]
            ]);
    }

    public function test_update_product_category(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->put('/product-category/1', [
            "name" => "category updated"
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_single_product_category(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->patch('/product-category/4', [
            "status" => "false"
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_many_product_category(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->post('/product-category?query=deletemany', [
            14, 15, 16
        ]);

        $response->assertStatus(200);
    }
}
