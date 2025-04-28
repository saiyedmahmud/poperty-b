<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReturnCartOrderTest extends TestCase
{
    public function test_create_single_return_cart_order(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/return-cart-order', [
            "customerId" => 1,
            "note" => "sales note",
            "cartOrderId" => "3PNJZGSNYDO1RKE",
            "returnType" => "PRODUCT", // REFUND, PRODUCT
            "cartOrderProductId" => 2,
            "productQuantity" => 1
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'date',
                'cartOrderId',
                'totalAmount',
                'totalVatAmount',
                'totalDiscountAmount',
                'note',
                'couponAmount',
                'returnType',
                'returnCartOrderStatus',
                'updatedAt',
                'createdAt',
                'id',
            ]);
    }

    public function test_get_all_return_cart_order_date_wise_paginated(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/return-cart-order?page=1&count=10&startDate=2022-09-01&endDate=2024-12-31');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllReturnCartOrder' => [
                    '*' => [
                        'id',
                        'cartOrderId',
                        'date',
                        'totalAmount',
                        'totalVatAmount',
                        'totalDiscountAmount',
                        'note',
                        'couponAmount',
                        'returnType',
                        'returnCartOrderStatus',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'returnCartOrderProduct' => [
                            '*' => [
                                'id',
                                'productId',
                                'cartOrderProductId',
                                'returnCartOrderId',
                                'colorId',
                                'productQuantity',
                                'productSalePrice',
                                'productVat',
                                'discountType',
                                'discount',
                                'createdAt',
                                'updatedAt',
                                'product',
                            ],
                        ],
                        'cartOrder' => [
                            'id',
                            'date',
                            'totalAmount',
                            'paidAmount',
                            'deliveryFee',
                            'due',
                            'isPaid',
                            'couponId',
                            'couponAmount',
                            'customerId',
                            'userId',
                            'deliveryAddress',
                            'customerPhone',
                            'note',
                            'isReOrdered',
                            'orderStatus',
                            'status',
                            'createdAt',
                            'updatedAt',
                            'courierMediumId',
                            'deliveryFeeId',
                            'previousCartOrderId',
                        ],
                    ],
                ],
                'totalReturnCartOrder',
            ]);
    }

    public function test_get_single_return_cart(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/return-cart-order/1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'cartOrderId',
                'date',
                'totalAmount',
                'totalVatAmount',
                'totalDiscountAmount',
                'note',
                'couponAmount',
                'returnType',
                'returnCartOrderStatus',
                'status',
                'createdAt',
                'updatedAt',
                'totalReturnPaidAmount',
                'totalReturnVatAmount',
                'totalReturnDiscountAmount',
                'totalReturnCouponAmount',
                'totaluomValue',
                'totalUnitQuantity',
                'returnCartOrderProduct' => [
                    '*' => [
                        'id',
                        'productId',
                        'cartOrderProductId',
                        'returnCartOrderId',
                        'colorId',
                        'productQuantity',
                        'productSalePrice',
                        'productVat',
                        'discountType',
                        'discount',
                        'createdAt',
                        'updatedAt',
                        'product',
                    ],
                ],
                'cartOrder' => [
                    'id',
                    'date',
                    'totalAmount',
                    'paidAmount',
                    'deliveryFee',
                    'due',
                    'isPaid',
                    'couponId',
                    'couponAmount',
                    'customerId',
                    'userId',
                    'deliveryAddress',
                    'customerPhone',
                    'note',
                    'isReOrdered',
                    'orderStatus',
                    'status',
                    'createdAt',
                    'updatedAt',
                    'courierMediumId',
                    'deliveryFeeId',
                    'previousCartOrderId',
                    'customer',
                ],
            ]);
    }

    public function test_update_return_cart_order_status(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->put('/return-cart-order/1', [
            "returnCartOrderStatus" => "REFUNDED" // PENDING, RECEIVED, REFUNDED, RESEND
        ]);

        $response->assertStatus(200);
    }

    public function test_get_single_return_resend_able_list(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/return-cart-order/resend');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'cartOrderId',
                    'date',
                    'totalAmount',
                    'totalVatAmount',
                    'totalDiscountAmount',
                    'note',
                    'couponAmount',
                    'returnType',
                    'returnCartOrderStatus',
                    'status',
                    'createdAt',
                    'updatedAt',
                ],
            ]);
    }

    public function test_re_order_return_cart_order(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/cart-order/reOrder', [
            "cartOrderId" => "YIQ92SJAEO41WIB",
            "returnCartOrderId" => 3
        ]);

        $response->assertStatus(201);
    }
}
