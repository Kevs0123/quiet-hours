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
    
    // Email verification (allow guests to verify)
    Route::get('/verify', [AuthController::class, 'showVerify'])->name('verify');
    Route::post('/verify', [AuthController::class, 'verify'])->name('verify.post');
    Route::post('/verify/resend', [AuthController::class, 'resendCode'])->name('verify.resend');
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
    Route::post('/bookings/{booking}/confirm', [AdminController::class, 'confirmBooking'])->name('bookings.confirm');
    Route::post('/bookings/{booking}/reject',  [AdminController::class, 'rejectBooking'])->name('bookings.reject');
    Route::post('/bookings/bulk-confirm',      [AdminController::class, 'bulkConfirmBookings'])->name('bookings.bulk-confirm');

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

    // Email verification (simple code flow)
    

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
    Route::get('/history/{booking}', [BookingController::class, 'showBookingDetail'])->name('history.show');
    Route::get('/history/{booking}/pdf', [BookingController::class, 'downloadBookingPdf'])->name('history.pdf');
    Route::get('/dashboard', [BookingController::class, 'dashboard'])->name('dashboard');
    Route::get('/room-availability/{room}', [BookingController::class, 'roomAvailability'])->name('room-availability');

    Route::middleware('booking.step:1')->group(function () {
        Route::get('/details',  [BookingController::class, 'showDetails'])->name('details');
        Route::post('/details', [BookingController::class, 'storeDetails'])->name('details.store');
    });

    Route::middleware('booking.step:2')->group(function () {
        Route::get('/payment',  [BookingController::class, 'showPayment'])->name('payment');
        Route::post('/payment', [BookingController::class, 'storePayment'])->name('payment.store');
    });

    Route::middleware('booking.step:3')->group(function () {
        Route::get('/summary', [BookingController::class, 'summary'])->name('summary');
    });
});
