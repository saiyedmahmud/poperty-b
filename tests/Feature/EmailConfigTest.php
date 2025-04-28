<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EmailConfigTest extends TestCase
{

    public function test_update_email_config(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->put('/email-config/1', [
            "emailConfigName" => "Omega h",
            "emailHost" => "server.vibd.me",
            "emailPort" => 46,
            "emailUser" => "no-reply@osapp.net",
            "emailPass" => "@omega@2020@omega"
        ]);

        $response->assertStatus(200);
    }

    public function test_info_email_config(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/email-config');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'emailConfigName',
                    'emailHost',
                    'emailPort',
                    'emailUser',
                    'emailPass',
                    'status',
                    'createdAt',
                    'updatedAt'
                ]
            ]);
    }

    public function test_delete_email_config(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->delete('/email-config/1');

        $response->assertStatus(200);
    }
}
