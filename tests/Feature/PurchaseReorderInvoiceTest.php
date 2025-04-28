<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PurchaseReorderInvoiceTest extends TestCase
{
    public $reorderInvoiceId;

    public function test_create_re_order_invoice(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/purchase-reorder-invoice', [
            "storeId" => 1,
            "reorderStocks" => [
                [
                    "stockId" => 1,
                    "productId" => 1,
                    "productQuantity" => 200
                ],
                [
                    "stockId" => 2,
                    "productId" => 2,
                    "productQuantity" => 200
                ]
            ]
        ]);

        $this->reorderInvoiceId = $response[0]['reorderInvoiceId'];
        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'reorderInvoiceId',
                    'storeId',
                    'stockId',
                    'productId',
                    'productQuantity',
                    'updatedAt',
                    'createdAt',
                    'id',
                ],
            ]);
    }


    public function test_get_all_re_order_invoice(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/purchase-reorder-invoice?query=all');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'reorderInvoiceId',
                    'createdAt',
                    'updatedAt',
                    'status'
                ]
            ]);
    }

    public function test_get_all_re_order_invoice_paginated(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/purchase-reorder-invoice?page=1&count=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllPurchaseReorderInvoice' => [
                    '*' => [
                        'reorderInvoiceId',
                        'createdAt',
                        'updatedAt',
                        'status'
                    ]
                ],
                'totalReorderInvoice'
            ]);
    }

    public function test_get_all_re_order_invoice_paginated_by_storeIds(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/purchase-reorder-invoice?page=1&count=10&storeId=1,2');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllPurchaseReorderInvoice' => [
                    '*' => [
                        'reorderInvoiceId',
                        'createdAt',
                        'updatedAt',
                        'status'
                    ]
                ],
                'totalReorderInvoice'
            ]);
    }

    public function test_get_single_re_order_invoice(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson("/purchase-reorder-invoice/{$this->reorderInvoiceId}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'reorderInvoiceId',
                'storeId',
                'createdAt',
                'updatedAt',
                'status',
                'productList' => [
                    '*' => [
                        'stockId',
                        'productId',
                        'reorderProductQuantity',
                        'product' => [
                            'id',
                            'name',
                            'productThumbnailImage',
                            'sku',
                            'eanNo',
                            'upcNo',
                            'isbnNo',
                            'partNo',
                            'weightUnitId',
                            'dimensionUnitId',
                            'length',
                            'width',
                            'height',
                            'weight',
                            'discountId',
                            'productGroupId',
                            'status',
                            'created_at',
                            'updated_at',
                            'purchaseInvoiceId',
                        ],
                    ],
                ],
            ]);
    }

    public function test_delete_re_order_invoice(): void
    {

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->delete("/purchase-reorder-invoice/{$this->reorderInvoiceId}");

        $response->assertStatus(200);
    }
}
