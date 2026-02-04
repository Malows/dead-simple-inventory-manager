# Implementation Plan - User Activity Logging

## Phase 1: Configuration & Setup
- [x] Task: Verify Package Installation & Migration
    - [x] Check if `spatie/laravel-activitylog` is present in `composer.json`.
    - [x] Check if `activity_log` table exists in database or run migration.
    - [x] Publish config file if missing: `php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-config"`.

## Phase 2: User Model Integration
- [x] Task: Implement Traits on User Model
    - [x] Add `Spatie\Activitylog\Traits\CausesActivity` to `App\Models\User`.
    - [x] Add `Spatie\Activitylog\Traits\LogsActivity` to `App\Models\User`.
    - [x] Implement `getActivitylogOptions()`:
        - [x] Log all attributes except `password`, `remember_token`.
        - [x] Use description: "User {$eventName} by {$causer.name}({$causer.id})".
        - [x] Enable `logOnlyDirty` and `dontSubmitEmptyLogs`.
- [x] Task: Test User Logging
    - [x] Create a test case to verify User profile updates generate logs.
    - [x] Verify sensitive fields are excluded.

## Phase 3: Core Inventory Models Integration
- [x] Task: Implement Logging on Product Model
    - [x] Add `LogsActivity` trait to `App\Models\Product`.
    - [x] Implement `getActivitylogOptions()`:
        - [x] Exclude `stock`, `price_updated_at`, `stock_updated_at` (and standard timestamps).
        - [x] Description: "Product {$eventName} by {$causer.name}({$causer.id})".
- [x] Task: Implement Logging on Brand, Category, Supplier, StorageLocation
    - [x] Add `LogsActivity` trait to `App\Models\Brand`.
    - [x] Add `LogsActivity` trait to `App\Models\Category`.
    - [x] Add `LogsActivity` trait to `App\Models\Supplier`.
    - [x] Add `LogsActivity` trait to `App\Models\StorageLocation`.
    - [x] Configure `getActivitylogOptions()` for each with consistent description format.

## Phase 4: Verification & Cleanup
- [x] Task: Comprehensive Integration Testing
    - [x] Create `ActivityLogTest` to verify CRUD operations on all models generate correct logs.
    - [x] Verify `causer` is correctly recorded.
    - [x] Verify exclusions (stock on Product).
- [x] Task: Update Documentation
    - [x] Edit `@IDEAS.md` to remove legacy logging sections as requested.
- [ ] Task: Conductor - User Manual Verification 'Verification & Cleanup' (Protocol in workflow.md)
