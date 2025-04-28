<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DesignationHistoryTest extends TestCase
{
    public function test_create_single_designation_history(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/designation-history', [
            "userId" => 1,
            "designationId" => 1,
            "designationStartDate" => "2020-03-19T14:21:00"
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'userId',
                'designationId',
                'startDate',
                'endDate',
                'comment',
                'updatedAt',
                'createdAt',
                'id',
            ]);
    }

    public function test_get_all_designation_history(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/designation-history?query=all');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'userId',
                    'designationId',
                    'startDate',
                    'endDate',
                    'comment',
                    'status',
                    'createdAt',
                    'updatedAt',
                ],
            ]);
    }

    public function test_get_single_designation_history(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('designation-history/1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'userId',
                    'designationId',
                    'startDate',
                    'endDate',
                    'comment',
                    'updatedAt',
                    'createdAt',
                    'id',
                ],
            ]);
    }

    public function test_update_designation_history(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->put('/designation-history/1', [
            "designationId" => 1,
            "designationStartDate" => "2020-03-19T14:21:00",
            "designationStartDate" => null,
            "designationComment" => "new employee"
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_single_designation_history(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->delete('/designation-history/1');

        $response->assertStatus(200);
    }

    public function test_delete_many_designation_history(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->post('/designation-history?query=deletemany', [
            1, 2
        ]);

        $response->assertStatus(200);
    }
}
