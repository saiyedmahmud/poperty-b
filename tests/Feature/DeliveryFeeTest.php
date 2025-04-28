<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeliveryFeeTest extends TestCase
{
    public function test_create_single_delivery_fee(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/delivery-fee', [
            "deliveryArea" => "dhaka",
            "deliveryFee" => "80"
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'deliveryArea',
                'deliveryFee',
                'updatedAt',
                'createdAt',
                'id'
            ]);
    }

    public function test_get_all_delivery_fee(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/delivery-fee');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'deliveryArea',
                    'deliveryFee',
                    'status',
                    'createdAt',
                    'updatedAt'
                ]
            ]);
    }

    public function test_update_delivery_fee(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->put('/delivery-fee/1', [
            "deliveryArea" => "feni"
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_single_delivery_fee(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->patch('/delivery-fee/1', [
            "status" => "false"
        ]);

        $response->assertStatus(200);
    }
}
