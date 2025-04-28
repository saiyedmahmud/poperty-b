<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AccountingTest extends TestCase
{
    public function test_create_account(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/account', [
            "name" => "rocket",
            "accountId" => 1
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'name' => true,
                'accountId' => true,
                'updatedAt' => true,
                'createdAt' => true,
                'id' => true
            ]);
    }

    public function test_get_trial_balance(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/account?query=tb');

        $response->assertStatus(200)
            ->assertJsonStructure([
                "match",
                "totalDebit",
                "totalCredit",
                "debits" => [
                    '*' => [
                        "account",
                        "subAccount",
                        "totalDebit",
                        "totalCredit",
                        "balance"
                    ]
                ],
                "credits" => [
                    '*' => [
                        "account",
                        "subAccount",
                        "totalDebit",
                        "totalCredit",
                        "balance"
                    ]
                ]
            ]);
    }

    public function test_get_balance_sheet(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/account?query=bs');

        $response->assertStatus(200)
            ->assertJsonStructure([
                "match",
                "totalAsset",
                "totalLiability",
                "totalEquity",
                "assets" => [
                    '*' => [
                        "account",
                        "subAccount",
                        "totalDebit",
                        "totalCredit",
                        "balance"
                    ]
                ],
                "liabilities" => [
                    '*' => [
                        "account",
                        "subAccount",
                        "totalDebit",
                        "totalCredit",
                        "balance"
                    ]
                ],
                "equity" => [
                    '*' => [
                        "account",
                        "subAccount",
                        "totalDebit",
                        "totalCredit",
                        "balance"
                    ]
                ]
            ]);
    }

    public function test_get_income_statement(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/account?query=is');

        $response->assertStatus(200)
            ->assertJsonStructure([
                "totalRevenue",
                "totalExpense",
                "profit",
                "revenue" => [
                    '*' => [
                        "id",
                        "account",
                        "subAccount",
                        "totalDebit",
                        "totalCredit",
                        "balance"
                    ]
                ],
                "expense" => [
                    '*' => [
                        "id",
                        "account",
                        "subAccount",
                        "totalDebit",
                        "totalCredit",
                        "balance"
                    ]
                ]
            ]);
    }

    public function test_get_sub_account_paginated_true_and_false(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/account?type=sa&status=true,false&page=1&count=20&accountId=1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                "getAllSubAccount" => [
                    '*' => [
                        "id",
                        "name",
                        "accountId",
                        "status",
                        "createdAt",
                        "updatedAt",
                        "account" => [
                            "id",
                            "name",
                            "type",
                            "createdAt",
                            "updatedAt"
                        ]
                    ]
                ],
                "totalSubAccount"
            ]);
    }

    public function test_get_sub_account_paginated_false(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/account?type=sa&status=false&page=1&count=2');

        $response->assertStatus(200)
            ->assertJsonStructure([
                "getAllSubAccount" => [
                    '*' => [
                        "id",
                        "name",
                        "accountId",
                        "status",
                        "createdAt",
                        "updatedAt",
                        "account" => [
                            "id",
                            "name",
                            "type",
                            "createdAt",
                            "updatedAt"
                        ]
                    ]
                ],
                "totalSubAccount"
            ]);
    }

    public function test_get_sub_account_paginated_true(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/account?type=sa&query=all');

        $response->assertStatus(200)
            ->assertJsonStructure([
                "*" => [
                    "id",
                    "name",
                    "accountId",
                    "status",
                    "createdAt",
                    "updatedAt",
                    "account" => [
                        "id",
                        "name",
                        "type",
                        "createdAt",
                        "updatedAt"
                    ]
                ]
            ]);
    }

    public function test_get_all_main_account(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/account?query=ma');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    "id",
                    "name",
                    "type",
                    "createdAt",
                    "updatedAt"
                ]
            ]);
    }

    public function test_get_single_sub_account(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/account/19');

        $response->assertStatus(200)
            ->assertJsonStructure([
                "id",
                "name",
                "accountId",
                "status",
                "createdAt",
                "updatedAt",
                "balance",
                "debit" => [
                    '*' => [
                        "id",
                        "date",
                        "debitId",
                        "creditId",
                        "particulars",
                        "amount",
                        "type",
                        "relatedId",
                        "status",
                        "createdAt",
                        "updatedAt"
                    ]
                ],
                "credit" => [
                    '*' => [
                        "id",
                        "date",
                        "debitId",
                        "creditId",
                        "particulars",
                        "amount",
                        "type",
                        "relatedId",
                        "status",
                        "createdAt",
                        "updatedAt"
                    ]
                ]
            ]);
    }

    public function test_get_search_account(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/account?type=sa&page=1&count=5&query=search&key=ca');

        $response->assertStatus(200)
            ->assertJsonStructure([
                "getAllSubAccount" => [
                    '*' => [
                        "id",
                        "name",
                        "accountId",
                        "status",
                        "createdAt",
                        "updatedAt",
                        "account" => [
                            "id",
                            "name",
                            "type",
                            "createdAt",
                            "updatedAt"
                        ]
                    ]
                ],
                "totalSubAccount"
            ]);
    }

    public function test_update_account(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->put('/account/17', [
            "accountId" => 6
        ]);

        $response->assertStatus(200);
    }

//    public function test_delete_account(): void
//    {
//        $response = $this->withHeaders([
//            'Authorization' => 'Bearer ' . $this->getToken()
//        ])->patch('/account/2', [
//            "status" => "false"
//        ]);
//
//        $response->assertStatus(200);
//    }
}
