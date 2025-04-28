<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReportTest extends TestCase
{
    public function test_get_stock_report(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/report/stock');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'SKU',
                    'productName',
                    'variation',
                    'category',
                    'subCategory',
                    'unitSellingPrice',
                    'currentStock',
                    'stockPurchasePrice',
                    'stockSalePrice',
                    'potentialProfit'
                ]
            ]);
    }

    public function test_get_purchase_report_date_wise(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/report/purchase?startDate=2023-01-01&endDate=2023-10-31');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'productName',
                    'SKU',
                    'supplier',
                    'purchaseInvoiceId',
                    'purchaseInvoiceDate',
                    'quantity',
                    'unitPurchasePrice',
                    'subTotal'
                ]
            ]);
    }

    public function test_get_purchase_report(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/report/purchase');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'productName',
                    'SKU',
                    'supplier',
                    'purchaseInvoiceId',
                    'purchaseInvoiceDate',
                    'quantity',
                    'unitPurchasePrice',
                    'subTotal'
                ]
            ]);
    }
}
