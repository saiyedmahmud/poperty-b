<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    public function test_get_info_group_by_day(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/dashboard?startDate=2022-01-01&endDate=2025-12-07');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'totalSaleInvoice',
                'totalSaleAmount',
                'totalSaleDue',
                'totalPurchaseInvoice',
                'totalPurchaseAmount',
                'totalPurchaseDue'
            ]);
    }
}
