<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DepartmentTest extends TestCase
{
    public function test_create_single_department(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/department', [
            "name" => "dept1"
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'name',
                'updatedAt',
                'createdAt',
                'id',
            ]);
    }

    public function test_create_many_department(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/department?query=createmany', [
            [
                "name" => "dept11"
            ],
            [
                "name" => "dept22"
            ],
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                '*' => [
                    'name',
                    'updatedAt',
                    'createdAt',
                    'id',
                ],
            ]);
    }

    public function test_get_all_department(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/department?query=all');

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

    public function test_get_all_department_paginated(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/department?page=1&count=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllDepartment' => [
                    '*' => [
                        'id',
                        'name',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'user',
                    ],
                ],
                'totalDepartment',
            ]);
    }

    public function test_get_single_department(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('department/1');

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

    public function test_update_department(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->put('/department/1', [
            "name" => "dept1 up"
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_single_department(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->patch('/department/1', [
            "status" => "false"
        ]);

        $response->assertStatus(200);
    }
}
