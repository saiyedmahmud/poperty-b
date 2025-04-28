<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UomTest extends TestCase
{
    public function test_create_single_uom(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/uom', [
            "name" => "packet"
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'name',
                'updatedAt',
                'createdAt',
                'id'
            ]);
    }

    public function test_get_all_uom(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/uom?query=all');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'status',
                    'createdAt',
                    'updatedAt'
                ]
            ]);
    }

    public function test_get_all_uom_paginated(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/uom?status=true&count=10&page=1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllUoM' => [
                    '*' => [
                        'id',
                        'name',
                        'status',
                        'createdAt',
                        'updatedAt',
                    ],
                ],
                'totalUoM',
            ]);
    }

    public function test_get_single_uom(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/uom/1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'name',
                'status',
                'updatedAt',
                'createdAt',
                'id'
            ]);
    }

    public function test_update_uom(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->put('/uom/1', [
            "name" => "uom up"
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_uom(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->patch('/uom/1', [
            "status" => "false"
        ]);

        $response->assertStatus(200);
    }
}
