<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
    // CREATE - POST /
    public function createSinglePayment(Request $request): JsonResponse
    {
        try {
            $created = Payment::create([
                'invoiceId' => $request->input('invoiceId'),
                'amount' => $request->input('amount'),
                'paymentDate' => $request->input('paymentDate'),
                'paymentMethod' => $request->input('paymentMethod'),
            ]);
            return $this->response($created->toArray());
        } catch (Exception $error) {
            return response()->json(['error' => 'An error occurred during create. Please try again later.'], 500);
        }
    }

    // READ ALL - GET /
    public function getAllPayment(Request $request): JsonResponse
    {
        if ($request->query('query') === 'all') {
            try {
                $all = Payment::with('invoice')->orderBy('id', 'desc')->get();

                return $this->response([
                    'getAllPayment' => $all->toArray(),
                    'totalPayment' => Payment::count(),
                ]);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during getting records. Please try again later.'], 500);
            }
        } else if ($request->query('query') === 'search') {
            try {
                $pagination = getPagination($request->query());
                $key = trim($request->query('key'));

                $results = Payment::where('paymentMethod', 'LIKE', '%' . $key . '%')
                    ->with('invoice')
                    ->orderBy('id', 'desc')
                    ->skip($pagination['skip'])
                    ->take($pagination['limit'])
                    ->get();

                $count = Payment::where('paymentMethod', 'LIKE', '%' . $key . '%')->count();

                return $this->response([
                    'getAllPayment' => $results->toArray(),
                    'totalPayment' => $count,
                ]);
            } catch (Exception $error) {
                return response()->json(['error' => 'An error occurred during search. Please try again later.'], 500);
            }
        } else {
            try {
                $pagination = getPagination($request->query());
                $results = Payment::with('invoice')
                    ->when($request->query('paymentMethod'), function ($query) use ($request) {
                        return $query->where('paymentMethod', $request->query('paymentMethod'));
                    })
                    ->orderBy('id', 'desc')
                    ->skip($pagination['skip'])
                    ->take($pagination['limit'])
                    ->get();

                $count = Payment::when($request->query('paymentMethod'), function ($query) use ($request) {
                    return $query->where('paymentMethod', $request->query('paymentMethod'));
                })->count();

                return $this->response([
                    'getAllPayment' => $results->toArray(),
                    'totalPayment' => $count,
                ]);
            } catch (Exception $error) {
                return response()->json(['error' => 'An error occurred during getting records. Please try again later.'], 500);
            }
        }
    }

    // READ SINGLE - GET /{id}
    public function getSinglePayment(Request $request, $id): JsonResponse
    {
        try {
            $single = Payment::with('invoice')->find($id);
            if (!$single) {
                return $this->badRequest('Record not found!');
            }
            return $this->response($single->toArray());
        } catch (Exception $error) {
            return response()->json(['error' => 'An error occurred during getting record. Please try again later.'], 500);
        }
    }

    // UPDATE - PUT /{id}
    public function updateSinglePayment(Request $request, $id): JsonResponse
    {
        try {
            $record = Payment::where('id', $id)->first();

            if (!$record) {
                return $this->badRequest('Record not found!');
            }

            $record->update([
                'invoiceId' => $request->input('invoiceId', $record->invoiceId),
                'amount' => $request->input('amount', $record->amount),
                'paymentDate' => $request->input('paymentDate', $record->paymentDate),
                'paymentMethod' => $request->input('paymentMethod', $record->paymentMethod),
            ]);

            return response()->json(['message' => 'Record Updated Successfully'], 200);
        } catch (Exception $error) {
            return response()->json(['error' => 'An error occurred during update. Please try again later.'], 500);
        }
    }

    // DELETE (Soft) - DELETE /{id}
    public function deleteSinglePayment(Request $request, $id): JsonResponse
    {
        try {
            $record = Payment::where('id', $id)->first();

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
