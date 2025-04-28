<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SendSmsTest extends TestCase
{
    public function test_create_single_send_sms(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/send-sms', [
            "phone" => "01609991247",
            "message" => "Hello, how are you?"
        ]);

        $response->assertStatus(200);
    }
}
