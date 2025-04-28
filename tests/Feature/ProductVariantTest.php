<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductVariantTest extends TestCase
{
    public function test_create_single_product_variant(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/product-variant', [
            "productGroupName" => "PG1",
            "manufacturerId" => 1,
            "productBrandId" => 1,
            "subCategoryId" => 1,
            "purchaseTaxId" => 1,
            "salesTaxId" => 2,
            "uomId" => 1,
            "uomValue" => 10
        ]);

        $response->assertStatus(201);
    }

    public function test_get_all_product_variant(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product-variant?query=all');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllProductVariant' => [
                    '*' => [
                        'id',
                        'productGroupName',
                        'manufacturerId',
                        'productBrandId',
                        'subCategoryId',
                        'productPurchaseTaxId',
                        'productSalesTaxId',
                        'uomId',
                        'uomValue',
                        'description',
                        'status',
                        'createdAt',
                        'updatedAt'
                    ]
                ],
                'totalCount'
            ]);
    }

    public function test_get_search_product_variant(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product-variant?query=search&key=1&page=1&count=10');

        $response->assertStatus(200);
    }

    public function test_get_all_product_variant_paginated(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product-variant?startDate=2024-01-01&endDate=2024-12-30&status=true&page=1&count=10');

        $response->assertStatus(200);
    }

    public function test_get_single_product_variant(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product-variant/7JJII1T8TT');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'productGroupName',
                'manufacturerId',
                'productBrandId',
                'subCategoryId',
                'productPurchaseTaxId',
                'productSalesTaxId',
                'uomId',
                'uomValue',
                'description',
                'status',
                'createdAt',
                'updatedAt',
                'manufacturer',
                'productBrand',
                'subCategory',
                'uom',
            ]);
    }

    public function test_update_product_variant(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->put('/product-variant/1', [
            "manufacturerId" => 2
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_single_product_variant(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->patch('/product-variant/1', [
            "status" => "false"
        ]);

        $response->assertStatus(200);
    }
}
