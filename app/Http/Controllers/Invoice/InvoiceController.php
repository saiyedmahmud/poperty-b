<?php

namespace App\Http\Controllers\Invoice;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class InvoiceController extends Controller
{
    // CREATE - POST /
    public function createSingleInvoice(Request $request): JsonResponse
    {
        try {
            $created = Invoice::create([
                'rentalId' => $request->input('rentalId'),
                'otherBill' => $request->input('otherBill'),
                'rentAmount' => $request->input('rentAmount'),
                'totalAmount' => $request->input('totalAmount'),
                'dueAmount' => $request->input('dueAmount'),
                'invoiceMonth' => $request->input('invoiceMonth'),
                'status' => $request->input('status', 'pending'),
            ]);
            return $this->response($created->toArray());
        } catch (Exception $error) {
            return response()->json(['error' => 'An error occurred during create. Please try again later.'], 500);
        }
    }

    // READ ALL - GET /
    public function getAllInvoice(Request $request): JsonResponse
    {
        if ($request->query('query') === 'all') {
            try {
                $all = Invoice::with('rental', 'payments')->orderBy('id', 'desc')->get();

                return $this->response([
                    'getAllInvoice' => $all->toArray(),
                    'totalInvoice' => Invoice::count(),
                ]);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during getting records. Please try again later.'], 500);
            }
        } else if ($request->query('query') === 'search') {
            try {
                $pagination = getPagination($request->query());
                $key = trim($request->query('key'));

                $results = Invoice::where('invoiceMonth', 'LIKE', '%' . $key . '%')
                    ->orWhere('status', 'LIKE', '%' . $key . '%')
                    ->with('rental', 'payments')
                    ->orderBy('id', 'desc')
                    ->skip($pagination['skip'])
                    ->take($pagination['limit'])
                    ->get();

                $count = Invoice::where('invoiceMonth', 'LIKE', '%' . $key . '%')
                    ->orWhere('status', 'LIKE', '%' . $key . '%')
                    ->count();

                return $this->response([
                    'getAllInvoice' => $results->toArray(),
                    'totalInvoice' => $count,
                ]);
            } catch (Exception $error) {
                return response()->json(['error' => 'An error occurred during search. Please try again later.'], 500);
            }
        } else {
            try {
                $pagination = getPagination($request->query());
                $results = Invoice::with('rental', 'payments')
                    ->when($request->query('status'), function ($query) use ($request) {
                        return $query->where('status', $request->query('status'));
                    })
                    ->orderBy('id', 'desc')
                    ->skip($pagination['skip'])
                    ->take($pagination['limit'])
                    ->get();

                $count = Invoice::when($request->query('status'), function ($query) use ($request) {
                    return $query->where('status', $request->query('status'));
                })->count();

                return $this->response([
                    'getAllInvoice' => $results->toArray(),
                    'totalInvoice' => $count,
                ]);
            } catch (Exception $error) {
                return response()->json(['error' => 'An error occurred during getting records. Please try again later.'], 500);
            }
        }
    }

    // READ SINGLE - GET /{id}
    public function getSingleInvoice(Request $request, $id): JsonResponse
    {
        try {
            $single = Invoice::with('rental', 'payments')->find($id);
            if (!$single) {
                return $this->badRequest('Record not found!');
            }
            return $this->response($single->toArray());
        } catch (Exception $error) {
            return response()->json(['error' => 'An error occurred during getting record. Please try again later.'], 500);
        }
    }

    // UPDATE - PUT /{id}
    public function updateSingleInvoice(Request $request, $id): JsonResponse
    {
        try {
            $record = Invoice::where('id', $id)->first();

            if (!$record) {
                return $this->badRequest('Record not found!');
            }

            $record->update([
                'rentalId' => $request->input('rentalId', $record->rentalId),
                'otherBill' => $request->input('otherBill', $record->otherBill),
                'rentAmount' => $request->input('rentAmount', $record->rentAmount),
                'totalAmount' => $request->input('totalAmount', $record->totalAmount),
                'dueAmount' => $request->input('dueAmount', $record->dueAmount),
                'invoiceMonth' => $request->input('invoiceMonth', $record->invoiceMonth),
                'status' => $request->input('status', $record->status),
            ]);

            return response()->json(['message' => 'Record Updated Successfully'], 200);
        } catch (Exception $error) {
            return response()->json(['error' => 'An error occurred during update. Please try again later.'], 500);
        }
    }

    // DELETE (Soft) - DELETE /{id}
    public function deleteSingleInvoice(Request $request, $id): JsonResponse
    {
        try {
            $record = Invoice::where('id', $id)->first();

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
