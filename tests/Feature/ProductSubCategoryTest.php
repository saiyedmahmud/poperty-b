<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductSubCategoryTest extends TestCase
{
    public function test_create_single_product_sub_category(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/product-sub-category', [
            "name" => "sub category 44",
            "productCategoryId" => 1
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'name',
                'productCategoryId',
                'updatedAt',
                'createdAt',
                'id'
            ]);
    }

    public function test_create_many_product_sub_category(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/product-sub-category?query=createmany', [
            [
                "name" => "sub category 11",
                "productCategoryId" => 1
            ],
            [
                "name" => "sub category 22",
                "productCategoryId" => 2
            ],
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'count'
            ]);
    }

    public function test_get_all_product_sub_category(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product-sub-category?query=all');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'productCategoryId',
                    'status',
                    'createdAt',
                    'updatedAt',
                    'product' => []
                ]
            ]);
    }

    public function test_get_all_product_sub_category_paginated_true(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product-sub-category?page=1&count=4&status=true');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllProductSubCategory' => [
                    '*' => [
                        'id',
                        'name',
                        'productCategoryId',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'product' => []
                    ]
                ],
                'totalProductSubCategory'
            ]);
    }

    public function test_get_all_product_sub_category_paginated_false(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product-sub-category?page=1&count=20&status=false');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllProductSubCategory' => [
                    '*' => [
                        'id',
                        'name',
                        'productCategoryId',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'product' => []
                    ]
                ],
                'totalProductSubCategory'
            ]);
    }

    public function test_get_all_product_sub_category_public(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product-sub-category/public');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'productCategoryId',
                    'status',
                    'createdAt',
                    'updatedAt'
                ]
            ]);
    }

    public function test_get_all_product_sub_category_info(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product-sub-category?query=info');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '_count' => [
                    'id'
                ]
            ]);
    }

    public function test_get_single_product_sub_category(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product-sub-category/1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'productCategoryId',
                'status',
                'createdAt',
                'updatedAt',
                'product' => []
            ]);
    }

    public function test_update_product_sub_category(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->put('/product-sub-category/1', [
            "name" => "sub category updated"
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_single_product_sub_category(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->patch('/product-sub-category/5', [
            "status" => "false"
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_many_product_sub_category(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->post('/product-sub-category?query=deletemany', [
            14, 15, 16
        ]);

        $response->assertStatus(200);
    }
}
