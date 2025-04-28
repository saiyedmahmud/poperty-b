<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WeightUnitTest extends TestCase
{
    public function test_create_single_weight_unit(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/weight-unit', [
            "name" => "kg"
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'name',
                'updatedAt',
                'createdAt'
            ]);
    }

    public function test_get_all_weight_unit(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/weight-unit');

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

    public function test_update_weight_unit(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->put('/weight-unit/1', [
            "name" => "kg up"
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_single_weight_unit(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->patch('/weight-unit/1', [
            "status" => "false"
        ]);

        $response->assertStatus(200);
    }
}
