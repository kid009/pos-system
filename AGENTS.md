<laravel-boost-guidelines>
=== foundation rules ===

# Laravel Boost Guidelines

## Foundational Context

This is a **Laravel POS (Point of Sale) System** built with:
- PHP 8.3, Laravel Framework v13
- Laravel Breeze (auth scaffolding with Blade)
- Tailwind CSS v3 + Vite
- SQLite (default), MySQL-compatible
- PHPUnit v12 for testing

## Domain Overview

**Core Entities**: Shop, User, Customer, Product, ProductCategory, Supplier, Bank, ShippingMethod, SalesChannel, Purchase, Transaction, TransactionDetail, ExpenseCategory

**Controller Organization**:
- `app/Http/Controllers/Admin/` - Admin functions
- `app/Http/Controllers/Auth/` - Authentication
- `app/Http/Controllers/Inventory/` - Inventory management
- `app/Http/Controllers/MasterData/` - CRUD for reference data
- `app/Http/Controllers/Report/` - Reporting

## Skills Activation

This project has domain-specific skills available in `**/skills/**`. You MUST activate the relevant skill whenever you work in that domain.

## Conventions

- Use PHP 8 constructor property promotion
- Use explicit return types and type hints
- Use TitleCase for Enum keys
- Check sibling files for existing patterns before creating new ones
- Prefer named routes and `route()` helper

=== boost rules ===

# Laravel Boost

## Tools

- Laravel Boost is an MCP server with tools designed specifically for this application. Prefer Boost tools over manual alternatives.
- Use `database-query` for read-only SQL instead of tinker
- Use `database-schema` before writing migrations
- Use `get-absolute-url` before sharing URLs
- Use `browser-logs` for debugging frontend issues (recent entries only)

## Searching Documentation (IMPORTANT)

- Always use `search-docs` before making code changes. Do not skip this step.
- Use broad, topic-based queries: `['rate limiting', 'routing']`
- Use `"quoted phrases"` for exact matches: `"infinite scroll"`
- Do not add package names to queries (info is already shared)

## Artisan Commands

```bash
# Run single test (recommended after changes)
php artisan test --compact --filter=testName

# Run feature tests in a file
php artisan test --compact tests/Feature/ExampleTest.php

# Run all tests
php artisan test --compact

# Route inspection
php artisan route:list --path=admin
php artisan route:list --method=POST

# Config inspection
php artisan config:show app.name
php artisan config:show database.default
```

## Code Generation

Always use `php artisan make:` with `--no-interaction`:

```bash
# Create model with factory and seeder
php artisan make:model Product --factory --seeder --no-interaction

# Create controller
php artisan make:controller MasterData/ProductController --no-interaction

# Create test (feature tests are default)
php artisan make:test ProductTest --no-interaction
php artisan make:test ProductUnitTest --unit --no-interaction
```

## Tinker

Use single quotes to prevent shell expansion:
```bash
php artisan tinker --execute 'User::count();'
php artisan tinker --execute 'User::where("active", true)->count();'
```

=== php rules ===

# PHP

- Always use curly braces for control structures
- Use PHP 8 constructor property promotion
- Use explicit return type declarations and type hints
- Use TitleCase for Enum keys
- Prefer PHPDoc blocks over inline comments
- Use array shape type definitions in PHPDoc

=== laravel/core rules ===

# Do Things the Laravel Way

## API Development

- Use Eloquent API Resources for APIs
- Follow existing API versioning conventions if present

## Testing

- This project uses **PHPUnit only** (convert Pest tests to PHPUnit)
- Use factories for model creation in tests
- Run minimal tests with `--filter` before finalizing
- Run full suite: `php artisan test --compact`

## Frontend

- Vite handles asset building (`vite.config.js`)
- Tailwind CSS v3 configured in `tailwind.config.js`
- If Vite manifest errors occur: run `npm run build` or ask user to run `npm run dev`/`composer run dev`

=== pint/core rules ===

# Laravel Pint Code Formatter

**Required before finalizing any PHP changes:**

```bash
vendor/bin/pint --dirty
```

Do not use `--test` flag - just run the fixer.

=== composer rules ===

# Composer Scripts

```bash
# Full project setup (run once)
composer run setup

# Development server (runs Laravel, queue, logs, Vite concurrently)
composer run dev

# Run tests with config clear
composer run test
```

=== project-specific ===

# POS System Notes

## Database

- Default: SQLite (`DB_CONNECTION=sqlite`)
- Testing: SQLite in-memory (`:memory:`)
- Migrations cover: users, shops, customers, products, categories, suppliers, banks, shipping methods, sales channels, purchases, transactions, expense categories

## Authentication

- Laravel Breeze provides auth scaffolding
- Routes in `routes/auth.php`
- Role-based access with `role` column on users table
- Shop-scoped multi-tenancy with `shop_id` on users table

## View Organization

- `resources/views/admin/` - Admin dashboards and user management
- `resources/views/master-data/` - CRUD views for reference data
- `resources/views/pos/` - Point of sale interface
- `resources/views/shop/` - Shop management
- `resources/views/reports/` - Reporting views

## Key Conventions

- Master data controllers follow CRUD patterns
- Partials stored in `*/partials/` subdirectories
- Activity logging enabled (spatie/laravel-activitylog)
- Always check for existing components before creating new ones

</laravel-boost-guidelines>
