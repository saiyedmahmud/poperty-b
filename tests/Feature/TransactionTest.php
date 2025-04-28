<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    public function test_create_transaction(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/transaction', [
            "date" => "2022-09-09T04:15:24.880Z",
            "debitId" => 16,
            "creditId" => 1,
            "amount" => 2000,
            "particulars" => "vat pay"
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                "date",
                "debitId",
                "creditId",
                "amount",
                "particulars",
                "updated_at",
                "created_at",
                "id"
            ]);
    }

    public function test_get_date_wise_paginated_transaction(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/transaction?page=1&count=20&startDate=2022-09-01&endDate=2024-12-31&status=true');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'aggregations' => [
                    '_count' => ['id'],
                    '_sum' => ['amount'],
                ],
                'getAllTransaction' => [
                    '*' => [
                        'id',
                        'date',
                        'debitId',
                        'creditId',
                        'particulars',
                        'amount',
                        'type',
                        'relatedId',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'debit' => [
                            'id',
                            'name',
                            'accountId',
                            'status',
                            'createdAt',
                            'updatedAt',
                        ],
                        'credit' => [
                            'id',
                            'name',
                            'accountId',
                            'status',
                            'createdAt',
                            'updatedAt',
                        ],
                    ],
                ],
                'totalTransaction',
            ]);
    }

    public function test_get_date_wise_paginated_inactive_transaction(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/transaction?query=inactive&page=1&count=20&startDate=2022-09-01&endDate=2023-12-31');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'aggregations' => [
                    '_count' => ['id'],
                    '_sum' => ['amount'],
                ],
                'getAllTransaction' => [
                    '*' => [
                        'id',
                        'date',
                        'debitId',
                        'creditId',
                        'particulars',
                        'amount',
                        'type',
                        'relatedId',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'debit' => [
                            'id',
                            'name',
                            'accountId',
                            'status',
                            'createdAt',
                            'updatedAt',
                        ],
                        'credit' => [
                            'id',
                            'name',
                            'accountId',
                            'status',
                            'createdAt',
                            'updatedAt',
                        ],
                    ],
                ],
                'totalTransaction',
            ]);
    }

    public function test_get_info_all_active_transaction(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/transaction?query=info');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '_count' => ['id'],
                '_sum' => ['amount'],
            ]);
    }

    public function test_get_all_transaction(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/transaction?query=all');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'date',
                    'debitId',
                    'creditId',
                    'particulars',
                    'amount',
                    'type',
                    'relatedId',
                    'status',
                    'createdAt',
                    'updatedAt',
                    'debit' => [
                        'id',
                        'name',
                        'accountId',
                        'status',
                        'createdAt',
                        'updatedAt',
                    ],
                    'credit' => [
                        'id',
                        'name',
                        'accountId',
                        'status',
                        'createdAt',
                        'updatedAt',
                    ],
                ]
            ]);
    }

    public function test_get_single_transaction(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/transaction/1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'date',
                'debitId',
                'creditId',
                'particulars',
                'amount',
                'type',
                'relatedId',
                'status',
                'createdAt',
                'updatedAt',
                'debit' => [
                    'id',
                    'name',
                ],
                'credit' => [
                    'id',
                    'name',
                ],
            ]);
    }

    public function test_get_search_transaction(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/transaction?query=search&transaction=1&page=1&count=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllTransaction' => [
                    '*' => [
                        'id',
                        'date',
                        'debitId',
                        'creditId',
                        'particulars',
                        'amount',
                        'type',
                        'relatedId',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'debit' => [
                            'id',
                            'name',
                            'accountId',
                            'status',
                            'createdAt',
                            'updatedAt',
                        ],
                        'credit' => [
                            'id',
                            'name',
                            'accountId',
                            'status',
                            'createdAt',
                            'updatedAt',
                        ],
                    ],
                ],
                'totalTransaction',
            ]);
    }

    public function test_delete_transaction(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->patch('/transaction/1', [
            "status" => "false"
        ]);

        $response->assertStatus(200);
    }
}
