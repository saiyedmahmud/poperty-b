<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdjustInventoryTest extends TestCase
{
    public function test_create_single_adjust_inventory_increment(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken(),
            'Content-Type' => 'application/json',
        ])->postJson('/adjust-inventory', [
            "userId" => 1,
            "storeId" => 1,
            "adjustType" => "increment",
            "creditId" => 9,
            "note" => "hello",
            "adjustInvoiceProduct" => [
                [
                    "productId" => 1,
                    "adjustQuantity" => 4
                ],
                [
                    "productId" => 2,
                    "adjustQuantity" => 9
                ]
            ]
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'date',
                'userId',
                'note',
                'updatedAt',
                'createdAt',
                'id'
            ]);
    }

    public function test_create_single_adjust_inventory_decrement(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken(),
            'Content-Type' => 'application/json',
        ])->postJson('/adjust-inventory', [
            "userId" => 1,
            "adjustType" => "decrement",
            "storeId" => 1,
            "debitId" => 9,
            "note" => "hello decrement",
            "adjustInvoiceProduct" => [
                [
                    "productId" => 1,
                    "adjustQuantity" => 4
                ],
                [
                    "productId" => 2,
                    "adjustQuantity" => 9
                ]
            ]
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'date',
                'userId',
                'note',
                'updatedAt',
                'createdAt',
                'id'
            ]);
    }

    public function test_get_all_adjust_inventory_paginated(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/adjust-inventory?startDate=2024-01-26&endDate=2025-03-28&page=1&count=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllAdjustInvoice' => [
                    '*' => [
                        'id',
                        'note',
                        'userId',
                        'date',
                        'createdAt',
                        'updatedAt',
                        'totalIncrementQuantity',
                        'totalIncrementPrice',
                        'totalDecrementQuantity',
                        'totalDecrementPrice',
                        'totalAdjustPrice'
                    ]
                ],
                'count' => [
                    'id'
                ]
            ]);
    }

    public function test_get_search_date_wise_adjust_inventory(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/adjust-inventory?query=search&key=AI_25UOJ26PYN7Z&startDate=2022-09-01&endDate=2029-09-01&page=1&count=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'adjustInvoice' => [
                    '*' => [
                        'id',
                        'note',
                        'userId',
                        'date',
                        'createdAt',
                        'updatedAt',
                        'totalIncrementQuantity',
                        'totalIncrementPrice',
                        'totalDecrementQuantity',
                        'totalDecrementPrice',
                        'totalAdjustPrice'
                    ]
                ]
            ]);
    }

    public function test_get_single_adjust_inventory(): void
    {

        //get the all adjust inventory
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/adjust-inventory?startDate=2024-01-26&endDate=2025-03-28&page=1&count=10');


        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/adjust-inventory/');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'note',
                'userId',
                'date',
                'createdAt',
                'updatedAt',
                'totalIncrementQuantity',
                'totalIncrementPrice',
                'totalDecrementQuantity',
                'totalDecrementPrice',
                'totalAdjustPrice',
                'user' => [
                    'id',
                    'firstName',
                    'lastName',
                    'username',
                ],
                'adjustInvoiceProduct' => [
                    '*' => [
                        'id',
                        'adjustInvoiceId',
                        'productId',
                        'adjustQuantity',
                        'adjustType',
                        'createdAt',
                        'updatedAt',
                        'product' => [],
                    ],
                ],
            ]);
    }

    public function test_get_report_adjust_inventory(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/adjust-inventory?query=report&type=INCREMENT,DECREMENT');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'totalGrandIncrementQuantity',
                'totalGrandIncrementPrice',
                'totalGrandDecrementQuantity',
                'totalGrandDecrementPrice',
                'totalGrandAdjustPrice',
                'allAdjustInvoice' => [
                    '*' => [
                        'id',
                        'note',
                        'userId',
                        'adjustType',
                        'date',
                        'createdAt',
                        'updatedAt',
                        'totalIncrementQuantity',
                        'totalIncrementPrice',
                        'totalDecrementQuantity',
                        'totalDecrementPrice',
                        'totalAdjustPrice',
                        'adjustInvoiceProduct',
                        'user'
                    ]
                ]
            ]);
    }
}
