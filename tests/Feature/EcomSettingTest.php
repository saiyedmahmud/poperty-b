<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EcomSettingTest extends TestCase
{
    public function test_create_single_ecommerce_setting(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/e-com-setting', [
            "isActive" => "false"
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'isActive',
                'createdAt',
                'updatedAt',
            ]);
    }
}
