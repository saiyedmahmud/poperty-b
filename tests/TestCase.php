<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $token;
    protected $refreshToken = "";

    protected function login(): void
    {
        if (!$this->token) {
            $response = $this->postJson('/user/login', [
                'username' => 'demo',
                'password' => '5555',
            ]);


            $response->assertStatus(200);
            $this->token = $response->json('token');

            $response->assertCookie('refreshToken');
            $this->refreshToken = $response->cookie('refreshToken');
        }
    }

    protected function getToken(): string
    {
        if (!$this->token) {
            $this->login();
        }

        return $this->token;
    }

    protected function getRefreshToken(): string
    {
        if (!$this->refreshToken) {
            $this->login();
        }

        return $this->token;
    }
}
