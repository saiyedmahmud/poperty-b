<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EmploymentStatusTest extends TestCase
{
    public function test_create_single_employment_status(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken(),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ])->postJson('/employment-status', [
            "name" => "white",
            "colourValue" => "#ffffff",
            "description" => "the white"
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'name',
                'colourValue',
                'description',
                'updatedAt',
                'createdAt',
                'id',
            ]);
    }

    public function test_create_many_employment_status(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/employment-status?query=createmany', [
            [
                "name" => "green",
                "colourValue" => "#00FF00",
                "description" => "the green"
            ],
            [
                "name" => "blue",
                "colourValue" => "#000fff",
                "description" => "the blue"
            ]
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                '*' => [
                    'name',
                    'colourValue',
                    'description',
                    'updatedAt',
                    'createdAt',
                    'id',
                ],
            ]);
    }

    public function test_get_all_employment_status(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/employment-status?query=all');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'colourValue',
                    'description',
                    'status',
                    'createdAt',
                    'updatedAt',
                ],
            ]);
    }

    public function test_get_all_employment_status_paginated_true(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/employment-status?status=true&page=1&count=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllEmploymentStatus' => [
                    '*' => [
                        'id',
                        'name',
                        'colourValue',
                        'description',
                        'status',
                        'createdAt',
                        'updatedAt',
                    ],
                ],
                'totalEmploymentStatus',
            ]);
    }

    public function test_get_all_employment_status_paginated_false(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/employment-status?status=false&page=1&count=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllEmploymentStatus' => [
                    '*' => [
                        'id',
                        'name',
                        'colourValue',
                        'description',
                        'status',
                        'createdAt',
                        'updatedAt',
                    ],
                ],
                'totalEmploymentStatus',
            ]);
    }

    public function test_get_single_employment_status(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('employment-status/1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'colourValue',
                'description',
                'status',
                'createdAt',
                'updatedAt',
                'user',
            ]);
    }

    public function test_delete_single_employment_status(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->patch('/employment-status/1', [
            "status" => "false"
        ]);

        $response->assertStatus(200);
    }
}
