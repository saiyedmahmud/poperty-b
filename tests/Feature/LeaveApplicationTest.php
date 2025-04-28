<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LeaveApplicationTest extends TestCase
{
    public function test_create_single_leave_application(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/leave-application', [
            "userId" => 1,
            "leaveType" => "PAID",
            "leaveFrom" => "2023-04-15T12:00:00.000Z",
            "leaveTo" => "2023-04-19T12:00:00.000Z",
            "reason" => "family problem"
        ]);

        $response->assertStatus(201);
    }

    public function test_get_all_leave_application(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/leave-application?query=all');

        $response->assertStatus(200);
    }

    public function test_get_all_leave_application_paginated_and_by_status(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/leave-application?page=1&count=10&status=ACCEPTED');

        $response->assertStatus(200);
    }

    public function test_get_single_leave_application(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('leave-application/1');

        $response->assertStatus(200);
    }

    public function test_get_history_leave_application_by_user_id(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/leave-application/1/leaveHistory?page=1&count=10');

        $response->assertStatus(200);
    }

    public function test_preview_leave_application(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->put('/leave-application/1', [
            "acceptLeaveFrom" => "2023-04-15T12:00:00.000Z",
            "acceptLeaveTo" => "2023-04-17T12:00:00.000Z",
            "reviewComment" => "test",
            "status" => "ACCEPTED"
        ]);

        $response->assertStatus(200);
    }
}
