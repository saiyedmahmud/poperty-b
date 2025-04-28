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
            //Files
            Route::middleware('files')
                ->prefix('files')
                ->group(base_path('app/Http/Controllers/Files/filesRoutes.php'));

            Route::middleware('permission')
                ->prefix('permission')
                ->group(base_path('app/Http/Controllers/HR/RolePermission/permissionRoutes.php'));
            Route::middleware('role')
                ->prefix('role')
                ->group(base_path('app/Http/Controllers/HR/RolePermission/roleRoutes.php'));
            Route::middleware('role-permission')
                ->prefix('role-permission')
                ->group(base_path('app/Http/Controllers/HR/RolePermission/rolePermissionRoutes.php'));

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
            //user
            Route::middleware('user')
                ->prefix('user')
                ->group(base_path('app/Http/Controllers/User/userRoutes.php'));

            //media
            Route::middleware('media')
                ->prefix('media')
                ->group(base_path('app/Http/Controllers/MediaFiles/MediaFileRoutes.php'));
        });
    }
}
