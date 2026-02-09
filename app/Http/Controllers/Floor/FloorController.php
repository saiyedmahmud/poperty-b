<?php

namespace App\Http\Controllers\Floor;

use App\Http\Controllers\Controller;
use App\Models\Floor;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FloorController extends Controller
{
    // CREATE - POST /
    public function createSingleFloor(Request $request): JsonResponse
    {
        try {
            $created = Floor::create([
                'buildingId' => $request->input('buildingId'),
                'floorNumber' => $request->input('floorNumber'),
            ]);
            return $this->response($created->toArray());
        } catch (Exception $error) {
            return response()->json(['error' => 'An error occurred during create. Please try again later.'], 500);
        }
    }

    // READ ALL - GET /
    public function getAllFloor(Request $request): JsonResponse
    {
        if ($request->query('query') === 'all') {
            try {
                $all = Floor::with('building', 'flats')->orderBy('id', 'desc')->get();

                return $this->response([
                    'getAllFloor' => $all->toArray(),
                    'totalFloor' => Floor::count(),
                ]);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during getting records. Please try again later.'], 500);
            }
        } else if ($request->query('query') === 'search') {
            try {
                $pagination = getPagination($request->query());
                $buildingId = trim($request->query('buildingId'));

                $results = Floor::where('buildingId', $buildingId)
                    ->with('building', 'flats')
                    ->orderBy('id', 'desc')
                    ->skip($pagination['skip'])
                    ->take($pagination['limit'])
                    ->get();

                $count = Floor::where('buildingId', $buildingId)->count();

                return $this->response([
                    'getAllFloor' => $results->toArray(),
                    'totalFloor' => $count,
                ]);
            } catch (Exception $error) {
                return response()->json(['error' => 'An error occurred during search. Please try again later.'], 500);
            }
        } else {
            try {
                $pagination = getPagination($request->query());
                $results = Floor::with('building', 'flats')
                    ->orderBy('id', 'desc')
                    ->skip($pagination['skip'])
                    ->take($pagination['limit'])
                    ->get();

                $count = Floor::count();

                return $this->response([
                    'getAllFloor' => $results->toArray(),
                    'totalFloor' => $count,
                ]);
            } catch (Exception $error) {
                return response()->json(['error' => 'An error occurred during getting records. Please try again later.'], 500);
            }
        }
    }

    // READ SINGLE - GET /{id}
    public function getSingleFloor(Request $request, $id): JsonResponse
    {
        try {
            $single = Floor::with('building', 'flats')->find($id);
            if (!$single) {
                return $this->badRequest('Record not found!');
            }
            return $this->response($single->toArray());
        } catch (Exception $error) {
            return response()->json(['error' => 'An error occurred during getting record. Please try again later.'], 500);
        }
    }

    // UPDATE - PUT /{id}
    public function updateSingleFloor(Request $request, $id): JsonResponse
    {
        try {
            $record = Floor::where('id', $id)->first();

            if (!$record) {
                return $this->badRequest('Record not found!');
            }

            $record->update([
                'buildingId' => $request->input('buildingId', $record->buildingId),
                'floorNumber' => $request->input('floorNumber', $record->floorNumber),
            ]);

            return response()->json(['message' => 'Record Updated Successfully'], 200);
        } catch (Exception $error) {
            return response()->json(['error' => 'An error occurred during update. Please try again later.'], 500);
        }
    }

    // DELETE (Soft) - DELETE /{id}
    public function deleteSingleFloor(Request $request, $id): JsonResponse
    {
        try {
            $record = Floor::where('id', $id)->first();

            if (!$record) {
                return $this->badRequest('Record not found!');
            }

            $record->delete();

            return response()->json(['message' => 'Record Deleted Successfully'], 200);
        } catch (Exception $error) {
            return response()->json(['error' => 'An error occurred during delete. Please try again later.'], 500);
        }
    }
}
