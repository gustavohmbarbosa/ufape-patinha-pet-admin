<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\StockController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

Route::get('/dashboard', function () {
    return redirect()->route('comercial.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('employeeRole:admin,basic')->group(function () {
        Route::resource('pets', PetController::class);
        Route::resource('customers', CustomerController::class);
        Route::resource('appointments', AppointmentController::class);
        Route::get('/customers/{customer}/history', [CustomerController::class, 'history'])->name('customers.history');
        Route::resource('comercial', SaleController::class)->only(['index']);
        Route::resource('sales', SaleController::class);
    });
    Route::middleware('employeeRole:admin')->group(function () {
        Route::resource('employees', EmployeeController::class);
        Route::resource('services', ServiceController::class);
        Route::resource('products', ProductController::class);
        Route::resource('stocks', StockController::class)->only(['index', 'create', 'store']);
    });
});

require __DIR__.'/auth.php';
