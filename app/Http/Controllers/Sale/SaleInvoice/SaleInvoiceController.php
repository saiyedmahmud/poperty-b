<?php

namespace App\Http\Controllers\Sale\SaleInvoice;

use Exception;
use Carbon\Carbon;
use App\Models\SaleInvoice;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\SalesInvoiceService;
use App\Services\SaleInvoiceInfoService;

class SaleInvoiceController extends Controller
{
    protected SalesInvoiceService $salesInvoiceService;
    protected SaleInvoiceInfoService $saleInvoiceInfoService;

    public function __construct(SalesInvoiceService $salesInvoiceService, SaleInvoiceInfoService $saleInvoiceInfoService)
    {
        $this->salesInvoiceService = $salesInvoiceService;
        $this->saleInvoiceInfoService = $saleInvoiceInfoService;
    }

    public function createSingleSaleInvoice(Request $request): JsonResponse
    {
        $validate = validator($request->all(), [
            'date' => 'required|date',
            'saleInvoiceProduct' => 'required|array|min:1',
            'saleInvoiceProduct.*.productId' => 'required|integer|distinct|exists:product,id',
            'saleInvoiceProduct.*.productQuantity' => 'nullable|integer|min:1',
            'saleInvoiceProduct.*.productUnitSalePrice' => 'nullable|numeric|min:0',
            'contactId' => 'required|integer|exists:contact,id',
            'userId' => 'required|integer|exists:users,id',
        ]);

        if ($validate->fails()) {
            return $this->badRequest($validate->errors()->first());
        }
        $data = $request->attributes->get("data");
        return $this->salesInvoiceService->createSaleInvoice($request->all(), $data);
    }

    // get all the saleInvoice controller method
    public function getAllSaleInvoice(Request $request): JsonResponse
    {
       

        if ($request->query('query') === 'info') {
            return $this->saleInvoiceInfoService->handleQuery($request->all());
        } else if ($request->query('query') === 'search') {
            try {
                $pagination = getPagination($request->query());

                $allSaleInvoice = SaleInvoice::where('id', $request->query('key'))
                    ->with('saleInvoiceProduct', 'user:id,firstName,lastName,username',)
                    ->orderBy('created_at', 'desc')
                    ->skip($pagination['skip'])
                    ->take($pagination['limit'])
                    ->get();


                $total = SaleInvoice::where('id', $request->query('key'))
                    ->count();

                $saleInvoicesIds = $allSaleInvoice->pluck('id')->toArray();

                // transaction of the total amount
                $totalNetAmount = Transaction::where('type', 'sale')
                    ->whereIn('relatedId', $saleInvoicesIds)
                    ->where(function ($query) {
                        $query->where('debitId', 4);
                    })
                    ->get();

                // calculate with sales commission
                $totalAmount = $totalNetAmount->sum('amount');

                // transaction of the paidAmount
                $totalPaidAmount = Transaction::where('type', 'sale')
                    ->whereIn('relatedId', $saleInvoicesIds)
                    ->where(function ($query) {
                        $query->orWhere('creditId', 4);
                    })
                    ->get();

                // transaction of the total amount
                $totalAmountOfReturn = Transaction::where('type', 'sale_return')
                    ->whereIn('relatedId', $saleInvoicesIds)
                    ->where(function ($query) {
                        $query->where('creditId', 4);
                    })
                    ->get();

                // transaction of the total instant return
                $totalInstantReturnAmount = Transaction::where('type', 'sale_return')
                    ->whereIn('relatedId', $saleInvoicesIds)
                    ->where(function ($query) {
                        $query->where('debitId', 4);
                    })
                    ->get();

                // calculate grand total due amount
                $totalDueAmount = (($totalAmount - $totalAmountOfReturn->sum('amount')) - $totalPaidAmount->sum('amount')) + $totalInstantReturnAmount->sum('amount');


                $allSaleInvoice = $allSaleInvoice->map(function ($item) use ($totalNetAmount, $totalPaidAmount, $totalAmountOfReturn, $totalInstantReturnAmount) {

                    $totalNetAmount = $totalNetAmount->filter(function ($trans) use ($item) {
                        return ($trans->relatedId === $item->id && $trans->type === 'sale' && $trans->debitId === 4);
                    })->reduce(function ($acc, $current) {
                        return $acc + $current->amount;
                    }, 0);

                    $totalAmount = $totalNetAmount ;

                    $totalPaid = $totalPaidAmount->filter(function ($trans) use ($item) {
                        return ($trans->relatedId === $item->id && $trans->type === 'sale' && $trans->creditId === 4);
                    })->reduce(function ($acc, $current) {
                        return $acc + $current->amount;
                    }, 0);

                    $totalReturnAmount = $totalAmountOfReturn->filter(function ($trans) use ($item) {
                        return ($trans->relatedId === $item->id && $trans->type === 'sale_return' && $trans->creditId === 4);
                    })->reduce(function ($acc, $current) {
                        return $acc + $current->amount;
                    }, 0);

                    $instantPaidReturnAmount = $totalInstantReturnAmount->filter(function ($trans) use ($item) {
                        return ($trans->relatedId === $item->id && $trans->type === 'sale_return' && $trans->debitId === 4);
                    })->reduce(function ($acc, $current) {
                        return $acc + $current->amount;
                    }, 0);

                    $totalDueAmount = (($totalAmount - $totalReturnAmount) - $totalPaid) + $instantPaidReturnAmount;

                    $item->paidAmount = $this->takeUptoThreeDecimal($totalPaid);
                    $item->saleCommission = $item->saleCommission;
                    $item->instantPaidReturnAmount = $this->takeUptoThreeDecimal($instantPaidReturnAmount);
                    $item->dueAmount = $this->takeUptoThreeDecimal($totalDueAmount);
                    $item->returnAmount = $this->takeUptoThreeDecimal($totalReturnAmount);
                    return $item;
                });

                $totaluomValue = $allSaleInvoice->sum('totaluomValue');
                $totalUnitQuantity = $allSaleInvoice->map(function ($item) {
                    return $item->saleInvoiceProduct->sum('productQuantity');
                })->sum();

                return response()->json([
                    'aggregations' => [
                        '_count' => [
                            'id' => $total,
                        ],
                        '_sum' => [
                            'totalAmount' => $this->takeUptoThreeDecimal($totalAmount),
                            'paidAmount' => $this->takeUptoThreeDecimal($totalPaidAmount->sum('amount')),
                            'dueAmount' => $this->takeUptoThreeDecimal($totalDueAmount),
                            'totalReturnAmount' => $this->takeUptoThreeDecimal($totalAmountOfReturn->sum('amount')),
                            'instantPaidReturnAmount' => $this->takeUptoThreeDecimal($totalInstantReturnAmount->sum('amount')),
                            'totaluomValue' => $totaluomValue,
                            'totalUnitQuantity' => $totalUnitQuantity,
                        ],
                    ],
                    'getAllSaleInvoice' => $this->arrayKeysToCamelCase($allSaleInvoice->toArray()),
                    'totalSaleInvoice' => $total,
                ], 200);
            } catch (Exception $err) {
                return response()->json(['error' => $err->getMessage()], 500);
            }
        } else if ($request->query('query') === 'search-order') {
            try {
                $allOrder = SaleInvoice::where(function ($query) use ($request) {
                        if ($request->has('status')) {
                            $status = $request->query('status');
                            $query->where('orderStatus', 'LIKE', "%$status%");
                        }
                    })
                    ->with('saleInvoiceProduct')
                    ->orderBy('created_at', 'desc')
                    ->where('isHold', 'false')
                    ->get();

                return $this->response($allOrder->toArray());
            } catch (Exception $err) {
                return response()->json(['error' => $err->getMessage()], 500);
            }
        } else if ($request->query('query') === 'report') {
            try {
                $allOrder = SaleInvoice::with('saleInvoiceProduct', 'user:id,firstName,lastName,username','saleInvoiceProduct.product:id,name')
                    ->when($request->query('salePersonId'), function ($query) use ($request) {
                        return $query->where('userId', $request->query('salePersonId'));
                    })
                    ->when($request->query('contactId'), function ($query) use ($request) {
                        return $query->whereIn('contactId', explode(',', $request->query('contactId')));
                    })
                    ->when($request->query('companyId'), function ($query) use ($request) {
                        return $query->whereIn('companyId', explode(',', $request->query('companyId')));
                    })
                    ->when($request->query('startDate') && $request->query('endDate'), function ($query) use ($request) {
                        return $query->where('date', '>=', Carbon::createFromFormat('Y-m-d', $request->query('startDate'))->startOfDay())
                            ->where('date', '<=', Carbon::createFromFormat('Y-m-d', $request->query('endDate'))->endOfDay());
                    })
                    ->orderBy('created_at', 'desc')
                    ->get();

                $saleInvoicesIds = $allOrder->pluck('id')->toArray();
                // modify data to actual data of sale invoice's current value by adjusting with transactions and returns



                // transaction of the total amount
                $totalNetAmount = Transaction::where('type', 'sale')
                    ->whereIn('relatedId', $saleInvoicesIds)
                    ->where(function ($query) {
                        $query->where('debitId', 4);
                    })
                    ->get();

                // calculate with sales commission
                $totalAmount = $totalNetAmount->sum('amount');

                // transaction of the paidAmount
                $totalPaidAmount = Transaction::where('type', 'sale')
                    ->whereIn('relatedId', $saleInvoicesIds)
                    ->where(function ($query) {
                        $query->orWhere('creditId', 4);
                    })
                    ->get();

                // calculate grand total due amount
                $totalDueAmount = ($totalAmount  - $totalPaidAmount->sum('amount'));

                // calculate paid amount and due amount of individual sale invoice from transactions and returnSaleInvoice and attach it to saleInvoices
                $allSaleInvoice = $allOrder->map(function ($item) use ($totalNetAmount, $totalPaidAmount) {


                    $totalNetAmount = $totalNetAmount->filter(function ($trans) use ($item) {
                        return ($trans->relatedId === $item->id && $trans->type === 'sale' && $trans->debitId === 4);
                    })->reduce(function ($acc, $current) {
                        return $acc + $current->amount;
                    }, 0);

                    $totalAmount = $totalNetAmount;

                    $totalPaid = $totalPaidAmount->filter(function ($trans) use ($item) {
                        return ($trans->relatedId === $item->id && $trans->type === 'sale' && $trans->creditId === 4);
                    })->reduce(function ($acc, $current) {
                        return $acc + $current->amount;
                    }, 0);


                    $totalDueAmount = ($totalAmount - $totalPaid);


                    $item->paidAmount = $this->takeUptoThreeDecimal($totalPaid);
                    $item->saleCommission = $item->saleCommission;
                    $item->dueAmount = $this->takeUptoThreeDecimal($totalDueAmount);
                    return $item;
                });

                $totaluomValue = $allSaleInvoice->sum('totaluomValue');
                $totalUnitQuantity = $allSaleInvoice->map(function ($item) {
                    return $item->saleInvoiceProduct->sum('productQuantity');
                })->sum();


                $counted = $allOrder->count();
                return $this->response([
                    'aggregations' => [
                        '_count' => [
                            'id' => $counted,
                        ],
                        '_sum' => [
                            'totalAmount' => $this->takeUptoThreeDecimal($totalAmount),
                            'paidAmount' => $this->takeUptoThreeDecimal($totalPaidAmount->sum('amount')),
                            'dueAmount' => $this->takeUptoThreeDecimal($totalDueAmount),
                            'totaluomValue' => $totaluomValue,
                            'totalUnitQuantity' => $totalUnitQuantity,
                        ],
                    ],
                    'getAllSaleInvoice' => $allSaleInvoice->toArray(),
                    'totalSaleInvoice' => $counted,
                ], 200);
            } catch (Exception $err) {
                return response()->json(['error' => $err->getMessage()], 500);
            }
        } else if ($request->query()) {
            try {
                $pagination = getPagination($request->query());
                $allOrder = SaleInvoice::with('saleInvoiceProduct', 'user:id,firstName,lastName,username', 'contact', 'company:id,companyName')
                    ->orderBy('created_at', 'desc')
                    ->when($request->query('salePersonId'), function ($query) use ($request) {
                        return $query->whereIn('userId', explode(',', $request->query('salePersonId')));
                    })
                    ->when($request->query('contactId'), function ($query) use ($request) {
                        return $query->whereIn('contactId', explode(',', $request->query('contactId')));
                    })
                    ->when($request->query('companyId'), function ($query) use ($request) {
                        return $query->whereIn('companyId', explode(',', $request->query('companyId')));
                    })
                    ->when($request->query('startDate') && $request->query('endDate'), function ($query) use ($request) {
                        return $query->where('date', '>=', Carbon::createFromFormat('Y-m-d', $request->query('startDate'))->startOfDay())
                            ->where('date', '<=', Carbon::createFromFormat('Y-m-d', $request->query('endDate'))->endOfDay());
                    })
                    ->skip($pagination['skip'])
                    ->take($pagination['limit'])
                    ->get();

                $saleInvoicesIds = $allOrder->pluck('id')->toArray();
            
                // transaction of the total amount
                $totalNetAmount = Transaction::where('type', 'sale')
                    ->whereIn('relatedId', $saleInvoicesIds)
                    ->where(function ($query) {
                        $query->where('debitId', 4);
                    })
                    ->get();

                // calculate with sales commission
                $totalAmount = $totalNetAmount->sum('amount');

                // transaction of the paidAmount
                $totalPaidAmount = Transaction::where('type', 'sale')
                    ->whereIn('relatedId', $saleInvoicesIds)
                    ->where(function ($query) {
                        $query->orWhere('creditId', 4);
                    })
                    ->get();


                // calculate grand total due amount
                $totalDueAmount = ($totalAmount - $totalPaidAmount->sum('amount'));

                // calculate paid amount and due amount of individual sale invoice from transactions and returnSaleInvoice and attach it to saleInvoices
                $allSaleInvoice = $allOrder->map(function ($item) use ($totalNetAmount, $totalPaidAmount) {

               
                    $totalNetAmount = $totalNetAmount->filter(function ($trans) use ($item) {
                        return ($trans->relatedId === $item->id && $trans->type === 'sale' && $trans->debitId === 4);
                    })->reduce(function ($acc, $current) {
                        return $acc + $current->amount;
                    }, 0);

                    $totalAmount = $totalNetAmount;

                    $totalPaid = $totalPaidAmount->filter(function ($trans) use ($item) {
                        return ($trans->relatedId === $item->id && $trans->type === 'sale' && $trans->creditId === 4);
                    })->reduce(function ($acc, $current) {
                        return $acc + $current->amount;
                    }, 0);


                    $totalDueAmount = (($totalAmount) - $totalPaid);
                    $item->paidAmount = $this->takeUptoThreeDecimal($totalPaid);
                    $item->dueAmount = $this->takeUptoThreeDecimal($totalDueAmount);
                    return $item;
                });

                $converted = $this->arrayKeysToCamelCase($allSaleInvoice->toArray());
                $totaluomValue = $allSaleInvoice->sum('totaluomValue');
                $totalUnitQuantity = $allSaleInvoice->map(function ($item) {
                    return $item->saleInvoiceProduct->sum('productQuantity');
                })->sum();

                $counted = SaleInvoice::
                    when($request->query('salePersonId'), function ($query) use ($request) {
                        return $query->whereIn('userId', explode(',', $request->query('salePersonId')));
                    })
                    ->when($request->query('orderStatus'), function ($query) use ($request) {
                        return $query->whereIn('orderStatus', explode(',', $request->query('orderStatus')));
                    })
                    ->when($request->query('customerId'), function ($query) use ($request) {
                        return $query->whereIn('customerId', explode(',', $request->query('customerId')));
                    })
                    ->when($request->query('startDate') && $request->query('endDate'), function ($query) use ($request) {
                        return $query->where('date', '>=', Carbon::createFromFormat('Y-m-d', $request->query('startDate'))->startOfDay())
                            ->where('date', '<=', Carbon::createFromFormat('Y-m-d', $request->query('endDate'))->endOfDay());
                    })
                    ->count();

                return response()->json([
                    'aggregations' => [
                        '_count' => [
                            'id' => $counted,
                        ],
                        '_sum' => [
                            'totalAmount' => $this->takeUptoThreeDecimal($totalAmount),
                            'paidAmount' => $this->takeUptoThreeDecimal($totalPaidAmount->sum('amount')),
                            'dueAmount' => $this->takeUptoThreeDecimal($totalDueAmount),
                            'totaluomValue' => $totaluomValue,
                            'totalUnitQuantity' => $totalUnitQuantity,
                        ],
                    ],
                    'getAllSaleInvoice' => $converted,
                    'totalSaleInvoice' => $counted,
                ], 200);
            } catch (Exception $err) {
                return response()->json(['error' => $err->getMessage()], 500);
            }
        } else {
            return response()->json(['error' => 'invalid query!'], 400);
        }
    }

    public function getSingleSaleInvoice(Request $request, $id): JsonResponse
    {
        try {
            // get single Sale invoice information with products
            $singleSaleInvoice = SaleInvoice::
                where('id', $id)
                ->with(['saleInvoiceProduct', 'saleInvoiceProduct' => function ($query) {
                    $query->with('product')->orderBy('id', 'desc');
                }, 'contact', 'company', 'user:id,firstName,lastName,username'])
                ->first();

            if (!$singleSaleInvoice) {
                return response()->json(['error' => 'This invoice not Found'], 400);
            }


            // transaction of the total amount
            $totalNetAmount = Transaction::where('type', 'sale')
                ->where('relatedId', $singleSaleInvoice->id)
                ->where(function ($query) {
                    $query->where('debitId', 4);
                })
                ->with('debit:id,name', 'credit:id,name')
                ->get();

            // calculate with sales commission
            $totalAmount = $totalNetAmount->sum('amount');
            // transaction of the paidAmount
            $totalPaidAmount = Transaction::where('type', 'sale')
                ->where('relatedId', $singleSaleInvoice->id)
                ->where(function ($query) {
                    $query->orWhere('creditId', 4);
                })
                ->with('debit:id,name', 'credit:id,name')
                ->get();

            // calculation of due amount
            $totalDueAmount = $totalAmount  - $totalPaidAmount->sum('amount');


            // get all transactions related to this sale invoice
            $transactions = Transaction::where('relatedId', $singleSaleInvoice->id)
                ->where(function ($query) {
                    $query->orWhere('type', 'sale');
                })
                ->with('debit:id,name', 'credit:id,name')
                ->orderBy('id', 'desc')
                ->get();

            return $this->response([
                'totalAmount' => $this->takeUptoThreeDecimal($totalAmount),
                'totalPaidAmount' => $this->takeUptoThreeDecimal($totalPaidAmount->sum('amount')),
                'dueAmount' => $this->takeUptoThreeDecimal($totalDueAmount),
                'singleSaleInvoice' => $singleSaleInvoice->toArray(),
                'transactions' => $transactions->toArray()
            ]);
        } catch (Exception $err) {
            return response()->json(['error' => $err->getMessage()], 500);
        }
    }

    
}
