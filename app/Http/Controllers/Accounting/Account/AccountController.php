<?php

namespace App\Http\Controllers\Accounting\Account;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\SubAccount;
use App\Models\Transaction;
use App\Traits\ErrorTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    use ErrorTrait;

    //create subAccount
    public function createSubAccount(Request $request): JsonResponse
    {
        try {
            $createdSubAccount = SubAccount::create([
                'name' => $request->input('name'),
                'accountId' => $request->input('accountId'),
            ]);

            return $this->response($createdSubAccount->toArray(),201);
        } catch (Exception $error) {
            return $this->badRequest($error->getMessage());
        }
    }

    //get all account
    public function getAllAccount(Request $request): JsonResponse
    {
        try {
            $data = $request->attributes->get("data");
            if ($data === null) {
                throw new Exception("Request data is not available.");
            }

            $role = $data['role'] ?? null;
            if ($request->query('query') === 'tb') {
                $allAccounts = Account::orderBy('id', 'desc')
                    ->with(['subAccount' => function ($query) use ( $role, $request) {
                        $query->with(['debit' => function ($query) use ( $role, $request) {
                            $query->where('status', 'true');
                            $query->orderBy('id', 'desc');
                        }, 'credit' => function ($query) use ( $role, $request) {
                            $query->orderBy('id', 'desc');
                        }]);
                    }])
                    ->get();


                $accountInfo = [];

                foreach ($allAccounts as $account) {
                    foreach ($account->subAccount as $subAccount) {
                        $totalDebit = $subAccount->debit->where('status', true)->sum('amount');
                        $totalCredit = $subAccount->credit->where('status', true)->sum('amount');
                        $balance = $totalDebit - $totalCredit;

                        $accountInfo[] = [
                            'account' => $account->name,
                            'subAccount' => $subAccount->name,
                            'totalDebit' => $this->takeUptoThreeDecimal($totalDebit),
                            'totalCredit' => $this->takeUptoThreeDecimal($totalCredit),
                            'balance' => $this->takeUptoThreeDecimal($balance),
                        ];
                    }
                }

                $trialBalance = $accountInfo; // Assuming you already have $accountInfo

                $debits = [];
                $credits = [];

                foreach ($trialBalance as $item) {
                    if ($item['balance'] > 0) {
                        $debits[] = $item;
                    }
                    if ($item['balance'] < 0) {
                        $credits[] = $item;
                    }
                }

                // Assuming you have already separated items into $debits and $credits arrays

                $totalDebit = array_reduce($debits, function ($carry, $debit) {
                    return $carry + $debit['balance'];
                }, 0);

                $totalCredit = array_reduce($credits, function ($carry, $credit) {
                    return $carry + $credit['balance'];
                }, 0);

                if (-$totalDebit === $totalCredit) {
                    $match = true;
                } else {
                    $match = false;
                }

                $responseData = [
                    'match' => $match,
                    'totalDebit' => $totalDebit,
                    'totalCredit' => $totalCredit,
                    'debits' => $debits,
                    'credits' => $credits,
                ];

                return $this->response($responseData);
            } elseif ($request->query('query') === 'bs') {
                $allAccount = Account::orderBy('id', 'desc')
                    ->with(['subAccount' => function ($query) use ( $role, $request) {
                        $query->with(['debit' => function ($query) use ( $role, $request) {
                            $query->where('status', 'true');
                            $query->orderBy('id', 'desc');
                        }, 'credit' => function ($query) use ( $role, $request) {
                            $query->orderBy('id', 'desc');
                        }]);
                    }])
                    ->get();


                $accountInfo = [];

                foreach ($allAccount as $account) {
                    foreach ($account->subAccount as $subAccount) {
                        $totalDebit = $subAccount->debit->sum('amount');
                        $totalCredit = $subAccount->credit->sum('amount');
                        $balance = $totalDebit - $totalCredit;


                        // Add the total debit and total credit to each subAccount object
                        $subAccount->totalDebit = $totalDebit;
                        $subAccount->totalCredit = $totalCredit;
                        $subAccount->balance = $balance;

                        // Create an array for the transformed subAccount data
                        $accountInfo[] = [
                            'account' => $account->type,
                            'subAccount' => $subAccount->name,
                            'totalDebit' => $this->takeUptoThreeDecimal($totalDebit),
                            'totalCredit' => $this->takeUptoThreeDecimal($totalCredit),
                            'balance' => $this->takeUptoThreeDecimal($balance),
                        ];
                    }
                }

                $balanceSheet = $accountInfo;
                $assets = [];
                $liabilities = [];
                $equity = [];

                foreach ($balanceSheet as $item) {
                    if ($item['account'] === "Asset" && $item['balance'] !== 0) {
                        $assets[] = $item;
                    }
                    if ($item['account'] === "Liability" && $item['balance'] !== 0) {
                        // Convert negative balance to positive
                        $item['balance'] = -$item['balance'];
                        $liabilities[] = $item;
                    }
                    if ($item['account'] === "Equity" && $item['balance'] !== 0) {
                        // Convert negative balance to positive
                        $item['balance'] = -$item['balance'];
                        $equity[] = $item;
                    }
                }

                $totalAsset = array_reduce($assets, function ($carry, $asset) {
                    return $carry + $asset['balance'];
                }, 0);

                $totalLiability = array_reduce($liabilities, function ($carry, $liability) {
                    return $carry + $liability['balance'];
                }, 0);

                $totalEquity = array_reduce($equity, function ($carry, $equityItem) {
                    return $carry + $equityItem['balance'];
                }, 0);

                if (-$totalAsset === $totalLiability + $totalEquity) {
                    $match = true;
                } else {
                    $match = false;
                }

                $responseData = [
                    'match' => $match,
                    'totalAsset' => $totalAsset,
                    'totalLiability' => $totalLiability,
                    'totalEquity' => $totalEquity,
                    'assets' => $assets,
                    'liabilities' => $liabilities,
                    'equity' => $equity,
                ];

                return $this->response($responseData);
            } elseif ($request->query('query') === 'is') {
                $allAccount = Account::with(['subAccount' => function ($query) use ( $role, $request) {
                    $query->with(['debit' => function ($query) use ( $role, $request) {
                        $query->when($request->query('startDate') && $request->query('endDate'), function ($query) use ($request) {
                            return $query->where('date', '>=', Carbon::createFromFormat('Y-m-d', $request->query('startDate'))->startOfDay())
                                ->where('date', '<=', Carbon::createFromFormat('Y-m-d', $request->query('endDate'))->endOfDay());
                        });
                        $query->where('status', 'true');
                        $query->orderBy('id', 'desc');
                    }, 'credit' => function ($query) use ( $role, $request) {
                        $query->when($request->query('startDate') && $request->query('endDate'), function ($query) use ($request) {
                            return $query->where('date', '>=', Carbon::createFromFormat('Y-m-d', $request->query('startDate'))->startOfDay())
                                ->where('date', '<=', Carbon::createFromFormat('Y-m-d', $request->query('endDate'))->endOfDay());
                        });
                        $query->orderBy('id', 'desc');
                    }]);
                }])
                    ->get();
                $accountInfo = [];

                foreach ($allAccount as $account) {
                    foreach ($account->subAccount as $subAccount) {
                        $totalDebit = $subAccount->debit->sum('amount');
                        $totalCredit = $subAccount->credit->sum('amount');
                        $balance = $totalDebit - $totalCredit;

                        // Create an array for the transformed subAccount data
                        $accountInfo[] = [
                            'id' => $subAccount->id,
                            'account' => $account->name,
                            'subAccount' => $subAccount->name,
                            'totalDebit' => $this->takeUptoThreeDecimal($totalDebit),
                            'totalCredit' => $this->takeUptoThreeDecimal($totalCredit),
                            'balance' => $this->takeUptoThreeDecimal($balance),
                        ];
                    }
                }
                $incomeStatement = $accountInfo;
                $revenue = [];
                $expense = [];

                foreach ($incomeStatement as $item) {
                    if ($item['account'] === "Revenue" && $item['balance'] !== 0) {
                        // Convert negative balance to positive
                        $item['balance'] = -$item['balance'];
                        $revenue[] = $item;
                    }
                    if ($item['account'] === "Expense" && $item['balance'] !== 0) {
                        // Convert negative balance to positive
                        $item['balance'] = -$item['balance'];
                        $expense[] = $item;
                    }
                }

                $totalRevenue = array_reduce($revenue, function ($carry, $revenueItem) {
                    return $carry + $revenueItem['balance'];
                }, 0);

                $totalExpense = array_reduce($expense, function ($carry, $expenseItem) {
                    return $carry + $expenseItem['balance'];
                }, 0);

                $profit = $totalRevenue + $totalExpense;

                $responseData = [
                    'totalRevenue' => $totalRevenue,
                    'totalExpense' => $totalExpense,
                    'profit' => $profit,
                    'revenue' => $revenue,
                    'expense' => $expense,
                ];
                return $this->response($responseData);
            } elseif ($request->query('type') === 'sa' && $request->query('query') === 'all') {
                $allSubAccount = SubAccount::where('status', 'true')
                    ->with(['account' => function ($query) {
                        $query->orderBy('id', 'desc');
                    }])
                    ->orderBy('id', 'desc')
                    ->get();

                return $this->response($allSubAccount->toArray());
            } elseif ($request->query('type') === 'sa' && $request->query('query') === 'search') {
                $pagination = getPagination($request->query());
                $key = trim($request->query('key'));

                $allSubAccount = SubAccount::where('name', 'LIKE', '%' . $key . '%')
                    ->with(['account' => function ($query) {
                        $query->orderBy('id', 'desc');
                    }])
                    ->where('status', 'true')
                    ->orderBy('id', 'desc')
                    ->skip($pagination['skip'])
                    ->take($pagination['limit'])
                    ->get();

                $allSubAccountCount = SubAccount::where('name', 'LIKE', '%' . $key . '%')
                    ->where('status', 'true')
                    ->count();

                return $this->response([
                    'getAllSubAccount' => $allSubAccount->toArray(),
                    'totalSubAccount' => $allSubAccountCount,
                ]);
            } elseif ($request->query('type') === 'sa') {
                $pagination = getPagination($request->query());

                $allSubAccount = SubAccount::when($request->query('status'), function ($query) use ($request) {
                    return $query->whereIn('status', explode(',', $request->query('status')));
                })->when($request->query('accountId'), function ($query) use ($request) {
                    return $query->whereIn('accountId', explode(',', $request->query('accountId')));
                })
                    ->skip($pagination['skip'])
                    ->take($pagination['limit'])
                    ->with('account')
                    ->orderBy('id', 'desc')
                    ->get();

                $allSubAccountCount = SubAccount::when($request->query('status'), function ($query) use ($request) {
                    return $query->whereIn('status', explode(',', $request->query('status')));
                })->when($request->query('accountId'), function ($query) use ($request) {
                    return $query->whereIn('accountId', explode(',', $request->query('accountId')));
                })
                    ->count();

                return $this->response([
                    'getAllSubAccount' => $allSubAccount->toArray(),
                    'totalSubAccount' => $allSubAccountCount,
                ]);
            } elseif ($request->query('query') === 'ma') {

                $allAccount = Account::orderBy('id', 'desc')->get();
                $converted = $this->arrayKeysToCamelCase($allAccount->toArray());
                return response()->json($converted, 200);
            } else {
                $allAccount = Account::with(['subAccount.credit' => function ($query) {
                    $query->orderBy('id', 'desc');
                }, 'subAccount.debit' => function ($query) {
                    $query->orderBy('id', 'desc');
                }])->orderBy('id', 'desc')->get();

                return $this->response($allAccount->toArray());
            }
        } catch (Exception $error) {
            return $this->badRequest($error);
        }
    }

    public function getSingleAccount(Request $request, $id): JsonResponse
    {
        try {
            $data = $request->attributes->get("data");

            $singleAccount = SubAccount::with(['debit' => function ($query) use ($data,  $request) {
                $query->orderBy('id', 'asc');
            }, 'credit' => function ($query) use ($data,  $request) {
                $query->orderBy('id', 'asc');
            }])->find($id);

            $totalDebit = $singleAccount->debit->sum('amount');
            $totalCredit = $singleAccount->credit->sum('amount');
            $balance = $totalDebit - $totalCredit;
            $singleAccount->balance = $balance;

            return $this->response($singleAccount->toArray());
        } catch (Exception $error) {
            return $this->badRequest($error->getMessage());
        }
    }

    //update the subAccount
    public function updateSubAccount(Request $request, $id): JsonResponse
    {
        try {
            $account = SubAccount::findOrFail($id);

            if (!$account) {
                return response()->json(['error' => 'Sub Account not found'], 404);
            }

            if ($id <= 15) {
                return response()->json(['error' => 'You can not update default sub account'], 400);
            }

            $debit = Transaction::where('debitId', $id)->first();
            $credit = Transaction::where('creditId', $id)->first();

            if ($debit || $credit) {
                if ($request->input('name') !== null && $request->input('accountId') === $account->accountId) {
                    SubAccount::where('id', $id)->update(['name' => $request->input('name')]);
                    return response()->json(['message' => 'Sub Account updated successfully']);
                }
                return response()->json(['error' => 'Transaction has already been made in this account'], 400);
            }

            $account->update($request->all());

            return $this->success('Sub Account updated successfully');
        } catch (Exception $error) {
            return $this->badRequest($error);
        }
    }

    public function deleteSubAccount(Request $request, $id): JsonResponse
    {
        try {

            if ($id <= 16) {
                return $this->badRequest('You can not delete default sub account');
            }

            //if transaction made in this account then can not delete
            $debit = Transaction::where('debitId', $id)->first();
            $credit = Transaction::where('creditId', $id)->first();

            if ($debit || $credit) {
                return $this->badRequest('Transaction has already been made in this account');
            }

            SubAccount::where('id', $id)->delete();
            return $this->success('Sub Account deleted successfully');
        } catch (Exception $error) {
            return $this->badRequest($error->getMessage());
        }
    }
}
