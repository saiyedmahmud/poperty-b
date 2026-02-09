<?php

namespace App\Http\Controllers\Flat;

use App\Http\Controllers\Controller;
use App\Models\Flat;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FlatController extends Controller
{
    // CREATE - POST /
    public function createSingleFlat(Request $request): JsonResponse
    {
        try {
            $created = Flat::create([
                'floorId' => $request->input('floorId'),
                'flatNo' => $request->input('flatNo'),
                'roomQty' => $request->input('roomQty'),
                'washroomQty' => $request->input('washroomQty'),
                'hasVeranda' => $request->input('hasVeranda', false),
                'hasKitchen' => $request->input('hasKitchen', false),
                'bashavara' => $request->input('bashavara'),
                'status' => $request->input('status', 'available'),
            ]);
            return $this->response($created->toArray());
        } catch (Exception $error) {
            return response()->json(['error' => 'An error occurred during create. Please try again later.'], 500);
        }
    }

    // READ ALL - GET /
    public function getAllFlat(Request $request): JsonResponse
    {
        if ($request->query('query') === 'all') {
            try {
                $all = Flat::with('floor', 'rentals')->orderBy('id', 'desc')->get();

                return $this->response([
                    'getAllFlat' => $all->toArray(),
                    'totalFlat' => Flat::count(),
                ]);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during getting records. Please try again later.'], 500);
            }
        } else if ($request->query('query') === 'search') {
            try {
                $pagination = getPagination($request->query());
                $key = trim($request->query('key'));

                $results = Flat::where('flatNo', 'LIKE', '%' . $key . '%')
                    ->orWhere('status', 'LIKE', '%' . $key . '%')
                    ->with('floor', 'rentals')
                    ->orderBy('id', 'desc')
                    ->skip($pagination['skip'])
                    ->take($pagination['limit'])
                    ->get();

                $count = Flat::where('flatNo', 'LIKE', '%' . $key . '%')
                    ->orWhere('status', 'LIKE', '%' . $key . '%')
                    ->count();

                return $this->response([
                    'getAllFlat' => $results->toArray(),
                    'totalFlat' => $count,
                ]);
            } catch (Exception $error) {
                return response()->json(['error' => 'An error occurred during search. Please try again later.'], 500);
            }
        } else {
            try {
                $pagination = getPagination($request->query());
                $results = Flat::with('floor', 'rentals')
                    ->when($request->query('status'), function ($query) use ($request) {
                        return $query->where('status', $request->query('status'));
                    })
                    ->orderBy('id', 'desc')
                    ->skip($pagination['skip'])
                    ->take($pagination['limit'])
                    ->get();

                $count = Flat::when($request->query('status'), function ($query) use ($request) {
                    return $query->where('status', $request->query('status'));
                })->count();

                return $this->response([
                    'getAllFlat' => $results->toArray(),
                    'totalFlat' => $count,
                ]);
            } catch (Exception $error) {
                return response()->json(['error' => 'An error occurred during getting records. Please try again later.'], 500);
            }
        }
    }

    // READ SINGLE - GET /{id}
    public function getSingleFlat(Request $request, $id): JsonResponse
    {
        try {
            $single = Flat::with('floor', 'rentals.renter')->find($id);
            if (!$single) {
                return $this->badRequest('Record not found!');
            }
            return $this->response($single->toArray());
        } catch (Exception $error) {
            return response()->json(['error' => 'An error occurred during getting record. Please try again later.'], 500);
        }
    }

    // UPDATE - PUT /{id}
    public function updateSingleFlat(Request $request, $id): JsonResponse
    {
        try {
            $record = Flat::where('id', $id)->first();

            if (!$record) {
                return $this->badRequest('Record not found!');
            }

            $record->update([
                'floorId' => $request->input('floorId', $record->floorId),
                'flatNo' => $request->input('flatNo', $record->flatNo),
                'roomQty' => $request->input('roomQty', $record->roomQty),
                'washroomQty' => $request->input('washroomQty', $record->washroomQty),
                'hasVeranda' => $request->input('hasVeranda', $record->hasVeranda),
                'hasKitchen' => $request->input('hasKitchen', $record->hasKitchen),
                'bashavara' => $request->input('bashavara', $record->bashavara),
                'status' => $request->input('status', $record->status),
            ]);

            return response()->json(['message' => 'Record Updated Successfully'], 200);
        } catch (Exception $error) {
            return response()->json(['error' => 'An error occurred during update. Please try again later.'], 500);
        }
    }

    // DELETE (Soft) - DELETE /{id}
    public function deleteSingleFlat(Request $request, $id): JsonResponse
    {
        try {
            $record = Flat::where('id', $id)->first();

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
