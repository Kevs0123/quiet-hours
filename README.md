# Quiet Hours — Hotel Booking App

This package contains **application files only** (models, migrations, controllers,
requests, middleware, routes, views). It is meant to be dropped into a real Laravel
project — either your existing "Quiet Hours" Laravel app, or a brand-new
`laravel new quiet-hours` install — because a full framework install (vendor/,
composer.lock, etc.) can't be generated in this sandbox.

## 1. Install into a Laravel project

If you don't already have one:

```bash
composer create-project laravel/laravel quiet-hours
cd quiet-hours
```

Then copy every folder from this zip **into your Laravel project root**, merging
with the existing folders (they won't overwrite Laravel's own core files — these are
all new files):

```
app/Models/RoomCategory.php
app/Models/Room.php
app/Models/Booking.php
app/Http/Controllers/RoomCategoryController.php
app/Http/Controllers/RoomController.php
app/Http/Controllers/BookingController.php
app/Http/Requests/StoreRoomCategoryRequest.php
app/Http/Requests/UpdateRoomCategoryRequest.php
app/Http/Requests/StoreRoomRequest.php
app/Http/Requests/UpdateRoomRequest.php
app/Http/Requests/BookingDetailsRequest.php
app/Http/Requests/BookingConfirmationRequest.php
app/Http/Middleware/EnsureBookingStepCompleted.php
database/migrations/2026_07_01_000001_create_room_categories_table.php
database/migrations/2026_07_01_000002_create_rooms_table.php
database/migrations/2026_07_01_000003_create_bookings_table.php
database/factories/RoomCategoryFactory.php
database/factories/RoomFactory.php
database/seeders/RoomCategorySeeder.php
database/seeders/RoomSeeder.php
database/seeders/DatabaseSeeder.php   <- replace the existing one
routes/web.php                        <- replace the existing one
resources/views/**                    <- replace/add all
```

## 2. Register the booking-step middleware

**Laravel 11+** — in `bootstrap/app.php`:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'booking.step' => \App\Http\Middleware\EnsureBookingStepCompleted::class,
    ]);
})
```

**Laravel 10 or earlier** — in `app/Http/Kernel.php`, add to `$middlewareAliases`:

```php
protected $middlewareAliases = [
    // ...
    'booking.step' => \App\Http\Middleware\EnsureBookingStepCompleted::class,
];
```

## 3. Configure your database

Edit `.env` with your DB credentials, then:

```bash
php artisan migrate
php artisan db:seed
```

This creates `room_categories`, `rooms`, and `bookings` tables, and seeds
5 named categories + 3 random ones, each with 4 rooms (via factories).

## 4. Enable file uploads

The confirmation upload step stores files on the `public` disk, so link it:

```bash
php artisan storage:link
```

## 5. Run it

```bash
php artisan serve
```

- `/` — home page
- `/categories` — Room Category CRUD (create, edit, delete, view rooms per category)
- `/rooms` — Room CRUD (create, edit, delete; category dropdown via relationship)
- `/booking` — start the booking wizard (enter your name)
- `/booking/start/{customerName}` — personalized welcome page
- `/booking/details` — Step 1 form (locked until name is set)
- `/booking/confirmation` — Step 2 file upload (locked until Step 1 done)
- `/booking/summary` — Step 3 summary + file preview (locked until upload done)

Users cannot jump ahead — the `booking.step` middleware redirects back to
`/booking` with an error message if an earlier step hasn't been completed yet.

## 6. Test everything in Tinker

```bash
php artisan tinker
```

```php
// Eloquent relationships
$category = App\Models\RoomCategory::with('rooms')->first();
$category->rooms;                       // hasMany
$category->rooms->first()->category;    // belongsTo

// CRUD
App\Models\RoomCategory::create(['name' => 'Test Suite', 'slug' => 'test-suite', 'description' => 'Testing']);
App\Models\Room::create(['room_category_id' => 1, 'name' => 'Room 501', 'price_per_night' => 3500, 'capacity' => 2]);
App\Models\Room::find(1)->update(['is_available' => false]);
App\Models\Room::find(1)->delete();

// Bookings created by the wizard
App\Models\Booking::latest()->first();
```

## Rubric coverage

| Rubric criteria | Where it's implemented |
|---|---|
| Migrations & Seeders | `database/migrations/*`, `database/seeders/*`, `database/factories/*` — full sample data on seed |
| Eloquent Relationships | `RoomCategory::rooms()` (hasMany) ↔ `Room::category()` (belongsTo), used throughout views/controllers |
| CRUD with Eloquent & Controllers | `RoomCategoryController`, `RoomController` — full resource CRUD, organized, with Form Request validation |
| Route Model Binding & Resource Routing | `Route::resource(...)` for both, implicit binding on `{category}` / `{room}` |
| Testing & Use of Tinker | See section 6 above — relationships, CRUD, and bookings all testable |
