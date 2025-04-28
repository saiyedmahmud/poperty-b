<?php

namespace App\Services;

use App\Models\SaleInvoice;
use App\Models\Transaction;
use App\Traits\ErrorTrait;
use App\Traits\SearchTrait;
use App\Traits\UpToThreeDecimalTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SaleInvoiceInfoService
{
    use UpToThreeDecimalTrait, ErrorTrait, SearchTrait;

    protected TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function handleQuery($request): JsonResponse
    {

        if ($request['query'] === 'info') {
            return $this->getInfoQuery();
        } elseif ($request['query'] === 'search') {
            return $this->getSearchQuery();
        }
// elseif ($request['query'] === 'search-order') {
//            return $this->getSearchOrderQuery();
//        } elseif ($request['query'] === 'report') {
//            return $this->getReportQuery();
//        } else {
//            return response()->json(['error' => 'Invalid query!'], 400);
//        }
    }

    private function getInfoQuery(): JsonResponse
    {
        $aggregation = $this->aggregationQuery(SaleInvoice::class);

        // transaction of the total amount
        $totalAmount = $this->transactionService->GetTransaction('sale', 4);

        // transaction of the paidAmount
        $totalPaidAmount = $this->transactionService->GetTransaction('sale', null, 4);

        // transaction of the total amount
        $totalAmountOfReturn = $this->transactionService->GetTransaction('sale_return', null, 4);

        // transaction of the total instant return
        $totalInstantReturnAmount = $this->transactionService->GetTransaction('instant_return', 4);

        // calculation of due amount
        $totalDueAmount = (($totalAmount->amount - $totalAmountOfReturn->amount) - $totalPaidAmount->amount) + $totalInstantReturnAmount->amount;

        $result = $this->result($aggregation, $totalAmount, $totalPaidAmount, $totalAmountOfReturn, $totalInstantReturnAmount, $totalDueAmount);

        return $this->response($result);

    }

    private function aggregationQuery($table): object
    {
        return $table::selectRaw('COUNT(id) as id, SUM(profit) as profit')
            ->where('isHold', false)
            ->first();
    }

    private function result($aggregation, $totalAmount, $totalPaidAmount, $totalAmountOfReturn, $totalInstantReturnAmount, $totalDueAmount): array
    {
        return [
            '_count' => [
                'id' => $aggregation->id
            ],
            '_sum' => [
                'totalAmount' => $this->takeUptoThreeDecimal($totalAmount->amount),
                'dueAmount' => $this->takeUptoThreeDecimal($totalDueAmount),
                'paidAmount' => $this->takeUptoThreeDecimal($totalPaidAmount->amount),
                'totalReturnAmount' => $this->takeUptoThreeDecimal($totalAmountOfReturn->amount),
                'instantPaidReturnAmount' => $this->takeUptoThreeDecimal($totalInstantReturnAmount->amount),
                'profit' => $this->takeUptoThreeDecimal($aggregation->profit),
            ],
        ];
    }

    private function getSearchQuery($request): Collection
    {
        $pagination = getPagination($request->query());
        return $this->searchQry(SaleInvoice::query(), ['id'], 'like', $request->query('key'))
            ->with('saleInvoiceProduct', 'user:id,firstName,lastName,username', 'customer:id,username')
            ->orderBy('created_at', 'desc')
            ->where('isHold', 'false')
            ->skip($pagination['skip'])
            ->take($pagination['limit'])
            ->get();
    }
}
