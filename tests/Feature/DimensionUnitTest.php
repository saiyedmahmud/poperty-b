<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DimensionUnitTest extends TestCase
{
    public function test_create_single_dimension_unit(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/dimension-unit', [
            "name" => "kg"
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'name',
                'updatedAt',
                'createdAt',
                'id'
            ]);
    }

    public function test_get_all_dimension_unit(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/dimension-unit');

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

    public function test_update_dimension_unit(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->put('/dimension-unit/1', [
            "name" => "kg up"
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_single_dimension_unit(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->patch('/dimension-unit/1', [
            "status" => "false"
        ]);

        $response->assertStatus(200);
    }
}
