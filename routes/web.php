<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DebitCardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

// routes/web.php
Route::middleware('auth')->group(function () {
    Route::get('/debit-cards', [DebitCardController::class, 'indexView'])->name('debit-cards.index');
    Route::get('/debit-cards/create', [DebitCardController::class, 'createView'])->name('debit-cards.create');
    Route::get('/debit-cards/{debitCard}/transactions', [DebitCardController::class, 'transactionsView'])
        ->name('debit-cards.transactions');
    Route::get('/transactions', [\App\Http\Controllers\TransactionController::class, 'indexView'])
        ->name('transactions');
       Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    
        // Existing routes
     
    // Logout (keep this for web)
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// Auth routes for web (login/register)
Route::middleware('guest')->group(function () {
    Route::get('/', fn() => redirect('/login'));
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return redirect('/login');
    });
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});
