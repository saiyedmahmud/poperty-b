<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DesignationTest extends TestCase
{
    public function test_create_single_designation(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/designation', [
            "name" => "des1"
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'name',
                'updatedAt',
                'createdAt',
                'id',
            ]);
    }

    public function test_create_many_designation(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/designation?query=createmany', [
            [
                "name" => "des1"
            ],
            [
                "name" => "des2"
            ],
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'count'
            ]);
    }

    public function test_get_all_designation(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/designation?query=all');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'status',
                    'createdAt',
                    'updatedAt',
                    'user',
                ],
            ]);
    }

    public function test_get_all_designation_paginated(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/designation?page=1&count=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllDesignation' => [
                    '*' => [
                        'id',
                        'name',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'user',
                    ],
                ],
                'totalDesignation',
            ]);
    }

    public function test_get_all_designation_paginated_true(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/designation?status=true&page=1&count=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllDesignation' => [
                    '*' => [
                        'id',
                        'name',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'user',
                    ],
                ],
                'totalDesignation',
            ]);
    }

    public function test_get_all_designation_paginated_false(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/designation?status=false&page=1&count=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllDesignation' => [
                    '*' => [
                        'id',
                        'name',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'user',
                    ],
                ],
                'totalDesignation',
            ]);
    }

    public function test_get_single_designation(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('designation/1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'status',
                'createdAt',
                'updatedAt',
                'user',
            ]);
    }

    public function test_search_designation(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/designation?query=search&page=1&count=10&key=manager');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllDesignation' => [
                    '*' => [
                        'id',
                        'name',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'user',
                    ],
                ],
                'totalDesignation',
            ]);
    }

    public function test_info_designation(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/designation?query=info');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '_count' => [
                    'id',
                ],
            ]);
    }

    public function test_update_designation(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->put('/designation/1', [
            "name" => "des1 up"
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_single_designation(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->patch('/designation/1', [
            "status" => "false"
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_many_designation(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->post('/designation?query=deletemany', [
            1, 2
        ]);

        $response->assertStatus(200);
    }
}
