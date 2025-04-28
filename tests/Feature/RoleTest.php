<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RoleTest extends TestCase
{
    public function test_create_single_role(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/role', [
            "name" => "viewer"
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'name',
                'updatedAt',
                'createdAt',
                'id',
            ]);
    }

    public function test_create_many_role(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/role?query=createmany', [
            [
                "name" => "HR"
            ],
            [
                "name" => "employee3"
            ],
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'count'
            ]);
    }

    public function test_get_all_role(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/role?query=all');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllRole' => [
                    '*' => [
                        'id',
                        'name',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'rolePermission'
                    ]
                ],
                'totalRole'
            ]);
    }

    public function test_get_all_role_paginated_true(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/role?status=true&page=1&count=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllRole' => [
                    '*' => [
                        'id',
                        'name',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'rolePermission'
                    ]
                ],
                'totalRole'
            ]);
    }

    public function test_get_all_role_paginated_false(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/role?status=false&page=1&count=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllRole' => [
                    '*' => [
                        'id',
                        'name',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'rolePermission'
                    ]
                ],
                'totalRole'
            ]);
    }

    public function test_get_single_role(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('role/1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'status',
                'createdAt',
                'updatedAt',
                'rolePermission',
            ]);
    }

    public function test_search_role(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/role?query=search&page=1&count=10&key=HR');

        $response->assertStatus(200);
    }

    public function test_update_role(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->put('/role/1', [
            "name" => "super admin"
        ]);

        $response->assertStatus(200);
    }

    public function test_change_status_role(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->patch('/role/1', [
            "status" => "false"
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_single_role(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->delete('/role/1');

        $response->assertStatus(200);
    }

    public function test_delete_many_role(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->post('/role?query=deletemany', [
            1, 2
        ]);

        $response->assertStatus(200);
    }
}
