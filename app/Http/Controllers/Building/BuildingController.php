<?php

namespace App\Http\Controllers\Building;

use App\Http\Controllers\Controller;
use App\Models\Building;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BuildingController extends Controller
{
    // CREATE - POST /
    public function createSingleBuilding(Request $request): JsonResponse
    {
        try {
            $created = Building::create([
                'name' => $request->input('name'),
                'address' => $request->input('address'),
            ]);
            return $this->response($created->toArray());
        } catch (Exception $error) {
            return response()->json(['error' => 'An error occurred during create. Please try again later.'], 500);
        }
    }

    // READ ALL - GET /
    public function getAllBuilding(Request $request): JsonResponse
    {
        if ($request->query('query') === 'all') {
            try {
                $all = Building::with('floors.flats')->orderBy('id', 'desc')->get();

                return $this->response([
                    'getAllBuilding' => $all->toArray(),
                    'totalBuilding' => Building::count(),
                ]);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during getting records. Please try again later.'], 500);
            }
        } else if ($request->query('query') === 'search') {
            try {
                $pagination = getPagination($request->query());
                $key = trim($request->query('key'));

                $results = Building::where('name', 'LIKE', '%' . $key . '%')
                    ->orWhere('address', 'LIKE', '%' . $key . '%')
                    ->orderBy('id', 'desc')
                    ->skip($pagination['skip'])
                    ->take($pagination['limit'])
                    ->get();

                $count = Building::where('name', 'LIKE', '%' . $key . '%')
                    ->orWhere('address', 'LIKE', '%' . $key . '%')
                    ->count();

                return $this->response([
                    'getAllBuilding' => $results->toArray(),
                    'totalBuilding' => $count,
                ]);
            } catch (Exception $error) {
                return response()->json(['error' => 'An error occurred during search. Please try again later.'], 500);
            }
        } else {
            try {
                $pagination = getPagination($request->query());
                $results = Building::with('floors.flats')
                    ->orderBy('id', 'desc')
                    ->skip($pagination['skip'])
                    ->take($pagination['limit'])
                    ->get();

                $count = Building::count();

                return $this->response([
                    'getAllBuilding' => $results->toArray(),
                    'totalBuilding' => $count,
                ]);
            } catch (Exception $error) {
                return response()->json(['error' => 'An error occurred during getting records. Please try again later.'], 500);
            }
        }
    }

    // READ SINGLE - GET /{id}
    public function getSingleBuilding(Request $request, $id): JsonResponse
    {
        try {
            $single = Building::with('floors.flats')->find($id);
            if (!$single) {
                return $this->badRequest('Record not found!');
            }
            return $this->response($single->toArray());
        } catch (Exception $error) {
            return response()->json(['error' => 'An error occurred during getting record. Please try again later.'], 500);
        }
    }

    // UPDATE - PUT /{id}
    public function updateSingleBuilding(Request $request, $id): JsonResponse
    {
        try {
            $record = Building::where('id', $id)->first();

            if (!$record) {
                return $this->badRequest('Record not found!');
            }

            $record->update([
                'name' => $request->input('name', $record->name),
                'address' => $request->input('address', $record->address),
            ]);

            return response()->json(['message' => 'Record Updated Successfully'], 200);
        } catch (Exception $error) {
            return response()->json(['error' => 'An error occurred during update. Please try again later.'], 500);
        }
    }

    // DELETE (Soft) - DELETE /{id}
    public function deleteSingleBuilding(Request $request, $id): JsonResponse
    {
        try {
            $record = Building::where('id', $id)->first();

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
