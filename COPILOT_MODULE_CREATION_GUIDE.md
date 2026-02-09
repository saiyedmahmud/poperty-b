# Module Creation Guide for Copilot

This guide explains the standard pattern for creating new modules in this Laravel application.

## Overview
When creating a new module (e.g., `demoModule`, `enquiry`, `supplyQuotation`), follow these steps:

---

## Step 1: Create Migration

**Location:** `database/migrations/`

**Naming Convention:**
- File: `YYYY_MM_DD_HHMMSS_create_[module_name]_table.php`
- Table: `camelCase` (e.g., `demoModule`, `supplyQuotation`)
- Use **singular form** (not plural)

**Structure:**
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('moduleName', function (Blueprint $table) {
            $table->id();
            // Add your fields here in camelCase
            $table->string('fieldName');
            $table->string('status')->default('true');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('moduleName');
    }
};
```

**Key Points:**
- Table names: `camelCase`, singular (e.g., `demoModule`, `enquiry`, `supplyQuotation`)
- Column names: `camelCase` (e.g., `fieldName`, `casaId`, `projectName`)
- Always include `status` field with default `'true'`
- Always include `timestamps()`
- Foreign keys: Use camelCase + `Id` (e.g., `enquiryId`, `supplyQuotationId`)

---

## Step 2: Create Model

**Location:** `app/Models/`

**Naming Convention:**
- Class name: `PascalCase`, singular (e.g., `DemoModule`, `Enquiry`, `SupplyQuotation`)
- File name: Same as class name + `.php`

**Structure:**
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModuleName extends Model
{
    use HasFactory;

    protected $table = 'moduleName'; // camelCase table name

    protected $fillable = [
        'fieldName',
        'anotherField',
        'status',
    ];

    // Add casts for specific data types
    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
        'submittedAt' => 'datetime',
    ];

    // Add relationships
    public function relatedModel()
    {
        return $this->belongsTo(RelatedModel::class, 'relatedModelId');
    }
}
```

**Key Points:**
- Always use `HasFactory` trait
- Set `$table` property to camelCase table name
- Define `$fillable` array with all fillable fields
- Add `$casts` for decimal, date, datetime fields
- Define Eloquent relationships (belongsTo, hasMany, etc.)

---

## Step 3: Create Controller

**Location:** `app/Http/Controllers/[ModuleName]/`

**Naming Convention:**
- Folder: `PascalCase` (e.g., `DemoModule`, `Enquiry`, `SupplyQuotation`)
- File: `[ModuleName]Controller.php`
- Class: `[ModuleName]Controller`

**Structure:**
```php
<?php

namespace App\Http\Controllers\ModuleName;

use App\Http\Controllers\Controller;
use App\Models\ModuleName;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ModuleNameController extends Controller
{
    // CREATE - POST /
    public function createSingleModuleName(Request $request): JsonResponse
    {
        try {
            $created = ModuleName::create([
                'fieldName' => $request->input('fieldName'),
                'status' => $request->input('status', 'true'),
            ]);
            return $this->response($created->toArray());
        } catch (Exception $error) {
            return response()->json(['error' => 'An error occurred during create. Please try again later.'], 500);
        }
    }

    // READ ALL - GET /
    public function getAllModuleName(Request $request): JsonResponse
    {
        if ($request->query('query') === 'all') {
            try {
                $all = ModuleName::orderBy('id', 'desc')
                    ->where('status', 'true')
                    ->get();

                return $this->response([
                    'getAllModuleName' => $all->toArray(),
                    'totalModuleName' => ModuleName::where('status', 'true')->count(),
                ]);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during getting records. Please try again later.'], 500);
            }
        } else if ($request->query('query') === 'search') {
            try {
                $pagination = getPagination($request->query());
                $key = trim($request->query('key'));

                $results = ModuleName::where('fieldName', 'LIKE', '%' . $key . '%')
                    ->orderBy('id', 'desc')
                    ->skip($pagination['skip'])
                    ->take($pagination['limit'])
                    ->get();

                $count = ModuleName::where('fieldName', 'LIKE', '%' . $key . '%')->count();

                return $this->response([
                    'getAllModuleName' => $results->toArray(),
                    'totalModuleName' => $count,
                ]);
            } catch (Exception $error) {
                return response()->json(['error' => 'An error occurred during search. Please try again later.'], 500);
            }
        } else if ($request->query()) {
            try {
                $pagination = getPagination($request->query());
                $results = ModuleName::when($request->query('status'), function ($query) use ($request) {
                    return $query->whereIn('status', explode(',', $request->query('status')));
                })
                    ->orderBy('id', 'desc')
                    ->skip($pagination['skip'])
                    ->take($pagination['limit'])
                    ->get();

                $count = ModuleName::when($request->query('status'), function ($query) use ($request) {
                    return $query->whereIn('status', explode(',', $request->query('status')));
                })->count();

                return $this->response([
                    'getAllModuleName' => $results->toArray(),
                    'totalModuleName' => $count,
                ]);
            } catch (Exception $error) {
                return response()->json(['error' => 'An error occurred during getting records. Please try again later.'], 500);
            }
        } else {
            return response()->json(['error' => 'Invalid query!'], 400);
        }
    }

    // READ SINGLE - GET /{id}
    public function getSingleModuleName(Request $request, $id): JsonResponse
    {
        try {
            $single = ModuleName::find($id);
            if (!$single) {
                return $this->badRequest('Record not found!');
            }
            return $this->response($single->toArray());
        } catch (Exception $error) {
            return response()->json(['error' => 'An error occurred during getting record. Please try again later.'], 500);
        }
    }

    // UPDATE - PUT /{id}
    public function updateSingleModuleName(Request $request, $id): JsonResponse
    {
        try {
            $record = ModuleName::where('id', $id)->first();

            if (!$record) {
                return $this->badRequest('Record not found!');
            }

            $record->update([
                'fieldName' => $request->input('fieldName'),
                'status' => $request->input('status', $record->status),
            ]);

            return response()->json(['message' => 'Record Updated Successfully'], 200);
        } catch (Exception $error) {
            return response()->json(['error' => 'An error occurred during update. Please try again later.'], 500);
        }
    }

    // DELETE (Soft) - PATCH /{id}
    public function deleteSingleModuleName(Request $request, $id): JsonResponse
    {
        try {
            $record = ModuleName::where('id', $id)->first();

            if (!$record) {
                return $this->badRequest('Record not found!');
            }

            $record->status = $request->input('status');
            $record->save();

            return response()->json(['message' => 'Record Deleted Successfully'], 200);
        } catch (Exception $error) {
            return response()->json(['error' => 'An error occurred during delete. Please try again later.'], 500);
        }
    }
}
```

**Key Points:**
- All methods return `JsonResponse`
- Use `try-catch` blocks for error handling
- Support search and pagination
- Soft delete using `status` field (not hard delete)
- Use `$this->response()` and `$this->badRequest()` helper methods

---

## Step 4: Create Routes File

**Location:** `app/Http/Controllers/[ModuleName]/`

**Naming Convention:**
- File: `[moduleName]Routes.php` (camelCase with `Routes` suffix)

**Structure:**
```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ModuleName\ModuleNameController;

Route::middleware('permission:create-moduleName')->post('/', [ModuleNameController::class, 'createSingleModuleName']);

Route::middleware('permission:readAll-moduleName')->get('/', [ModuleNameController::class, 'getAllModuleName']);

Route::middleware('permission:readSingle-moduleName')->get('/{id}', [ModuleNameController::class, 'getSingleModuleName']);

Route::middleware('permission:update-moduleName')->put('/{id}', [ModuleNameController::class, 'updateSingleModuleName']);

Route::middleware('permission:delete-moduleName')->patch('/{id}', [ModuleNameController::class, 'deleteSingleModuleName']);
```

**Key Points:**
- File name: camelCase + `Routes.php`
- All routes use permission middleware
- Permission format: `{action}-{moduleName}` (e.g., `create-demoModule`)
- HTTP methods: POST (create), GET (read), PUT (update), PATCH (delete)

---

## Step 5: Register Middleware in Kernel.php

**Location:** `app/Http/Kernel.php`

**Add to `$middlewareGroups` array:**
```php
protected $middlewareGroups = [
    // ... existing middleware groups
    
    'moduleName' => [
        ThrottleRequests::class,
        SubstituteBindings::class,
    ],
];
```

**Key Points:**
- Use camelCase for middleware group name
- Always include `ThrottleRequests::class` and `SubstituteBindings::class`

---

## Step 6: Register Routes in RouteServiceProvider.php

**Location:** `app/Providers/RouteServiceProvider.php`

**Add to `boot()` method in `$this->routes()` closure:**
```php
Route::middleware('moduleName')
    ->prefix('moduleName')
    ->group(base_path('app/Http/Controllers/ModuleName/moduleNameRoutes.php'));
```

**Key Points:**
- Middleware name: camelCase (matches Kernel.php)
- Prefix: camelCase (becomes URL path)
- Path: Correct controller folder and routes file

---

## Step 7: Add Permissions to PermissionSeeder.php

**Location:** `database/seeders/PermissionSeeder.php`

**Add to `endpoints` array:**
```php
define('endpoints', [
    // ... existing endpoints
    
    // moduleName //
    ['name' => 'moduleName', 'type' => 'moduleName'],
    // moduleName //
]);
```

**Key Points:**
- Name and type: camelCase
- This creates 5 permissions automatically:
  - `create-moduleName`
  - `readAll-moduleName`
  - `readSingle-moduleName`
  - `update-moduleName`
  - `delete-moduleName`

---

## Complete Checklist

When creating a new module, ensure you've completed ALL steps:

- [ ] **Step 1:** Created migration with camelCase table name (singular)
- [ ] **Step 2:** Created model in `app/Models/` with PascalCase name (singular)
- [ ] **Step 3:** Created controller in `app/Http/Controllers/[ModuleName]/`
- [ ] **Step 4:** Created routes file `[moduleName]Routes.php`
- [ ] **Step 5:** Added middleware group to `app/Http/Kernel.php`
- [ ] **Step 6:** Registered routes in `app/Providers/RouteServiceProvider.php`
- [ ] **Step 7:** Added permissions to `database/seeders/PermissionSeeder.php`
- [ ] **Step 8:** Run migrations: `php artisan migrate`
- [ ] **Step 9:** Seed permissions: `php artisan db:seed --class=PermissionSeeder`

---

## Naming Convention Summary

| Item | Convention | Example |
|------|-----------|---------|
| Table Name | camelCase, singular | `demoModule`, `supplyQuotation` |
| Column Name | camelCase | `casaId`, `projectName`, `validUntil` |
| Model Class | PascalCase, singular | `DemoModule`, `SupplyQuotation` |
| Model File | PascalCase + .php | `DemoModule.php` |
| Controller Folder | PascalCase | `DemoModule/`, `SupplyQuotation/` |
| Controller Class | PascalCase + Controller | `DemoModuleController` |
| Controller File | PascalCase + Controller.php | `DemoModuleController.php` |
| Routes File | camelCase + Routes.php | `demoModuleRoutes.php` |
| Middleware Group | camelCase | `demoModule`, `supplyQuotation` |
| URL Prefix | camelCase | `/demoModule`, `/supplyQuotation` |
| Permission Name | action-camelCase | `create-demoModule` |

---

## Example: Creating a "Project" Module

1. Migration: `2026_01_05_000001_create_project_table.php` → Table: `project`
2. Model: `app/Models/Project.php` → Class: `Project`
3. Controller: `app/Http/Controllers/Project/ProjectController.php`
4. Routes: `app/Http/Controllers/Project/projectRoutes.php`
5. Kernel: Add `'project' => [...]` to `$middlewareGroups`
6. RouteServiceProvider: Register with middleware `'project'` and prefix `'project'`
7. PermissionSeeder: Add `['name' => 'project', 'type' => 'project']`

---

## Common Pitfalls to Avoid

❌ **Don't use plural table names** (use `enquiry`, not `enquiries`)
❌ **Don't use snake_case for table names** (use `supplyQuotation`, not `supply_quotation`)
❌ **Don't forget to add middleware to Kernel.php**
❌ **Don't forget to register routes in RouteServiceProvider.php**
❌ **Don't forget to add permissions to PermissionSeeder.php**
❌ **Don't use hard delete** (use status field for soft delete)

✅ **Do use camelCase consistently** for tables and columns
✅ **Do use singular forms** for table and model names
✅ **Do include status field** with default 'true'
✅ **Do include timestamps** in migrations
✅ **Do use permission middleware** on all routes
✅ **Do follow the folder structure** exactly as shown

---

## Notes

- This application uses **camelCase** instead of Laravel's default snake_case
- Tables are **singular**, not plural (unlike Laravel convention)
- Soft delete is implemented via `status` field, not Laravel's SoftDeletes trait
- All APIs return JSON responses
- Permission system is custom, using `AuthorizeMiddleware`
- Helper methods `$this->response()` and `$this->badRequest()` are available in Controller base class

---

**Last Updated:** January 4, 2026
