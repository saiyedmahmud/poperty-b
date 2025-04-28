<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PermissionTest extends TestCase
{
    public function test_get_all_permission(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/permission?query=all');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'createdAt',
                    'updatedAt'
                ]
            ]);
    }

    public function test_get_all_permission_paginated(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/permission?page=1&count=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'createdAt',
                    'updatedAt'
                ]
            ]);
    }
}
