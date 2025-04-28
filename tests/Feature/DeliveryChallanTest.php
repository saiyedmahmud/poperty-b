<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeliveryChallanTest extends TestCase
{
    public function test_create_single_delivery_challan(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/delivery-challan', [
            "saleInvoiceId" => "S_9R8HDLECSYM7K",
            "challanDate" => "2023-05-24T14:21:00",
            "challanNote" => "be safe",
            "vehicleNo" => "156-981",
            "challanProduct" => [
                [
                    "productId" => 1,
                    "productQty" => 5
                ]
            ]
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'saleInvoiceId',
                'challanNo',
                'challanDate',
                'challanNote',
                'vehicleNo',
                'updatedAt',
                'createdAt',
                'id',
            ]);
    }

    public function test_get_all_delivery_challan(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/delivery-challan?query=all');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'saleInvoiceId',
                    'challanNo',
                    'challanDate',
                    'challanNote',
                    'vehicleNo',
                    'status',
                    'createdAt',
                    'updatedAt',
                    'saleInvoice',
                    'deliveryChallanProduct' => [
                        '*' => [
                            'id',
                            'deliveryChallanId',
                            'productId',
                            'quantity',
                            'createdAt',
                            'updatedAt',
                            'product',
                        ],
                    ],
                ],
            ]);
    }

    public function test_get_all_delivery_challan_paginated_true(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/delivery-challan?status=true&page=1&count=5');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllDeliveryChallan' => [
                    '*' => [
                        'id',
                        'saleInvoiceId',
                        'challanNo',
                        'challanDate',
                        'challanNote',
                        'vehicleNo',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'saleInvoice',
                        'deliveryChallanProduct' => [
                            '*' => [
                                'id',
                                'deliveryChallanId',
                                'productId',
                                'quantity',
                                'createdAt',
                                'updatedAt',
                                'product',
                            ],
                        ],
                    ],
                ],
                'totalDeliveryChallan',
            ]);
    }

    public function test_get_all_delivery_challan_paginated_false(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/delivery-challan?status=false&page=1&count=5');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllDeliveryChallan' => [
                    '*' => [
                        'id',
                        'saleInvoiceId',
                        'challanNo',
                        'challanDate',
                        'challanNote',
                        'vehicleNo',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'saleInvoice',
                        'deliveryChallanProduct' => [
                            '*' => [
                                'id',
                                'deliveryChallanId',
                                'productId',
                                'quantity',
                                'createdAt',
                                'updatedAt',
                                'product',
                            ],
                        ],
                    ],
                ],
                'totalDeliveryChallan',
            ]);
    }

    public function test_get_search_delivery_challan(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/delivery-challan?query=search&key=1&page=1&count=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllDeliveryChallan' => [
                    '*' => [
                        'id',
                        'saleInvoiceId',
                        'challanNo',
                        'challanDate',
                        'challanNote',
                        'vehicleNo',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'saleInvoice',
                        'deliveryChallanProduct' => [
                            '*' => [
                                'id',
                                'deliveryChallanId',
                                'productId',
                                'quantity',
                                'createdAt',
                                'updatedAt',
                                'product',
                            ],
                        ],
                    ],
                ],
                'totalDeliveryChallan',
            ]);
    }

    public function test_get_single_delivery_challan(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/delivery-challan/1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'saleInvoiceId',
                'challanNo',
                'challanDate',
                'challanNote',
                'vehicleNo',
                'status',
                'createdAt',
                'updatedAt',
                'saleInvoice',
                'deliveryChallanProduct' => [
                    '*' => [
                        'id',
                        'deliveryChallanId',
                        'productId',
                        'quantity',
                        'createdAt',
                        'updatedAt',
                        'product',
                    ],
                ],
            ]);
    }

    public function test_delete_single_delivery_challan(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->patch('/delivery-challan/1', [
            "status" => "false"
        ]);

        $response->assertStatus(200);
    }
}
