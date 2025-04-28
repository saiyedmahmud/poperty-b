<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ManufacturerTest extends TestCase
{
    public function test_create_single_manufacturer(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/manufacturer', [
            "name" => "hello"
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'name',
                'updatedAt',
                'createdAt'
            ]);
    }

    public function test_get_all_manufacturer(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/manufacturer');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'updatedAt',
                    'createdAt'
                ]
            ]);
    }

    public function test_update_manufacturer(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->put('/manufacturer/1', [
            "name" => "hello up"
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_single_manufacturer(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->patch('/manufacturer/1', [
            "status" => "false"
        ]);

        $response->assertStatus(200);
    }
}
