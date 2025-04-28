<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductAttributeValueTest extends TestCase
{
    public function test_create_single_product_attribute_value(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/product-attribute-value', [
            "productAttributeId" => 1,
            "name" => "S"
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'productAttributeId',
                'name',
                'updatedAt',
                'createdAt',
                'id',
            ]);
    }

    public function test_create_many_product_attribute_value(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/product-attribute-value?query=createmany', [
            [
                "productAttributeId" => 1,
                "name" => "M"
            ],
            [
                "productAttributeId" => 1,
                "name" => "L"
            ],
            [
                "productAttributeId" => 1,
                "name" => "XL"
            ],
        ]);

        $response->assertStatus(201);
    }

    public function test_get_all_product_attribute_value(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product-attribute-value?query=all');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'productAttributeId',
                    'name',
                    'status',
                    'createdAt',
                    'updatedAt',
                ],
            ]);
    }

    public function test_get_paginated_product_attribute_value(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product-attribute-value?count=10&status=true&page=1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllProductAttributeValue' => [
                    '*' => [
                        'id',
                        'productAttributeId',
                        'name',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'productAttribute',
                    ],
                ],
                'totalProductAttributeValue',
            ]);
    }

    public function test_get_single_product_attribute_value(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product-attribute-value/1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'productAttributeId',
                    'name',
                    'status',
                    'createdAt',
                    'updatedAt',
                    'productAttribute' => [
                        'id',
                        'name',
                        'status',
                        'createdAt',
                        'updatedAt',
                    ],
                ],
            ]);
    }

    public function test_update_product_attribute_value(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->put('/product-attribute-value/2', [
            "name" => "XXL"
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_single_product_attribute_value(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->patch('/product-attribute-value/2', [
            "status" => "false"
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_many_product_attribute_value(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->patch('/product-attribute-value?query=deletemany', [
            2, 3
        ]);

        $response->assertStatus(200);
    }
}
