<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReturnSaleInvoiceTest extends TestCase
{
    public function test_create_single_return_sale_invoice(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/return-sale-invoice', [
            "date" => "2024-02-15T04:15:24.880Z",
            "saleInvoiceId" => "S_LMSM9FPS1DMZ5",
            "invoiceMemoNo" => "",
            "instantReturnAmount" => [
                [
                    "amount" => 0
                ]
            ],
            "note" => "customer return added",
            "returnSaleInvoiceProduct" => [
                [
                    "saleInvoiceProductId" => 1,
                    "productQuantity" => 1
                ]
            ]
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'date',
                'totalAmount',
                'instantReturnAmount',
                'tax',
                'saleInvoiceId',
                'invoiceMemoNo',
                'note',
                'id',
                'updatedAt',
                'createdAt'
            ]);
    }

    public function test_get_all_return_sale_invoice(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/return-sale-invoice?query=all');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'date',
                    'totalAmount',
                    'instantReturnAmount',
                    'tax',
                    'note',
                    'saleInvoiceId',
                    'invoiceMemoNo',
                    'status',
                    'createdAt',
                    'updatedAt',
                    'saleInvoice'
                ]
            ]);
    }

    public function test_get_all_return_sale_invoice_true_paginated(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/return-sale-invoice?status=true&page=1&count=10&startDate=2022-12-01&endDate=2024-12-31');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'aggregations' => [
                    'count' => [
                        'id'
                    ],
                    'sum' => [
                        'totalAmount'
                    ]
                ],
                'allSaleInvoice' => [
                    '*' => [
                        'id',
                        'date',
                        'totalAmount',
                        'instantReturnAmount',
                        'tax',
                        'note',
                        'saleInvoiceId',
                        'invoiceMemoNo',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'saleInvoice'
                    ]
                ]
            ]);
    }

    public function test_get_all_return_sale_invoice_false_paginated(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/return-sale-invoice?status=false&page=1&count=10&startdate=2022-09-05&enddate=2022-10-09');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'aggregations' => [
                    'count' => [
                        'id'
                    ],
                    'sum' => [
                        'totalAmount'
                    ]
                ],
                'allSaleInvoice' => [
                    '*' => [
                        'id',
                        'date',
                        'totalAmount',
                        'instantReturnAmount',
                        'tax',
                        'note',
                        'saleInvoiceId',
                        'invoiceMemoNo',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'saleInvoice'
                    ]
                ]
            ]);
    }

    public function test_get_all_return_sale_invoice_info(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/return-sale-invoice?query=info');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '_count' => [
                    'id'
                ],
                '_sum' => [
                    'totalAmount'
                ]
            ]);
    }

    public function test_get_all_return_sale_invoice_info_by_group(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/return-sale-invoice?query=group');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'sum' => [
                        'totalAmount'
                    ],
                    'count' => [
                        'id'
                    ],
                    'date'
                ]
            ]);
    }

    public function test_get_single_return_sale_invoice(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/return-sale-invoice/SR_G6Z1MME55ZGN');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'date',
                'totalAmount',
                'instantReturnAmount',
                'tax',
                'note',
                'saleInvoiceId',
                'invoiceMemoNo',
                'status',
                'createdAt',
                'updatedAt',
                'returnSaleInvoiceProduct',
                'saleInvoice'
            ]);
    }

    public function test_update_return_sale_invoice(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->put('/return-sale-invoice/SR_G6Z1MME55ZGN', [
            "total_amount" => 10,
            "due_amount" => 1,
            "paid_amount" => 11,
            "supplier_id" => 1,
            "note" => "update note",
            "purchaseInvoiceProduct" => [
                [
                    "warehouseStock_id" => 1,
                    "product_quantity" => 1111,
                    "product_purchase_price" => 500
                ],
                [
                    "warehouseStock_id" => 2,
                    "product_quantity" => 11110,
                    "product_purchase_price" => 800
                ]
            ]
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_return_sale_invoice(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->patch('return-sale-invoice/SR_G6Z1MME55ZGN', [
            "status" => "false",
        ]);

        $response->assertStatus(200);
    }
}
