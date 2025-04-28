<?php

namespace App\Http\Controllers\Crm\Opportunity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Exception;
use App\Models\OpportunityStage;

class OpportunityStageController extends Controller
{
    //create a opportunityStage controller method
    public function createOpportunityStage(Request $request): jsonResponse
    {
        if ($request->query('query') === 'deletemany') {
            try {
                $ids = json_decode($request->getContent(), true);
                $deletedOpportunityStage = OpportunityStage::destroy($ids);

                $deletedCounted = [
                    'count' => $deletedOpportunityStage,
                ];

                return response()->json($deletedCounted, 200);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during deleting many Opportunity Stage. Please try again later.'], 500);
            }
        } else if ($request->query('query') === 'createmany') {
            try {
                $opportunityStageData = json_decode($request->getContent(), true);
                $createdOpportunityStage = collect($opportunityStageData)->map(function ($item) {
                    return OpportunityStage::create([
                        'opportunityStageName' => $item['opportunityStageName'],
                    ]);
                });

                $converted = arrayKeysToCamelCase($createdOpportunityStage->toArray());
                return response()->json($converted, 201);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during create many Opportunity Stage. Please try again later.'], 500);
            }
        } else {
            try {
                $opportunityStageData = json_decode($request->getContent(), true);
                $createdOpportunityStage = OpportunityStage::create([
                    'opportunityStageName' => $opportunityStageData['opportunityStageName'],
                ]);

                $converted = arrayKeysToCamelCase($createdOpportunityStage->toArray());
                return response()->json($converted, 201);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during creating a single Opportunity Stage. Please try again later.'], 500);
            }
        }
    }

    // get all the opportunityStage data controller method
    public function getAllOpportunityStage(Request $request): jsonResponse
    {
        try {
            $allOpportunityStage = OpportunityStage::with('opportunity')->orderBy('id', 'desc')->get();

            $converted = arrayKeysToCamelCase($allOpportunityStage->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during getting all Opportunity Stage. Please try again later.'], 500);
        }
    }

    // get a single opportunityStage data controller method
    public function getSingleOpportunityStage(Request $request, $id): jsonResponse
    {
        try {
            $singleOpportunityStage = OpportunityStage::with('opportunity')->findOrFail($id);

            $converted = arrayKeysToCamelCase($singleOpportunityStage->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during getting a single Opportunity Stage. Please try again later.'], 500);
        }
    }

    // update single opportunityStage data controller method
    public function updateOpportunityStage(Request $request, $id): jsonResponse
    {
        try {
            $opportunityStageData = json_decode($request->getContent(), true);
            $updatedOpportunityStage = OpportunityStage::where('id', $id)->first();
            $updatedOpportunityStage->update([
                'opportunityStageName' => $opportunityStageData['opportunityStageName'],
            ]);

            $converted = arrayKeysToCamelCase($updatedOpportunityStage->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during updating a single Opportunity Stage. Please try again later.'], 500);
        }
    }

    // delete single opportunityStage data controller method
    public function deleteOpportunityStage(Request $request, $id): jsonResponse
    {
        try {
            $deletedOpportunityStage = OpportunityStage::where('id', $id)->delete();

            if ($deletedOpportunityStage) {
                return response()->json(['message' => 'Opportunity Stage Deleted Successfully'], 200);
            } else {
                return response()->json(['error' => 'Failed to delete Opportunity Stage!'], 404);
            }
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during deleting a single Opportunity Stage. Please try again later.'], 500);
        }
    }
}
