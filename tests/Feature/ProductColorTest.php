<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductColorTest extends TestCase
{
    public function test_create_single_product_color(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/product-color', [
            "name" => "test",
            "colorCode" => "test"
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'name',
                'colorCode',
                'updatedAt',
                'createdAt',
                'id',
            ]);
    }

    public function test_create_many_product_color(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/product-color?query=createmany', [
            [
                "name" => "test1",
                "colorCode" => "test1"
            ],
            [
                "name" => "test2",
                "colorCode" => "test2"
            ],
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'count',
            ]);
    }

    public function test_get_all_product_color(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product-color?query=all');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'colorCode',
                    'status',
                    'createdAt',
                    'updatedAt',
                ]
            ]);
    }

    public function test_get_all_product_color_paginated_true(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product-color?page=1&count=4&status=true');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllProductColor' => [
                    '*' => [
                        'id',
                        'name',
                        'colorCode',
                        'status',
                        'createdAt',
                        'updatedAt',
                    ]
                ],
                'totalProductColor'
            ]);
    }

    public function test_get_all_product_color_paginated_false(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product-color?page=1&count=20&status=false');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllProductColor' => [
                    '*' => [
                        'id',
                        'name',
                        'colorCode',
                        'status',
                        'createdAt',
                        'updatedAt',
                    ]
                ],
                'totalProductColor'
            ]);
    }

    public function test_get_all_product_color_public(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product-color/public');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'colorCode',
                    'status',
                    'createdAt',
                    'updatedAt',
                ]
            ]);
    }

    public function test_get_all_product_color_info(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product-color?query=info');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '_count' => [
                    'id'
                ]
            ]);
    }

    public function test_get_single_product_color(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product-color/1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'colorCode',
                'status',
                'createdAt',
                'updatedAt',
                'productColor' => [
                    '*' => [
                        'id',
                        'productId',
                        'colorId',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'product' => []
                    ]
                ]
            ]);
    }

    public function test_update_product_color(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->put('/product-color/1', [
            "name" => "light",
            "colorCode" => "light"
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_single_product_color(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->patch('/product-color/1', [
            "status" => "false"
        ]);

        $response->assertStatus(200);
    }
}
