<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AwardTest extends TestCase
{
    public function test_create_single_award(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/award', [
            "name" => "Bests employee of the Quarter",
            "description" => "best employee"
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'name',
                'description',
                'updatedAt',
                'createdAt',
                'id',
            ]);
    }

    public function test_create_many_award(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/award?query=createmany', [
            [
                "name" => "Bests employee of the Quarter1",
                "description" => "best employee1"
            ],
            [
                "name" => "Bests employee of the Quarter2",
                "description" => "best employee2"
            ],
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                '*' => [
                    'name',
                    'description',
                    'updatedAt',
                    'createdAt',
                    'id',
                ]
            ]);
    }

    public function test_get_all_award(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/award?query=all');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'description',
                    'image',
                    'status',
                    'createdAt',
                    'updatedAt',
                ]
            ]);
    }

    public function test_get_all_award_paginated(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/award?page=1&count=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllAward' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                        'image',
                        'status',
                        'createdAt',
                        'updatedAt',
                    ]
                ],
                'totalAward'
            ]);
    }

    public function test_get_all_award_paginated_true(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/award?status=true&page=1&count=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllAward' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                        'image',
                        'status',
                        'createdAt',
                        'updatedAt',
                    ]
                ],
                'totalAward'
            ]);
    }

    public function test_get_all_award_paginated_false(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/award?status=false&page=1&count=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllAward' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                        'image',
                        'status',
                        'createdAt',
                        'updatedAt',
                    ]
                ],
                'totalAward'
            ]);
    }

    public function test_get_single_award(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('award/1');

        $response->assertStatus(200);
    }

    public function test_update_award(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->put('/award/1', [
            "name" => "Bests employee of the Quarter updated",
            "description" => "best employee updated"
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_single_award(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->patch('/award/1', [
            "status" => "false"
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_many_award(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->post('/award?query=deletemany', [
            1, 2
        ]);

        $response->assertStatus(200);
    }
}
