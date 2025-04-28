<?php

namespace App\Http\Controllers\Crm\Opportunity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Exception;
use App\Models\OpportunitySource;

class OpportunitySourceController extends Controller
{
    //create a opportunitySource controller method
    public function createOpportunitySource(Request $request): jsonResponse
    {
        if ($request->query('query') === 'deletemany') {
            try {
                $ids = json_decode($request->getContent(), true);
                $deletedOpportunitySource = OpportunitySource::destroy($ids);

                $deletedCounted = [
                    'count' => $deletedOpportunitySource,
                ];

                return response()->json($deletedCounted, 200);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during deleting many Opportunity Source. Please try again later.'], 500);
            }
        } else if ($request->query('query') === 'createmany') {
            try {
                $opportunitySourceData = json_decode($request->getContent(), true);
                $createdOpportunitySource = collect($opportunitySourceData)->map(function ($item) {
                    return OpportunitySource::create([
                        'opportunitySourceName' => $item['opportunitySourceName'],
                    ]);
                });

                $converted = arrayKeysToCamelCase($createdOpportunitySource->toArray());
                return response()->json($converted, 201);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during creating many Opportunity Source. Please try again later.'], 500);
            }
        } else {
            try {
                $opportunitySourceData = json_decode($request->getContent(), true);
                $createdOpportunitySource = OpportunitySource::create([
                    'opportunitySourceName' => $opportunitySourceData['opportunitySourceName'],
                ]);

                $converted = arrayKeysToCamelCase($createdOpportunitySource->toArray());
                return response()->json($converted, 201);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during creating a single Opportunity Source. Please try again later.'], 500);
            }
        }
    }

    // get all the opportunitySource data controller method
    public function getAllOpportunitySource(Request $request): jsonResponse
    {
        try {
            $allOpportunitySource = OpportunitySource::with('opportunity')->orderBy('id', 'desc')->get();

            $converted = arrayKeysToCamelCase($allOpportunitySource->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during getting all Opportunity Source. Please try again later.'], 500);
        }
    }

    // get a single opportunitySource data controller method
    public function getSingleOpportunitySource(Request $request, $id): jsonResponse
    {
        try {
            $singleOpportunitySource = OpportunitySource::with('opportunity')->findOrFail($id);

            function arrayKeysToCamelCase($array)
            {
                $result = [];
                foreach ($array as $key => $value) {
                    $key = Str::camel($key);
                    if (is_array($value)) {
                        $value = arrayKeysToCamelCase($value);
                    }
                    $result[$key] = $value;
                }
                return $result;
            }

            $singleOpportunitySource = arrayKeysToCamelCase($singleOpportunitySource->toArray());
            return response()->json($singleOpportunitySource, 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during getting a single Opportunity Source. Please try again later.'], 500);
        }
    }

    // update single opportunitySource data controller method
    public function updateOpportunitySource(Request $request, $id): jsonResponse
    {
        try {
            $opportunitySourceData = json_decode($request->getContent(), true);
            $updatedOpportunitySource = OpportunitySource::where('id', $id)->first();
            $updatedOpportunitySource->update([
                'opportunitySourceName' => $opportunitySourceData['opportunitySourceName'],
            ]);

            $converted = arrayKeysToCamelCase($updatedOpportunitySource->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during updating a single Opportunity Source. Please try again later.'], 500);
        }
    }

    // delete single opportunitySource data controller method
    public function deleteOpportunitySource(Request $request, $id): jsonResponse
    {
        try {
            $deletedOpportunitySource = OpportunitySource::where('id', $id)->delete();

            if ($deletedOpportunitySource) {
                return response()->json(['message' => 'OpportunitySource Deleted Successfully'], 200);
            } else {
                return response()->json(['error' => 'Failed to delete Opportunity Source!'], 404);
            }
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during deleting a single Opportunity Source. Please try again later.'], 500);
        }
    }
}
