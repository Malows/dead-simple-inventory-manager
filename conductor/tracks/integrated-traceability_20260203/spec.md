# Track Specification: User Activity Logging

## 1. Overview
Implement comprehensive user activity logging using `spatie/laravel-activitylog`. The goal is to provide full traceability of user actions on key system models, ensuring that all modifications (Create, Update, Delete) are audited. This implementation supersedes the legacy "Macro Activity Log" approach.

## 2. Functional Requirements

### 2.1 Package Integration
-   [ ] Verify and configure `spatie/laravel-activitylog` (already installed).
-   [ ] Ensure the `activity_log` table migration is present and run.
-   [ ] Configure `config/activitylog.php` if necessary (e.g., retention periods).

### 2.2 Model Auditing
Enable activity logging for the following models using `Spatie\Activitylog\Traits\LogsActivity` and `Spatie\Activitylog\Traits\CausesActivity` (for User):

1.  **User**
    -   Must implement `CausesActivity`.
    -   Must also be audited (implement `LogsActivity`) for profile changes.
    -   **Exclusions:** `password`, `remember_token`.

2.  **Product**
    -   **Exclusions:** `stock`, `price_updated_at`, `stock_updated_at`.
    -   **Log:** Name, Code, Price, Description, etc.

3.  **Brand**
    -   Log all attributes.

4.  **Category**
    -   Log all attributes.

5.  **Supplier**
    -   Log all attributes.

6.  **StorageLocation**
    -   Log all attributes.

### 2.3 Logging Behavior
-   **Events:** Log automatically on `created`, `updated`, and `deleted` events.
-   **Content:**
    -   `logOnlyDirty()`: Log only changed attributes.
    -   `dontSubmitEmptyLogs()`: Prevent empty log entries.
    -   **Description:** "{$modelName} {$eventName} by {$causer.name}({$causer.id})".

## 3. Non-Functional Requirements
-   **Performance:** Use `logOnlyDirty` to minimize database writes.
-   **Security:** Strictly exclude sensitive data like passwords.
-   **Consistency:** Use a standardized logging format across all models.

## 4. Acceptance Criteria
-   [ ] All specified models (`User`, `Product`, `Brand`, `Category`, `Supplier`, `StorageLocation`) have the `LogsActivity` trait.
-   [ ] Creating, updating, or deleting any of these entities creates a corresponding row in the `activity_log` table.
-   [ ] `Product` updates do **not** log changes to the `stock`, `price_updated_at`, or `stock_updated_at` columns.
-   [ ] `User` updates do **not** log changes to `password`.
-   [ ] The `causer_id` is correctly populated with the authenticated user's ID.
-   [ ] `IDEAS.md` is updated to remove the legacy logging sections upon completion.

## 5. Out of Scope
-   Bulk Operation logging (deferred to a future track).
-   Authentication logging (Login/Logout).
-   API endpoint for viewing logs (Backend implementation only for now).
