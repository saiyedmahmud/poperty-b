<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductProductAttributeValueTest extends TestCase
{
    public function test_create_single_product_product_attribute_value(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/product-product-attribute-value', [
            "productId" => 1,
            "productAttributeValueId" => 1
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'productId',
                'productAttributeValueId',
                'createdAt',
                'updatedAt',
            ]);
    }

    public function test_create_many_product_product_attribute_value(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/product-product-attribute-value?query=createmany', [
            [
                "productId" => 1,
                "productAttributeValueId" => 1
            ],
            [
                "productId" => 1,
                "productAttributeValueId" => 2
            ]
        ]);

        $response->assertStatus(201);
    }

    public function test_get_all_product_product_attribute_value(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product-product-attribute-value?query=all');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'productId',
                    'productAttributeValueId',
                    'status',
                    'createdAt',
                    'updatedAt',
                ],
            ]);
    }

    public function test_get_paginated_product_product_attribute_value(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product-product-attribute-value?status=true&page=1&count=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllProductProductAttributeValue' => [
                    '*' => [
                        'id',
                        'productId',
                        'productAttributeValueId',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'product',
                        'productAttributeValue',
                    ],
                ],
                'totalProductProductAttributeValue',
            ]);
    }

    public function test_get_single_product_product_attribute_value(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product-product-attribute-value/1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'productId',
                'productAttributeValueId',
                'status',
                'createdAt',
                'updatedAt',
                'product',
                'productAttributeValue',
            ]);
    }

    public function test_update_product_product_attribute_value(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->put('/product-product-attribute-value/2', [
            "productId" => 2,
            "productAttributeValueId" => 1
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_single_product_product_attribute_value(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->patch('/product-product-attribute-value/2', [
            "status" => "false"
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_many_product_product_attribute_value(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->patch('/product-product-attribute-value?query=deletemany', [
            2, 3
        ]);

        $response->assertStatus(200);
    }
}
