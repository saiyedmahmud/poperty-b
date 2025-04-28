<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ReviewRatingTest extends TestCase
{
    public function test_create_review_rating(): void
    {
        $randomFileName = uniqid() . '_' . time() . '.jpg';
        Storage::fake('public');
        $file = UploadedFile::fake()->image($randomFileName);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken(),
            'Content-Type' => 'multipart/form-data',
        ])->postJson('/review-rating', [
            "productId" => 1,
            "customerId" => 1,
            "rating" => 1,
            "review" => "good",
            "images" => [$file],
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'productId',
                'customerId',
                'rating',
                'review',
                'updatedAt',
                'createdAt',
                'id'
            ]);
    }

    public function test_create_review_rating_reply(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken(),
            'Content-Type' => 'multipart/form-data',
        ])->postJson('/review-rating/reply', [
            "reviewId" => 1,
            "comment" => "reply hello"
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'reviewId',
                'comment',
                'updatedAt',
                'createdAt',
                'id'
            ]);
    }

    public function test_get_all_review_rating(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/review-rating?page=1&count=10&status=true');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllReviewRating' => [
                    '*' => [
                        'id',
                        'productId',
                        'customerId',
                        'rating',
                        'review',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'images',
                        'reviewReply',
                        'customer' => [
                            'id',
                            'username'
                        ],
                        'product' => [
                            'id',
                            'name'
                        ]
                    ]
                ],
                'totalReviewRating'
            ]);
    }

    public function test_get_single_review_rating(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/review-rating/1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'productId',
                'customerId',
                'rating',
                'review',
                'status',
                'createdAt',
                'updatedAt',
                'images',
                'reviewReply'
            ]);
    }

    public function test_get_single_review_rating_by_product_id(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/review-rating/product/1?page=1&count=10&status=true');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllReviewRating' => [
                    '*' => [
                        'id',
                        'productId',
                        'customerId',
                        'rating',
                        'review',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'images',
                        'reviewReply',
                        'customer',
                        'product'
                    ]
                ],
                'totalReviewRating'
            ]);
    }

    public function test_delete_single_review_rating(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->patch('/review-rating/1', [
            "status" => "false"
        ]);

        $response->assertStatus(200);
    }
}
