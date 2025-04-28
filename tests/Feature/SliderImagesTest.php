<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SliderImagesTest extends TestCase
{
    public function test_create_single_slider_images(): void
    {

        $randomFileName = uniqid() . '_' . time() . '.jpg';
        Storage::fake('public');
        $file = UploadedFile::fake()->image($randomFileName);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->post('/slider-images', [
            'images' => [$file],
            "linkUrl" => "",
            "index" => 3,
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'image',
                'linkUrl',
                'index',
                'updatedAt',
                'createdAt',
            ]);
    }

    public function test_get_all_slider_images(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/slider-images?query=all');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'image',
                    'linkUrl',
                    'index',
                    'status',
                    'createdAt',
                    'updatedAt',
                ]
            ]);
    }

    public function test_get_all_slider_images_public(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/slider-images/public?query=all');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'image',
                    'linkUrl',
                    'index',
                    'status',
                    'createdAt',
                    'updatedAt',
                ]
            ]);
    }

    public function test_update_slider_images(): void
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('test_image.jpg');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->post('/slider-images/1', [
            "_method" => "PUT",
            'images' => [$file],
            "linkUrl" => "",
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_single_slider_images(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->delete('/slider-images/1');

        $response->assertStatus(200);
    }
}
