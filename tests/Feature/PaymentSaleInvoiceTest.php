<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PaymentSaleInvoiceTest extends TestCase
{
    public function test_create_payment_sale_invoice(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/payment-sale-invoice', [
            "date" => "2024-01-03T12:25:41.137Z",
            "storeId" => 1,
            "paidAmount" => [
                [
                    "amount" => 100,
                    "paymentType" => 1
                ],
                [
                    "amount" => 0,
                    "paymentType" => 2
                ]
            ],
            "saleInvoiceNo" => "S_12RYYRUFZNV0R"
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'transaction' => [
                    'date',
                    'storeId',
                    'debitId',
                    'creditId',
                    'amount',
                    'particulars',
                    'type',
                    'relatedId',
                    'updatedAt',
                    'createdAt',
                    'id'
                ]
            ]);
    }

    public function test_get_paginated_payment_sale_invoice(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/payment-sale-invoice?page=1&count=20');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllPaymentSaleInvoice' => [
                    '*' => [
                        'id',
                        'date',
                        'storeId',
                        'debitId',
                        'creditId',
                        'particulars',
                        'amount',
                        'type',
                        'relatedId',
                        'status',
                        'createdAt',
                        'updatedAt'
                    ]
                ],
                'totalPaymentSaleInvoiceCount',
                'totalAmount',
                'totalPaymentSaleInvoice'
            ]);
    }

    public function test_get_all_payment_sale_invoice(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/payment-sale-invoice?query=all');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'date',
                    'storeId',
                    'debitId',
                    'creditId',
                    'particulars',
                    'amount',
                    'type',
                    'relatedId',
                    'status',
                    'createdAt',
                    'updatedAt'
                ]
            ]);
    }

    public function test_get_info_payment_sale_invoice(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/payment-sale-invoice?query=info');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '_count' => [
                    'id'
                ],
                '_sum' => [
                    'amount'
                ]
            ]);
    }
}
