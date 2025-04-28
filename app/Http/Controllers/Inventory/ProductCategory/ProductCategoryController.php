<?php

namespace App\Http\Controllers\Inventory\ProductCategory;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    //create single productCategory controller method
    public function createSingleProductCategory(Request $request): JsonResponse
    {
        if ($request->query('query') === 'deletemany') {
            try {
                $ids = json_decode($request->getContent(), true);
                $deletedManyProductCategory = ProductCategory::destroy($ids);

                $deletedCount = [
                    'count' => $deletedManyProductCategory
                ];

                return response()->json($deletedCount, 200);
            } catch (Exception $err) {
                return response()->json([
                    'message' => 'An error occurred during delete Product Category. Please try again later.',
                    'error' => $err->getMessage()
                ], 500);
            }
        } elseif ($request->query('query') === 'createmany') {
            try {
                $categoryData = json_decode($request->getContent(), true);

                $createdProductCategory = collect($categoryData)->map(function ($item) {
                    return ProductCategory::firstOrCreate([
                        'name' => $item['name'],
                    ]);
                });

                $result = [
                    'count' => count($createdProductCategory),
                ];

                return response()->json($result, 201);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during create productCategory. Please try again later.'], 500);
            }
        } else {
            try {
                $categoryData = json_decode($request->getContent(), true);

                $createdProductCategory = ProductCategory::create([
                    'name' => $categoryData['name'],
                ]);

                return $this->response($createdProductCategory->toArray(),201);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during create productCategory. Please try again later.'], 500);
            }
        }
    }

    // get all productCategory controller method
    public function getAllProductCategory(Request $request): JsonResponse
    {
        if ($request->query('query') === 'all') {
            try {
                $getAllProductCategory = ProductCategory::orderBy('id', 'desc')
                    ->get();
                return $this->response($getAllProductCategory->toArray());
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during getting productCategory. Please try again later.'], 500);
            }
        } elseif ($request->query('query') === 'info') {
            try {
                $aggregation = ProductCategory::where('status', 'true')
                    ->count();

                $result = [
                    '_count' => [
                        'id' => $aggregation,
                    ],
                ];

                return response()->json($result, 200);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during getting productCategory. Please try again later.'], 500);
            }
        } else if ($request->query('query') === 'search') {
            try {
                $pagination = getPagination($request->query());
                $getAllProductCategory = ProductCategory::where('name', 'LIKE', '%' . $request->query('key') . '%')
                    ->orderBy('id', 'desc')
                    ->skip($pagination['skip'])
                    ->take($pagination['limit'])
                    ->get();

                $totalProductCategory = ProductCategory::where('name', 'LIKE', '%' . $request->query('key') . '%')
                    ->count();

                return $this->response([
                    'getAllProductCategory' => $getAllProductCategory->toArray(),
                    'totalProductCategory' => $totalProductCategory,
                ]);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during getting productCategory. Please try again later.'], 500);
            }
        } else if ($request->query()) {
            try {
                $pagination = getPagination($request->query());
                $getAllProductCategory = ProductCategory::when($request->query('status'), function ($query) use ($request) {
                    return $query->whereIn('status', explode(',', $request->query('status')));
                })
                    ->orderBy('id', 'desc')
                    ->skip($pagination['skip'])
                    ->take($pagination['limit'])
                    ->get();
                $totalProductCategory = ProductCategory::when($request->query('status'), function ($query) use ($request) {
                    return $query->whereIn('status', explode(',', $request->query('status')));
                })
                    ->count();

                return $this->response([
                    'getAllProductCategory' => $getAllProductCategory->toArray(),
                    'totalProductCategory' => $totalProductCategory,
                ]);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during getting productCategory. Please try again later.'], 500);
            }
        } else {
            return response()->json(['error' => 'Invalid Query!'], 400);
        }
    }

    // get a single productCategory controller method
    public function getSingleProductCategory(Request $request, $id): JsonResponse
    {
        try {
            $singleProductCategory = ProductCategory::where('id', (int) $id)
                ->first();

            if (!$singleProductCategory) {
                return response()->json(['error' => 'Product Category not found'], 404);
            }

            return $this->response($singleProductCategory->toArray());
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during getting productCategory. Please try again later.'], 500);
        }
    }

    // update a single productCategory controller method
    public function updateSingleProductCategory(Request $request, $id): JsonResponse
    {
        try {
            $updatedProductCategory = ProductCategory::where('id', (int) $id)
                ->update($request->all());

            if (!$updatedProductCategory) {
                return response()->json(['error' => 'Failed To Update Product Category'], 404);
            }
            return response()->json(['message' => 'Product Category updated Successfully'], 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during getting productCategory. Please try again later.'], 500);
        }
    }

    // delete a single productCategory controller method
    public function deleteSingleProductCategory(Request $request, $id): JsonResponse
    {
        try {
            $deletedProductCategory = ProductCategory::where('id', (int) $id)
                ->update([
                    'status' => $request->input('status'),
                ]);

            if (!$deletedProductCategory) {
                return response()->json(['error' => 'Failed To Delete Product Category'], 404);
            }
            return response()->json(['message' => 'Product Category deleted Successfully'], 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during getting productCategory. Please try again later.'], 500);
        }
    }
}
