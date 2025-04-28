<?php

namespace App\Http\Controllers\Inventory\Product;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\{JsonResponse, Request};
use App\Models\Product;
use App\Models\ProductCategory;

class ProductController extends Controller
{
    //create product Controller method
    public function createSingleProduct(Request $request): jsonResponse
    {
        if ($request->query('query') === 'deletemany') {
            try {
                $ids = json_decode($request->getContent(), true);
                $deletedProduct = Product::destroy($ids);

                $deletedCounted = [
                    'count' => $deletedProduct,
                ];

                return response()->json($deletedCounted, 200);
            } catch (Exception $err) {
            
                return response()->json(['error' => 'An error occurred during deleting many Products. Please try again later.'], 500);
            }
        } else if ($request->query('query') === 'createmany') {
            try {
                $data = json_decode($request->getContent(), true);

                $productToInsert = [];
                $errors = [];
                foreach ($data as $index => $row) {
                    if (!isset($row['category'])) {
                        continue; 
                    }
                    $category = ProductCategory::where('name', $row['category'])->first();
                    if (!$category) {
                        $errors[] = [
                            'error' => 'row ' . $index+1 . ' ' . $row['category'] . 'not found',
                        ];
                        continue;
                    }
                    $productToInsert[] = [
                        'name' => $row['name'],
                        'description' => $row['description'] ?? null,
                        'rate' => $row['rate'] ?? null,
                        'unit' => $row['unit'] ?? null,
                        'productCategoryId' => $category->id ?? null,
                    ];

                }
                $createdProduct = Product::insert($productToInsert);
                return response()->json(['count' => count($createdProduct), 
                'errors' => $errors], 201);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during creating many Products. Please try again later.'], 500);
            }
        } else {
            try {
                $productData = json_decode($request->getContent(), true);
              

                if(isset($productData['productCategoryId'])){
                    $category = ProductCategory::where('id', $productData['productCategoryId'])->first();
                    if (!$category) {
                        return response()->json(['error' => 'Product Category not found'], 404);
                    }
                }

                $createdProduct = Product::create([
                    'name' => $productData['name'],
                    'description' => $productData['description'] ?? null,
                    'rate' => $productData['rate'] ?? null,
                    'unit' => $productData['unit'] ?? null,
                    'productCategoryId' => $productData['productCategoryId'] ?? null,
                ]);

                $converted = arrayKeysToCamelCase($createdProduct->toArray());
                return response()->json($converted, 201);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during creating a single Products. Please try again later.'], 500);
            }
        }
    }

    // get all the product data controller method
    public function getAllProduct(Request $request): jsonResponse
    {
        if ($request->query('query') === 'all') {
            try {
                $allProduct = Product::where('status', 'true')->orderBy('id', 'desc')->get();

                $converted = arrayKeysToCamelCase($allProduct->toArray());
                return response()->json($converted, 200);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during getting all Products. Please try again later.'], 500);
            }
        } else if ($request->query('query') === 'search') {
            try {
                $key = trim($request->query('key'));
                $pagination = getPagination($request->query());
         
              
                $allProduct = Product::orderBy('id', 'desc')
                ->where('name', 'LIKE', '%' . $key . '%')
                ->whereHas('category', function ($query) use ($key) {
                    $query->where('name', 'LIKE', '%' . $key . '%');
                })
                ->orWhere('name', $key)
                ->skip($pagination['skip'])
                ->take($pagination['limit'])
                ->get();

                $count = Product::orderBy('id', 'desc')
                ->where('name', 'LIKE', '%' . $key . '%')
                ->whereHas('category', function ($query) use ($key) {
                    $query->where('name', 'LIKE', '%' . $key . '%');
                })
                ->orWhere('name', $key)
                ->count();

                $converted = arrayKeysToCamelCase($allProduct->toArray());
                $finalResult = [
                    'getAllProduct' => $converted,
                    'totalProductCount' => [
                        '_count' => [
                            'id' => $count
                        ],
                    ],
                ];

                return response()->json($finalResult, 200);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during getting all Products. Please try again later.'], 500);
            }
        } else if($request->query()){
            try {
                $pagination = getPagination($request->query());
                $allProduct = Product::with('category')
                ->when($request->query('status'), function ($query) use ($request) {
                    return $query->whereIn('status', explode(',', $request->query('status')));
                })
                ->when($request->query('productCategoryId'), function ($query) use ($request) {
                    return $query->whereIn('productCategoryId', explode(',', $request->query('productCategoryId')));
                })
                ->orderBy('id', 'desc')
                ->skip($pagination['skip'])
                ->take($pagination['limit'])
                ->get();

                $totalProduct = Product::
                with('category')
                ->when($request->query('status'), function ($query) use ($request) {
                    return $query->whereIn('status', explode(',', $request->query('status')));
                })
                ->when($request->query('productCategoryId'), function ($query) use ($request) {
                    return $query->whereIn('productCategoryId', explode(',', $request->query('productCategoryId')));
                })
                ->count();

                $converted = arrayKeysToCamelCase($allProduct->toArray());
                $final = [
                    "getAllProduct" => $converted,
                    "totalProduct" => $totalProduct,
                ];

                return response()->json($final, 200);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during getting all Products. Please try again later.'], 500);
            }
        }else {
            return response()->json(['error' => 'Invalid Query'], 400);
        }
    }

    // get a single product data controller method
    public function getSingleProduct(Request $request, $id): jsonResponse
    {
        try {
            $singleProduct = Product::with('category')->where('id', $id)->first();

            $converted = arrayKeysToCamelCase($singleProduct->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during getting a single Product. Please try again later.'], 500);
        }
    }

    // update a product data controller method
    public function updateSingleProduct(Request $request, $id): jsonResponse
    {
        try {
            $productData = json_decode($request->getContent(), true);
            $category = Product::where('id', $productData['productCategoryId'])->first();

            if (!$category) {
                return response()->json(['error' => 'Product Category not found'], 404);
            }

            $updatedProduct = Product::where('id', $id)->first();
            $updatedProduct->update([
                'name' => $productData['name'],
                'description' => $productData['description'] ?? null,
                'rate' => $productData['rate'],
                'unit' => $productData['unit'],
                'productCategoryId' => $productData['productCategoryId'],
            ]);

            $converted = arrayKeysToCamelCase($updatedProduct->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during updating a single Product. Please try again later.'], 500);
        }
    }

    // delete a product data controller method
    public function deleteSingleProduct(Request $request, $id): jsonResponse
    {
        try {
            $deletedProduct = Product::where('id', $id)->first();
            if (!$deletedProduct) {
                return response()->json(['error' => 'Product not found'], 404);
            }

            $deletedProduct->status = $request->status;
            $deletedProduct->save();

            return response()->json(['message' => 'Product Status Changed Successfully'], 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during deleting a single Product. Please try again later.'], 500);
        }
    }
}
