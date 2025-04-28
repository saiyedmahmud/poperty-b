<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductHighlightedTest extends TestCase
{
    public function test_get_all_new_product(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product-reports?query=new-products&page=1&count=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllNewProduct' => [
                    '*' => [
                        'id',
                        'name',
                        'productThumbnailImage',
                        'productSubCategoryId',
                        'productBrandId',
                        'description',
                        'sku',
                        'productQuantity',
                        'productSalePrice',
                        'uomId',
                        'uomValue',
                        'reorderQuantity',
                        'productVatId',
                        'shippingChargeComment',
                        'discountId',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'purchaseInvoiceId',
                        'productSubCategory',
                        'productBrand',
                        'reviewRating',
                        'discount',
                        'productSalePriceWithVat',
                        'productThumbnailImageUrl',
                    ],
                ],
                'totalNewProduct',
            ]);
    }

    public function test_get_all_top_selling_product(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->getJson('/product-reports?query=top-selling-products&page=1&count=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'getAllTopSellingProduct' => [
                    '*' => [
                        'id',
                        'name',
                        'productThumbnailImage',
                        'productSubCategoryId',
                        'productBrandId',
                        'description',
                        'sku',
                        'productQuantity',
                        'productSalePrice',
                        'uomId',
                        'uomValue',
                        'reorderQuantity',
                        'productVatId',
                        'shippingChargeComment',
                        'discountId',
                        'status',
                        'createdAt',
                        'updatedAt',
                        'purchaseInvoiceId',
                        'productThumbnailImageUrl',
                    ],
                ],
                'totalTopSellingProduct',
            ]);
    }
}
