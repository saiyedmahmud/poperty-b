<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WeeklyHolidayTest extends TestCase
{
    public function test_create_single_weekly_holiday(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/weekly-holiday', [
            "name" => "Saturday-Friday",
            "startDay" => "Saturday",
            "endDay" => "Friday"
        ]);

        $response->assertStatus(201);
    }

    public function test_create_many_weekly_holiday(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/weekly-holiday?query=createmany', [
            [
                "name" => "Saturday-Friday",
                "startDay" => "Saturday",
                "endDay" => "Friday"
            ],
            [
                "name" => "Saturday-Thursday",
                "startDay" => "Saturday",
                "endDay" => "Thursday"
            ],
        ]);

        $response->assertStatus(201);
    }

    public function test_get_all_weekly_holiday(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/weekly-holiday?query=all');

        $response->assertStatus(200);
    }

    public function test_get_all_weekly_holiday_paginated_true(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/weekly-holiday?status=true&page=1&count=10');

        $response->assertStatus(200);
    }

    public function test_get_all_weekly_holiday_paginated_false(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/weekly-holiday?status=false&page=1&count=10');

        $response->assertStatus(200);
    }

    public function test_get_single_weekly_holiday(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('weekly-holiday/1');

        $response->assertStatus(200);
    }

    public function test_update_weekly_holiday(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->put('/weekly-holiday/1', [
            "name" => "Saturday-Saturday",
            "startDay" => "Saturday",
            "endDay" => "Saturday"
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_single_weekly_holiday(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->delete('/weekly-holiday/1');

        $response->assertStatus(200);
    }

    public function test_delete_many_weekly_holiday(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->post('/weekly-holiday?query=deletemany', [
            1, 2
        ]);

        $response->assertStatus(200);
    }
}
