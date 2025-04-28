<?php

namespace App\Http\Controllers\HR\Project;

use App\Http\Controllers\Controller;
use App\Models\Milestone;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MilestoneController extends Controller
{
    //create Milestone
    public function createMilestone(Request $request): JsonResponse
    {
        try {
            $createdMilestone = Milestone::create([
                'projectId' => $request->input('projectId'),
                'name' => $request->input('name'),
                'startDate' => Carbon::parse($request->input('startDate')),
                'endDate' => Carbon::parse($request->input('endDate')),
                'description' => $request->input('description'),
            ]);

            $converted = arrayKeysToCamelCase($createdMilestone->toArray());
            return response()->json($converted, 201);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during create Milestone. Please try again later.'], 500);
        }
    }

    //get all Milestone
    public function getAllMilestone(Request $request): JsonResponse
    {
        if ($request->query('query') === "all") {
            try {
                $milestone = Milestone::with('project')->get();
                $converted = arrayKeysToCamelCase($milestone->toArray());
                return response()->json($converted, 200);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during getting Milestone. Please try again later.'], 500);
            }
        } else if ($request->query('status')) {
            $pagination = getPagination($request->query());
            try {
                $milestone = Milestone::where('status', $request->query('status'))
                    ->skip($pagination['skip'])
                    ->limit($pagination['limit'])
                    ->get();
                $converted = arrayKeysToCamelCase($milestone->toArray());
                return response()->json($converted, 200);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during getting Milestone. Please try again later.'], 500);
            }
        } else {
            return response()->json(['error' => 'No Milestone Found'], 404);
        }
    }

    //get milestone by project id
    public function getMilestoneByProjectId($id): JsonResponse
    {
        try {
            $milestone = Milestone::with('project')->where('projectId', $id)->where('status', '!=', 'DELETED')->get();
            $converted = arrayKeysToCamelCase($milestone->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during getting Milestone. Please try again later.'], 500);
        }
    }

    //get single Milestone
    public function getSingleMilestone(Request $request, $id): JsonResponse
    {
        try {
            $milestone = Milestone::with('project')->find($id);
            $converted = arrayKeysToCamelCase($milestone->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during getting Milestone. Please try again later.'], 500);
        }
    }

    //update Milestone
    public function updateMilestone(Request $request, $id): JsonResponse
    {

        if ($request->query('query') === 'all') {
            try {
                $milestone = Milestone::where('id', $id)->update([
                    'projectId' => $request->input('projectId'),
                    'name' => $request->input('name'),
                    'startDate' => Carbon::parse($request->input('startDate')),
                    'endDate' => Carbon::parse($request->input('endDate')),
                    'description' => $request->input('description'),
                ]);

                if (!$milestone) {
                    return response()->json(['error' => 'Failed To Update Milestone'], 404);
                }
                return response()->json(['message' => 'Milestone updated successfully'], 200);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during getting Milestone. Please try again later.'], 500);
            }
        } else if ($request->query('query') === 'status') {
            try {
                $milestone = Milestone::where('id', $id)->update([
                    'status' => $request->input('status'),
                ]);

                if (!$milestone) {
                    return response()->json(['error' => 'Failed To Update Milestone'], 404);
                }
                return response()->json(['message' => 'Milestone Status updated successfully'], 200);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during getting Milestone. Please try again later.'], 500);
            }
        } else {
            return response()->json(['error' => 'No Milestone Found'], 404);
        }
    }

    //delete Milestone
    public function deleteMilestone(Request $request, $id): JsonResponse
    {
        try {
            $milestone = Milestone::where('id', $id)->update([
                'status' => $request->input('status'),
            ]);
            if (!$milestone) {
                return response()->json(['error' => 'Failed To Delete Milestone'], 404);
            }
            return response()->json(['message' => 'Milestone Deleted Successfully'], 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during getting Milestone. Please try again later.'], 500);
        }
    }
}
