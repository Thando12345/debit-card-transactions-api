<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DebitCardController;
use App\Http\Controllers\TransactionController;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $debitCardController;
    protected $transactionController;

    public function __construct(DebitCardController $debitCardController, TransactionController $transactionController)
    {
        $this->debitCardController = $debitCardController;
        $this->transactionController = $transactionController;
    }

  // app/Http/Controllers/DashboardController.php

  public function index()
  {
      // Remove the middleware line from here - it's already applied in routes
      $cards = auth()->user()->debitCards()->with('transactions')->get();
      $transactions = Transaction::whereHas('debitCard', function ($q) {
          $q->where('user_id', auth()->id());
      })->latest()->take(10)->get();
      
      return view('debit-cards.dashboard', compact('cards', 'transactions'));
  }
}