<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AuthController;

Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('pos.index');
    });

    Route::resource(
        'categories',
        CategoryController::class
    );
    Route::resource(
        'menus',
        MenuController::class
    );

    Route::get(
        '/pos',
        [
            TransactionController::class,
            'index'
        ]
    )->name('pos.index');
    Route::post(
        '/pos',
        [
            TransactionController::class,
            'store'
        ]
    )->name('pos.store');
    Route::delete(
        '/pos/{id}',
        [
            TransactionController::class,
            'destroy'
        ]
    )->name('pos.destroy');
    Route::get(
        '/transactions',
        [
            TransactionController::class,
            'history'
        ]
    )->name('transactions.history');

    //print
    Route::get(
        '/transactions/print',
        [
            TransactionController::class,
            'print'
        ]
    )
        ->name('transactions.print');

    // Settings routes
    Route::get(
        '/settings',
        [
            AuthController::class,
            'showSettings'
        ]
    )->name('settings.edit');
    Route::post(
        '/settings',
        [
            AuthController::class,
            'updateSettings'
        ]
    )->name('settings.update');

    // logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Auth routes
Route::middleware(['guest'])->group(function () {
    Route::get('/create/auth/admin/user/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/create/auth/admin/user/register', [AuthController::class, 'register'])->name('register.store');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

// Password reset routes
Route::get('/auth/admin/user/forgot-password', [AuthController::class, 'showForgot'])->name('forgot-password');
Route::post('/auth/admin/user/forgot-password', [AuthController::class, 'sendReset'])->name('forgot-password.send');

Route::get('/auth/admin/user/reset-password/{token}', [AuthController::class, 'showReset'])->name('password.reset');
Route::post('/auth/admin/user/reset-password/{token}', [AuthController::class, 'resetPassword'])->name('password.update');
