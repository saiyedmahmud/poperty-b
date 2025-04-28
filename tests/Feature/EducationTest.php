<?php

namespace Tests\Feature;

use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EducationTest extends TestCase
{
    public function test_education_create_single(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/education', [
            "userId" => 1,
            "degree" => "Bachelor's",
            "institution" => "Example University",
            "fieldOfStudy" => "Computer Science",
            "result" => "3.5 GPA",
            "studyStartDate" => "2018-09-01"
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'userId',
                'degree',
                'institution',
                'fieldOfStudy',
                'result',
                'studyStartDate',
                'studyEndDate',
                'updatedAt',
                'createdAt',
                'id'
            ]);
    }


    public function test_education_create_many(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/education?query=createmany', [
            [
                "userId" => 1,
                "degree" => "BBS",
                "institution" => "Example University",
                "fieldOfStudy" => "Computer Science",
                "result" => "3.5 GPA",
                "studyStartDate" => "2018-09-01"
            ],
            [
                "userId" => 1,
                "degree" => "BBA",
                "institution" => "Example University",
                "fieldOfStudy" => "Computer Science",
                "result" => "3.5 GPA",
                "studyStartDate" => "2018-09-01"
            ],
            [
                "userId" => 1,
                "degree" => "CSE",
                "institution" => "Example University",
                "fieldOfStudy" => "Computer Science",
                "result" => "3.5 GPA",
                "studyStartDate" => "2018-09-01"
            ]
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'userId',
                    'degree',
                    'institution',
                    'fieldOfStudy',
                    'result',
                    'studyStartDate',
                    'studyEndDate',
                    'createdAt',
                    'updatedAt',
                ]
            ]);
    }

    public function test_get_all_education(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/education?query=all');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'userId',
                    'degree',
                    'institution',
                    'fieldOfStudy',
                    'result',
                    'studyStartDate',
                    'studyEndDate',
                    'status',
                    'createdAt',
                    'updatedAt',
                ]
            ]);
    }

    public function test_get_all_education_paginated_true(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/education?status=true&page=1&count=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'userId',
                    'degree',
                    'institution',
                    'fieldOfStudy',
                    'result',
                    'studyStartDate',
                    'studyEndDate',
                    'status',
                    'createdAt',
                    'updatedAt',
                ]
            ]);
    }

    public function test_get_all_education_paginated_false(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/education?status=false&page=1&count=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'userId',
                    'degree',
                    'institution',
                    'fieldOfStudy',
                    'result',
                    'studyStartDate',
                    'studyEndDate',
                    'status',
                    'createdAt',
                    'updatedAt',
                ]
            ]);
    }


    public function test_get_single_education(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('education/1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'userId',
                'degree',
                'institution',
                'fieldOfStudy',
                'result',
                'studyStartDate',
                'studyEndDate',
                'createdAt',
                'updatedAt',
            ]);
    }

    public function test_update_education(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' .  $this->getToken()
        ])->put('/education/1', [
            "userId" => 1,
            "degree" => "Bachelor's up",
            "institution" => "Example University",
            "fieldOfStudy" => "Computer Science",
            "result" => "3.5 GPA",
            "studyStartDate" => "2018-09-01"
        ]);

        $response->assertStatus(200);
    }

    public function test_change_status_education(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' .  $this->getToken()
        ])->put('/education/1?query=status', [
            "status" => "false"
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_single_education(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' .  $this->getToken()
        ])->delete('/education/1');

        $response->assertStatus(200);
    }

    public function test_delete_many_education(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' .  $this->getToken()
        ])->post('/education?query=deletemany', [
            1, 2
        ]);

        $response->assertStatus(200);
    }
}
