<?php

use App\Http\Controllers\GuestOrderController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\Admin\OrdersController as AdminOrdersController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\User\ProfileController as UserProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CategoryController;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Public Guest Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    $products = Product::all();
    return view('welcome', compact('products'));
})->name('home');

Route::get('/welcome', function () {
    $products = Product::all();
    return view('welcome', compact('products'));
})->name('welcome');

// Guest order routes
Route::post('/guest/order', [GuestOrderController::class, 'store'])->name('guest.order');

// Order routes for guests and logged-in users
Route::post('/order/store', [OrdersController::class, 'store'])->name('order.store');
Route::post('/order/store-multiple', [OrdersController::class, 'storeMultiple'])->name('order.store-multiple');
Route::get('/order/track', [OrdersController::class, 'track'])->name('order.track');
Route::get('/order/confirmation/{id}', [OrdersController::class, 'confirmation'])->name('orders.confirmation');

/*
|--------------------------------------------------------------------------
| Auth Pages
|--------------------------------------------------------------------------
*/

Route::get('/login', fn() => view('auth.login'))->name('login');
Route::get('/register', fn() => view('auth.register'))->name('register');


/*
|--------------------------------------------------------------------------
| Protected Routes (All Users)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Products (view only)
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');

    // Orders (view only)
    Route::get('/orders', [OrdersController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrdersController::class, 'show'])->name('orders.show');
});


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // ================= USERS =================
        Route::delete('users/delete-selected', [UserController::class, 'destroySelected'])
            ->name('users.delete-selected');

        Route::resource('users', UserController::class);


        // ================= PRODUCTS =================
        Route::delete('products/delete-selected', [ProductController::class, 'destroySelected'])
            ->name('products.delete-selected');

        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
        Route::get('/products/{id}/edit-data', [ProductController::class, 'editData'])->name('products.edit-data');


        // ================= CATEGORIES =================
        Route::delete('categories/delete-selected', [CategoryController::class, 'destroySelected'])
            ->name('categories.delete-selected');

        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/list', [CategoryController::class, 'list'])->name('categories.list');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');


        // ================= ORDERS =================
        Route::post('/orders/bulk-delete', [AdminOrdersController::class, 'bulkDelete'])->name('orders.bulk-delete');
        Route::get('/orders', [AdminOrdersController::class, 'index'])->name('orders.index');
        Route::get('/orders/{id}', [AdminOrdersController::class, 'show'])->name('orders.show');
        Route::put('/orders/{id}/status', [AdminOrdersController::class, 'updateStatus'])->name('orders.updateStatus');
        Route::post('/orders/{id}/cancel', [AdminOrdersController::class, 'cancel'])->name('orders.cancel');
        Route::delete('/orders/{id}', [AdminOrdersController::class, 'destroy'])->name('orders.destroy');


        // ================= PROFILE =================
        Route::get('/profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile/photo', [AdminProfileController::class, 'removePhoto'])->name('profile.remove-photo');
        Route::get('/profile/password', [AdminProfileController::class, 'password'])->name('profile.password');
        Route::put('/profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password.update');
});


/*
|--------------------------------------------------------------------------
| User Profile Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {

        Route::get('/', [UserProfileController::class, 'index'])->name('index');
        Route::get('/profile', [UserProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [UserProfileController::class, 'update'])->name('profile.update');
        
        // ADD THIS LINE FOR PHOTO UPLOAD
        Route::patch('/profile/upload-photo', [UserProfileController::class, 'uploadPhoto'])->name('profile.upload-photo');
        
        Route::delete('/profile/remove-photo', [UserProfileController::class, 'removePhoto'])->name('profile.remove-photo');
        
        // Password routes
        Route::get('/profile/password', [UserProfileController::class, 'passwordForm'])->name('profile.password');
        Route::put('/profile/password', [UserProfileController::class, 'updatePassword'])->name('profile.password.update');
        
        // User Products
        Route::get('/products', [\App\Http\Controllers\User\ProductController::class, 'browse'])->name('products.browse');
        
        // User Orders
        Route::post('/order', [\App\Http\Controllers\User\OrderController::class, 'store'])->name('order.store');
        Route::get('/orders', [\App\Http\Controllers\User\OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{id}', [\App\Http\Controllers\User\OrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{id}/cancel', [\App\Http\Controllers\User\OrderController::class, 'cancel'])->name('orders.cancel');
        Route::get('/orders/statistics', [\App\Http\Controllers\User\OrderController::class, 'statistics'])->name('orders.statistics');
        Route::get('/orders-history', [\App\Http\Controllers\User\OrderController::class, 'history'])->name('orders.history');
});

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';