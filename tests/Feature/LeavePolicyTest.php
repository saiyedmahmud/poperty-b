<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LeavePolicyTest extends TestCase
{
    public function test_create_single_leave_policy(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/leave-policy', [
            "name" => "Policy 10-12",
            "paidLeaveCount" => 10,
            "unpaidLeaveCount" => 12
        ]);

        $response->assertStatus(201);
    }

    public function test_create_many_leave_policy(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/leave-policy?query=createmany', [
            [
                "name" => "Policy 5-10",
                "paidLeaveCount" => 5,
                "unpaidLeaveCount" => 10
            ],
            [
                "name" => "Policy 10-10",
                "paidLeaveCount" => 10,
                "unpaidLeaveCount" => 10
            ],
            [
                "name" => "Policy 10-15",
                "paidLeaveCount" => 10,
                "unpaidLeaveCount" => 15
            ],
        ]);

        $response->assertStatus(201);
    }

    public function test_get_all_leave_policy(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/leave-policy?query=all');

        $response->assertStatus(200);
    }

    public function test_get_all_leave_policy_paginated_true(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/leave-policy?status=true&page=1&count=10');

        $response->assertStatus(200);
    }

    public function test_get_all_leave_policy_paginated_false(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/leave-policy?status=false&page=1&count=10');

        $response->assertStatus(200);
    }

    public function test_get_single_leave_policy(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('leave-policy/1');

        $response->assertStatus(200);
    }

    public function test_update_leave_policy(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->put('/leave-policy/1', [
            "name" => "Policy15-15",
            "paidLeaveCount" => 15,
            "unpaidLeaveCount" => 15
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_single_leave_policy(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->delete('/leave-policy/1');

        $response->assertStatus(200);
    }

    public function test_delete_many_leave_policy(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->post('/leave-policy?query=deletemany', [
            1, 2
        ]);

        $response->assertStatus(200);
    }
}
