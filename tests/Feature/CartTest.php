<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CartTest extends TestCase
{
    public function test_create_single_cart(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/cart', [
            "customerId" => 1,
            "cartProduct" => [
                [
                    "productId" => 1,
                    "productQuantity" => 1,
                    "productAttributeValueId" => [
                        1
                    ],
                    "colorId" => 1
                ]
            ]
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'customerId',
                'totalAmount',
                'createdAt',
                'updatedAt',
            ]);
    }

    public function test_get_all_cart(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/cart?query=all');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'customerId',
                    'totalAmount',
                    'createdAt',
                    'updatedAt',
                ],
            ]);
    }

    public function test_get_cart_customer_id(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/cart/customer/1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'customerId',
                'totalAmount',
                'createdAt',
                'updatedAt',
                'cartProducts' => [
                    '*' => [
                        'id',
                        'cartId',
                        'productId',
                        'colorId',
                        'productQuantity',
                        'createdAt',
                        'updatedAt',
                        'cartAttributeValue',
                        'colors',
                        'product',
                    ],
                ],
                'customer',
            ]);
    }

    public function test_get_single_cart(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/cart/1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'customerId',
                'totalAmount',
                'createdAt',
                'updatedAt',
                'cartProducts' => [
                    '*' => [
                        'id',
                        'cartId',
                        'productId',
                        'colorId',
                        'productQuantity',
                        'createdAt',
                        'updatedAt',
                        'cartAttributeValue',
                        'colors',
                        'product',
                    ],
                ],
                'customer',
            ]);
    }

    public function test_update_cart_product(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->put('/cart/cart-product/1', [
            "cartProductId" => 1,
            "productQuantity" => 1,
            "type" => "increment"
        ]);

        $response->assertStatus(200);
    }
}
