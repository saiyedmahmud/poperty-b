<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SaleInvoiceTest extends TestCase
{
    public function test_create_single_sale_invoice(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/sale-invoice', [
            "date" => "2024-02-10T12:51:00+06:00",
            "storeId" => 2,
            "invoiceMemoNo" => "",
            "paidAmount" => [
                [
                    "amount" => 0,
                    "paymentType" => 1
                ]
            ],
            "customerId" => 1,
            "userId" => 1,
            "saleCommission" => 50,
            "address" => "Mirpur, Dhaka",
            "note" => "sales note",
            "paymentTerm" => "test",
            "termsAndConditon" => "test test test long test",
            "isHold" => "false",
            "shippingAddress" => "dhaka,chittagong",
            "saleInvoiceProduct" => [
                [
                    "productId" => 1,
                    "productQuantity" => 2,
                    "productUnitSalePrice" => 250,
                    "productDiscount" => 10,
                    "tax" => 0
                ]
            ]
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'createdInvoice' => [
                    'date',
                    'storeId',
                    'invoiceMemoNo',
                    'totalAmount',
                    'totalTaxAmount',
                    'totalDiscountAmount',
                    'paidAmount',
                    'profit',
                    'dueAmount',
                    'dueDate',
                    'note',
                    'termsAndConditions',
                    'orderStatus',
                    'customerId',
                    'userId',
                    'id',
                    'updatedAt',
                    'createdAt',
                ]
            ]);
    }

    public function test_get_all_sale_invoice_date_wise_paginated(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/sale-invoice?page=1&count=10&startDate=2023-12-31&endDate=2025-12-30');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'aggregations' => [
                    '_count' => [
                        'id'
                    ],
                    '_sum' => [
                        'totalAmount',
                        'paidAmount',
                        'dueAmount',
                        'totalReturnAmount',
                        'instantPaidReturnAmount',
                        'profit',
                        'totaluomValue',
                        'totalUnitQuantity'
                    ]
                ],
                'getAllSaleInvoice' => [
                    '*' => [
                        'id',
                        'date',
                        'storeId',
                        'invoiceMemoNo',
                        'totalAmount',
                        'totalTaxAmount',
                        'totalDiscountAmount',
                        'paidAmount',
                        'dueAmount',
                        'profit',
                        'dueDate',
                        'termsAndConditions',
                        'customerId',
                        'userId',
                        'note',
                        'address',
                        'orderStatus',
                        'isHold',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'instantPaidReturnAmount',
                        'returnAmount',
                        'saleInvoiceProduct',
                        'user',
                        'customer'
                    ]
                ],
                'totalSaleInvoice'
            ]);
    }

    public function test_get_all_sale_invoice_info(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/sale-invoice?query=info&startdate=2022-09-01&endDate=2025-08-12');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'count' => [
                    'id'
                ],
                'sum' => [
                    'totalAmount',
                    'dueAmount',
                    'paidAmount',
                    'totalReturnAmount',
                    'instantPaidReturnAmount',
                    'profit'
                ]
            ]);
    }

    public function test_get_all_sale_invoice_report(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/sale-invoice?query=report&startDate=2023-1-31&endDate=2026-12-30');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'aggregations' => [
                    'count' => [
                        'id'
                    ],
                    'sum' => [
                        'totalAmount',
                        'paidAmount',
                        'dueAmount',
                        'totalReturnAmount',
                        'instantPaidReturnAmount',
                        'profit',
                        'totaluomValue',
                        'totalUnitQuantity'
                    ]
                ],
                'getAllSaleInvoice' => [
                    '*' => [
                        'id',
                        'date',
                        'storeId',
                        'invoiceMemoNo',
                        'totalAmount',
                        'totalTaxAmount',
                        'totalDiscountAmount',
                        'paidAmount',
                        'dueAmount',
                        'profit',
                        'dueDate',
                        'termsAndConditions',
                        'customerId',
                        'userId',
                        'note',
                        'address',
                        'orderStatus',
                        'isHold',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'instantPaidReturnAmount',
                        'returnAmount',
                        'saleInvoiceProduct',
                        'user',
                        'customer'
                    ]
                ],
                'totalSaleInvoice'
            ]);
    }

    public function test_get_all_sale_invoice_filtered_by_customerId(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/sale-invoice?page=1&count=10&customerId=5');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'aggregations' => [
                    '_count' => [
                        'id'
                    ],
                    '_sum' => [
                        'totalAmount',
                        'paidAmount',
                        'dueAmount',
                        'totalReturnAmount',
                        'instantPaidReturnAmount',
                        'profit',
                        'totaluomValue',
                        'totalUnitQuantity'
                    ]
                ],
                'getAllSaleInvoice' => [
                    '*' => [
                        'id',
                        'date',
                        'storeId',
                        'invoiceMemoNo',
                        'totalAmount',
                        'totalTaxAmount',
                        'totalDiscountAmount',
                        'paidAmount',
                        'dueAmount',
                        'profit',
                        'dueDate',
                        'termsAndConditions',
                        'customerId',
                        'userId',
                        'note',
                        'address',
                        'orderStatus',
                        'isHold',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'instantPaidReturnAmount',
                        'returnAmount',
                        'saleInvoiceProduct',
                        'user',
                        'customer'
                    ]
                ],
                'totalSaleInvoice'
            ]);
    }

    public function test_get_all_sale_invoice_filtered_by_date_range(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/sale-invoice?page=1&count=10&startDate=2023-12-01&endDate=2024-12-31');

        $response->assertStatus(200);
    }

    public function test_get_all_sale_invoice_filtered_by_order_status(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/sale-invoice?page=1&count=10&orderStatus=pending');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'aggregations' => [
                    '_count' => [
                        'id'
                    ],
                    '_sum' => [
                        'totalAmount',
                        'paidAmount',
                        'dueAmount',
                        'totalReturnAmount',
                        'instantPaidReturnAmount',
                        'profit',
                        'totaluomValue',
                        'totalUnitQuantity'
                    ]
                ],
                'getAllSaleInvoice' => [
                    '*' => [
                        'id',
                        'date',
                        'storeId',
                        'invoiceMemoNo',
                        'totalAmount',
                        'totalTaxAmount',
                        'totalDiscountAmount',
                        'paidAmount',
                        'dueAmount',
                        'profit',
                        'dueDate',
                        'termsAndConditions',
                        'customerId',
                        'userId',
                        'note',
                        'address',
                        'orderStatus',
                        'isHold',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'instantPaidReturnAmount',
                        'returnAmount',
                        'saleInvoiceProduct',
                        'user',
                        'customer'
                    ]
                ],
                'totalSaleInvoice'
            ]);
    }

    public function test_get_all_sale_invoice_filtered_by_sale_person_id(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/sale-invoice?page=1&count=10&salePersonId=5');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'aggregations' => [
                    '_count' => [
                        'id'
                    ],
                    '_sum' => [
                        'totalAmount',
                        'paidAmount',
                        'dueAmount',
                        'totalReturnAmount',
                        'instantPaidReturnAmount',
                        'profit',
                        'totaluomValue',
                        'totalUnitQuantity'
                    ]
                ],
                'getAllSaleInvoice' => [
                    '*' => [
                        'id',
                        'date',
                        'storeId',
                        'invoiceMemoNo',
                        'totalAmount',
                        'totalTaxAmount',
                        'totalDiscountAmount',
                        'paidAmount',
                        'dueAmount',
                        'profit',
                        'dueDate',
                        'termsAndConditions',
                        'customerId',
                        'userId',
                        'note',
                        'address',
                        'orderStatus',
                        'isHold',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'instantPaidReturnAmount',
                        'returnAmount',
                        'saleInvoiceProduct',
                        'user',
                        'customer'
                    ]
                ],
                'totalSaleInvoice'
            ]);
    }

    public function test_get_single_sale_invoice(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/sale-invoice/S_12RYYRUFZNV0R');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'totalAmount',
                'totalPaidAmount',
                'totalReturnAmount',
                'instantPaidReturnAmount',
                'dueAmount',
                'totaluomValue',
                'singleSaleInvoice' => [
                    'id',
                    'date',
                    'storeId',
                    'invoiceMemoNo',
                    'totalAmount',
                    'totalTaxAmount',
                    'totalDiscountAmount',
                    'paidAmount',
                    'dueAmount',
                    'profit',
                    'dueDate',
                    'termsAndConditions',
                    'customerId',
                    'userId',
                    'note',
                    'address',
                    'orderStatus',
                    'isHold',
                    'status',
                    'createdAt',
                    'updatedAt',
                    'saleInvoiceProduct',
                    'customer',
                    'user'
                ],
                'returnSaleInvoice',
                'transactions'
            ]);
    }

    public function test_get_search_sale_invoice(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/sale-invoice?query=search&key=S_12RYYRUFZNV0R&page=1&count=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'aggregations' => [
                    'count' => [
                        'id'
                    ],
                    'sum' => [
                        'totalAmount',
                        'paidAmount',
                        'dueAmount',
                        'totalReturnAmount',
                        'instantPaidReturnAmount',
                        'profit',
                        'totaluomValue',
                        'totalUnitQuantity'
                    ]
                ],
                'getAllSaleInvoice' => [
                    '*' => [
                        'id',
                        'date',
                        'storeId',
                        'invoiceMemoNo',
                        'totalAmount',
                        'totalTaxAmount',
                        'totalDiscountAmount',
                        'paidAmount',
                        'dueAmount',
                        'profit',
                        'dueDate',
                        'termsAndConditions',
                        'customerId',
                        'userId',
                        'note',
                        'address',
                        'orderStatus',
                        'isHold',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'instantPaidReturnAmount',
                        'returnAmount',
                        'saleInvoiceProduct',
                        'user',
                        'customer'
                    ]
                ],
                'totalSaleInvoice'
            ]);
    }

    public function test_get_search_sale_invoice_by_order_status(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/sale-invoice?query=search-order&key=PENDING');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'aggregations' => [
                    'count' => [
                        'id'
                    ],
                    'sum' => [
                        'totalAmount',
                        'paidAmount',
                        'dueAmount',
                        'totalReturnAmount',
                        'instantPaidReturnAmount',
                        'profit',
                        'totaluomValue',
                        'totalUnitQuantity'
                    ]
                ],
                'getAllSaleInvoice' => [
                    '*' => [
                        'id',
                        'date',
                        'storeId',
                        'invoiceMemoNo',
                        'totalAmount',
                        'totalTaxAmount',
                        'totalDiscountAmount',
                        'paidAmount',
                        'dueAmount',
                        'profit',
                        'dueDate',
                        'termsAndConditions',
                        'customerId',
                        'userId',
                        'note',
                        'address',
                        'orderStatus',
                        'isHold',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'instantPaidReturnAmount',
                        'returnAmount',
                        'saleInvoiceProduct',
                        'user',
                        'customer'
                    ]
                ],
                'totalSaleInvoice'
            ]);
    }

    public function test_update_order_status(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->patch('/sale-invoice/order', [
            "invoiceId" => "S_BWJJ91X13SHU7",
            "orderStatus" => "DELIVERED"
        ]);

        $response->assertStatus(200);
    }
}
