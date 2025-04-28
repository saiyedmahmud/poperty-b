<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CartOrderTest extends TestCase
{
    public function test_create_single_cart_order(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/cart-order', [
            "customerId" => 1,
            "deliveryAddress" => "Mirpur, Dhaka",
            "note" => "sales note",
            "paymentMethodId" => 1,
            "couponCode" => "",
            "deliveryFeeId" => 1,
            "cartId" => 1
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'date',
                'totalAmount',
                'paidAmount',
                'due',
                'profit',
                'couponId',
                'couponAmount',
                'customerId',
                'userId',
                'deliveryAddress',
                'customerPhone',
                'deliveryFeeId',
                'deliveryFee',
                'id',
                'updatedAt',
                'createdAt',
            ]);
    }

    public function test_get_all_cart_order_date_wise_paginated(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/cart-order?page=1&count=10&startDate=2022-09-01&endDate=2025-12-31');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'aggregations' => [
                    'count' => [
                        'id',
                    ],
                    'sum' => [
                        'totalAmount',
                        'paidAmount',
                        'dueAmount',
                        'totalReturnAmount',
                        'instantPaidReturnAmount',
                        'totaluomValue',
                        'totalUnitQuantity',
                    ],
                ],
                'getAllCartOrder' => [
                    '*' => [
                        'id',
                        'date',
                        'invoiceMemoNo',
                        'totalAmount',
                        'totalTaxAmount',
                        'totalDiscountAmount',
                        'paidAmount',
                        'dueAmount',
                        'dueDate',
                        'termsAndConditions',
                        'customerId',
                        'userId',
                        'note',
                        'address',
                        'orderStatus',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'instantPaidReturnAmount',
                        'returnAmount',
                        'user',
                        'customer',
                        'cartOrderProduct',
                    ],
                ],
                'totalCartOrder',
            ]);
    }

    public function test_get_filtered_cart_order_paginated_by_order_status(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/cart-order?page=1&count=10&orderStatus=PENDING&startDate=2023-01-01&endDate=2025-11-01');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'aggregations' => [
                    'count' => [
                        'id',
                    ],
                    'sum' => [
                        'totalAmount',
                        'paidAmount',
                        'dueAmount',
                        'totalReturnAmount',
                        'instantPaidReturnAmount',
                        'totaluomValue',
                        'totalUnitQuantity',
                    ],
                ],
                'getAllCartOrder' => [
                    '*' => [
                        'id',
                        'date',
                        'invoiceMemoNo',
                        'totalAmount',
                        'totalTaxAmount',
                        'totalDiscountAmount',
                        'paidAmount',
                        'dueAmount',
                        'dueDate',
                        'termsAndConditions',
                        'customerId',
                        'userId',
                        'note',
                        'address',
                        'orderStatus',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'instantPaidReturnAmount',
                        'returnAmount',
                        'user',
                        'customer',
                        'cartOrderProduct',
                    ],
                ],
                'totalCartOrder',
            ]);
    }

    public function test_get_filtered_cart_order_paginated_by_customer_id(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/cart-order?page=1&count=10&customerId=1&startDate=2023-01-01&endDate=2025-11-01');

        $response->assertStatus(200);
    }

    public function test_get_search_cart_order(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/cart-order?query=search&key=doe&page=1&count=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'aggregations' => [
                    'count' => [
                        'id',
                    ],
                    'sum' => [
                        'totalAmount',
                        'paidAmount',
                        'dueAmount',
                        'totalReturnAmount',
                        'instantPaidReturnAmount',
                        'totaluomValue',
                        'totalUnitQuantity',
                    ],
                ],
                'getAllCartOrder' => [
                    '*' => [
                        'id',
                        'date',
                        'invoiceMemoNo',
                        'totalAmount',
                        'totalTaxAmount',
                        'totalDiscountAmount',
                        'paidAmount',
                        'dueAmount',
                        'dueDate',
                        'termsAndConditions',
                        'customerId',
                        'userId',
                        'note',
                        'address',
                        'orderStatus',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'instantPaidReturnAmount',
                        'returnAmount',
                        'user',
                        'customer',
                        'cartOrderProduct',
                    ],
                ],
                'totalCartOrder',
            ]);
    }

    public function test_get_search_cart_order_by_order_status(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/cart-order?query=search&key=PENDING');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'aggregations' => [
                    'count' => [
                        'id',
                    ],
                    'sum' => [
                        'totalAmount',
                        'paidAmount',
                        'dueAmount',
                        'totalReturnAmount',
                        'instantPaidReturnAmount',
                        'totaluomValue',
                        'totalUnitQuantity',
                    ],
                ],
                'getAllCartOrder' => [
                    '*' => [
                        'id',
                        'date',
                        'invoiceMemoNo',
                        'totalAmount',
                        'totalTaxAmount',
                        'totalDiscountAmount',
                        'paidAmount',
                        'dueAmount',
                        'dueDate',
                        'termsAndConditions',
                        'customerId',
                        'userId',
                        'note',
                        'address',
                        'orderStatus',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'instantPaidReturnAmount',
                        'returnAmount',
                        'user',
                        'customer',
                        'cartOrderProduct',
                    ],
                ],
                'totalCartOrder',
            ]);
    }

    public function test_get_single_cart_order(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/cart-order/S_C1937TAW18IIA');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'totalAmount',
                'totalPaidAmount',
                'totalReturnPaidAmount',
                'dueAmount',
                'totalVatAmount',
                'totalDiscountAmount',
                'totalCouponAmount',
                'deliveryFee',
                'totaluomValue',
                'singleCartOrder' => [
                    'id',
                    'date',
                    'totalAmount',
                    'paidAmount',
                    'deliveryFee',
                    'due',
                    'isPaid',
                    'profit',
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
                    'cartOrderProduct',
                    'customer',
                    'user',
                    'manualPayment',
                    'courierMedium',
                    'previousCartOrder',
                ],
                'returnSingleCartOrder',
                'transactions',
            ]);
    }

    public function test_update_cart_order_status(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->put('/cart-order/order', [
            "invoiceId" => "7DXUT7NZLEPMWAK",
            "orderStatus" => "RECEIVED",
            "deliveryFee" => 50,
            "deliveryBoyId" => null,
            "courierId" => 1,
            "courierMediumId" => 1
        ]);

        $response->assertStatus(200);
    }
}
