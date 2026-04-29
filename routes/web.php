<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\TransactionController;

Route::get('/', function () {
    return redirect()->route('pos.index');
});

Route::resource('categories', CategoryController::class);
Route::resource('menus', MenuController::class);

Route::get('/pos', [TransactionController::class, 'index'])->name('pos.index');
Route::post('/pos', [TransactionController::class, 'store'])->name('pos.store');
Route::delete('/pos/{id}', [TransactionController::class, 'destroy'])->name('pos.destroy');
Route::get('/transactions', [TransactionController::class, 'history'])->name('transactions.history');
