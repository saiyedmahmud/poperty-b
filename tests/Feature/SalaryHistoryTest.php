<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SalaryHistoryTest extends TestCase
{
    public function test_create_single_salary_history(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/salary-history', [
            "userId" => 4,
            "salary" => 40000,
            "salaryStartDate" => "2020-03-19T08:21:00.000000Z",
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'userId',
                'salary',
                'startDate',
                'endDate',
                'comment',
                'updatedAt',
                'createdAt',
                'id',
            ]);
    }

    public function test_get_all_salary_history(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/salary-history?query=all');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'userId',
                    'salary',
                    'startDate',
                    'endDate',
                    'comment',
                    'status',
                    'createdAt',
                    'updatedAt',
                ]
            ]);
    }

    public function test_get_all_salary_history_paginated_true(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/salary-history?status=true&page=1&count=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'userId',
                    'salary',
                    'startDate',
                    'endDate',
                    'comment',
                    'status',
                    'createdAt',
                    'updatedAt',
                ]
            ]);
    }

    public function test_get_all_salary_history_paginated_false(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/salary-history?status=false&page=1&count=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'userId',
                    'salary',
                    'startDate',
                    'endDate',
                    'comment',
                    'status',
                    'createdAt',
                    'updatedAt',
                ]
            ]);
    }

    public function test_get_single_salary_history(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('salary-history/1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'userId',
                'salary',
                'startDate',
                'endDate',
                'comment',
                'status',
                'createdAt',
                'updatedAt',
                'user',
            ]);
    }

    public function test_update_salary_history(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->put('/salary-history/1', [
            "salary" => 12000,
            "salaryStartDate" => "2023-09-18 07:24:18",
            "salaryEndDate" => "2024-03-19T14:21:00",
            "salaryComment" => "new employee"
        ]);

        $response->assertStatus(200);
    }

    public function test_update_status_salary_history(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->put('/salary-history/1?query=status', [
            "status" => "false",
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_single_salary_history(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->delete('/salary-history/1');

        $response->assertStatus(200);
    }

    public function test_delete_many_salary_history(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->post('/salary-history?query=deletemany', [
            1, 2
        ]);

        $response->assertStatus(200);
    }
}
