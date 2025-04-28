<?php

namespace App\Http\Controllers\Crm\Task;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Exception;
use App\Models\CrmTaskStatus;

class CrmTaskStatusController extends Controller
{
    //create crmTaskStatus controller method
    public function createCrmTaskStatus(Request $request): jsonResponse
    {
        if ($request->query('query') === 'deletemany') {
            try {
                $ids = json_decode($request->getContent(), true);
                $deletedCrmTaskStatus = CrmTaskStatus::destroy($ids);

                $deletedCounted = [
                    'count' => $deletedCrmTaskStatus,
                ];

                return response()->json($deletedCounted, 200);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during deleting many crm task Status. Please try again later.'], 500);
            }
        } else if ($request->query('query') === 'createmany') {
            try {
                $crmTaskStatusData = json_decode($request->getContent(), true);
                $createdCrmTaskStatus = collect($crmTaskStatusData)->map(function ($item) {
                    return CrmTaskStatus::create([
                        'taskStatusName' => $item['taskStatusName'],
                    ]);
                });

                $converted = arrayKeysToCamelCase($createdCrmTaskStatus->toArray());
                return response()->json($converted, 201);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during creating many crm task Status. Please try again later.'], 500);
            }
        } else {
            try {
                $crmTaskStatusData = json_decode($request->getContent(), true);
                $createdCrmTaskStatus = CrmTaskStatus::create([
                    'taskStatusName' => $crmTaskStatusData['taskStatusName'],
                ]);

                $converted = arrayKeysToCamelCase($createdCrmTaskStatus->toArray());
                return response()->json($converted, 201);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during creating a single crm task Status. Please try again later.'], 500);
            }
        }
    }

    // get all the crmTaskStatus Data controller method
    public function getAllCrmTaskStatus(Request $request): jsonResponse
    {
        try {
            $allCrmTaskStatus = CrmTaskStatus::with('tasks')->orderBy('id', 'desc')->get();

            $converted = arrayKeysToCamelCase($allCrmTaskStatus->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during getting all crm task Status. Please try again later.'], 500);
        }
    }

    // get a single crmTaskStatus data controller method
    public function getSingleCrmTaskStatus(Request $request, $id): jsonResponse
    {
        try {
            $singleCrmTaskStatus = CrmTaskStatus::with('tasks')->orderBy('id', 'desc')->findOrFail($id);

            $converted = arrayKeysToCamelCase($singleCrmTaskStatus->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during getting a single crm task Status. Please try again later.'], 500);
        }
    }

    // update crmTaskStatus controller method
    public function updateCrmTaskStatus(Request $request, $id): jsonResponse
    {
        try {
            $crmTaskStatusData = json_decode($request->getContent(), true);
            $updatedCrmTaskStatus = CrmTaskStatus::where('id', $id)->first();
            $updatedCrmTaskStatus->update([
                'taskStatusName' => $crmTaskStatusData['taskStatusName'],
            ]);

            $converted = arrayKeysToCamelCase($updatedCrmTaskStatus->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during updating a single crm task Status. Please try again later.'], 500);
        }
    }

    // delete crmTaskStatus Controller method
    public function deleteCrmTaskStatus(Request $request, $id): jsonResponse
    {
        try {
            $deletedCrmTaskStatus = CrmTaskStatus::where('id', $id)->delete();

            if ($deletedCrmTaskStatus) {
                return response()->json(['message' => 'Crm Task Status Deleted Successfully'], 200);
            } else {
                return response()->json(['error' => 'Failed to update Crm Task Status!'], 409);
            }
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during deleting a single crm task Status. Please try again later.'], 500);
        }
    }
}
