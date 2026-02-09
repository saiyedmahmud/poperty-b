<?php

namespace App\Http\Controllers\Rental;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RentalController extends Controller
{
    // CREATE - POST /
    public function createSingleRental(Request $request): JsonResponse
    {
        try {
            $created = Rental::create([
                'flatId' => $request->input('flatId'),
                'renterId' => $request->input('renterId'),
                'startDate' => $request->input('startDate'),
                'endDate' => $request->input('endDate'),
                'securityDeposit' => $request->input('securityDeposit'),
                'isActive' => $request->input('isActive', true),
            ]);
            return $this->response($created->toArray());
        } catch (Exception $error) {
            return response()->json(['error' => 'An error occurred during create. Please try again later.'], 500);
        }
    }

    // READ ALL - GET /
    public function getAllRental(Request $request): JsonResponse
    {
        if ($request->query('query') === 'all') {
            try {
                $all = Rental::with('flat', 'renter', 'invoices')->orderBy('id', 'desc')->get();

                return $this->response([
                    'getAllRental' => $all->toArray(),
                    'totalRental' => Rental::count(),
                ]);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during getting records. Please try again later.'], 500);
            }
        } else if ($request->query('query') === 'search') {
            try {
                $pagination = getPagination($request->query());
                $isActive = $request->query('isActive');

                $results = Rental::where('isActive', $isActive)
                    ->with('flat', 'renter')
                    ->orderBy('id', 'desc')
                    ->skip($pagination['skip'])
                    ->take($pagination['limit'])
                    ->get();

                $count = Rental::where('isActive', $isActive)->count();

                return $this->response([
                    'getAllRental' => $results->toArray(),
                    'totalRental' => $count,
                ]);
            } catch (Exception $error) {
                return response()->json(['error' => 'An error occurred during search. Please try again later.'], 500);
            }
        } else {
            try {
                $pagination = getPagination($request->query());
                $results = Rental::with('flat', 'renter', 'invoices')
                    ->when($request->query('isActive'), function ($query) use ($request) {
                        return $query->where('isActive', $request->query('isActive'));
                    })
                    ->orderBy('id', 'desc')
                    ->skip($pagination['skip'])
                    ->take($pagination['limit'])
                    ->get();

                $count = Rental::when($request->query('isActive'), function ($query) use ($request) {
                    return $query->where('isActive', $request->query('isActive'));
                })->count();

                return $this->response([
                    'getAllRental' => $results->toArray(),
                    'totalRental' => $count,
                ]);
            } catch (Exception $error) {
                return response()->json(['error' => 'An error occurred during getting records. Please try again later.'], 500);
            }
        }
    }

    // READ SINGLE - GET /{id}
    public function getSingleRental(Request $request, $id): JsonResponse
    {
        try {
            $single = Rental::with('flat', 'renter', 'invoices')->find($id);
            if (!$single) {
                return $this->badRequest('Record not found!');
            }
            return $this->response($single->toArray());
        } catch (Exception $error) {
            return response()->json(['error' => 'An error occurred during getting record. Please try again later.'], 500);
        }
    }

    // UPDATE - PUT /{id}
    public function updateSingleRental(Request $request, $id): JsonResponse
    {
        try {
            $record = Rental::where('id', $id)->first();

            if (!$record) {
                return $this->badRequest('Record not found!');
            }

            $record->update([
                'flatId' => $request->input('flatId', $record->flatId),
                'renterId' => $request->input('renterId', $record->renterId),
                'startDate' => $request->input('startDate', $record->startDate),
                'endDate' => $request->input('endDate', $record->endDate),
                'securityDeposit' => $request->input('securityDeposit', $record->securityDeposit),
                'isActive' => $request->input('isActive', $record->isActive),
            ]);

            return response()->json(['message' => 'Record Updated Successfully'], 200);
        } catch (Exception $error) {
            return response()->json(['error' => 'An error occurred during update. Please try again later.'], 500);
        }
    }

    // DELETE (Soft) - DELETE /{id}
    public function deleteSingleRental(Request $request, $id): JsonResponse
    {
        try {
            $record = Rental::where('id', $id)->first();

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
