<?php

namespace App\Http\Controllers\Crm\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Contact;
use App\Models\CrmTask;
use App\Models\CrmTaskStatus;
use App\Models\Lead;
use App\Models\Opportunity;
use App\Models\Quote;
use App\Models\SalaryHistory;
use App\Models\SaleInvoice;
use App\Models\Tasks;
use App\Models\Ticket;
use App\Models\Users;
use Exception;
use Illuminate\Console\View\Components\Task;
use Illuminate\Http\JsonResponse;

class CrmDashboardController extends Controller
{
    public function getDashboardData(): JsonResponse
    {
        try {
            //total user count
            $userCount = Users::where('status', 'true')
            ->count();

            $totalUserCount = [
                'count' => $userCount
            ];

            //lead count
            $leadCount = Lead::where('status', 'true')
            ->count();

            $totalLeadCount = [
                'count' => $leadCount
            ];

            //total saleInvoice
            $saleInvoiceCount = SaleInvoice::where('status', 'true')
            ->count();

            $totalSaleInvoice = [
                'count' => $saleInvoiceCount
            ];

            //saleInvoice value
            $saleInvoiceValue = SaleInvoice::where('status', 'true')
            ->sum('totalAmount');
           

            //total ticket
            $ticketCount = Ticket::where('status', 'true')
            ->count();

            $totalTicket = [
                'count' => $ticketCount
            ];

            $userSalary = SalaryHistory::orderBy('id', 'desc')->get();
            $salary = 0;
            foreach ($userSalary as $key => $value) {
                $salary += $value->salary;
            }

            $totalSalary = [
                'value' => $salary
            ];

            //calculate total salary from all users

            //total opportunity and value
            $totalOpportunityCount = Opportunity::where('status', 'true')
            ->count();
            $totalOpportunityValue = Opportunity::where('status', 'true')
            ->sum('amount');

            $opportunity = [
                'count' => $totalOpportunityCount,
                'value' => $totalOpportunityValue
            ];

            //total quote and value
            $totalQuoteCount = Quote::where('status', 'true')
            ->count();
            $totalQuoteValue = Quote::where('status', 'true')
            ->sum('totalAmount');
            $quote = [
                'count' => $totalQuoteCount,
                'value' => $totalQuoteValue
            ];

            //total contact count and value
            $totalContactCount = Contact::where('status', 'true')
            ->count();
            $contact = [
                'count' => $totalContactCount,
            ];


            //total company
            $totalCompanyCount = Company::where('status', 'true')
            ->count();
            $company = [
                'count' => $totalCompanyCount,
            ];

            //total crmTask by status
            $totalCrmTask = CrmTaskStatus::all();

            $crmTask = [];
            foreach ($totalCrmTask as $key => $value) {
                $crmTask[$key]['statusName'] = $value->taskStatusName;
                $crmTask[$key]['statusCount'] = Tasks::where('status', $value->id)->count();
            }

            $data = [
                'totalUsers' => $totalUserCount,
                'totalSalary' => $totalSalary,
                'opportunity' => $opportunity,
                'quote' => $quote,
                'contact' => $contact,
                'company' => $company,
                'task' => $crmTask,
                'lead' => $totalLeadCount,
                'saleInvoice' => $totalSaleInvoice,
                'ticket' => $totalTicket,
                'saleInvoiceValue' => $saleInvoiceValue
            ];

            return response()->json($data, 200);
        } catch (Exception $err) {
            return $this->badRequest($err->getMessage());
        }
    }
}
