<?php

namespace App\Http\Controllers\Crm\Industry;

use App\Http\Controllers\Controller;
use App\Models\Industry;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class IndustryController extends Controller
{
    public function crateIndustry(Request $request): JsonResponse
    {
        if ($request->query('query') === 'deletemany') {
            try {
                $data = json_decode($request->getContent(), true);
                $deleteMany = Industry::destroy($data);

                return response()->json([
                    'count' => $deleteMany,
                ], 200);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during deleting many industry. Please try again later.'], 500);
            }
        } else if ($request->query('query') === 'createmany') {
            try {
                $data = json_decode($request->getContent(), true);
                foreach ($data as $item) {
                    Industry::insertOrIgnore($item);
                }
                return response()->json(['count' => $data], 201);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during creating many industry. Please try again later.'], 500);
            }
        } else {
            try {
                $createdIndustry = Industry::create([
                    'industryName' => $request->input('industryName'),
                ]);
                $converted = arrayKeysToCamelCase($createdIndustry->toArray());
                return response()->json($converted, 201);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during creating a single industry. Please try again later.'], 500);
            }
        }
    }

    //get all industry
    public function getAllIndustry(Request $request): JsonResponse
    {
        try {
            $allIndustry = Industry::with('company')->orderBy('id', 'desc')->get();
            $converted = arrayKeysToCamelCase($allIndustry->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during getting all industry. Please try again later.'], 500);
        }
    }

    //get single
    public function getSingleIndustry(Request $request, $id): JsonResponse
    {
        try {
            $singleIndustry = Industry::with('company')
                ->find($id);

            $converted = arrayKeysToCamelCase($singleIndustry->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during getting a single industry. Please try again later.'], 500);
        }
    }

    //update
    public function updateIndustry(Request $request, $id): JsonResponse
    {
        try {
            $singleIndustry = Industry::find($id);
            $singleIndustry->industryName = $request->input('industryName');
            $singleIndustry->save();

            $converted = arrayKeysToCamelCase($singleIndustry->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during updating a single industry. Please try again later.'], 500);
        }
    }

    //delete
    public function deleteIndustry(Request $request, $id): JsonResponse
    {
        try {
            $singleIndustry = Industry::find($id);
            $singleIndustry->delete();

            $converted = arrayKeysToCamelCase($singleIndustry->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {

            return response()->json(['error' => 'An error occurred during deleting a single industry. Please try again later.'], 500);
        }
    }
}
