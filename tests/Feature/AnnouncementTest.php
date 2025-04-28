<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AnnouncementTest extends TestCase
{
    public function test_create_single_announcement(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/announcement', [
            "title" => "test",
            "description" => "test test"
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'title',
                'description',
                'updatedAt',
                'createdAt',
                'id',
            ]);
    }

    public function test_get_all_announcement(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/announcement?query=all');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'title',
                    'description',
                    'status',
                    'createdAt',
                    'updatedAt',
                ],
            ]);
    }

    public function test_get_all_announcement_paginated_true(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/announcement?status=true&page=1&count=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllAnnouncement' => [
                    '*' => [
                        'id',
                        'title',
                        'description',
                        'status',
                        'createdAt',
                        'updatedAt',
                    ],
                ],
                'totalAnnouncement',
            ]);
    }

    public function test_get_all_announcement_paginated_false(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/announcement?status=false&page=1&count=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllAnnouncement' => [
                    '*' => [
                        'id',
                        'title',
                        'description',
                        'status',
                        'createdAt',
                        'updatedAt',
                    ],
                ],
                'totalAnnouncement',
            ]);
    }

    public function test_get_single_announcement(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('announcement/1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'title',
                'description',
                'status',
                'createdAt',
                'updatedAt',
            ]);
    }

    public function test_update_announcement(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->put('/announcement/1', [
            "title" => "test up",
            "description" => "test test up"
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_single_announcement(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->patch('/announcement/1', [
            "status" => "false"
        ]);

        $response->assertStatus(200);
    }
}
