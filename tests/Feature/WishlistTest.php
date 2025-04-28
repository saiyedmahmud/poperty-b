<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WishlistTest extends TestCase
{
    public function test_create_single_wish_list(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/product-wishlist', [
            "customerId" => 1,
            "productId" => 9
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'productId',
                'wishlistId',
                'updatedAt',
                'createdAt',
                'id',
            ]);
    }

    public function test_get_all_wish_list(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product-wishlist?query=all');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'customerId',
                    'status',
                    'createdAt',
                    'updatedAt',
                    'customer',
                ],
            ]);
    }

    public function test_get_all_wish_list_by_customer_id(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product-wishlist/customer/1?page=1&count=2');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllProductWishlist' => [
                    '*' => [
                        'id',
                        'name',
                        'productThumbnailImage',
                        'productSubCategoryId',
                        'productBrandId',
                        'description',
                        'sku',
                        'productQuantity',
                        'productSalePrice',
                        'uomId',
                        'productVatId',
                        'shippingChargeComment',
                        'discountId',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'discount',
                        'productBrand',
                        'productProductAttributeValue',
                        'productColor',
                    ],
                ],
                'totalProductWishlist',
            ]);
    }

    public function test_get_paginated_wish_list_by_status(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product-wishlist?status=true&page=1&count=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllWishlist' => [
                    '*' => [
                        'id',
                        'customerId',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'customer',
                    ],
                ],
                'totalWishlist',
            ]);
    }

    public function test_delete_single_wish_list_by_customer(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->delete('/product-wishlist/customer/1');

        $response->assertStatus(200);
    }
}
