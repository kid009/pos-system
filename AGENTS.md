# Agent Quick-Start: POS System

Compact reference for OpenCode sessions. If a fact is obvious from filenames or standard Laravel docs, it is omitted.

---

## Stack & Versions

- **Laravel** 13.9, **PHP** ^8.3
- **Frontend:** Blade + Tailwind CSS v3 + Vite + AlpineJS
- **Auth:** Laravel Breeze (blade views, session-based)
- **RBAC:** Spatie Laravel Permission (`roles`, `permissions` tables, `HasRoles` trait)
- **Activity Log:** Spatie Laravel Activitylog (`LogsActivity` trait, `getActivitylogOptions()`)
- **DB default:** SQLite (`.env.example`). Production likely MySQL.
- **Timezone:** `Asia/Bangkok`
- **Locale:** Thai/English mixed. UI strings and comments often use Thai.

---

## One-Time Setup

```bash
composer run setup
# equivalent to: composer install, .env copy, key:generate, migrate --force,
#                 npm install --ignore-scripts, npm run build
```

---

## Daily Dev Commands

```bash
# Run everything concurrently (server + queue + logs + vite)
composer run dev

# Run tests (auto-clears config first)
composer run test
# Single test
php artisan test --compact --filter=testName

# Code formatting before commit
vendor/bin/pint --dirty
# Do NOT use --test flag with pint in this repo
```

---

## Architecture

**Request pipeline:** `FormRequest → DTO → Action → Controller → Response`

**Namespace layout:**
- `app/Http/Controllers/Admin/` — RBAC, roles, permissions
- `app/Http/Controllers/MasterData/` — CRUD for reference data (products, categories)
- `app/Actions/{Domain}/{Entity}/{Action}.php` — business logic
- `app/DTOs/` — data transfer objects (readonly classes, constructor property promotion)
- `app/Enums/` — `ModuleTypeEnum`, `ActionTypeEnum`, `RoleTypeEnum`
- `app/Policies/` — authorization (registered implicitly via auto-discovery)

---

## Critical Conventions

### UUIDs, Not IDs
- Models use `HasUuids` with `uniqueIds(): ['uuid']`.
- The `id` column is intentionally **hidden** from serialization (`$hidden = ['id']`).
- `Product` and `Category` explicitly set `getRouteKeyName()` to return `'uuid'` for route model binding.
- Always use `uuid` in public APIs, route model binding, and frontend links. Do not expose `id`.

### Soft Deletes
- `Product` and `User` use `SoftDeletes`.
- Controllers may offer `restore` and `forceDelete` routes for these models.

### Authorization
- `AppServiceProvider::boot()` has a global `Gate::before`:
  1. Banned users (`is_banned = true`) are blocked from **all** abilities.
  2. `super_admin` role bypasses all permission checks.
- Core roles (`super_admin`, `owner`) are protected from edit/delete in controllers.

### Query Patterns
- Eager load relations with `->with('category')` to prevent N+1.
- Use `->withDefault([...])` on `BelongsTo` so missing relations return a stub instead of `null`.
- Controllers paginate with `->withQueryString()` so search filters survive page changes.

### PHP 8.3 Attributes
- `User` model uses `#[Fillable(...)]` and `#[Hidden(...)]` attributes, but still retains `protected $hidden = ['id']` alongside them.
- `Product` and `Category` models still use traditional `$fillable` and `$hidden` property declarations.

---

## Database & Migrations

**Key tables:** `users`, `products`, `categories`, `roles`, `permissions`, Spatie pivot tables.
- `products`: `category_id` FK, `softDeletes()`, `uuid`.
- `categories`: `uuid`, no soft deletes.
- Dynamic permission seeder generates CRUD permissions per module from enums.

**Seeded defaults** (`php artisan db:seed`):
- Users: `superadmin@mail.com`, `owner@mail.com`, `employee@mail.com` (password: `password`)
- 5 categories, 23 products (3 uncategorized for edge-case testing).

---

## Testing

- **Framework:** PHPUnit only. Do NOT use Pest.
- **DB:** `:memory:` SQLite (see `phpunit.xml`).
- **Base:** `tests/TestCase.php` extends Laravel's base.
- Run focused tests with `--compact` to reduce noise.

---

## Code Quality

- Run `vendor/bin/pint --dirty` before finalizing changes.
- Do **not** pass `--test` to pint (repo convention).
- Check sibling files in a directory before creating new ones (follow existing naming/placement).

---

## OpenCode / Boost Integration

- `opencode.json` enables `laravel-boost` MCP locally.
- `.opencode/skills/` contains:
  - `laravel-best-practices` — full CRUD pipeline, query optimization, testing checklist (Thai/English)
  - `web-colors` — POS-tailwind color palette, badge/status classes
- `boost.json` registers `laravel-best-practices` and `tailwindcss-development` skills.

When working on models, controllers, migrations, routes, or CRUD features, the `laravel-best-practices` skill is the primary source of truth for repo conventions.

---

## Route Structure

- `routes/web.php` — app routes (dashboard, profile, admin roles, master data)
- `routes/auth.php` — Breeze auth routes (login, register, password reset, logout)
- Named routes preferred. Use `route('name')` helper in controllers and views.

---

## Views

- `resources/views/layouts/` — base layouts
- `resources/views/admin/` — role/permission management
- `resources/views/master-data/` — products, categories
- Partials go in `*/partials/` subdirectories.
- Reuse existing Blade components before creating new ones.
