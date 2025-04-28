<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SettingTest extends TestCase
{
    public function test_get_setting(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/setting');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'companyName',
                'dashboardType',
                'tagLine',
                'address',
                'phone',
                'email',
                'website',
                'footer',
                'logo',
                'currencyId',
                'createdAt',
                'updatedAt',
                'currency'
            ]);
    }

    public function test_update_setting(): void
    {
        $randomFileName = uniqid() . '_' . time() . '.jpg';
        Storage::fake('public');
        $file = UploadedFile::fake()->image($randomFileName);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken(),
            'Content-Type' => 'multipart/form-data',
        ])->post('/setting', [
            "companyName" => "omega",
            "tagLine" => "psdfsdfsdfsdf1",
            "address" => "dhaka",
            "phone" => 121354678,
            "email" => "omega@gmail.com",
            "website" => "hdsfnks.com",
            "footer" => 'dfghjkhgfd',
            "currencyId" => 1,
            "dashboardType" => 'inventory',
            "images" => [$file],
            "_method" => "PUT"
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'companyName',
                'dashboardType',
                'tagLine',
                'address',
                'phone',
                'email',
                'website',
                'footer',
                'logo',
                'currencyId',
                'createdAt',
                'updatedAt'
            ]);
    }
}
