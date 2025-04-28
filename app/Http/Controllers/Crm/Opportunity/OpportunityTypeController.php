<?php

namespace App\Http\Controllers\Crm\Opportunity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Exception;
use App\Models\OpportunityType;

class OpportunityTypeController extends Controller
{
    //create a opportunityType controller method
    public function createOpportunityType(Request $request): jsonResponse
    {
        if ($request->query('query') === 'deletemany') {
            try {
                $ids = json_decode($request->getContent(), true);
                $deletedOpportunityType = OpportunityType::destroy($ids);

                $deletedCounted = [
                    'count' => $deletedOpportunityType,
                ];

                return response()->json($deletedCounted, 200);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during deleting many Opportunity Type. Please try again later.'], 500);
            }
        } else if ($request->query('query') === 'createmany') {
            try {
                $opportunityTypeData = json_decode($request->getContent(), true);
                $createdOpportunityType = collect($opportunityTypeData)->map(function ($item) {
                    return OpportunityType::create([
                        'opportunityTypeName' => $item['opportunityTypeName'],
                    ]);
                });

                $converted = arrayKeysToCamelCase($createdOpportunityType->toArray());
                return response()->json($converted, 201);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during create many Opportunity Type. Please try again later.'], 500);
            }
        } else {
            try {
                $opportunityTypeData = json_decode($request->getContent(), true);
                $createdOpportunityType = OpportunityType::create([
                    'opportunityTypeName' => $opportunityTypeData['opportunityTypeName'],
                ]);

                $converted = arrayKeysToCamelCase($createdOpportunityType->toArray());
                return response()->json($converted, 201);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during create a single Opportunity Type. Please try again later.'], 500);
            }
        }
    }

    // get all the opportunityType data controller method
    public function getAllOpportunityType(Request $request): jsonResponse
    {
        try {
            $allOpportunityType = OpportunityType::with('opportunity')->orderBy('id', 'desc')->get();

            $converted = arrayKeysToCamelCase($allOpportunityType->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during getting all Opportunity Type. Please try again later.'], 500);
        }
    }

    // get a single opportunityType data controller method
    public function getSingleOpportunityType(Request $request, $id): jsonResponse
    {
        try {
            $singleOpportunityType = OpportunityType::with('opportunity')->findOrFail($id);

            $converted = arrayKeysToCamelCase($singleOpportunityType->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during getting a single Opportunity Type. Please try again later.'], 500);
        }
    }

    // update single opportunityType data controller method
    public function updateOpportunityType(Request $request, $id): jsonResponse
    {
        try {
            $opportunityTypeData = json_decode($request->getContent(), true);
            $updatedOpportunityType = OpportunityType::where('id', $id)->first();
            $updatedOpportunityType->update([
                'opportunityTypeName' => $opportunityTypeData['opportunityTypeName'],
            ]);

            $converted = arrayKeysToCamelCase($updatedOpportunityType->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during updating a single Opportunity Type. Please try again later.'], 500);
        }
    }

    // delete single opportunityType data controller method
    public function deleteOpportunityType(Request $request, $id): jsonResponse
    {
        try {
            $deletedOpportunityType = OpportunityType::where('id', $id)->delete();

            if ($deletedOpportunityType) {
                return response()->json(['message' => 'OpportunityType Deleted Successfully'], 200);
            } else {
                return response()->json(['error' => 'Failed to delete Opportunity Type!'], 404);
            }
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during deleting a single Opportunity Type. Please try again later.'], 500);
        }
    }
}
