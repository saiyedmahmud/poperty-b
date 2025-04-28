<?php

namespace App\Http\Controllers\Settings\Priority;

use App\Http\Controllers\Controller;
use App\Models\Priority;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;

class PriorityController extends Controller
{
    public function createPriority(Request $request): JsonResponse
    {
        if ($request->query('query') === "createmany") {
            try {

                $data = json_decode($request->getContent(), true);
                foreach ($data as $item) {
                    Priority::insertOrIgnore($item);
                }
                return response()->json(["count" => count(json_decode($data))], 201);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during creating Priority. Please try again later.'], 500);
            }
        } else {
            try {
            
                $createPriority = Priority::create([
                    'name' => $request->input('name'),
                    'colourValue' => $request->input('colourValue')
                ]);

                $converted = arrayKeysToCamelCase($createPriority->toArray());
                return response()->json($converted, 201);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during creating Priority. Please try again later.'], 500);
            }
        }
    }

    public function getAllPriority(Request $request): JsonResponse
    {
        if ($request->query('query') === "all") {
            try {
                $getAllPriority = Priority::orderBy('id', 'asc')->get();
                $converted = arrayKeysToCamelCase($getAllPriority->toArray());
                return response()->json($converted);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during getting Priority. Please try again later.'], 500);
            }
        } else {
            return response()->json(["error" => "Invalid query!"], 400);
        }
    }

    public function getSinglePriority($id): JsonResponse
    {
        try {
            $getSinglePriority = Priority::find($id);
            $converted = arrayKeysToCamelCase($getSinglePriority->toArray());
            return response()->json($converted);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during getting Priority. Please try again later.'], 500);
        }
    }

    public function updatePriority(Request $request, $id): JsonResponse
    {
        try {
            $updatePriority = Priority::where('id', $id)->update($request->all());

            if (!$updatePriority) {
                return response()->json(['error' => 'Failed to update Priority!'], 404);
            }
            return response()->json(['message' => 'Priority Updated Successfully'], 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during updating Priority. Please try again later.'], 500);
        }
    }

    public function deletePriority($id): JsonResponse
    {
        try {
            $deletePriority = Priority::find($id);
            $deletePriority->delete();
            return response()->json(["message" => "Priority deleted successfully"], 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during deleting Priority. Please try again later.'], 500);
        }
    }
}
