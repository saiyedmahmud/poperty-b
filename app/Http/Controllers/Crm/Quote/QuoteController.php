<?php

namespace App\Http\Controllers\Crm\Quote;

use DateTime;
use Exception;
use Carbon\Carbon;
use App\Models\Transaction;
use App\Models\SaleInvoiceProduct;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\{JsonResponse, Request};
use App\Models\{Quote, QuoteProduct, SaleInvoice};

class QuoteController extends Controller
{
    //create quote
    public function createQuote(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if ($request->query('query') === 'deletemany') {
            try {
                $deleteMany = Quote::destroy($data);
                return response()->json(['count' => $deleteMany], 200);
            } catch (Exception $e) {
                return $this->badRequest($e->getMessage());
            }
        } elseif ($request->query('query') === 'createmany') {
            try {
                //check if product already exists
                $quoteData = collect($data)
                    ->map(function ($item) {
                        $quoteData = Quote::where('name', $item['name'])->first();
                        if ($quoteData) {
                            return null;
                        }
                        return $item;
                    })
                    ->filter(function ($item) {
                        return $item !== null;
                    })
                    ->toArray();

                //if all products already exists
                if (count($quoteData) === 0) {
                    return response()->json(['error' => 'All Quote already exists.'], 500);
                }

                foreach ($data as $item) {
                    Quote::insertOrIgnore($item);
                }
                return response()->json(['count' => count(json_decode($data))], 200);
            } catch (Exception $e) {
                return $this->badRequest($e->getMessage());
            }
        } else {
            try {
                //calculate the total amount
                $quoteProduct = $request->input('quoteProduct');
                $totalAmount = 0;
                foreach ($quoteProduct as $item) {
                    $totalAmount += $item['productQuantity'] * $item['unitPrice'];
                }
                $data = $request->attributes->get('data');

                $totalDiscount = 0;
                foreach ($quoteProduct as $item) {
                    $totalDiscount += isset($item['productDiscount']) ? $item['productDiscount'] : 0;
                }

                //quote date
                $quoteDate = new DateTime($request->input('quoteDate'));
                $expirationDate = new DateTime($request->input('expirationDate'));

                $createdQuote = Quote::create([
                    'quoteOwnerId' => $data['sub'] ?? null,
                    'quoteName' => $request->input('quoteName'),
                    'companyId' => $request->input('companyId') ?? null,
                    'contactId' => $request->input('contactId'),
                    'opportunityId' => $request->input('opportunityId') ?? null,
                    'quoteStageId' => $request->input('quoteStageId') ?? null,
                    'quoteDate' => $quoteDate ?? null,
                    'expirationDate' => $expirationDate ?? null,
                    'termsAndConditions' => $request->input('termsAndConditions') ?? null,
                    'description' => $request->input('description') ?? null,
                    'discount' => $totalDiscount ?? 0,
                    'totalAmount' => $totalAmount - $request->input('discount'),
                ]);

                //create quoteProduct
                $quoteProduct = $request->input('quoteProduct');
                foreach ($quoteProduct as $item) {
                    QuoteProduct::create([
                        'quoteId' => $createdQuote->id,
                        'productId' => $item['productId'],
                        'productQuantity' => $item['productQuantity'],
                        'unitPrice' => $item['unitPrice'],
                        'productDiscount' => $item['productDiscount'] ?? 0,
                        'productFinalAmount' => ($item['productQuantity'] * $item['unitPrice']) - $item['productDiscount'],
                    ]);
                }

                return $this->response($createdQuote->toArray());
            } catch (Exception $e) {
                return $this->badRequest($e->getMessage());
            }
        }
    }

    //get all quote
    public function getAllQuote(Request $request): JsonResponse
    {
        if ($request->query('query') === 'search') {
            try {
                $pagination = getPagination($request->query());
                $getAllQuote = Quote::with('quoteOwner:id,username')
                    ->where('status', 'true')
                    ->where('quoteName', 'LIKE', '%' . $request->query('key') . '%')
                    ->orderBy('id', 'desc')
                    ->skip($pagination['skip'])
                    ->take($pagination['limit'])
                    ->get();

                $totalQuote = Quote::where('quoteName', 'LIKE', '%' . $request->query('key') . '%')->count();

                return response()->json(
                    [
                        'getAllQuote' => $getAllQuote->toArray(),
                        'totalQuote' => $totalQuote,
                    ],
                    200,
                );
            } catch (Exception $e) {
                return $this->badRequest($e->getMessage());
            }
        } elseif ($request->query('query') === 'all') {
            try {
                $quote = Quote::with('quoteOwner:id,username')->orderBy('id', 'desc')->where('status', 'true')->get();

                return $this->response($quote->toArray());
            } catch (Exception $e) {
                return $this->badRequest($e->getMessage());
            }
        } elseif ($request->query()) {
            try {
                $pagination = getPagination($request->query());

                $quote = Quote::with('quoteOwner:id,username', 'quoteProduct.product', 'company:id,companyName', 'contact:id,firstName,lastName', 'opportunity:id,opportunityName', 'quoteStage:id,quoteStageName')
                    ->when($request->query('quoteOwner'), function ($query) use ($request) {
                        return $query->whereIn('quoteOwnerId', explode(',', $request->query('quoteOwner')));
                    })
                    ->when($request->query('quoteDate'), function ($query) use ($request) {
                        $dates = explode(',', $request->query('quoteDate'));
                        return $query->whereIn(DB::raw('DATE(quoteDate)'), $dates);
                    })
                    ->when($request->query('expirationDate'), function ($query) use ($request) {
                        $dates = explode(',', $request->query('expirationDate'));
                        return $query->whereIn(DB::raw('DATE(expirationDate)'), $dates);
                    })
                    ->when($request->query('status'), function ($query) use ($request) {
                        return $query->whereIn('status', explode(',', $request->query('status')));
                    })
                    ->when($request->query('quoteStage'), function ($query) use ($request) {
                        return $query->whereIn('quoteStageId', explode(',', $request->query('quoteStage')));
                    })
                    ->orderBy('id', 'desc')
                    ->skip($pagination['skip'])
                    ->take($pagination['limit'])
                    ->get();

                $totalQuote = Quote::with('quoteOwner:id,username')
                    ->when($request->query('quoteOwnerId'), function ($query) use ($request) {
                        return $query->whereIn('quoteOwnerId', explode(',', $request->query('quoteOwnerId')));
                    })
                    ->when($request->query('quoteDate'), function ($query) use ($request) {
                        $dates = explode(',', $request->query('quoteDate'));
                        return $query->whereIn(DB::raw('DATE(quoteDate)'), $dates);
                    })
                    ->when($request->query('expirationDate'), function ($query) use ($request) {
                        $dates = explode(',', $request->query('expirationDate'));
                        return $query->whereIn(DB::raw('DATE(expirationDate)'), $dates);
                    })
                    ->when($request->query('status'), function ($query) use ($request) {
                        return $query->whereIn('status', explode(',', $request->query('status')));
                    })
                    ->count();

                return $this->response([
                    'getAllQuote' => $quote->toArray(),
                    'totalQuote' => $totalQuote,
                ]);
            } catch (Exception $e) {
                return $this->badRequest($e->getMessage());
            }
        } else {
            return response()->json(['message' => 'invalid query'], 500);
        }
    }

    //get single quote
    public function getSingleQuote(Request $request, $id): JsonResponse
    {
        try {
            $quote = Quote::where('id', $id)->with('quoteOwner:id,username', 'quoteProduct.product', 'quoteProduct.product', 'company:id,companyName', 'contact:id,firstName,lastName', 'opportunity:opportunityName,id', 'quoteStage:id,QuoteStageName')->first();

            if (!$quote) {
                return $this->notFound('Quote not found');
            }
            return $this->response($quote->toArray());
        } catch (Exception $err) {
            return $this->badRequest($err->getMessage());
        }
    }

    //update quote
    public function updateQuote(Request $request, $id): JsonResponse
    {
        try {
            $quote = Quote::findOrFail($id);

            $quoteProduct = $request->input('quoteProduct');
            $totalAmount = 0;
            foreach ($quoteProduct as $item) {
                $totalAmount += $item['productQuantity'] * $item['unitPrice'];
            }

            $discount = $request->input('discount') ?? 0;
            $totalAmount = $totalAmount - $discount;
            // Convert the datetime values
            if ($request->has('quoteDate')) {
                $quoteDate = Carbon::parse($request->input('quoteDate'))->format('Y-m-d H:i:s');
                $quote->quoteDate = $quoteDate;
            }

            if ($request->has('expirationDate')) {
                $expirationDate = Carbon::parse($request->input('expirationDate'))->format('Y-m-d H:i:s');
                $quote->expirationDate = $expirationDate;
            }

            // Update other fields
            // $quote->update($request->except(['quoteDate', 'expirationDate']));
            //update quote individual fields
            $quote->quoteOwnerId = $request->input('quoteOwnerId') ?? $quote->quoteOwnerId;
            $quote->quoteName = $request->input('quoteName') ?? $quote->quoteName;
            $quote->companyId = $request->input('companyId') ?? $quote->companyId;
            $quote->contactId = $request->input('contactId') ?? $quote->contactId;
            $quote->opportunityId = $request->input('opportunityId') ?? $quote->opportunityId;
            $quote->quoteStageId = $request->input('quoteStageId') ?? $quote->quoteStageId;
            $quote->termsAndConditions = $request->input('termsAndConditions') ?? $quote->termsAndConditions;
            $quote->description = $request->input('description') ?? $quote->description;

            $quote->discount = $request->input('discount') ?? $quote->discount;
            $quote->totalAmount = $totalAmount;
            $quote->save();

            $quoteProduct = $request->input('quoteProduct');
            foreach ($quoteProduct as $item) {
                QuoteProduct::updateOrCreate(
                    [
                        'quoteId' => $quote->id,
                        'productId' => $item['productId']
                    ],
                    [
                        'productQuantity' => $item['productQuantity'],
                        'unitPrice' => $item['unitPrice']
                    ]
                );
            }

            return $this->response($quote->toArray());
        } catch (Exception $err) {
            return $this->badRequest($err->getMessage());
        }
    }

    //delete quote
    public function deleteQuote(Request $request, $id): JsonResponse
    {
        try {
            $deletedQuote = Quote::where('id', $id)->update([
                'status' => $request->input('status'),
            ]);
            if (!$deletedQuote) {
                return response()->json(['message' => 'Quote not updated'], 404);
            }
            return response()->json(['message' => 'Quote Deleted Successfully'], 200);
        } catch (Exception $err) {
            return $this->badRequest($err->getMessage());
        }
    }

    //convert quote to invoice

    public function convertQuoteToInvoice(Request $request, $id): JsonResponse
    {
        try {
            DB::beginTransaction();
            $quote = Quote::where('id', $id)->first();
            if (!$quote) {
                return $this->notFound('Quote not found');
            }

            $quoteProduct = QuoteProduct::where('quoteId', $id)->get();
            $totalAmount = 0;
            foreach ($quoteProduct as $item) {
                $totalAmount += $item->productQuantity * $item->unitPrice;
            }

            $invoice = SaleInvoice::create([
                'date' => Carbon::now(),
                'invoiceMemoNo' => null,
                'totalAmount' => $totalAmount - $quote->discount,
                'totalTaxAmount' => 0,
                'totalDiscountAmount' => $quote->discount ?? 0,
                'paidAmount' => 0,
                'dueAmount' => $totalAmount - $quote->discount,
                'dueDate' => null,
                'note' => $quote->description ?? null,
                'address' => null,
                'termsAndConditions' => $quote->termsAndConditions ?? null,
                'contactId' => $quote->contactId,
                'companyId' => $quote->companyId ?? null,
                'userId' => $quote->quoteOwnerId,
                'paymentStatus' => 'due',
            ]);

            if(!$invoice) {
                DB::rollBack();
                return $this->badRequest('Invoice not created');
            }

            foreach ($quoteProduct as $item) {
                SaleInvoiceProduct::create([
                'invoiceId' => $invoice->id,
                'productId' => $item->productId,
                'productQuantity' => $item->productQuantity,
                'productUnitSalePrice' => $item->unitPrice,
                'productDiscount' => $item->productDiscount,
                'productFinalAmount' => ($item->productQuantity * $item->unitPrice) - $item->productDiscount,
                'tax' => 0,
                'taxAmount' => 0,
                ]);
                if(!$invoice) {
                    DB::rollBack();
                    return $this->badRequest('Invoice product not created');
                }
            }

            $date = Carbon::parse($request['date']);
            $transaction = Transaction::create([
                'date' => $date,
                'debitId' => 4,
                'creditId' => 8,
                'amount' => $totalAmount,
                'particulars' => "total sale price with discount on Sale Invoice #$invoice->id",
                'type' => 'sale',
                'relatedId' => $invoice->id,
            ]);

            if(!$transaction) {
                DB::rollBack();
                return $this->badRequest('Transaction not created');
            }

            $quote->isConverted = "true";
            $quote->save();

            DB::commit();
            return $this->response($invoice->toArray());
        } catch (Exception $err) {
            DB::rollBack();
            return $this->badRequest($err->getMessage());
        }
    }
}
