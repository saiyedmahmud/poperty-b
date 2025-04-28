<?php

namespace App\Http;

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\AuthorizeMiddleware;
use App\Http\Middleware\FileUploader;
use App\Http\Middleware\PreventRequestsDuringMaintenance;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\TrimStrings;
use App\Http\Middleware\TrustProxies;
use App\Http\Middleware\ValidateSignature;
use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Auth\Middleware\RequirePassword;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Foundation\Http\Middleware\ValidatePostSize;
use Illuminate\Http\Middleware\HandleCors;
use Illuminate\Http\Middleware\SetCacheHeaders;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Session\Middleware\AuthenticateSession;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        TrustProxies::class,
        HandleCors::class,
        PreventRequestsDuringMaintenance::class,
        ValidatePostSize::class,
        TrimStrings::class,
        ConvertEmptyStringsToNull::class,
    ];
    protected $routeMiddleware = [
        // Other middleware definitions
        'permission' => AuthorizeMiddleware::class,
        'fileUploader' => FileUploader::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        'user' => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        'permission' => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        'role' => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        'role-permission' => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        'setting' => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        'account' => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        'transaction' => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        'designation' => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        'files' => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        'email-config' => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        'email' => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        'customer' => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        'product' => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        "quote" => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        "email-invoice" => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        "shift" => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        "education" => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        "department" => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        "designation-history" => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        "employment-status" => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        "salary-history" => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        "award" => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        "award-history" => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        "announcement" => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        'payment-method' => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        'manual-payment' => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        'customer-profileImage' => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        'terms-and-condition' => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        'media' => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        'quick-link' => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],

       
        //crm
        'industry' => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        'company-type' => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        'company' => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        'contact-source' => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        'contact-stage' => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        'contact' => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        'crm-task-type' => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        'crm-task-status' => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        'opportunity-source' => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        'opportunity-type' => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        'opportunity-stage' => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        'opportunity' => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],

        'ticket-category' => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        'quote-stage' => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        'ticket-status' => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        'ticket' => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        'note' => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        'ticket-comment' => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        'task' => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        'attachment' => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],

        'crm-email' => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        'show-ticket-image' => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        'show-ticket-comment-image' => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        'crm-dashboard' => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        'priority' => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        'lead-source' => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        'lead' => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        'project' => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        'milestone' => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        'projectTask' => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        'task-status' => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        'project-team' => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        'product-category' => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        'saleInvoice' => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        'payment-sale-invoice' => [
            ThrottleRequests::class,
            SubstituteBindings::class,
        ],
        
    ];

    /**
     * The application's middleware aliases.
     *
     * Aliases may be used instead of class names to conveniently assign middleware to routes and groups.
     *
     * @var array<string, class-string|string>
     */
    protected $middlewareAliases = [
        'auth' => Authenticate::class,
        'auth.basic' => AuthenticateWithBasicAuth::class,
        'auth.session' => AuthenticateSession::class,
        'cache.headers' => SetCacheHeaders::class,
        'can' => Authorize::class,
        'guest' => RedirectIfAuthenticated::class,
        'password.confirm' => RequirePassword::class,
        'precognitive' => HandlePrecognitiveRequests::class,
        'signed' => ValidateSignature::class,
        'throttle' => ThrottleRequests::class,
        'verified' => EnsureEmailIsVerified::class,
    ];
}
