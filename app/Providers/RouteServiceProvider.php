<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('global', function (Request $request) {
            return Limit::perMinute(1000)->by($request->ip())->response(function () {
                return response()->json(['error' => 'Too Many Attempts.'], 429);
            });
        });

        $this->routes(function () {
            Route::middleware('web')
            ->group(base_path('routes/web.php'));
            //Accounting
            Route::middleware('account')
                ->prefix('account')
                ->group(base_path('app/Http/Controllers/Accounting/Account/accountRoutes.php'));
            Route::middleware('transaction')
                ->prefix('transaction')
                ->group(base_path('app/Http/Controllers/Accounting/Transaction/transactionRoutes.php'));



            //Customer
            Route::middleware('customer')
                ->prefix('customer')
                ->group(base_path('app/Http/Controllers/Customer/customerRoutes.php'));
            route::middleware('customer-profileImage')
                ->prefix('customer-profileImage')
                ->group(base_path('app/Http/Controllers/Customer/customerProfileImageRoutes.php'));


            //Email
            Route::middleware('email-config')
                ->prefix('email-config')
                ->group(base_path('app/Http/Controllers/Email/emailConfigRoutes.php'));
            Route::middleware('email')
                ->prefix('email')
                ->group(base_path('app/Http/Controllers/Email/emailRoutes.php'));
            Route::middleware('email-invoice')
                ->prefix('email-invoice')
                ->group(base_path('app/Http/Controllers/Email/sendEmailRoutes.php'));

            //Files
            Route::middleware('files')
                ->prefix('files')
                ->group(base_path('app/Http/Controllers/Files/filesRoutes.php'));



            Route::middleware('announcement')
                ->prefix('announcement')
                ->group(base_path('app/Http/Controllers/HR/Announcement/announcementRoutes.php'));
            Route::middleware('award')
                ->prefix('award')
                ->group(base_path('app/Http/Controllers/HR/Award/awardRoutes.php'));
            Route::middleware('award-history')
                ->prefix('award-history')
                ->group(base_path('app/Http/Controllers/HR/Award/awardHistoryRoutes.php'));
            Route::middleware('department')
                ->prefix('department')
                ->group(base_path('app/Http/Controllers/HR/Department/departmentRoutes.php'));
            Route::middleware('designation')
                ->prefix('designation')
                ->group(base_path('app/Http/Controllers/HR/Designation/designationRoutes.php'));
            Route::middleware('designation-history')
                ->prefix('designation-history')
                ->group(base_path('app/Http/Controllers/HR/Designation/designationHistoryRoutes.php'));
            Route::middleware('education')
                ->prefix('education')
                ->group(base_path('app/Http/Controllers/HR/Education/educationRoutes.php'));
            Route::middleware('employment-status')
                ->prefix('employment-status')
                ->group(base_path('app/Http/Controllers/HR/EmploymentStatus/employmentStatusRoutes.php'));
            Route::middleware('permission')
                ->prefix('permission')
                ->group(base_path('app/Http/Controllers/HR/RolePermission/permissionRoutes.php'));
            Route::middleware('role')
                ->prefix('role')
                ->group(base_path('app/Http/Controllers/HR/RolePermission/roleRoutes.php'));
            Route::middleware('role-permission')
                ->prefix('role-permission')
                ->group(base_path('app/Http/Controllers/HR/RolePermission/rolePermissionRoutes.php'));
            Route::middleware('salary-history')
                ->prefix('salary-history')
                ->group(base_path('app/Http/Controllers/HR/SalaryHistory/salaryHistoryRoutes.php'));
            Route::middleware('shift')
                ->prefix('shift')
                ->group(base_path('app/Http/Controllers/HR/Shift/shiftRoutes.php'));


            //Inventory



            Route::middleware('product')
                ->prefix('product')
                ->group(base_path('app/Http/Controllers/Inventory/Product/productRoutes.php'));
            Route::middleware('product-category')
                ->prefix('product-category')
                ->group(base_path('app/Http/Controllers/Inventory/ProductCategory/productCategoryRoutes.php'));
            Route::middleware('quote')
                ->prefix('quote')
                ->group(base_path('app/Http/Controllers/Crm/Quote/quoteRoutes.php'));

            //Payment
            route::middleware('payment-method')
                ->prefix('payment-method')
                ->group(base_path('app/Http/Controllers/Payment/PaymentMethod/paymentMethodRoutes.php'));
            route::middleware('manual-payment')
                ->prefix('manual-payment')
                ->group(base_path('app/Http/Controllers/Payment/ManualPayment/manualPaymentRoutes.php'));




            //Settings
            Route::middleware('setting')
                ->prefix('setting')
                ->group(base_path('app/Http/Controllers/Settings/AppSetting/appSettingRoutes.php'));
            Route::middleware('priority')
                ->prefix('priority')
                ->group(base_path('app/Http/Controllers/Settings/Priority/priorityRoutes.php'));
            

            route::middleware('quick-link')
                ->prefix('quick-link')
                ->group(base_path('app/Http/Controllers/Settings/QuickLink/quickLinkRoutes.php'));


            //user
            Route::middleware('user')
                ->prefix('user')
                ->group(base_path('app/Http/Controllers/User/userRoutes.php'));

            //media
            Route::middleware('media')
                ->prefix('media')
                ->group(base_path('app/Http/Controllers/MediaFiles/MediaFileRoutes.php'));


            //crm
            Route::middleware('crm-dashboard')
                ->prefix('crm-dashboard')
                ->group(base_path('app/Http/Controllers/Crm/Dashboard/crmDashboardRoutes.php'));
            Route::middleware('contact-source')
                ->prefix('contact-source')
                ->group(base_path('app/Http/Controllers/Crm/Contact/contactSourceRoutes.php'));
            Route::middleware('contact-stage')
                ->prefix('contact-stage')
                ->group(base_path('app/Http/Controllers/Crm/Contact/contactStageRoutes.php'));
            Route::middleware('company-type')
                ->prefix('company-type')
                ->group(base_path('app/Http/Controllers/Crm/Company/companyTypeRoutes.php'));
            Route::middleware('industry')
                ->prefix('industry')
                ->group(base_path('app/Http/Controllers/Crm/Industry/industryRoutes.php'));
            Route::middleware('company')
                ->prefix('company')
                ->group(base_path('app/Http/Controllers/Crm/Company/companyRoutes.php'));
            Route::middleware('crm-task-type')
                ->prefix('crm-task-type')
                ->group(base_path('app/Http/Controllers/Crm/Task/crmTaskTypeRoutes.php'));
            Route::middleware('crm-task-status')
                ->prefix('crm-task-status')
                ->group(base_path('app/Http/Controllers/Crm/Task/crmTaskStatusRoutes.php'));
            Route::middleware('contact')
                ->prefix('contact')
                ->group(base_path('app/Http/Controllers/Crm/Contact/contactRoutes.php'));
            Route::middleware('opportunity-source')
                ->prefix('opportunity-source')
                ->group(base_path('app/Http/Controllers/Crm/Opportunity/opportunitySourceRoutes.php'));
            Route::middleware('opportunity-type')
                ->prefix('opportunity-type')
                ->group(base_path('app/Http/Controllers/Crm/Opportunity/opportunityTypeRoutes.php'));
            Route::middleware('opportunity-stage')
                ->prefix('opportunity-stage')
                ->group(base_path('app/Http/Controllers/Crm/Opportunity/opportunityStageRoutes.php'));
            Route::middleware('opportunity')
                ->prefix('opportunity')
                ->group(base_path('app/Http/Controllers/Crm/Opportunity/opportunityRoutes.php'));
            Route::middleware('ticket-category')
                ->prefix('ticket-category')
                ->group(base_path('app/Http/Controllers/Crm/Ticket/ticketCategoryRoutes.php'));
            Route::middleware('ticket-status')
                ->prefix('ticket-status')
                ->group(base_path('app/Http/Controllers/Crm/Ticket/ticketStatusRoutes.php'));
            Route::middleware('ticket')
                ->prefix('ticket')
                ->group(base_path('app/Http/Controllers/Crm/Ticket/ticketRoutes.php'));
            Route::middleware('show-ticket-image')
                ->prefix('show-ticket-image')
                ->group(base_path('app/Http/Controllers/Crm/Ticket/showTicketImageRoute.php'));
            Route::middleware('show-ticket-comment-image')
                ->prefix('show-ticket-comment-image')
                ->group(base_path('app/Http/Controllers/Crm/Ticket/showTicketCommentImage.php'));

            Route::middleware('quote-stage')
                ->prefix('quote-stage')
                ->group(base_path('app/Http/Controllers/Crm/Quote/quoteStageRoutes.php'));
            Route::middleware('note')
                ->prefix('note')
                ->group(base_path('app/Http/Controllers/Crm/Note/noteRoutes.php'));
            Route::middleware('ticket-comment')
                ->prefix('ticket-comment')
                ->group(base_path('app/Http/Controllers/Crm/Ticket/ticketCommentRoutes.php'));
            Route::middleware('task')
                ->prefix('crm-task')
                ->group(base_path('app/Http/Controllers/Crm/Task/crmTaskRoutes.php'));
            Route::middleware('attachment')
                ->prefix('attachment')
                ->group(base_path('app/Http/Controllers/Crm/Attachment/attachmentRoutes.php'));
            Route::middleware('crm-email')
                ->prefix('crm-email')
                ->group(base_path('app/Http/Controllers/Crm/CrmEmail/crmEmailRoutes.php'));
            Route::middleware('lead-source')
                ->prefix('lead-source')
                ->group(base_path('app/Http/Controllers/Crm/Lead/leadSourceRoutes.php'));
            Route::middleware('lead')
                ->prefix('lead')
                ->group(base_path('app/Http/Controllers/Crm/Lead/leadRoutes.php'));


            //project
            Route::middleware('project')
                ->prefix('project')
                ->group(base_path('app/Http/Controllers/HR/Project/projectRoutes.php'));
            Route::middleware('milestone')
                ->prefix('milestone')
                ->group(base_path('app/Http/Controllers/HR/Project/milestoneRoutes.php'));
            Route::middleware('projectTask')
                ->prefix('tasks')
                ->group(base_path('app/Http/Controllers/HR/Project/taskRoutes.php'));
            Route::middleware('task-status')
                ->prefix('task-status')
                ->group(base_path('app/Http/Controllers/HR/Project/taskStatusRoutes.php'));
            Route::middleware('project-team')
                ->prefix('project-team')
                ->group(base_path('app/Http/Controllers/HR/Project/projectTeamRoutes.php'));

            //Task
            Route::middleware('task')
                ->prefix('task')
                ->group(base_path('app/Http/Controllers/Tasks/tasksRoutes.php'));

            //saleInvoice
            Route::middleware('saleInvoice')
                ->prefix('sale-invoice')
                ->group(base_path('app/Http/Controllers/Sale/SaleInvoice/saleInvoiceRoutes.php'));
            Route::middleware('payment-sale-invoice')
                ->prefix('payment-sale-invoice')
                ->group(base_path('app/Http/Controllers/Sale/PaymentSaleInvoice/paymentSaleInvoiceRoutes.php'));
        });
    }
}
