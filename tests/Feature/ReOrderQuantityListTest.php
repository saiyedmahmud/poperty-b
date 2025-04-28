<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReOrderQuantityListTest extends TestCase
{
    public function test_get_all_re_order_quantity_list(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/reorder-quantity?page=1&count=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllReOderList' => [
                    '*' => [
                        'id',
                        'storeId',
                        'productId',
                        'productQuantity',
                        'productSalePrice',
                        'productPurchasePrice',
                        'reorderQuantity',
                        'isNegativeSale',
                        'comment',
                        'createdAt',
                        'updatedAt',
                        'product' => [],
                    ],
                ],
                'count' => [
                    'id'
                ],
            ]);
    }

    public function test_get_all_re_order_quantity_list_storeId_wise(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/reorder-quantity?storeId=1,2&page=1&count=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllReOderList' => [
                    '*' => [
                        'id',
                        'storeId',
                        'productId',
                        'productQuantity',
                        'productSalePrice',
                        'productPurchasePrice',
                        'reorderQuantity',
                        'isNegativeSale',
                        'comment',
                        'createdAt',
                        'updatedAt',
                        'product' => [],
                    ],
                ],
                'count' => [
                    'id'
                ],
            ]);
    }
}
