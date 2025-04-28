<?php

namespace Tests\Feature;

use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    public function test_customer_registration(): void
    {
        $response = $this->postJson('/customer/register', [
            "username" => "mukhter",
            "email" => "mohammadmukhter@gmail.com",
            "password" => "\$Aa12345678",
            "confirmPassword" => "\$Aa12345678"
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'username',
                'email',
                'roleId',
                'updatedAt',
                'createdAt',
                'id',
            ]);
    }

    public function test_customer_login(): void
    {
        $response = $this->postJson('/customer/login', [
            'email' => 'dev@omega.ac',
            'password' => '12345678'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'profileImage',
                'firstName',
                'lastName',
                'username',
                'googleId',
                'email',
                'phone',
                'address',
                'roleId',
                'isLogin',
                'status',
                'created_at',
                'updated_at',
                'token',
                'role',
            ]);
    }


    public function test_get_all_customers(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/customer?query=all');

        $response->assertStatus(200)->assertJsonStructure([
            '*' => [
                'id',
                'profileImage',
                'firstName',
                'lastName',
                'username',
                'googleId',
                'email',
                'phone',
                'address',
                'roleId',
                'isLogin',
                'status',
                'createdAt',
                'updatedAt',
                'saleInvoice'
            ]
        ]);
    }

    public function test_get_all_paginated_customers(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/customer?status=true,false&page=1&count=20');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllCustomer' => [
                    '*' => [
                        'id',
                        'profileImage',
                        'firstName',
                        'lastName',
                        'username',
                        'googleId',
                        'email',
                        'phone',
                        'address',
                        'roleId',
                        'isLogin',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'saleInvoice'
                    ]
                ],
                'totalCustomer'
            ]);
    }

    public function test_get_search_customers(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/customer?query=search&page=1&count=20&key=hello');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllCustomer' => [
                    '*' => [
                        'id',
                        'profileImage',
                        'firstName',
                        'lastName',
                        'username',
                        'googleId',
                        'email',
                        'phone',
                        'address',
                        'roleId',
                        'isLogin',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'saleInvoice'
                    ]
                ],
                'totalCustomer'
            ]);
    }

    public function test_get_info_customers(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/customer?query=info');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '_count' => [
                    'id'
                ]
            ]);
    }

    public function test_get_report_customers(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/customer?query=report');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'grandData' => [
                    'grandTotalAmount',
                    'grandTotalPaidAmount',
                    'grandTotalReturnAmount',
                    'grandInstantPaidReturnAmount',
                    'grandDueAmount'
                ],
                'allCustomer' => [
                    '*' => [
                        'id',
                        'profileImage',
                        'firstName',
                        'lastName',
                        'username',
                        'googleId',
                        'email',
                        'phone',
                        'address',
                        'password',
                        'roleId',
                        'isLogin',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'totalAmount',
                        'totalPaidAmount',
                        'totalReturnAmount',
                        'instantPaidReturnAmount',
                        'dueAmount',
                        'saleInvoice'
                    ]
                ]
            ]);
    }


    public function test_get_single_customer(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('customer/1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'profileImage',
                'firstName',
                'lastName',
                'username',
                'googleId',
                'email',
                'phone',
                'address',
                'roleId',
                'isLogin',
                'status',
                'createdAt',
                'updatedAt',
                'totalAmount',
                'totalPaidAmount',
                'totalReturnAmount',
                'instantPaidReturnAmount',
                'dueAmount',
                'totalSaleInvoice',
                'totalReturnSaleInvoice',
                'allTransaction',
                'returnSaleInvoice',
                'saleInvoice',
                'cartOrder'
            ]);
    }

    public function test_get_profile_customer(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/customer/profile');

        $response->assertStatus(200);
    }
}
