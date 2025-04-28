<?php

namespace App\Services;

use App\Models\Product;
use App\Models\SaleInvoice;
use App\Models\SaleInvoiceProduct;
use App\Models\Transaction;
use App\Traits\ErrorTrait;
use App\Traits\UpToThreeDecimalTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SalesInvoiceService
{
    use ErrorTrait, UpToThreeDecimalTrait;

    protected TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function createSaleInvoice($request, $data): JsonResponse
    {
        DB::beginTransaction();
        try {

            $allProducts = $this->getAllProducts($request, $data);

            if ($allProducts->contains(false)) {
                return $this->notFound('Products not found');
            }
            $totalSalePriceWithDiscount = $this->calculateTotalSalePriceWithDiscount($request);
            $totalTax = $this->calculateTotalTax($request);
            $totalPaidAmount = $this->calculateTotalPaidAmount($request);
            $due = $this->calculateDueAmount($totalSalePriceWithDiscount, $totalTax, $totalPaidAmount);
            $discountAmount = $this->totalDiscountAmount($request);

            // $productOutOfStock = $this->productOutOfStock($request, $this->getAllProducts($request, $data));
            // if ($productOutOfStock === true) {
            //     return $this->badRequest('Product out of stock');
            // }

            $createdInvoice = $this->createInvoice($request, $discountAmount, $totalSalePriceWithDiscount, $totalTax, $totalPaidAmount, $due);

            $this->createInvoiceProducts($request, $createdInvoice);

            $this->createTransactions($request, $createdInvoice, $totalSalePriceWithDiscount, $totalTax);

            $this->updateProductQuantities($request, $allProducts);

            DB::commit();
            return $this->response(['createdInvoice' => $createdInvoice->toArray()]);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->badRequest($e);
        }
    }

    private function getAllProducts($request, $data): Collection|JsonResponse|bool
    {

        return collect($request['saleInvoiceProduct'])->map(function ($item) use ($data) {
            $stock = Product::where('id', $item['productId'])
                ->first();
            if (!$stock) {
                return false;
            }
            return $stock;
        });
    }

    private function calculateTotalSalePriceWithDiscount($request): float
    {
        $totalSalePriceWithDiscount = 0;
        foreach ($request['saleInvoiceProduct'] as $item) {
            $productFinalAmount = ((int)$item['productQuantity'] * (float)$item['productUnitSalePrice']) - (float)$item['productDiscount'];
            $totalSalePriceWithDiscount += $productFinalAmount;
        }
        return $totalSalePriceWithDiscount;
    }

    private function calculateTotalTax($request): float
    {
        $totalTax = 0;
        foreach ($request['saleInvoiceProduct'] as $item) {
            $productFinalAmount = ((int)$item['productQuantity'] * (float)$item['productUnitSalePrice']) - (float)$item['productDiscount'];
            $taxAmount = ($productFinalAmount * (float)$item['tax']) / 100;
            $totalTax += $taxAmount;
        }
        return $totalTax;
    }


    private function calculateTotalPaidAmount($request): float
    {
        $totalPaidAmount = 0;
        foreach ($request['paidAmount'] as $amountData) {
            $totalPaidAmount += $amountData['amount'];
        }
        return $totalPaidAmount;
    }

    private function calculateDueAmount($totalSalePriceWithDiscount, $totalTax, $totalPaidAmount): float
    {
        return $totalSalePriceWithDiscount + $totalTax - (float)$totalPaidAmount;
    }

    private function totalDiscountAmount($request): float
    {
        $totalDiscountAmount = 0;
        foreach ($request['saleInvoiceProduct'] as $item) {
            $totalDiscountAmount += (float)$item['productDiscount'];
        }
        return $totalDiscountAmount;
    }

    private function productOutOfStock($request, $allProducts): bool
    {
        foreach ($request['saleInvoiceProduct'] as $item) {

            $product = $allProducts->firstWhere('id', $item['productId']);
            if ($product->unit < $item['productQuantity']) {
                return true;
            }
            return false;
        }
    }

    /**
     * @throws Exception
     */
    private function createInvoice($request, $discountAmount, $totalSalePriceWithDiscount, $totalTax, $totalPaidAmount, $due): SaleInvoice
    {

        $date = Carbon::parse($request['date']);
        $dueDate = isset($request['dueDate']) ? Carbon::parse($request['dueDate']) : null;
        $due = $totalSalePriceWithDiscount + $totalTax - (float)$totalPaidAmount;


        return SaleInvoice::create([
            'date' => $date,
            'invoiceMemoNo' => isset($request['invoiceMemoNo']),
            'totalAmount' => $this->takeUptoThreeDecimal($totalSalePriceWithDiscount),
            'totalTaxAmount' => $totalTax ? $this->takeUptoThreeDecimal($totalTax) : 0,
            'totalDiscountAmount' => $this->takeUptoThreeDecimal($discountAmount),
            'paidAmount' => $this->takeUptoThreeDecimal((float)$totalPaidAmount),
            'dueAmount' => $this->takeUptoThreeDecimal($due),
            'dueDate' => $dueDate ?? null,
            'note' => $request['note'] ?? null,
            'address' => $request['address'] ?? null,
            'termsAndConditions' => $request['termsAndConditions'] ?? null,
            'contactId' => $request['contactId'],
            'companyId' => $request['companyId'] ?? null,
            'userId' => $request['userId'],
            'paymentStatus' => $due > 0 ? 'due' : 'paid',
        ]);
    }

    private function createInvoiceProducts($request, $createdInvoice): void
    {
        foreach ($request['saleInvoiceProduct'] as $item) {
            SaleInvoiceProduct::create([
                'invoiceId' => $createdInvoice->id,
                'productId' => (int)$item['productId'],
                'productQuantity' => (int)$item['productQuantity'],
                'productUnitSalePrice' => $this->takeUptoThreeDecimal((float)$item['productUnitSalePrice']),
                'productDiscount' => $this->takeUptoThreeDecimal((float)$item['productDiscount']),
                'productFinalAmount' => $this->takeUptoThreeDecimal(((int)$item['productQuantity'] * (float)$item['productUnitSalePrice']) - (float)$item['productDiscount']),
                'tax' => $this->takeUptoThreeDecimal($item['tax']),
                'taxAmount' => $this->takeUptoThreeDecimal(((int)$item['productQuantity'] * (float)$item['productUnitSalePrice'] - (float)$item['productDiscount']) * (float)$item['tax'] / 100),
            ]);
        }
    }

    private function createTransactions($request, $createdInvoice, $totalSalePriceWithDiscount, $totalTax,): void
    {
        $date = Carbon::parse($request['date']);
        // transaction for account receivable of sales
        $this->transactionService->Transaction($date, 4, 8, $totalSalePriceWithDiscount, "total sale price with discount on Sale Invoice", $createdInvoice->id, 'sale');

        if ($totalTax > 0) {
            // transaction for account receivable of vat
            $this->transactionService->Transaction($date, 4, 15, $totalTax, "Tax on Sale Invoice ", $createdInvoice->id, 'sale');
        }

        // new transactions will be created as journal entry for paid amount
        $paidAmount = $this->calculateTotalPaidAmount($request);
        foreach ($request['paidAmount'] as $amountData) {
            if ($paidAmount > 0) {
                $debitId = $amountData['paymentType'] ?: 1;
                $this->transactionService->Transaction($date, $debitId, 4, $amountData['amount'], "Payment receive on Sale Invoice", $createdInvoice->id, 'sale');
            }
        }
    }

    private function updateProductQuantities($request, $allProducts): void
    {
        foreach ($request['saleInvoiceProduct'] as $item) {
            $product = $allProducts->firstWhere('id', $item['productId']);
            $product->update([
                'unit' => (int)$product->unit - (int)$item['productQuantity'],
            ]);
        }
    }
}
