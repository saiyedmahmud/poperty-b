<?php

namespace App\Http\Controllers\Renter;

use App\Http\Controllers\Controller;
use App\Models\Renter;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RenterController extends Controller
{
    // CREATE - POST /
    public function createSingleRenter(Request $request): JsonResponse
    {
        try {
            $created = Renter::create([
                'fullName' => $request->input('fullName'),
                'phone' => $request->input('phone'),
                'nidNumber' => $request->input('nidNumber'),
                'address' => $request->input('address'),
            ]);
            return $this->response($created->toArray());
        } catch (Exception $error) {
            return response()->json(['error' => 'An error occurred during create. Please try again later.'], 500);
        }
    }

    // READ ALL - GET /
    public function getAllRenter(Request $request): JsonResponse
    {
        if ($request->query('query') === 'all') {
            try {
                $all = Renter::with('rentals')->orderBy('id', 'desc')->get();

                return $this->response([
                    'getAllRenter' => $all->toArray(),
                    'totalRenter' => Renter::count(),
                ]);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during getting records. Please try again later.'], 500);
            }
        } else if ($request->query('query') === 'search') {
            try {
                $pagination = getPagination($request->query());
                $key = trim($request->query('key'));

                $results = Renter::where('fullName', 'LIKE', '%' . $key . '%')
                    ->orWhere('phone', 'LIKE', '%' . $key . '%')
                    ->orWhere('nidNumber', 'LIKE', '%' . $key . '%')
                    ->with('rentals')
                    ->orderBy('id', 'desc')
                    ->skip($pagination['skip'])
                    ->take($pagination['limit'])
                    ->get();

                $count = Renter::where('fullName', 'LIKE', '%' . $key . '%')
                    ->orWhere('phone', 'LIKE', '%' . $key . '%')
                    ->orWhere('nidNumber', 'LIKE', '%' . $key . '%')
                    ->count();

                return $this->response([
                    'getAllRenter' => $results->toArray(),
                    'totalRenter' => $count,
                ]);
            } catch (Exception $error) {
                return response()->json(['error' => 'An error occurred during search. Please try again later.'], 500);
            }
        } else {
            try {
                $pagination = getPagination($request->query());
                $results = Renter::with('rentals')
                    ->orderBy('id', 'desc')
                    ->skip($pagination['skip'])
                    ->take($pagination['limit'])
                    ->get();

                $count = Renter::count();

                return $this->response([
                    'getAllRenter' => $results->toArray(),
                    'totalRenter' => $count,
                ]);
            } catch (Exception $error) {
                return response()->json(['error' => 'An error occurred during getting records. Please try again later.'], 500);
            }
        }
    }

    // READ SINGLE - GET /{id}
    public function getSingleRenter(Request $request, $id): JsonResponse
    {
        try {
            $single = Renter::with('rentals.flat')->find($id);
            if (!$single) {
                return $this->badRequest('Record not found!');
            }
            return $this->response($single->toArray());
        } catch (Exception $error) {
            return response()->json(['error' => 'An error occurred during getting record. Please try again later.'], 500);
        }
    }

    // UPDATE - PUT /{id}
    public function updateSingleRenter(Request $request, $id): JsonResponse
    {
        try {
            $record = Renter::where('id', $id)->first();

            if (!$record) {
                return $this->badRequest('Record not found!');
            }

            $record->update([
                'fullName' => $request->input('fullName', $record->fullName),
                'phone' => $request->input('phone', $record->phone),
                'nidNumber' => $request->input('nidNumber', $record->nidNumber),
                'address' => $request->input('address', $record->address),
            ]);

            return response()->json(['message' => 'Record Updated Successfully'], 200);
        } catch (Exception $error) {
            return response()->json(['error' => 'An error occurred during update. Please try again later.'], 500);
        }
    }

    // DELETE (Soft) - DELETE /{id}
    public function deleteSingleRenter(Request $request, $id): JsonResponse
    {
        try {
            $record = Renter::where('id', $id)->first();

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
