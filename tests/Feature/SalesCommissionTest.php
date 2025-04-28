<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SalesCommissionTest extends TestCase
{
    public function test_get_all_sale_commission(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/sale-commission?page=1&count=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'userId',
                    'saleCommission',
                    'user' => [
                        'id',
                        'username',
                        'email',
                        'firstName',
                        'lastName'
                    ],
                    'totalPayableCommission',
                    'commissionReduceForReturn',
                    'remainingCommission',
                    'paidCommission'
                ]
            ]);
    }

    public function test_get_sale_commission_by_user_id(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/sale-commission/user/1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'totalPayableCommission',
                'paidCommission',
                'remainingCommission',
                'saleCommission' => [
                    '*' => [
                        'id',
                        'userId',
                        'saleCommission',
                        'user',
                        'totalPayableCommission',
                        'commissionReduceForReturn',
                        'remainingCommission',
                        'paidCommission',
                    ],
                ],
                'transaction',
            ]);
    }
}
