# GEMINI.md - Project Specific Mandates

This file contains foundational mandates for the POS System project. These instructions take absolute precedence over general workflows and tool defaults.

## Project Context
- **Framework:** Laravel 11.x (PHP 8.2+)
- **Architecture:** Traditional Laravel MVC with Service Layer for business logic.
- **Frontend:** Laravel Blade with Vanilla CSS/JS (Vite).
- **Domain:** Point of Sale (POS) system for managing Shops, Products, Categories, Customers, and Transactions.

## Coding Standards & Conventions
- **PHP Style:** Follow PSR-12 and Laravel's default coding style.
- **Service Layer:** Move complex business logic from Controllers to Service classes (found in `app/Services`).
- **Models:**
    - Use Eloquent strictly.
    - Implement the `Auditable` trait for models requiring activity tracking.
    - Define relationships clearly in models.
- **Controllers:**
    - Keep controllers "thin".
    - Use Admin-specific controllers in `app/Http/Controllers/Admin`.
    - Use `PosController` for the main POS interface.
- **Migrations:**
    - Always create migrations for database changes.
    - Ensure migrations are reversible (where possible).
    - Use descriptive names (e.g., `create_xxx_table`, `add_xxx_to_yyy_table`).
- **Views:**
    - Use Blade components and partials to maintain DRY (Don't Repeat Yourself).
    - Maintain the layout structure in `resources/views/layouts`.

## Validation & Security
- **Requests:** Use `FormRequest` classes for complex validation logic.
- **Security:** Never expose sensitive configuration or credentials.
- **Auditing:** Ensure models sensitive to changes use the `Auditable` trait.

## Development Workflow
- **Migrations:** Always run `php artisan migrate` after creating migrations.
- **Seeders:** Maintain `DatabaseSeeder` and specific seeders for testing and initial setup.
- **Testing:** Add Feature/Unit tests for new functionality in the `tests/` directory.
