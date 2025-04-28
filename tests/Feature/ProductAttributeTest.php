<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductAttributeTest extends TestCase
{
    public function test_create_single_product_attribute(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/product-attribute', [
            "name" => "sizer"
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'name',
                'updatedAt',
                'createdAt',
                'id',
            ]);
    }

    public function test_create_many_product_attribute(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/product-attribute?query=createmany', [
            [
                "name" => "fabric"
            ],
            [
                "name" => "sleeve"
            ],
            [
                "name" => "wheel"
            ],
        ]);

        $response->assertStatus(201);
    }

    public function test_get_all_product_attribute(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product-attribute?query=all');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'status',
                    'createdAt',
                    'updatedAt',
                    'productAttributeValue',
                ],
            ]);
    }

    public function test_get_paginated_product_attribute(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product-attribute?count=10&status=true&page=1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllProductAttribute' => [
                    '*' => [
                        'id',
                        'name',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'productAttributeValue',
                    ],
                ],
                'totalProductAttribute',
            ]);
    }

    public function test_get_single_product_attribute(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product-attribute/1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'status',
                'createdAt',
                'updatedAt',
                'productAttributeValue',
            ]);
    }

    public function test_update_product_attribute(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->put('/product-attribute/1', [
            "name" => "sizeew"
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_single_product_attribute(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->patch('/product-attribute/1', [
            "status" => "false"
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_many_product_attribute(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->patch('/product-attribute?query=deletemany', [
            1, 2
        ]);

        $response->assertStatus(200);
    }
}
