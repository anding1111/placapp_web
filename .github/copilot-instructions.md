# Copilot Instructions for PlacApp

## Project Overview

**PlacApp** is a Laravel 12 web application for managing license plates and user access control. It's built with:
- **Backend**: PHP 8.2+ with Laravel 12 framework
- **Frontend**: Blade templates with Tailwind CSS 4.0 and Alpine.js
- **Build**: Vite for frontend assets, Composer for PHP dependencies
- **Database**: Supports multiple databases (configured via `.env`)
- **Testing**: PHPUnit with feature and unit test suites

### Core Purpose
The app manages plate (license plate) inventory with role-based access:
- **Users**: Can view online users and search plates
- **Admins**: Can import/delete plates via Excel, manage users

---

## Architecture & Key Patterns

### Database Structure
- **Plates**: Core entity with `plate_name` (identifier), level, location, detail, enable flag
  - Uses soft-delete pattern: `plate_enable` boolean instead of `deleted_at`
- **Users**: Authentication model with role-based access (role=1 for admin)
  - Tracks online status and last connection time via `UpdateUserLastActivity` middleware
- **Uuids**: Related to users (hasMany relationship)

### Route & Middleware Pattern
Routes are protected by authentication middleware and role checks:
```php
Route::middleware(['auth'])->group(...)  // Requires login
Route::middleware(['admin'])->group(...) // Requires admin role
```

**Custom Middleware**:
- `UpdateUserLastActivity`: Runs on every request to update user's `online_status` and `last_connection`
- `Authenticate`: Redirects to login if not authenticated
- Admin check: Uses `User::isAdmin()` method (checks `role == 1`)

### Excel Import/Export Pattern
Uses **maatwebsite/excel** for data operations:
- **PlatesImport**: Validates headers, maps Excel columns to Plate fields
  - Skips plates that already exist (enabled)
  - Validation rules: `plate` required/max:12, optional level/location/detail
- **DeletePlatesImport**: Sets `plate_enable = 0` (soft delete via boolean)

Controllers handle imports/exports:
```
/upload-excel    → PlateController::uploadExcel
/delete-excel    → PlateController::deleteExcel
```

---

## Developer Workflows

### Local Setup
```bash
# Install dependencies
composer install
npm install

# Configure environment
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate:fresh --seed
```

### Running the Application
```bash
# Development server (two terminals)
php artisan serve                    # Backend on http://localhost:8000
npm run dev                          # Frontend assets with Vite

# Production build
npm run build                        # Compiles frontend assets
```

### Testing
```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit

# With coverage
php artisan test --coverage
```

**Test Structure**:
- `tests/Feature`: Full request/response cycles
- `tests/Unit`: Business logic isolation
- **Database**: Configured for SQLite in-memory by default (see `phpunit.xml`)

### Database Migrations
```bash
php artisan migrate              # Run migrations
php artisan migrate:refresh      # Reset + run all
php artisan migrate:fresh --seed # Reset + seed (fresh start)
php artisan tinker              # Interactive shell for testing
```

---

## Code Conventions

### Model Patterns
- **Protected $fillable**: Explicitly list mass-assignable attributes
- **Protected $casts**: Use for date/boolean type casting
  ```php
  protected $casts = [
      'plate_entry_date' => 'datetime',  // Carbon instance
      'plate_enable' => 'boolean',       // Cast to bool
  ];
  ```
- **Helper Methods**: Add business logic methods like `User::isAdmin()`

### Controller Patterns
- Controllers extend base `Controller` class
- Use `Request` validation before processing:
  ```php
  $request->validate(['excel' => 'required|file|mimes:xlsx,xls,csv']);
  ```
- Return JSON for AJAX: `response()->json(['success' => true])`
- Use try-catch for file operations (Excel imports can throw)

### Blade Templates
- Located in `resources/views/` with subdirectories by domain (plates, users, etc.)
- Use form helpers: `@csrf`, `@method('DELETE')` for security
- Data table filtering happens client-side via jQuery DataTables

### Soft Delete Pattern (Plate-specific)
PlacApp uses **boolean flags** instead of Laravel's `SoftDeletes`:
- Query active plates: `where('plate_enable', 1)` or `true`
- "Delete": Set `plate_enable = 0` (not an actual delete)
- This preserves historical data while hiding from normal queries

---

## Important Files & Their Roles

| File | Purpose |
|------|---------|
| `routes/web.php` | Route definitions with middleware groups |
| `app/Http/Kernel.php` | Global/route middleware registration, including custom `UpdateUserLastActivity` |
| `app/Models/Plate.php` | Plate entity with mass-assignable fields |
| `app/Models/User.php` | Auth model with `isAdmin()` helper and `uuids()` relationship |
| `app/Imports/PlatesImport.php` | Excel import with validation and deduplication |
| `app/Http/Middleware/UpdateUserLastActivity.php` | Tracks user online status on each request |
| `database/migrations/` | Schema changes; key: `2025_03_18_214913_create_plates_table.php` |
| `phpunit.xml` | Test configuration (SQLite in-memory by default) |
| `.env.example` | Template for environment variables (DB_CONNECTION, etc.) |

---

## Integration Points & Dependencies

### External Packages
- **laravel/sanctum** (v4.0): API token authentication (configured but not actively used in web routes)
- **maatwebsite/excel** (v3.1): Excel import/export handling
- **laravel/pint**: Code style fixer (`composer pint`)
- **phpunit/phpunit** (v11.5.3): Testing framework

### Database Connections
Default configured for SQLite, but supports MySQL, PostgreSQL via `.env`:
```env
DB_CONNECTION=sqlite  # or mysql, pgsql
DB_DATABASE=database.sqlite
```

### Frontend Build
- **Vite** orchestrates CSS/JS bundling
- **Tailwind CSS 4.0**: PostCSS-based utility framework
- **Alpine.js** for minimal interactivity
- Entry points: `resources/js/app.js`, `resources/css/app.css`

---

## Common Tasks & Patterns

### Adding a New Feature
1. Create migration: `php artisan make:migration create_feature_table`
2. Define model: `php artisan make:model Feature -m` (with migration)
3. Add routes in `routes/web.php` under appropriate middleware group
4. Create controller: `php artisan make:controller FeatureController`
5. Add Blade view in `resources/views/feature/`
6. Write tests: `php artisan make:test FeatureTest`

### Bulk Operations (Excel Import)
- Use `Imports/YourImport.php` implementing `ToModel`, `WithHeadingRow`, `WithValidation`
- Override `model()` to map Excel row to Eloquent model
- Define `rules()` for row validation
- Controller handles file upload, Excel facade does parsing

### User Activity Tracking
- `UpdateUserLastActivity` middleware updates `last_connection` and `online_status` on each request
- Runs in `web` middleware group (all authenticated routes)
- Query online users: `User::where('online_status', true)->get()`

---

## Troubleshooting Notes

- **Migration errors**: Check `.env` DB_CONNECTION matches available drivers
- **Asset not loading**: Ensure `npm run dev` (dev) or `npm run build` (prod) is run
- **Excel import fails**: Verify Excel file has headers matching PlatesImport expectations (plate, level, location, detail)
- **Tests fail**: Reset database with `php artisan migrate:fresh --seed` before running
- **Permission issues on XAMPP**: Use `chmod` or run with `sudo` for file operations in htdocs

---

## Questions to Ask When Extending
- Is this a soft-delete or hard-delete operation?
- Should this require admin role? Add middleware `['admin']` to route
- Does this need Excel import? Create new Import class with validation
- Should user activity be tracked? It's automatic via middleware
