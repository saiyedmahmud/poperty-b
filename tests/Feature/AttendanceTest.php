<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AttendanceTest extends TestCase
{
    public function test_create_attendance(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/attendance', [
            "userId" => 1,
            "comment" => "",
            "ip" => "10.10.10.10"
        ]);

        $response->assertStatus(201);
    }

    public function test_create_attendance_manual(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/attendance?query=manualPunch', [
            "userId" => 1,
            "inTime" => "2023-04-16T09:00:00.000Z",
            "outTime" => "2023-04-16T18:00:00.000Z",
            "comment" => "mistake",
            "ip" => "10.10.10.10"
        ]);

        $response->assertStatus(201);
    }

    public function test_get_all_attendance(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/attendance?query=all');

        $response->assertStatus(200);
    }

    public function test_get_all_attendance_paginated(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/attendance?page=1&count=20&startdate=2023-03-30&enddate=2023-04-30');

        $response->assertStatus(200);
    }

    public function test_get_single_attendance(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('attendance/1');

        $response->assertStatus(200);
    }

    public function test_get_attendance_by_user_id(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/attendance/1/user');

        $response->assertStatus(200);
    }

    public function test_get_attendance_by_user_id_paginated(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/attendance/1/user?page=1&count=10');

        $response->assertStatus(200);
    }

    public function test_get_attendance_by_last_user(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/attendance/1/last');

        $response->assertStatus(200);
    }
}
