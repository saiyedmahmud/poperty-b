<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RolePermissionTest extends TestCase
{

    public function test_create_many_role_permission(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/role-permission', [
            "roleId" => 6,
            "permissionId" => [1, 2, 3, 4]
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'count'
            ]);
    }

    public function test_get_all_role_permission_by_role_id(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/role-permission/permission?roleId=6');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'permissions',
                'totalPermissions'
            ]);
    }

    public function test_delete_single_role_permission(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->delete('/role-permission/85');

        $response->assertStatus(200);
    }

    public function test_delete_many_role_permission(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->post('/role-permission?query=deletemany', [
            81, 83
        ]);

        $response->assertStatus(200);
    }
}
