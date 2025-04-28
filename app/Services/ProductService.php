<?php

namespace App\Services;

use App\Models\Images;
use App\Models\Product;
use App\Models\ProductProductAttributeValue;
use App\Models\ProductVariant;
use App\Traits\ErrorTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ProductService
{
    use ErrorTrait;

    public function createProduct($request): JsonResponse
    {

        DB::beginTransaction();
        try {
            $products = is_array($request) ? $request : [$request];

            if (isset($products[0]['productGroupName'])) {
                $productGroups = $this->createProductGroup($products);
                if (isset($products[0]['galleryImages'])) {
                    $this->galleryImages($productGroups->getOriginalContent()['id'], $products[0]['galleryImages']);
                }
                if ($productGroups->getStatusCode() !== 200) {
                    return $productGroups;
                }
            }
            if (isset($products[0]['productGroupId'])) {
                $groupId = $products[0]['productGroupId'];
            }

            foreach ($products as $item) {
                $productGroup = $item['productGroup'] ?? [];
                $sku = $this->exitsSku($productGroup);
                if ($sku->getStatusCode() !== 200) {
                    return $sku;
                }
                foreach ($productGroup as $items) {
                    $product = Product::create([
                        'name' => $items['name'],
                        'productThumbnailImage' => $items['productThumbnailImage'] ?? null,
                        'description' => $items['description'] ?? null,
                        'sku' => $items['sku'] ?? null,
                        'uomId' => $items['uomId'] ?? null,
                        'uomValue' => $items['uomValue'] ?? null,
                        'eanNo' => $items['eanNo'] ?? null,
                        'upcNo' => $items['upcNo'] ?? null,
                        'isbnNo' => $items['isbnNo'] ?? null,
                        'partNo' => $items['partNo'] ?? null,
                        'length' => $items['length'] ?? null,
                        'width' => $items['width'] ?? null,
                        'height' => $items['height'] ?? null,
                        'weight' => $items['weight'] ?? null,
                        'discountId' => $item['discountId'] ?? null,
                        "dimensionUnitId" => $items['dimensionUnitId'] ?? null,
                        'weightUnitId' => $items['weightUnitId'] ?? null,
                        'productGroupId' => $productGroups?->getOriginalContent()['id'] ?? $groupId,
                        'reorderQuantity' => $items['reorderQuantity'] ?? null,
                    ]);
                    $currentAppUrl = url('/');
                    if ($product) {
                        $product->productThumbnailImageUrl = "{$currentAppUrl}/product-image/{$product->productThumbnailImage}";
                    }
                    if (isset($items['productAttributeValueId'])) {
                        $attribute = $this->createProductAttribute($product->id, $items['productAttributeValueId']);
                        if ($attribute->getStatusCode() !== 200) {
                            return $attribute;
                        }
                    }
                }
                DB::commit();
            }

            return $this->success('Product created successfully');
        } catch (Exception $error) {
            DB::rollBack();
            return $this->badRequest($error);
        }
    }

    private function createProductGroup($request): JsonResponse
    {
        DB::beginTransaction();
        // Create a new product group
        try {
            foreach ($request as $item) {
                $productVariant = ProductVariant::create([
                    'productGroupName' => $item['productGroupName'],
                    'manufacturerId' => $item['manufacturerId'] ?? null,
                    'productBrandId' => $item['productBrandId'] ?? null,
                    'subCategoryId' => $item['subCategoryId'] ?? null,
                    'productPurchaseTaxId' => $item['productPurchaseTaxId'] ?? null,
                    'productSalesTaxId' => $item['productSalesTaxId'] ?? null,
                    'uomId' => $item['uomId'] ?? null,
                    'uomValue' => $item['uomValue'] ?? null,
                    'description' => $item['description'] ?? null,
                ]);
                if (!$productVariant) {
                    return $this->badRequest("Product variant not created");
                }
                DB::commit();
                return $this->response($productVariant->toArray());
            }
        } catch (Exception $error) {
            DB::rollBack();
            return $this->badRequest($error->getMessage());
        }
    }

    private function galleryImages($productGroupId, $images): JsonResponse
    {
        try {
            foreach ($images as $item) {
                Images::create([
                    'productGroupId' => $productGroupId,
                    'imageName' => $item,
                ]);
            }
            return $this->success('Product gallery images created successfully');
        } catch (Exception $error) {
            DB::rollBack();
            return $this->badRequest($error->getMessage());
        }
    }

    private function exitsSku($request): JsonResponse
    {
        try {
            foreach ($request as $item) {
                $product = Product::where('sku', $item['sku'])->first();
                if ($product) {
                    return $this->badRequest("Product sku {$item['sku']} already exist'");
                }
            }
            return $this->success('Product sku does not exist');
        } catch (Exception $error) {
            return $this->badRequest($error->getMessage());
        }
    }

    private function createProductAttribute($productId, $attributeValueId): JsonResponse
    {
        DB::beginTransaction();
        try {
            foreach ($attributeValueId as $item) {
                ProductProductAttributeValue::create([
                    'productId' => $productId,
                    'productAttributeValueId' => $item,
                ]);
            }
            DB::commit();
            return $this->success('Product attribute created successfully');
        } catch (Exception $error) {
            DB::rollBack();
            return $this->badRequest($error->getMessage());
        }
    }

}
