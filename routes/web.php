<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UnitController;
use Illuminate\Support\Facades\Route;



Route::get('/login', [DashboardController::class, 'showLoginForm'])
    ->name('login');

Route::post('/login', [DashboardController::class, 'login']);

Route::get('/register', [DashboardController::class, 'showRegistrationForm'])
    ->name('register');

Route::post('/register', [DashboardController::class, 'register']);


// Protected Routes
Route::middleware('auth')->group(function () {

    Route::post('/logout', [DashboardController::class, 'logout'])
        ->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'dashboard'])
        ->name('dashboard');

    Route::resource('categories', CategoryController::class);
    Route::resource('units', UnitController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('products', ProductController::class);
    Route::resource('purchases', PurchaseController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('sales', SaleController::class);

    Route::prefix('reports')->name('reports.')->group(function () {

        Route::get('stock', [ReportController::class, 'stock'])
            ->name('stock');

        Route::get('purchase', [ReportController::class, 'purchase'])
            ->name('purchase');

        Route::get('sales', [ReportController::class, 'sales'])
            ->name('sales');

        Route::get('profit-loss', [ReportController::class, 'profitLoss'])
            ->name('profit_loss');
    });


    // routes/web.php
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});
