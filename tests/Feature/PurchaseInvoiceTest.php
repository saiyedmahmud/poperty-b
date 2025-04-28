<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PurchaseInvoiceTest extends TestCase
{
    public function test_create_single_purchase_invoice(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/purchase-invoice', [
            "date" => "2025-08-12T11:01:14.870Z",
            "storeId" => 1,
            "paidAmount" => [
                [
                    "amount" => 100,
                    "paymentType" => 1
                ]
            ],
            "supplierId" => 1,
            "note" => "New note added",
            "supplierMemoNo" => "A2012",
            "invoiceMemoNo" => "hello1234",
            "purchaseInvoiceProduct" => [
                [
                    "productId" => 2,
                    "productQuantity" => 10,
                    "productUnitPurchasePrice" => 150,
                    "productUnitSalePrice" => 200,
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
                    'totalTax',
                    'paidAmount',
                    'dueAmount',
                    'supplierId',
                    'note',
                    'supplierMemoNo',
                    'id',
                    'updatedAt',
                    'createdAt'
                ]
            ]);
    }

    public function test_get_dateWisePaginated_purchase_invoice(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/purchase-invoice?page=1&count=10&startDate=2025-08-12&endDate=2025-08-12');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'aggregations' => [
                    '_count' => [
                        'id',
                    ],
                    '_sum' => [
                        'totalAmount',
                        'paidAmount',
                        'dueAmount',
                        'totalReturnAmount',
                        'instantPaidReturnAmount',
                    ],
                ],
                'getAllPurchaseInvoice' => [
                    '*' => [
                        'id',
                        'date',
                        'storeId',
                        'totalAmount',
                        'totalTax',
                        'paidAmount',
                        'dueAmount',
                        'supplierId',
                        'note',
                        'supplierMemoNo',
                        'invoiceMemoNo',
                        'createdAt',
                        'updatedAt',
                        'instantPaidReturnAmount',
                        'returnAmount',
                        'purchaseInvoiceProduct',
                        'supplier',
                    ],
                ],
                'totalPurchaseInvoice',
            ]);
    }

    public function test_get_single_purchase_invoice(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('purchase-invoice/P_71THILM272ST1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'totalAmount',
                'totalPaidAmount',
                'totalReturnAmount',
                'instantPaidReturnAmount',
                'dueAmount',
                'singlePurchaseInvoice' => [
                    'id',
                    'date',
                    'storeId',
                    'totalAmount',
                    'totalTax',
                    'paidAmount',
                    'dueAmount',
                    'supplierId',
                    'note',
                    'supplierMemoNo',
                    'invoiceMemoNo',
                    'createdAt',
                    'updatedAt',
                    'purchaseInvoiceProduct',
                    'supplier',
                ],
                'returnPurchaseInvoice',
                'transactions',
            ]);
    }

    public function test_info_purchase_invoice(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/purchase-invoice?startdate=2023-01-01&enddate=2023-12-30&query=info');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '_count' => [
                    'id',
                ],
                '_sum' => [
                    'totalAmount',
                    'dueAmount',
                    'paidAmount',
                    'totalReturnAmount',
                    'instantReturnPaidAmount',
                ],
            ]);
    }

    public function test_search_purchase_invoice(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/purchase-invoice?query=search&key=P_71THILM272ST1&page=1&count=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllPurchaseInvoice' => [
                    '*' => [
                        'id',
                        'date',
                        'storeId',
                        'totalAmount',
                        'totalTax',
                        'paidAmount',
                        'dueAmount',
                        'supplierId',
                        'note',
                        'supplierMemoNo',
                        'invoiceMemoNo',
                        'createdAt',
                        'updatedAt',
                        'instantPaidReturnAmount',
                        'returnAmount',
                        'purchaseInvoiceProduct',
                        'supplier',
                    ],
                ],
                'totalPurchaseInvoice',
            ]);
    }

    public function test_get_report_purchase_invoice(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/purchase-invoice?page=1&count=10&startDate=2025-08-12&endDate=2025-08-12&query=report');

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
                    ],
                ],
                'getAllPurchaseInvoice' => [
                    '*' => [
                        'id',
                        'date',
                        'storeId',
                        'totalAmount',
                        'totalTax',
                        'paidAmount',
                        'dueAmount',
                        'supplierId',
                        'note',
                        'supplierMemoNo',
                        'invoiceMemoNo',
                        'createdAt',
                        'updatedAt',
                        'instantPaidReturnAmount',
                        'returnAmount',
                        'purchaseInvoiceProduct',
                        'supplier',
                    ],
                ],
                'totalPurchaseInvoice',
            ]);
    }
}
