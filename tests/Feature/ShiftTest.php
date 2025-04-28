<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShiftTest extends TestCase
{
    public function test_create_single_shift(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/shift', [
            "name" => "test000",
            "startTime" => "02:00:00.000Z",
            "endTime" => "04:00:00.000Z"
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'name',
                'startTime',
                'endTime',
                'workHour',
                'updatedAt',
                'createdAt',
                'id',
            ]);
    }

    public function test_create_many_shift(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/shift?query=createmany', [
            [
                "name" => "test1",
                "startTime" => "02:00:00.000Z",
                "endTime" => "04:00:00.000Z"
            ],
            [
                "name" => "test2",
                "startTime" => "02:00:00.000Z",
                "endTime" => "04:00:00.000Z"
            ],
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                '*' => [
                    'name',
                    'startTime',
                    'endTime',
                    'updatedAt',
                    'createdAt',
                    'id',
                ],
            ]);
    }

    public function test_get_all_shift(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/shift?query=all');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'startTime',
                    'endTime',
                    'workHour',
                    'status',
                    'createdAt',
                    'updatedAt',
                ],
            ]);
    }

    public function test_get_all_shift_paginated(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/shift?page=1&count=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllShift' => [
                    '*' => [
                        'id',
                        'name',
                        'startTime',
                        'endTime',
                        'workHour',
                        'status',
                        'createdAt',
                        'updatedAt',
                    ],
                ],
                'totalShift',
            ]);
    }

    public function test_get_single_shift(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('shift/1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'startTime',
                'endTime',
                'workHour',
                'status',
                'createdAt',
                'updatedAt',
                'user',
            ]);
    }

    public function test_update_shift(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->put('/shift/1', [
            "name" => "test000 up",
            "startTime" => "02:00:00.000Z",
            "endTime" => "04:00:00.000Z"
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_single_shift(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->patch('/shift/1', [
            "status" => "false"
        ]);

        $response->assertStatus(200);
    }
}
