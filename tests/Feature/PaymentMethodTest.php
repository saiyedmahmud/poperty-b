<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PaymentMethodTest extends TestCase
{
    public function test_create_single_payment_method(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken(),
            'Content-Type' => 'multipart/form-data',
        ])->postJson('/payment-method', [
            "methodName" => "rocket",
            "subAccountId" => 19,
            "ownerAccount" => 23456786575,
            "instruction" => "hello hello",
            "images[]" => "",
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'subAccountId',
                'methodName',
                'logo',
                'ownerAccount',
                'instruction',
                'updatedAt',
                'createdAt',
                'id'
            ]);
    }

    public function test_get_all_payment_method(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/payment-method?query=all');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'subAccountId',
                    'methodName',
                    'logo',
                    'ownerAccount',
                    'instruction',
                    'isActive',
                    'status',
                    'createdAt',
                    'updatedAt',
                    'subAccount'
                ]
            ]);
    }

    public function test_get_all_payment_method_paginated_true(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/payment-method?status=true&count=10&page=1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllPaymentMethod' => [
                    '*' => [
                        'id',
                        'subAccountId',
                        'methodName',
                        'logo',
                        'ownerAccount',
                        'instruction',
                        'isActive',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'subAccount'
                    ]
                ],
                'totalPaymentMethod'
            ]);
    }

    public function test_get_all_payment_method_paginated_false(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/payment-method?status=false&count=10&page=1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllPaymentMethod' => [
                    '*' => [
                        'id',
                        'subAccountId',
                        'methodName',
                        'logo',
                        'ownerAccount',
                        'instruction',
                        'isActive',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'subAccount'
                    ]
                ],
                'totalPaymentMethod'
            ]);
    }

    public function test_get_search_payment_method(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/payment-method?query=search&count=10&page=1&key=cash');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllPaymentMethod' => [
                    '*' => [
                        'id',
                        'subAccountId',
                        'methodName',
                        'logo',
                        'ownerAccount',
                        'instruction',
                        'isActive',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'subAccount'
                    ]
                ],
                'totalPaymentMethod'
            ]);
    }

    public function test_update_payment_method(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->put('/payment-method/2', [
            "isActive" => "false"
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_single_payment_method(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->patch('/payment-method/2', [
            "status" => "false"
        ]);

        $response->assertStatus(200);
    }
}
