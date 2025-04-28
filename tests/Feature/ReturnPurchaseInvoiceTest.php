<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReturnPurchaseInvoiceTest extends TestCase
{
    public function test_create_single_return_purchase_invoice(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/return-purchase-invoice', [
            "date" => "2025-08-12T11:01:14.870Z",
            "purchaseInvoiceId" => "P_FTOW0P399MGIF",
            "instantReturnAmount" => [
                [
                    "amount" => 100,
                    "paymentType" => 1
                ]
            ],
            "note" => "New note added",
            "invoiceMemoNo" => "hello1234",
            "returnPurchaseInvoiceProduct" => [
                [
                    "purchaseInvoiceProductId" => 1,
                    "productQuantity" => 5
                ]
            ]
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'createdReturnPurchaseInvoice' => [
                    'date',
                    'totalAmount',
                    'tax',
                    'instantReturnAmount',
                    'purchaseInvoiceId',
                    'invoiceMemoNo',
                    'note',
                    'id',
                    'updatedAt',
                    'createdAt',
                ],
            ]);
    }

    public function test_get_all_return_purchase_invoice(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/return-purchase-invoice?query=all');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'date',
                    'totalAmount',
                    'instantReturnAmount',
                    'tax',
                    'note',
                    'purchaseInvoiceId',
                    'invoiceMemoNo',
                    'status',
                    'createdAt',
                    'updatedAt',
                    'purchaseInvoice',
                ],
            ]);
    }

    public function test_get_paginated_return_purchase_invoice(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/return-purchase-invoice?status=true&page=1&count=10&startdate=2023-12-01&enddate=2023-12-31');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'aggregations' => [
                    '_count' => [
                        'id',
                    ],
                    '_sum' => [
                        'totalAmount',
                    ],
                ],
                'allPurchaseInvoice' => [
                    '*' => [
                        'id',
                        'date',
                        'totalAmount',
                        'instantReturnAmount',
                        'tax',
                        'note',
                        'purchaseInvoiceId',
                        'invoiceMemoNo',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'purchaseInvoice',
                    ],
                ],
            ]);
    }

    public function test_get_single_return_purchase_invoice(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('return-purchase-invoice/PR_43X4O70DK0BR');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'date',
                'totalAmount',
                'instantReturnAmount',
                'tax',
                'note',
                'purchaseInvoiceId',
                'invoiceMemoNo',
                'status',
                'createdAt',
                'updatedAt',
                'returnPurchaseInvoiceProduct' => [
                    '*' => [
                        'id',
                        'invoiceId',
                        'purchaseInvoiceProductId',
                        'productId',
                        'productQuantity',
                        'productUnitPurchasePrice',
                        'productFinalAmount',
                        'tax',
                        'taxAmount',
                        'createdAt',
                        'updatedAt',
                        'product',
                    ],
                ],
                'purchaseInvoice',
            ]);
    }

    public function test_info_return_purchase_invoice(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/return-purchase-invoice?query=info');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '_count' => [
                    'id',
                ],
                '_sum' => [
                    'totalAmount',
                ],
            ]);
    }

    public function test_info_return_purchase_invoice_by_group(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/return-purchase-invoice?query=group');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'sum' => [
                        'totalAmount',
                    ],
                    'count' => [
                        'id',
                    ],
                    'date',
                ],
            ]);
    }
}
