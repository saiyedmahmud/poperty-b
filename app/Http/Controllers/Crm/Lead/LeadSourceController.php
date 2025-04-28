<?php

namespace App\Http\Controllers\Crm\Lead;

use App\Http\Controllers\Controller;
use App\Models\LeadSource;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;


class LeadSourceController extends Controller
{
    //create leadSource controller method
    public function createLeadSource(Request $request): jsonResponse
    {
        if ($request->query('query') === 'deletemany') {
            try {
                $ids = json_decode($request->getContent(), true);
                $deletedLeadSource = LeadSource::destroy($ids);

                $deletedCounted = [
                    'count' => $deletedLeadSource,
                ];

                return response()->json($deletedCounted, 200);
            } catch (Exception $err) {
                return $this->badRequest($err->getMessage());
            }
        } else if ($request->query('query') === 'createmany') {
            try {
                $leadSourceData = json_decode($request->getContent(), true);

                $createdLeadSource = collect($leadSourceData)->map(function ($item) {
                    return LeadSource::create([
                        'name' => $item['name'],
                    ]);
                });

                $converted = arrayKeysToCamelCase($createdLeadSource->toArray());
                return response()->json($converted, 201);
            } catch (Exception $err) {
                return $this->badRequest($err->getMessage());
            }
        } else {
            try {
                $leadSourceData = json_decode($request->getContent(), true);

                $createdLeadSource = LeadSource::create([
                    'name' => $leadSourceData['name'],
                ]);

                $converted = arrayKeysToCamelCase($createdLeadSource->toArray());
                return response()->json($converted, 201);
            } catch (Exception $err) {
                return $this->badRequest($err->getMessage());
            }
        }
    }

    //get all leadSource controller method
    public function getAllLeadSource(): JsonResponse
    {
        try {
            $leadSource = LeadSource::with('lead')->orderBy('id', 'desc')->get();

            $converted = arrayKeysToCamelCase($leadSource->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during getting all lead sources'], 500);
        }

    }

    //get single leadSource controller method
    public function getSingleLeadSource($id): JsonResponse
    {
        try {
            $singleLeadSource = LeadSource::with('lead')->orderBy('id', 'desc')->findOrfail($id);

            $converted = arrayKeysToCamelCase($singleLeadSource->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during getting single lead source'], 500);
        }

    }

    //update leadSource controller method
    public function updateLeadSource(Request $request, $id): JsonResponse
    {
        try {
            $leadSourceData = json_decode($request->getContent(), true);

            $updatedLeadSource = LeadSource::where('id', $id)->first();
            $updatedLeadSource->update([
                'name' => $leadSourceData['name'],
            ]);

            $converted = arrayKeysToCamelCase($updatedLeadSource->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during updating lead source'], 500);
        }
    }

    //delete leadSource controller method
    public function deleteLeadSource($id): JsonResponse
    {
        try {
            $deletedLeadSource = LeadSource::where('id', $id)->delete();

            if($deletedLeadSource){
                return response()->json(['message' => 'Lead source deleted successfully'], 200);
            } else {
                return response()->json(['error' => 'Failed to delete lead source'], 409);
            }
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during deleting lead source'], 500);
        }
    }
}