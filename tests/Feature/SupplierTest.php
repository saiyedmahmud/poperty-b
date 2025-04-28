<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SupplierTest extends TestCase
{
    public function test_create_single_supplier(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/supplier', [
            "name" => "suppas 5",
            "phone" => "01788a88889",
            "address" => "ulala@gmail.com",
            "email" => "ulala@gmail.com"
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'name',
                'phone',
                'address',
                'email',
                'updatedAt',
                'createdAt',
                'id',
            ]);
    }

    public function test_create_many_supplier(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/supplier?query=createmany', [
            [
                "name" => "supplier 12",
                "phone" => "017888880284"
            ],
            [
                "name" => "supplier 2",
                "phone" => "0178888885"
            ],
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'count',
            ]);
    }

    public function test_get_all_supplier(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/supplier?query=all');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'phone',
                    'address',
                    'email',
                    'status',
                    'createdAt',
                    'updatedAt',
                    'purchaseInvoice',
                ],
            ]);
    }

    public function test_get_all_supplier_paginated_true(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/supplier?status=true&page=1&count=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllSupplier' => [
                    '*' => [
                        'id',
                        'name',
                        'phone',
                        'address',
                        'email',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'purchaseInvoice',
                    ],
                ],
                'totalSupplier',
            ]);
    }

    public function test_get_all_supplier_paginated_false(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/supplier?status=false&page=1&count=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllSupplier' => [
                    '*' => [
                        'id',
                        'name',
                        'phone',
                        'address',
                        'email',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'purchaseInvoice',
                    ],
                ],
                'totalSupplier',
            ]);
    }

    public function test_get_single_supplier(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('supplier/1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'phone',
                'address',
                'email',
                'status',
                'createdAt',
                'updatedAt',
                'totalAmount',
                'totalPaidAmount',
                'totalReturnAmount',
                'instantPaidReturnAmount',
                'dueAmount',
                'totalPurchaseInvoice',
                'totalReturnPurchaseInvoice',
                'allTransaction',
                'returnPurchaseInvoice',
                'purchaseInvoice',
            ]);
    }

    public function test_search_supplier(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/supplier?query=search&page=1&count=20&key=samsung');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllSupplier' => [
                    '*' => [
                        'id',
                        'name',
                        'phone',
                        'address',
                        'email',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'purchaseInvoice',
                    ],
                ],
                'totalSupplier',
            ]);
    }

    public function test_info_supplier(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/supplier?query=info');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'count' => [
                    'id',
                ],
            ]);
    }

    public function test_get_report_supplier(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/supplier?status=true,false&page=1&count=20&query=report');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'grandData' => [
                    'grandTotalAmount',
                    'grandTotalPaidAmount',
                    'grandTotalReturnAmount',
                    'grandInstantPaidReturnAmount',
                    'grandDueAmount',
                ],
                'allSupplier' => [
                    '*' => [
                        'id',
                        'name',
                        'phone',
                        'address',
                        'email',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'totalAmount',
                        'totalPaidAmount',
                        'totalReturnAmount',
                        'instantPaidReturnAmount',
                        'dueAmount',
                        'purchaseInvoice',
                    ],
                ],
            ]);
    }

    public function test_update_supplier(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->put('/supplier/1', [
            "name" => "up up oooo"
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_single_supplier(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->patch('/supplier/1', [
            "status" => "false"
        ]);

        $response->assertStatus(200);
    }
}
