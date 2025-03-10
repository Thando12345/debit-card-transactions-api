<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DebitCardController;
use App\Http\Controllers\TransactionController;
// routes/api.php

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('debit-cards', DebitCardController::class);
    Route::get('debit-cards/{debitCard}/transactions', [DebitCardController::class, 'transactions']);
    
    // Debit Card Transaction endpoints matching requirements
    Route::get('debit-card-transactions', [TransactionController::class, 'index']);
    Route::post('debit-card-transactions', [TransactionController::class, 'store']);
    Route::get('debit-card-transactions/{transaction}', [TransactionController::class, 'show']);
    
    // Alternative transaction endpoints (for compatibility)
    Route::post('transactions', [TransactionController::class, 'store']);
});

// Public auth routes
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/login', [LoginController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [LoginController::class, 'logout']);