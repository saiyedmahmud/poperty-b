<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProductTest extends TestCase
{
    public function test_create_single_product(): void
    {
        $randomFileName = uniqid() . '_' . time() . '.jpg';
        Storage::fake('public');
        $file = UploadedFile::fake()->image($randomFileName);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken(),
            'Content-Type' => 'multipart/form-data',
        ])->post('/product', [
            "name" => "p1",
            "sku" => "AGN345DGI",
            "eanNo" => 345678934,
            "isbnNo" => 1234567897,
            "upcNo" => 13245678,
            "partNo" => 1324567,
            "weightUnitId" => 1,
            "dimensionUnitId" => 1,
            "length" => 10,
            "width" => 10,
            "height" => 5,
            "weight" => 1,
            "productGroupId" => 1,
            'images' => [$file],
            "discountId" => 1,
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'name',
                'productThumbnailImage',
                'sku',
                'discountId',
                'updatedAt',
                'createdAt',
                'id',
                'productThumbnailImageUrl'
            ]);
    }

    public function test_create_many_product(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken(),
            'Content-Type' => 'multipart/form-data',
        ])->post('/product?query=createmany', [
            [
                "name" => "product 1",
                "sku" => "PRO123ABC",
                "eanNo" => 123456789098,
                "upcNo" => 123456789098,
                "isbnNo" => 123456789098,
                "partNo" => 123456789098,
                "length" => 12,
                "width" => 10,
                "height" => 10,
                "weight" => 20,
                "weightUnitId" => 1,
                "dimensionUnitId" => 1,
                "productGroupId" => "VOFVENAGV1"
            ],
            [
                "name" => "product 2",
                "sku" => "PRO223ABC",
                "eanNo" => 123456789098,
                "upcNo" => 123456789098,
                "isbnNo" => 123456789098,
                "partNo" => 123456789098,
                "length" => 12,
                "width" => 10,
                "height" => 10,
                "weight" => 20,
                "weightUnitId" => 1,
                "dimensionUnitId" => 1,
                "productGroupId" => "VOFVENAGV1"
            ]
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'count'
            ]);
    }

    public function test_get_all_product(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product?query=all');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'productThumbnailImage',
                    'sku',
                    'eanNo',
                    'upcNo',
                    'isbnNo',
                    'partNo',
                    'weightUnitId',
                    'dimensionUnitId',
                    'length',
                    'width',
                    'height',
                    'weight',
                    'discountId',
                    'productGroupId',
                    'status',
                    'createdAt',
                    'updatedAt',
                    'purchaseInvoiceId',
                    'stockInfo',
                    'averageRating',
                    'reviewRating',
                    'productGroup',
                    'dimensionUnit',
                    'weightUnit',
                ],
            ]);
    }

    public function test_get_all_product_paginated_true(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product?status=true&page=1&count=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllProduct' => [
                    '*' => [
                        'id',
                        'name',
                        'productThumbnailImage',
                        'sku',
                        'eanNo',
                        'upcNo',
                        'isbnNo',
                        'partNo',
                        'weightUnitId',
                        'dimensionUnitId',
                        'length',
                        'width',
                        'height',
                        'weight',
                        'discountId',
                        'productGroupId',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'purchaseInvoiceId',
                        'totalRating',
                        'productGroup' => [],
                        'reviewRating',
                        'stockInfo',
                    ],
                ],
                'totalProduct',
            ]);
    }

    public function test_get_all_product_paginated_false(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product?status=false&page=1&count=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllProduct' => [
                    '*' => [
                        'id',
                        'name',
                        'productThumbnailImage',
                        'sku',
                        'eanNo',
                        'upcNo',
                        'isbnNo',
                        'partNo',
                        'weightUnitId',
                        'dimensionUnitId',
                        'length',
                        'width',
                        'height',
                        'weight',
                        'discountId',
                        'productGroupId',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'purchaseInvoiceId',
                        'totalRating',
                        'productGroup' => [],
                        'reviewRating',
                        'stockInfo',
                    ],
                ],
                'totalProduct',
            ]);
    }

    public function test_get_single_product(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('product/1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'productThumbnailImage',
                'sku',
                'eanNo',
                'upcNo',
                'isbnNo',
                'partNo',
                'weightUnitId',
                'dimensionUnitId',
                'length',
                'width',
                'height',
                'weight',
                'discountId',
                'productGroupId',
                'status',
                'createdAt',
                'updatedAt',
                'purchaseInvoiceId',
                'productProductAttributeValue',
                'dimensionUnit',
                'weightUnit',
                'reviewRating',
                'productGroup' => [
                    'id',
                    'productGroupName',
                    'manufacturerId',
                    'productBrandId',
                    'subCategoryId',
                    'productPurchaseTaxId',
                    'productSalesTaxId',
                    'uomId',
                    'uomValue',
                    'description',
                    'status',
                    'createdAt',
                    'updatedAt',
                    'productBrand' => [
                        'id',
                        'name',
                        'status',
                        'createdAt',
                        'updatedAt',
                    ],
                    'manufacturer' => [
                        'id',
                        'name',
                        'status',
                        'createdAt',
                        'updatedAt',
                    ],
                    'subCategory' => [
                        'id',
                        'name',
                        'productCategoryId',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'productCategory' => [
                            'id',
                            'name',
                            'status',
                            'createdAt',
                            'updatedAt',
                        ],
                    ],
                    'uom' => [
                        'id',
                        'name',
                        'status',
                        'createdAt',
                        'updatedAt',
                    ],
                    'galleryImage',
                    'totalReviewRating' => [
                        'totalReview',
                        'averageRating',
                        'Rating' => [
                            'one',
                            'two',
                            'three',
                            'four',
                            'five',
                        ],
                    ],
                    'products' => [
                        '*' => [
                            'id',
                            'name',
                            'productThumbnailImage',
                            'sku',
                            'eanNo',
                            'upcNo',
                            'isbnNo',
                            'partNo',
                            'weightUnitId',
                            'dimensionUnitId',
                            'length',
                            'width',
                            'height',
                            'weight',
                            'discountId',
                            'productGroupId',
                            'status',
                            'createdAt',
                            'updatedAt',
                            'purchaseInvoiceId',
                            'stockInfo' => [
                                '*' => [
                                    'id',
                                    'storeId',
                                    'productId',
                                    'productQuantity',
                                    'productSalePrice',
                                    'productPurchasePrice',
                                    'reorderQuantity',
                                    'isNegativeSale',
                                    'comment',
                                    'createdAt',
                                    'updatedAt',
                                    'store' => [
                                        'id',
                                        'name',
                                        'city',
                                        'address',
                                        'phone',
                                        'email',
                                        'description',
                                        'status',
                                        'createdAt',
                                        'updatedAt',
                                    ],
                                ],
                            ],
                            'productProductAttributeValue',
                        ],
                    ],
                ],
                'stockInfo',
                'stockStoreInfo',
            ]);
    }

    public function test_search_product_by_sku(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product?query=sku&key=cerave_');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status'
            ]);
    }

    public function test_search_product_by_name(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product?query=name&key=W7 Banana Dreams Loose');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status'
            ]);
    }

    public function test_all_card(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product?query=card');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'uniqueProduct',
                'totalProductCount',
                'inventorySalesValue',
                'inventoryPurchaseValue',
                'shortProductCount',
            ]);
    }

    public function test_all_public_product(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product/public?query=all&page=1&count=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'productThumbnailImage',
                    'sku',
                    'eanNo',
                    'upcNo',
                    'isbnNo',
                    'partNo',
                    'weightUnitId',
                    'dimensionUnitId',
                    'length',
                    'width',
                    'height',
                    'weight',
                    'discountId',
                    'productGroupId',
                    'status',
                    'createdAt',
                    'updatedAt',
                    'purchaseInvoiceId',
                    'stockInfo',
                    'averageRating',
                    'reviewRating',
                    'productGroup',
                    'dimensionUnit',
                    'weightUnit',
                ],
            ]);
    }

    public function test_all_public_product_by_brand_id(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product/public?productBrandId=2&page=1&count=2');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllProduct' => [
                    '*' => [
                        'id',
                        'name',
                        'productThumbnailImage',
                        'sku',
                        'eanNo',
                        'upcNo',
                        'isbnNo',
                        'partNo',
                        'weightUnitId',
                        'dimensionUnitId',
                        'length',
                        'width',
                        'height',
                        'weight',
                        'discountId',
                        'productGroupId',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'purchaseInvoiceId',
                        'totalRating',
                        'productGroup' => [],
                        'reviewRating',
                        'stockInfo',
                    ],
                ],
                'totalProduct',
            ]);
    }

    public function test_all_public_product_by_color(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product/public?color=1,2&page=1&count=2');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllProduct' => [
                    '*' => [
                        'id',
                        'name',
                        'productThumbnailImage',
                        'sku',
                        'eanNo',
                        'upcNo',
                        'isbnNo',
                        'partNo',
                        'weightUnitId',
                        'dimensionUnitId',
                        'length',
                        'width',
                        'height',
                        'weight',
                        'discountId',
                        'productGroupId',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'purchaseInvoiceId',
                        'totalRating',
                        'productGroup' => [],
                        'reviewRating',
                        'stockInfo',
                    ],
                ],
                'totalProduct',
            ]);
    }

    public function test_all_public_product_by_price_range(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product/public?pricerange=100-500&page=1&count=2');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllProduct' => [
                    '*' => [
                        'id',
                        'name',
                        'productThumbnailImage',
                        'sku',
                        'eanNo',
                        'upcNo',
                        'isbnNo',
                        'partNo',
                        'weightUnitId',
                        'dimensionUnitId',
                        'length',
                        'width',
                        'height',
                        'weight',
                        'discountId',
                        'productGroupId',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'purchaseInvoiceId',
                        'totalRating',
                        'productGroup' => [],
                        'reviewRating',
                        'stockInfo',
                    ],
                ],
                'totalProduct',
            ]);
    }

    public function test_all_public_product_by_sub_category(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product/public?productSubCategoryId=1&page=1&count=2');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllProduct' => [
                    '*' => [
                        'id',
                        'name',
                        'productThumbnailImage',
                        'sku',
                        'eanNo',
                        'upcNo',
                        'isbnNo',
                        'partNo',
                        'weightUnitId',
                        'dimensionUnitId',
                        'length',
                        'width',
                        'height',
                        'weight',
                        'discountId',
                        'productGroupId',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'purchaseInvoiceId',
                        'totalRating',
                        'productGroup' => [],
                        'reviewRating',
                        'stockInfo',
                    ],
                ],
                'totalProduct',
            ]);
    }

    public function test_all_public_product_search(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product?query=search&status=true&key=lenovo&page=1&count=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllProduct' => [
                    '*' => [
                        'id',
                        'name',
                        'productThumbnailImage',
                        'sku',
                        'eanNo',
                        'upcNo',
                        'isbnNo',
                        'partNo',
                        'weightUnitId',
                        'dimensionUnitId',
                        'length',
                        'width',
                        'height',
                        'weight',
                        'discountId',
                        'productGroupId',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'purchaseInvoiceId',
                        'totalRating',
                        'productGroup' => [],
                        'reviewRating',
                        'stockInfo',
                    ],
                ],
                'totalProduct',
            ]);
    }

    public function test_all_public_product_sort_by_price(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product/public?page=1&count=100&price=HighToLow');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllProduct' => [
                    '*' => [
                        'id',
                        'name',
                        'productThumbnailImage',
                        'sku',
                        'eanNo',
                        'upcNo',
                        'isbnNo',
                        'partNo',
                        'weightUnitId',
                        'dimensionUnitId',
                        'length',
                        'width',
                        'height',
                        'weight',
                        'discountId',
                        'productGroupId',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'purchaseInvoiceId',
                        'totalRating',
                        'productGroup' => [],
                        'reviewRating',
                        'stockInfo',
                    ],
                ],
                'totalProduct',
            ]);
    }

    public function test_single_public_product(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product/public/5');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'productThumbnailImage',
                'sku',
                'eanNo',
                'upcNo',
                'isbnNo',
                'partNo',
                'weightUnitId',
                'dimensionUnitId',
                'length',
                'width',
                'height',
                'weight',
                'discountId',
                'productGroupId',
                'status',
                'createdAt',
                'updatedAt',
                'purchaseInvoiceId',
                'productProductAttributeValue',
                'dimensionUnit',
                'weightUnit',
                'reviewRating',
                'productGroup' => [
                    'id',
                    'productGroupName',
                    'manufacturerId',
                    'productBrandId',
                    'subCategoryId',
                    'productPurchaseTaxId',
                    'productSalesTaxId',
                    'uomId',
                    'uomValue',
                    'description',
                    'status',
                    'createdAt',
                    'updatedAt',
                    'productBrand',
                    'manufacturer',
                    'subCategory' => [
                        'id',
                        'name',
                        'productCategoryId',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'productCategory',
                    ],
                    'uom',
                    'galleryImage',
                    'totalReviewRating',
                    'products' => [
                        '*' => [
                            'id',
                            'name',
                            'productThumbnailImage',
                            'sku',
                            'eanNo',
                            'upcNo',
                            'isbnNo',
                            'partNo',
                            'weightUnitId',
                            'dimensionUnitId',
                            'length',
                            'width',
                            'height',
                            'weight',
                            'discountId',
                            'productGroupId',
                            'status',
                            'createdAt',
                            'updatedAt',
                            'purchaseInvoiceId',
                            'stockInfo',
                            'productProductAttributeValue',
                        ],
                    ],
                ],
                'stockInfo',
                'stockStoreInfo',
            ]);
    }

    public function test_get_product_info(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product?query=info');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '_count' => [
                    'id'
                ]
            ]);
    }

    public function test_update_product(): void
    {
        $randomFileName = uniqid() . '_' . time() . '.jpg';
        Storage::fake('public');
        $file = UploadedFile::fake()->image($randomFileName);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken(),
            'Content-Type' => 'multipart/form-data',
        ])->post('/product/2', [
            "name" => "p111",
            "sku" => "AGN345DGI1",
            "eanNo" => 345678934,
            "isbnNo" => 1234567897,
            "upcNo" => 13245678,
            "partNo" => 1324567,
            "weightUnitId" => 1,
            "dimensionUnitId" => 1,
            "length" => 10,
            "width" => 10,
            "height" => 5,
            "weight" => 1,
            "productGroupId" => 1,
            'images' => [$file],
            "discountId" => 1,
            "_method" => "PUT"
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_single_product(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->patch('/product/2', [
            "status" => "false"
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_single_product_image(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->patch('/product/4?query=delete-image&imageId=3');

        $response->assertStatus(200);
    }

    public function test_delete_many_product(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->post('/product?query=deletemany', [
            1, 2
        ]);

        $response->assertStatus(200);
    }
}
