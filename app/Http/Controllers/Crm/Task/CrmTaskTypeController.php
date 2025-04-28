<?php

namespace App\Http\Controllers\Crm\Task;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Exception;
use App\Models\CrmTaskType;

class CrmTaskTypeController extends Controller
{
    //create crmTaskType controller method
    public function createCrmTaskType(Request $request): jsonResponse
    {
        if ($request->query('query') === 'deletemany') {
            try {
                $ids = json_decode($request->getContent(), true);
                $deletedCrmTaskType = CrmTaskType::destroy($ids);

                $deletedCounted = [
                    'count' => $deletedCrmTaskType,
                ];

                return response()->json($deletedCounted, 200);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during deleting many Crm Task Type. Please try again later.'], 500);
            }
        } else if ($request->query('query') === 'createmany') {
            try {
                $crmTaskTypeData = json_decode($request->getContent(), true);
                $createdCrmTaskType = collect($crmTaskTypeData)->map(function ($item) {
                    return CrmTaskType::create([
                        'taskTypeName' => $item['taskTypeName'],
                    ]);
                });

                $converted = arrayKeysToCamelCase($createdCrmTaskType->toArray());
                return response()->json($converted, 201);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during creating many Crm Task Type. Please try again later.'], 500);
            }
        } else {
            try {
                $crmTaskTypeData = json_decode($request->getContent(), true);
                $createdCrmTaskType = CrmTaskType::create([
                    'taskTypeName' => $crmTaskTypeData['taskTypeName'],
                ]);

                $converted = arrayKeysToCamelCase($createdCrmTaskType->toArray());
                return response()->json($converted, 201);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during creating a single Crm Task Type. Please try again later.'], 500);
            }
        }
    }

    // get all the crmTaskType Data controller method
    public function getAllCrmTaskType(Request $request): jsonResponse
    {
        try {
            $allCrmTaskType = CrmTaskType::with('tasks')->orderBy('id', 'desc')->get();

            $converted = arrayKeysToCamelCase($allCrmTaskType->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during getting all Crm Task Type. Please try again later.', 
        'mas'=> $err->getMessage()], 500);
        }
    }

    // get a single crmTaskType data controller method
    public function getSingleCrmTaskType(Request $request, $id): jsonResponse
    {
        try {
            $singleCrmTaskType = CrmTaskType::with('tasks')->orderBy('id', 'desc')->findOrFail($id);

            $converted = arrayKeysToCamelCase($singleCrmTaskType->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during getting a single Crm Task Type. Please try again later.'], 500);
        }
    }

    // update crmTaskType controller method
    public function updateCrmTaskType(Request $request, $id): jsonResponse
    {
        try {
            $crmTaskTypeData = json_decode($request->getContent(), true);

            $updatedCrmTaskType = CrmTaskType::where('id', $id)->first();
            $updatedCrmTaskType->update([
                'taskTypeName' => $crmTaskTypeData['taskTypeName'],
            ]);

            $converted = arrayKeysToCamelCase($updatedCrmTaskType->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during updating a single Crm Task Type. Please try again later.'], 500);
        }
    }

    // delete crmTaskType Controller method
    public function deleteCrmTaskType(Request $request, $id): jsonResponse
    {
        try {
            $deletedCrmTaskType = CrmTaskType::where('id', $id)->delete();

            if ($deletedCrmTaskType) {
                return response()->json(['message' => 'Crm Task Type Deleted Successfully'], 200);
            } else {
                return response()->json(['error' => 'Failed to delete Crm Task Type!'], 409);
            }
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during deleting a single Crm Task Type. Please try again later.'], 500);
        }
    }
}
