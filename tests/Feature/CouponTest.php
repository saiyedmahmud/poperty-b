<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CouponTest extends TestCase
{
    public function test_create_single_coupon(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/coupon', [
            "couponCode" => "SAK88",
            "type" => "percentage",
            "value" => 10,
            "startDate" => "2024-01-25T00:00:00",
            "endDate" => "2024-12-30T00:00:00"
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'couponCode',
                'type',
                'value',
                'startDate',
                'endDate',
                'updatedAt',
                'createdAt',
                'id'
            ]);
    }

    public function test_get_all_coupon(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/coupon?query=all');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllCoupon' => [
                    '*' => [
                        'id',
                        'couponCode',
                        'type',
                        'value',
                        'startDate',
                        'endDate',
                        'status',
                        'createdAt',
                        'updatedAt'
                    ]
                ],
                'totalCoupon'
            ]);
    }

    public function test_get_all_coupon_paginated(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/coupon?page=1&count=10&status=true&startDate=2024-05-24&endDate=2025-05-29');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllCoupon' => [
                    '*' => [
                        'id',
                        'couponCode',
                        'type',
                        'value',
                        'startDate',
                        'endDate',
                        'status',
                        'createdAt',
                        'updatedAt'
                    ]
                ],
                'totalCoupon'
            ]);
    }

    public function test_get_all_valid_coupon(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/coupon/valid');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'couponCode',
                    'type',
                    'value',
                    'startDate',
                    'endDate',
                    'status',
                    'createdAt',
                    'updatedAt'
                ]
            ]);
    }

    public function test_get_valid_single_coupon(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/coupon/valid/SAK88');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'couponCode',
                'type',
                'value',
                'startDate',
                'endDate',
                'status',
                'createdAt',
                'updatedAt'
            ]);
    }

    public function test_get_single_coupon(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/coupon/1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'couponCode',
                'type',
                'value',
                'startDate',
                'endDate',
                'status',
                'createdAt',
                'updatedAt'
            ]);
    }

    public function test_update_coupon(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->put('/coupon/1', [
            "couponCode" => "SAK88",
            "type" => "flat",
            "value" => 60,
            "startDate" => "2023-11-03T18:00:00.000Z",
            "endDate" => "2023-12-30T18:00:00.000Z"
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_single_coupon(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->patch('/coupon/1', [
            "status" => "false"
        ]);

        $response->assertStatus(200);
    }
}
