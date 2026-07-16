<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\RoomCategoryController;
use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public landing — passes rooms + categories to the welcome view
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    $categories = \App\Models\RoomCategory::with(['rooms' => function ($q) {
        $q->where('is_available', true)->orderBy('price_per_night');
    }])->orderBy('name')->get()->filter(fn ($c) => $c->rooms->isNotEmpty())->values();

    $featuredRooms = \App\Models\Room::with('category')
        ->where('is_available', true)
        ->orderBy('price_per_night')
        ->take(6)
        ->get();

    return view('welcome', compact('categories', 'featuredRooms'));
})->name('home');

/*
|--------------------------------------------------------------------------
| Authentication (guest only)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',    [AuthController::class, 'login'])->name('login.post');
    Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Admin — authenticated + admin role
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard/stats', [AdminController::class, 'dashboardStats'])->name('dashboard.stats');

    // Bookings CRUD
    Route::get('/bookings',                [AdminController::class, 'bookings'])->name('bookings');
    Route::get('/bookings/{booking}',      [AdminController::class, 'showBooking'])->name('bookings.show');
    Route::get('/bookings/{booking}/edit', [AdminController::class, 'editBooking'])->name('bookings.edit');
    Route::put('/bookings/{booking}',      [AdminController::class, 'updateBooking'])->name('bookings.update');
    Route::delete('/bookings/{booking}',   [AdminController::class, 'destroyBooking'])->name('bookings.destroy');

    // Clients CRUD
    Route::resource('clients', ClientController::class)->names([
        'index'   => 'clients.index',
        'create'  => 'clients.create',
        'store'   => 'clients.store',
        'show'    => 'clients.show',
        'edit'    => 'clients.edit',
        'update'  => 'clients.update',
        'destroy' => 'clients.destroy',
    ]);

    // Room Categories CRUD
    Route::resource('categories', RoomCategoryController::class)->parameters([
        'categories' => 'category',
    ])->names([
        'index'   => 'categories.index',
        'create'  => 'categories.create',
        'store'   => 'categories.store',
        'show'    => 'categories.show',
        'edit'    => 'categories.edit',
        'update'  => 'categories.update',
        'destroy' => 'categories.destroy',
    ]);

    // Rooms CRUD
    Route::resource('rooms', RoomController::class)->names([
        'index'   => 'rooms.index',
        'create'  => 'rooms.create',
        'store'   => 'rooms.store',
        'show'    => 'rooms.show',
        'edit'    => 'rooms.edit',
        'update'  => 'rooms.update',
        'destroy' => 'rooms.destroy',
    ]);
});

/*
|--------------------------------------------------------------------------
| Booking Wizard (auth required — clients only)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('booking')->name('booking.')->group(function () {
    Route::get('/',      [BookingController::class, 'home'])->name('home');
    Route::get('/reset', [BookingController::class, 'reset'])->name('reset');
    Route::get('/start/{customerName}', [BookingController::class, 'start'])->name('start');
    Route::get('/history', [BookingController::class, 'history'])->name('history');
    Route::get('/dashboard', [BookingController::class, 'dashboard'])->name('dashboard');
    Route::get('/room-availability/{room}', [BookingController::class, 'roomAvailability'])->name('room-availability');

    Route::middleware('booking.step:1')->group(function () {
        Route::get('/details',  [BookingController::class, 'showDetails'])->name('details');
        Route::post('/details', [BookingController::class, 'storeDetails'])->name('details.store');
    });

    Route::middleware('booking.step:2')->group(function () {
        Route::get('/confirmation',  [BookingController::class, 'showConfirmation'])->name('confirmation');
        Route::post('/confirmation', [BookingController::class, 'storeConfirmation'])->name('confirmation.store');
    });

    Route::middleware('booking.step:3')->group(function () {
        Route::get('/summary', [BookingController::class, 'summary'])->name('summary');
        Route::get('/summary/file/view', [BookingController::class, 'viewConfirmationFile'])->name('summary.file.view');
        Route::get('/summary/file/download', [BookingController::class, 'downloadConfirmationFile'])->name('summary.file.download');
    });
});
