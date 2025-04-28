<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ManualPaymentTest extends TestCase
{
    public function test_create_single_manual_payment(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/manual-payment', [
            "paymentMethodId" => 1,
            "customerId" => 1,
            "cartOrderId" => "1ALXIN6PLK901K5",
            "amount" => 73168.75,
            "CustomerAccount" => "01820361645",
            "CustomerTransactionId" => "ALU9QE10Y9"
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'paymentMethodId',
                'customerId',
                'cartOrderId',
                'amount',
                'manualTransactionId',
                'customerAccount',
                'customerTransactionId',
                'updatedAt',
                'createdAt',
                'id'
            ]);
    }

    public function test_get_all_manual_payment(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/manual-payment?query=all&status=true');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'paymentMethodId',
                    'customerId',
                    'cartOrderId',
                    'amount',
                    'manualTransactionId',
                    'customerAccount',
                    'customerTransactionId',
                    'isVerified',
                    'status',
                    'createdAt',
                    'updatedAt',
                    'customer',
                    'cartOrder',
                    'paymentMethod',
                ],
            ]);
    }


    public function test_get_all_manual_payment_paginated(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/manual-payment?page=1&count=10&status=true');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllManualPayment' => [
                    '*' => [
                        'id',
                        'paymentMethodId',
                        'customerId',
                        'cartOrderId',
                        'amount',
                        'manualTransactionId',
                        'customerAccount',
                        'customerTransactionId',
                        'isVerified',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'customer',
                        'cartOrder',
                        'paymentMethod',
                    ],
                ],
                'totalManualPayment',
            ]);
    }

    public function test_get_info_manual_payment(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/manual-payment?query=info');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'totalAmount',
                'totalManualPayment',
            ]);
    }

    public function test_get_search_manual_payment(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/manual-payment?query=search&key=2024-01-04&page=1&count=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllManualPayment' => [
                    '*' => [
                        'id',
                        'paymentMethodId',
                        'customerId',
                        'cartOrderId',
                        'amount',
                        'manualTransactionId',
                        'customerAccount',
                        'customerTransactionId',
                        'isVerified',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'customer',
                        'cartOrder',
                        'paymentMethod',
                    ],
                ],
                'totalManualPayment',
            ]);
    }

    public function test_get_filter_manual_payment_by_payment_method_id(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/manual-payment?page=1&count=10&paymentMethodId=1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllManualPayment' => [
                    '*' => [
                        'id',
                        'paymentMethodId',
                        'customerId',
                        'cartOrderId',
                        'amount',
                        'manualTransactionId',
                        'customerAccount',
                        'customerTransactionId',
                        'isVerified',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'customer',
                        'cartOrder',
                        'paymentMethod',
                    ],
                ],
                'totalManualPayment',
            ]);
    }

    public function test_get_filter_manual_payment_by_from_date(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/manual-payment?page=1&count=10&fromDate=2024-01-05');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllManualPayment' => [
                    '*' => [
                        'id',
                        'paymentMethodId',
                        'customerId',
                        'cartOrderId',
                        'amount',
                        'manualTransactionId',
                        'customerAccount',
                        'customerTransactionId',
                        'isVerified',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'customer',
                        'cartOrder',
                        'paymentMethod',
                    ],
                ],
                'totalManualPayment',
            ]);
    }

    public function test_get_manual_payment_by_payment_method_id(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/manual-payment/payment-method/1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'totalAmount',
                'totalManualPayment',
            ]);
    }

    public function test_get_single_manual_payment(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/manual-payment/1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'paymentMethodId',
                'customerId',
                'cartOrderId',
                'amount',
                'manualTransactionId',
                'customerAccount',
                'customerTransactionId',
                'isVerified',
                'status',
                'createdAt',
                'updatedAt',
                'customer',
                'cartOrder',
                'paymentMethod',
            ]);
    }

    public function test_verify_manual_payment(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->put('/manual-payment/verify/1', [
            "isVerified" => "Accept"
        ]);

        $response->assertStatus(200);
    }
}
