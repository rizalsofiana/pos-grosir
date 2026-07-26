<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiscountRuleController;
use App\Http\Controllers\MasterDataController;

use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PurchaseReturnController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SaleReturnController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\StockOpnameController;



use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/midtrans/notification', [SaleController::class, 'midtransNotification'])->name('midtrans.notification');


Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware('role:admin,kasir')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/purchases', [PurchaseController::class, 'index'])->name('purchases');
        Route::post('/purchases', [PurchaseController::class, 'store'])->name('purchases.store');

        Route::get('/purchase-returns', [PurchaseReturnController::class, 'index'])->name('purchase-returns');
        Route::post('/purchase-returns', [PurchaseReturnController::class, 'store'])->name('purchase-returns.store');

        Route::get('/sales', [SaleController::class, 'index'])->name('sales');
        Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
        Route::get('/sales/history', [SaleController::class, 'history'])->name('sales.history');
        Route::get('/sales/{sale}/receipt', [SaleController::class, 'receipt'])->name('sales.receipt');
        Route::get('/sales/{sale}/status', [SaleController::class, 'checkStatus'])->name('sales.status');

        Route::post('/sales/hold', [SaleController::class, 'hold'])->name('sales.hold');
        Route::post('/sales/hold/{heldSale}/resume', [SaleController::class, 'resumeHold'])->name('sales.hold.resume');
        Route::delete('/sales/hold/{heldSale}', [SaleController::class, 'destroyHold'])->name('sales.hold.destroy');


        Route::get('/sale-returns', [SaleReturnController::class, 'index'])->name('sale-returns');
        Route::post('/sale-returns', [SaleReturnController::class, 'store'])->name('sale-returns.store');
        Route::post('/sale-returns/find-sale', [SaleReturnController::class, 'findSale'])->name('sale-returns.find-sale');



        Route::get('/stock', [StockController::class, 'index'])->name('stock');
        Route::get('/stock/{product}/history', [StockController::class, 'history'])->name('stock.history');
    });

    Route::middleware('role:admin')->group(function () {
        Route::post('/stock/adjust', [StockController::class, 'adjust'])->name('stock.adjust');

        Route::get('/stock-opnames', [StockOpnameController::class, 'index'])->name('stock-opnames');
        Route::post('/stock-opnames', [StockOpnameController::class, 'store'])->name('stock-opnames.store');
        Route::get('/stock-opnames/{stockOpname}', [StockOpnameController::class, 'show'])->name('stock-opnames.show');

        Route::get('/products', [MasterDataController::class, 'products'])->name('products');

        Route::post('/products', [MasterDataController::class, 'storeProduct'])->name('products.store');
        Route::put('/products/{product}', [MasterDataController::class, 'updateProduct'])->name('products.update');
        Route::patch('/products/{product}/toggle', [MasterDataController::class, 'toggleProduct'])->name('products.toggle');
        Route::get('/products/{product}/price-history', [MasterDataController::class, 'priceHistory'])->name('products.price-history');


        Route::get('/categories', [MasterDataController::class, 'categories'])->name('categories');
        Route::post('/categories', [MasterDataController::class, 'storeCategory'])->name('categories.store');
        Route::put('/categories/{category}', [MasterDataController::class, 'updateCategory'])->name('categories.update');
        Route::patch('/categories/{category}/toggle', [MasterDataController::class, 'toggleCategory'])->name('categories.toggle');

        Route::get('/suppliers', [MasterDataController::class, 'suppliers'])->name('suppliers');
        Route::post('/suppliers', [MasterDataController::class, 'storeSupplier'])->name('suppliers.store');
        Route::put('/suppliers/{supplier}', [MasterDataController::class, 'updateSupplier'])->name('suppliers.update');
        Route::patch('/suppliers/{supplier}/toggle', [MasterDataController::class, 'toggleSupplier'])->name('suppliers.toggle');

        Route::get('/customers', [MasterDataController::class, 'customers'])->name('customers');
        Route::post('/customers', [MasterDataController::class, 'storeCustomer'])->name('customers.store');
        Route::put('/customers/{customer}', [MasterDataController::class, 'updateCustomer'])->name('customers.update');
        Route::patch('/customers/{customer}/toggle', [MasterDataController::class, 'toggleCustomer'])->name('customers.toggle');

        Route::get('/discounts', [DiscountRuleController::class, 'index'])->name('discounts');
        Route::post('/discounts', [DiscountRuleController::class, 'store'])->name('discounts.store');
        Route::put('/discounts/{discountRule}', [DiscountRuleController::class, 'update'])->name('discounts.update');
        Route::patch('/discounts/{discountRule}/toggle', [DiscountRuleController::class, 'toggle'])->name('discounts.toggle');
        Route::delete('/discounts/{discountRule}', [DiscountRuleController::class, 'destroy'])->name('discounts.destroy');

        Route::get('/reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
        Route::get('/reports/purchases', [ReportController::class, 'purchases'])->name('reports.purchases');
        Route::get('/reports/stock', [ReportController::class, 'stock'])->name('reports.stock');

        Route::get('/settings', [SettingController::class, 'index'])->name('settings');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    });
});
