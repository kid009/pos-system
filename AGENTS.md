# AGENTS.md

## Project

Laravel 12 POS system (Thai-language gas shop domain). PHP 8.2+, MySQL, Blade + Bootstrap 5 (CDN) + Vite.

## Commands

| Task | Command |
|------|---------|
| Run all tests | `composer test` |
| Run single test | `php artisan test --filter=TestName` |
| Run single test file | `php artisan test tests/Feature/ExampleTest.php` |
| Lint (Pint) | `./vendor/bin/pint --test` |
| Dev server (all) | `composer dev` (serves app + queue listener + Vite concurrently) |
| Build assets | `npm run build` |
| Fresh DB | `php artisan migrate:fresh --seed` |

Run lint before tests: `./vendor/bin/pint --test && composer test`

## Architecture

- **No API routes** — web-only, Blade-rendered pages. All routes in `routes/web.php`.
- **Service Layer** — complex business logic lives in `app/Services/` (e.g. `TransactionService` for checkout). Keep controllers thin; delegate to services.
- **No FormRequest classes** — validation is inline in controllers currently. If adding complex validation, create `app/Http/Requests/` classes.
- **Auth** — custom session-based (not Breeze/Fortify). `LoginService` handles authentication with rate limiting. Roles are `admin`/`staff` stored as a `role` column on User (no Spatie permission package).
- **Auditable trait** — `App\Traits\Auditable` is a custom trait (not a package). It auto-fills `created_by`/`updated_by` via Eloquent boot events. Add it to models that need change tracking.
- **executeSafely()** — base `Controller` class provides `protected function executeSafely(Closure $action, $successMessage)` that wraps DB transactions and error handling. Use for store/update/destroy in controllers.
- **Activity Logging** — uses `spatie/laravel-activitylog` package. Models use `LogsActivity` trait with `getActivitylogOptions()` method (see Bank, ShippingMethod for examples).

## Key Models

`User` → `Shop` (belongsTo), `Shop` → `Category/Product`, `Transaction` → `TransactionDetail`, `Customer`

Relationships: Transaction belongsTo Shop, Customer, User (as cashier). Product belongsTo Shop and Category.

## Database

Tests run on **SQLite in-memory** (configured in `phpunit.xml`). Production uses MySQL. Always run `php artisan migrate` after creating new migrations.

Seed credentials: `admin@pgas.com` / `password` (role: admin), `staff@pgas.com` / `password` (role: staff).

## Conventions

- Controllers for admin pages: `app/Http/Controllers/Admin/`
- MasterData controllers: `app/Http/Controllers/MasterData/` (Bank, ShippingMethod, etc.)
- POS interface controller: `PosController` (not in Admin namespace)
- Auth controller: `app/Http/Controllers/Auth/LoginController.php`
- Views organized by domain: `resources/views/{admin,pos,auth,layouts,components,master-data}/`
- Invoice numbers use format `REC{YYYYMMDD}{0001}` — generated in `TransactionService`
- UI strings are in **Thai language** — maintain this convention
- Route Model Binding — use type-hinted model parameters in controllers (e.g., `public function edit(Bank $bank)`) instead of `findOrFail($id)`
- Soft delete pattern — MasterData controllers disable records (`is_active = false`) rather than hard delete to preserve historical data integrity

## Frontend

- Bootstrap 5 loaded via CDN in `layouts/app.blade.php` — do not use Tailwind CSS
- Vite handles JS build (`resources/js/app.js` imports axios/bootstrap)
- `@vite(['resources/css/app.css', 'resources/js/app.js'])` directive added to layout
- Feather Icons via CDN (`data-feather` attributes)
- Alpine.js for reactivity (sidebar toggle, etc.)

## Recent Changes

- Tailwind CSS removed (now uses Bootstrap 5 CDN only)
- Added `spatie/laravel-activitylog` for audit logging
- Added `executeSafely()` pattern to base Controller for transaction safety