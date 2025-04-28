<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PaymentPurchaseInvoiceTest extends TestCase
{
    public function test_create_payment_purchase_invoice(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/payment-purchase-invoice', [
            "date" => "2023-09-24T12:25:41.137Z",
            "storeId" => 1,
            "paidAmount" => [
                [
                    "amount" => 600,
                    "paymentType" => 1
                ],
                [
                    "amount" => 400,
                    "paymentType" => 2
                ]
            ],
            "purchaseInvoiceNo" => "P_FTOW0P399MGIF"
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
                    'id',
                ],
            ]);
    }

    public function test_get_paginated_payment_purchase_invoice(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/payment-purchase-invoice?page=1&count=20');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllPaymentPurchaseInvoice' => [
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
                        'updatedAt',
                    ],
                ],
                'totalPaymentPurchaseInvoice',
                'totalAmount',
                'totalPaymentPurchaseInvoiceCount' => [
                    'contact' => [
                        'id',
                    ],
                ],
            ]);
    }

    public function test_get_all_payment_purchase_invoice(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/payment-purchase-invoice?query=all');

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
                    'updatedAt',
                ],
            ]);
    }

    public function test_get_info_payment_purchase_invoice(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/payment-purchase-invoice?query=info');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '_count' => [
                    'id',
                ],
                '_sum' => [
                    'amount',
                ],
            ]);
    }
}
