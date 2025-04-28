<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StockTest extends TestCase
{
    public function test_get_all_stock(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/stock?query=all');

        $response->assertStatus(200)
            ->assertJsonStructure([
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
                    'product' => [
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
                        'productGroup',
                    ],
                ],
            ]);
    }

    public function test_get_filter_stock_by_productSalePrice(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/stock?page=1&count=20&productSalePrice=220');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllInventory' => [
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
                        'product' => [
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
                        ],
                    ],
                ],
                'totalInventory',
            ]);
    }

    public function test_get_filter_stock_by_productPurchasePrice(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/stock?page=1&count=20&productPurchasePrice=100');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllInventory' => [
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
                        'product' => [
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
                        ],
                    ],
                ],
                'totalInventory',
            ]);
    }

    public function test_get_single_stock(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/stock/1');

        $response->assertStatus(200)
            ->assertJsonStructure([
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
                'product' => [
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
                    ],
                    'dimensionUnit',
                    'weightUnit',
                    'reviewRating',
                ],
            ]);
    }
}
