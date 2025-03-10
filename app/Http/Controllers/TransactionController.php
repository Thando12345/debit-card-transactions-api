<?php

namespace App\Http\Controllers;

use App\Models\DebitCard;
use App\Models\Transaction;
use App\Http\Requests\Transaction\StoreRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct()
    {
        // Separate middleware for web/API
        $this->middleware('auth')->only(['indexView']);
        $this->middleware('auth:sanctum')->except(['indexView']);
    }

    /**
     * GET API endpoint for returning user transactions 
     */
    public function index()
    {
        $transactions = Transaction::whereHas('debitCard', function ($query) {
            $query->where('user_id', auth()->id());
        })->with('debitCard')->latest()->get();
        
        return response()->json($transactions);
    }

    /**
     * POST API endpoint for creating a transaction
     */
    public function store(StoreRequest $request)
    {
        // Find debit card and verify ownership
        $card = DebitCard::where([
            'id' => $request->debit_card_id,
            'user_id' => auth()->id()
        ])->firstOrFail();

        // Check if card is frozen
        if ($card->is_frozen) {
            return response()->json([
                'error' => 'Card is frozen'
            ], 422);
        }

        // Check daily limit for debit transactions
        if ($request->type === 'debit') {
            $todayTotal = $card->transactions()
                ->where('type', 'debit')
                ->whereDate('created_at', today())
                ->sum('amount');

            if (($todayTotal + $request->amount) > $card->daily_limit) {
                return response()->json([
                    'error' => 'Daily limit exceeded'
                ], 422);
            }
        }

        // Create transaction in database transaction
        return DB::transaction(function () use ($request, $card) {
            $transaction = $card->transactions()->create([
                'type' => $request->type,
                'amount' => $request->amount,
                'status' => $request->status,
                'description' => $request->details ?? null,
                'transaction_date' => $request->transaction_date ?? now()
            ]);

            return response()->json($transaction, 201);
        });
    }

    /**
     * Show specific transaction with ownership check
     */
    public function show(Transaction $transaction)
    {
        $this->authorize('view', $transaction);
        return response()->json($transaction);
    }

    public function indexView()
    {
        $transactions = Transaction::whereHas('debitCard', function ($query) {
            $query->where('user_id', auth()->id());
        })->with('debitCard')->latest()->paginate(10);
        
        return view('debit-cards.all-transactions', compact('transactions'));
    }
}