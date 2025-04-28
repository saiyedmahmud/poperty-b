<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class QuoteTest extends TestCase
{
    public function test_create_single_quote(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/quote', [
            "quoteOwnerId" => 1,
            "quoteName" => "hello",
            "quoteDate" => "2023-05-24T14:21:00",
            "expirationDate" => "2023-08-24T14:21:00",
            "termsAndConditions" => "test",
            "description" => "test",
            "quoteProduct" => [
                [
                    "productId" => 1,
                    "productQuantity" => 1,
                    "unitPrice" => 150
                ],
                [
                    "productId" => 2,
                    "productQuantity" => 1,
                    "unitPrice" => 50
                ]
            ],
            "discount" => 50
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'quoteOwnerId',
                'quoteName',
                'quoteDate',
                'expirationDate',
                'termsAndConditions',
                'description',
                'discount',
                'totalAmount',
                'updatedAt',
                'createdAt',
                'id'
            ]);
    }

    public function test_get_all_quote(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/quote?page=1&count=10&status=true');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllQuote' => [
                    '*' => [
                        'id',
                        'quoteOwnerId',
                        'quoteName',
                        'quoteDate',
                        'expirationDate',
                        'termsAndConditions',
                        'description',
                        'discount',
                        'totalAmount',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'quoteOwner' => [
                            'id',
                            'username'
                        ]
                    ]
                ],
                'totalQuote'
            ]);
    }

    public function test_get_filtered_quote_paginated_by_quote_owner_id(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/quote?status=true&page=1&count=20&quoteOwner=1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllQuote' => [
                    '*' => [
                        'id',
                        'quoteOwnerId',
                        'quoteName',
                        'quoteDate',
                        'expirationDate',
                        'termsAndConditions',
                        'description',
                        'discount',
                        'totalAmount',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'quoteOwner' => [
                            'id',
                            'username'
                        ]
                    ]
                ],
                'totalQuote'
            ]);
    }

    public function test_get_search_quote_paginated(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/quote?page=1&count=5&query=search&key=h');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllQuote' => [
                    '*' => [
                        'id',
                        'quoteOwnerId',
                        'quoteName',
                        'quoteDate',
                        'expirationDate',
                        'termsAndConditions',
                        'description',
                        'discount',
                        'totalAmount',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'quoteOwner' => [
                            'id',
                            'username'
                        ]
                    ]
                ],
                'totalQuote'
            ]);
    }

    public function test_get_single_quote(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/quote/1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'quoteOwnerId',
                'quoteName',
                'quoteDate',
                'expirationDate',
                'termsAndConditions',
                'description',
                'discount',
                'totalAmount',
                'status',
                'createdAt',
                'updatedAt',
                'quoteOwner' => [
                    'id',
                    'username'
                ],
                'quoteProduct' => [
                    '*' => [
                        'id',
                        'quoteId',
                        'productId',
                        'unitPrice',
                        'productQuantity',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'product' => []
                    ]
                ]
            ]);
    }

    public function test_update_quote(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->put('/quote/1', [
            "quoteOwnerId" => 1,
            "quoteName" => "hello updated",
            "quoteDate" => "2023-05-24T14:21:00",
            "expirationDate" => "2023-08-24T14:21:00",
            "termsAndConditions" => "test updated",
            "description" => "test updated",
            "quoteProduct" => [
                [
                    "productId" => 1,
                    "productQuantity" => 1,
                    "unitPrice" => 200
                ],
                [
                    "productId" => 2,
                    "productQuantity" => 1,
                    "unitPrice" => 300
                ]
            ],
            "discount" => 50
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_single_quote(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->patch('/quote/1', [
            "status" => "false"
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_many_quote(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->post('/quote?query=deletemany', [
            2, 3
        ]);

        $response->assertStatus(200);
    }
}
