<?php

namespace Tests\Feature;

use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UsersTest extends TestCase
{


    public function test_user_registration(): void
    {
        $faker = Factory::create();

        $response = $this->postJson('/user/register', [
            'firstName' => $faker->name,
            'lastName' => $faker->name,
            'username' => $faker->name,
            'password' => 'johndoe',
            'roleId' => 1,
            'email' => $faker->email,
            'phone' => $faker->phoneNumber,
            'street' => '1234 Main St',
            'city' => 'City',
            'state' => 'State',
            'zipCode' => '12345',
            'country' => 'Country',
            'joinDate' => "2024-03-27T15:50:20.155Z",
            'employeeId' => $faker->randomNumber(5),
            'bloodGroup' => 'A+',
            'departmentId' => 1,
            "address" => "hello",
            "department" => "No department",
            'designationId' => 1,
            "employmentStatusId" => 1,
            "leavePolicyId" => 2,
            "weeklyHolidayId" => 2,
            "shiftId" => 1,
            "designationStartDate" => "2023-04-12T11:36:03.700Z",
            "designationComment" => "new employee",
            "salary" => "150000",
            "salaryStartDate" => "2020-03-19T14:21:00",
            "salaryComment" => "new employee",
            'education' => [],
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'firstName',
                'lastName',
                'username',
                'roleId',
                'email',
                'street',
                'city',
                'state',
                'zipCode',
                'joinDate',
                'leaveDate',
                'employeeId',
                'phone',
                'bloodGroup',
                'image',
                'designationId',
                'employmentStatusId',
                'departmentId',
                'shiftId',
                'updatedAt',
                'createdAt',
                'id',
            ]);
    }

    public function test_user_login(): void
    {
        $response = $this->postJson('/user/login', [
            'username' => 'demo',
            'password' => '5555'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'firstName',
                'lastName',
                'username',
                'email',
                'employeeId',
                'phone',
                'street',
                'city',
                'state',
                'zipCode',
                'country',
                'bloodGroup',
                'image',
                'employmentStatusId',
                'departmentId',
                'shiftId',
                'roleId',
                'joinDate',
                'leaveDate',
                'status',
                'createdAt',
                'updatedAt',
                'designationId',
                'role',
                'token',
            ]);
    }


    public function test_get_all_users(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/user?query=all');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllUser' => [
                    '*' => [
                        'id',
                        'firstName',
                        'lastName',
                        'username',
                        'email',
                        'employeeId',
                        'phone',
                        'street',
                        'city',
                        'state',
                        'zipCode',
                        'country',
                        'bloodGroup',
                        'image',
                        'employmentStatusId',
                        'departmentId',
                        'shiftId',
                        'roleId',
                        'joinDate',
                        'leaveDate',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'designationId',
                    ],
                ],
                'totalUser',
            ]);
    }

    public function test_get_all_users_by_status(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/user?status=true&page=1&count=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllUser' => [
                    '*' => [
                        'id',
                        'firstName',
                        'lastName',
                        'username',
                        'email',
                        'employeeId',
                        'phone',
                        'street',
                        'city',
                        'state',
                        'zipCode',
                        'country',
                        'bloodGroup',
                        'image',
                        'employmentStatusId',
                        'departmentId',
                        'shiftId',
                        'roleId',
                        'joinDate',
                        'leaveDate',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'designationId',
                    ],
                ],
                'totalUser',
            ]);
    }


    public function test_get_single_user(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('user/1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'firstName',
                'lastName',
                'username',
                'email',
                'employeeId',
                'phone',
                'street',
                'city',
                'state',
                'zipCode',
                'country',
                'bloodGroup',
                'image',
                'employmentStatusId',
                'departmentId',
                'shiftId',
                'roleId',
                'joinDate',
                'leaveDate',
                'status',
                'createdAt',
                'updatedAt',
                'designationId',
                'saleInvoice' => [],
                'employmentStatus' => [],
                'shift' => [],
                'education' => [],
                'awardHistory' => [],
                'salaryHistory' => [],
                'designationHistory' => [],
                'quote' => [],
                'role' => [],
                'department' => [],
            ]);
    }

    public function test_get_user_refresh_token(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])
            ->withCookie('refreshToken', $this->getRefreshToken())
            ->getJson('user/refresh-token');

        $response->assertStatus(200);
    }

    public function test_update_user(): void
    {
        $faker = Factory::create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' .  $this->getToken()
        ])->put('user/2', [
            'username' => $faker->name,
            'password' => 'john doe',
        ]);

        $response->assertStatus(200);
    }


    public function test_delete_user(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' .  $this->getToken()
        ])->patch('user/2', [
            'status' => 'false'
        ]);

        $response->assertStatus(200);
    }
}
