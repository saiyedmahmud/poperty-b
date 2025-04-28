<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AwardHistoryTest extends TestCase
{
    public function test_create_single_award_history(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/award-history', [
            "userId" => 1,
            "awardId" => 1,
            "awardedDate" => "2020-03-19T14:21:00",
            "comment" => "new employee"
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'userId',
                'awardId',
                'awardedDate',
                'comment',
                'updatedAt',
                'createdAt',
                'id',
            ]);
    }

    public function test_get_all_award_history(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/award-history?query=all');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'userId',
                    'awardId',
                    'awardedDate',
                    'comment',
                    'status',
                    'createdAt',
                    'updatedAt',
                ],
            ]);
    }

    public function test_get_single_award_history(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('award-history/1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'userId',
                'awardId',
                'awardedDate',
                'comment',
                'status',
                'createdAt',
                'updatedAt',
            ]);
    }

    public function test_update_award_history(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->put('/award-history/1', [
            "awardId" => 1,
            "awardedDate" => "2024-03-19T14:21:00",
            "comment" => "new employessse updated"
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_single_award_history(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->delete('/award-history/1');

        $response->assertStatus(200);
    }

    public function test_delete_many_award_history(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->post('/award-history?query=deletemany', [
            1, 2
        ]);

        $response->assertStatus(200);
    }
}
