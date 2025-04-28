<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PageSizeTest extends TestCase
{
    public function test_create_single_page_size(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->postJson('/page-size', [
            "pageSizeName" => "A5",
            "width" => 8.3,
            "height" => 11.7
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'pageSizeName',
                'width',
                'height',
                'updatedAt',
                'createdAt',
                'id'
            ]);
    }

    public function test_get_all_page_size(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/page-size?status=true&page=1&count=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllPageSize' => [
                    '*' => [
                        'id',
                        'pageSizeName',
                        'width',
                        'height',
                        'unit',
                        'status',
                        'createdAt',
                        'updatedAt'
                    ]
                ],
                'totalPageSizeCount'
            ]);
    }

    public function test_get_single_page_size(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/page-size/2');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'pageSizeName',
                'width',
                'height',
                'unit',
                'status',
                'createdAt',
                'updatedAt'
            ]);
    }

    public function test_update_page_size(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->put('/page-size/1', [
            "pageSizeName" => "A5",
            "width" => 8.4,
            "height" => 11.87,
            "unit" => "mm"
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_single_page_size(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->patch('/page-size/1', [
            "status" => "false"
        ]);

        $response->assertStatus(200);
    }
}
