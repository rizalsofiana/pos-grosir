<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MasterDataController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/products', [MasterDataController::class, 'products'])->name('products');
    Route::post('/products', [MasterDataController::class, 'storeProduct'])->name('products.store');

    Route::get('/categories', [MasterDataController::class, 'categories'])->name('categories');
    Route::post('/categories', [MasterDataController::class, 'storeCategory'])->name('categories.store');

    Route::get('/suppliers', [MasterDataController::class, 'suppliers'])->name('suppliers');
    Route::post('/suppliers', [MasterDataController::class, 'storeSupplier'])->name('suppliers.store');

    Route::get('/customers', [MasterDataController::class, 'customers'])->name('customers');
    Route::post('/customers', [MasterDataController::class, 'storeCustomer'])->name('customers.store');
});

Route::middleware('role:admin')->get('/admin', function () {
    return 'Admin dashboard';
});
