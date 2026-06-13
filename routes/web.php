<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Kasir\DashboardController as KasirDashboardController;
use App\Http\Controllers\Kasir\PosController;
use App\Http\Controllers\Kasir\TransactionHistoryController;
use App\Http\Controllers\Owner\CategoryController;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\Owner\ExpenseController;
use App\Http\Controllers\Owner\ProductController;
use App\Http\Controllers\Owner\StockMovementController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::get('/', function () {
    $user = request()->user();

    if (! $user) {
        return redirect()->route('login');
    }

    return redirect()->route($user->isOwner() ? 'owner.dashboard' : 'kasir.dashboard');
})->middleware(['auth', 'active'])->name('home');

Route::middleware(['auth', 'active', 'role:'.User::ROLE_OWNER])
    ->prefix('owner')
    ->name('owner.')
    ->group(function (): void {
        Route::get('/dashboard', OwnerDashboardController::class)->name('dashboard');
        Route::resource('categories', CategoryController::class)->except('show');
        Route::resource('products', ProductController::class)->except('show');

        // Sprint 4 – Stok
        Route::get('/stock', [StockMovementController::class, 'index'])->name('stock.index');
        Route::get('/stock/restock', [StockMovementController::class, 'create'])->name('stock.restock');
        Route::post('/stock/restock', [StockMovementController::class, 'store'])->name('stock.restock.store');

        // Sprint 5 – Pengeluaran
        Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');
        Route::get('/expenses/create', [ExpenseController::class, 'create'])->name('expenses.create');
        Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
        Route::get('/expenses/{expense}', [ExpenseController::class, 'show'])->name('expenses.show');
    });

Route::middleware(['auth', 'active', 'role:'.User::ROLE_KASIR])
    ->prefix('kasir')
    ->name('kasir.')
    ->group(function (): void {
        Route::get('/dashboard', KasirDashboardController::class)->name('dashboard');
        Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
        Route::post('/pos/cart/{product}', [PosController::class, 'addToCart'])->name('pos.cart.add');
        Route::patch('/pos/cart/{product}', [PosController::class, 'updateCart'])->name('pos.cart.update');
        Route::delete('/pos/cart/{product}', [PosController::class, 'removeFromCart'])->name('pos.cart.remove');
        Route::post('/pos/checkout', [PosController::class, 'checkout'])->name('pos.checkout');
        Route::get('/transactions', [TransactionHistoryController::class, 'index'])->name('transactions.index');
        Route::get('/transactions/{transaction}', [TransactionHistoryController::class, 'show'])->name('transactions.show');
    });
