<?php

namespace App\Http\Controllers;

use App\Http\Requests\DebitCard\StoreRequest;
use App\Http\Requests\DebitCard\UpdateRequest;
use App\Models\DebitCard;
use Illuminate\Http\Request;

class DebitCardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('auth:sanctum')->only([
            'index',
            'store',
            'show',
            'update',
            'destroy',
            'transactions',
        ]);
    }

    public function indexView()
    {
        return view('debit-cards.index', [
            'debitCards' => auth()->user()->debitCards,
        ]);
    }

    public function createView()
    {
        return view('debit-cards.create');
    }

    public function transactionsView(DebitCard $debitCard)
    {
        $this->authorize('view', $debitCard);

        $transactions = $debitCard->transactions()->latest()->paginate(10);

        return view('debit-cards.transactions', [
            'debitCard' => $debitCard,
            'transactions' => $transactions,
        ]);
    }

    public function index()
    {
        $debitCards = auth()->user()->debitCards()->latest()->get();

        if (request()->expectsJson()) {
            return response()->json($debitCards);
        }

        return redirect()->route('debit-cards.index');
    }

    public function store(StoreRequest $request)
    {
        $debitCard = auth()->user()->debitCards()->create($request->validated());

        if ($request->expectsJson()) {
            return response()->json($debitCard, 201);
        }

        return redirect()->route('debit-cards.index');
    }

    public function show($id)
    {
        // Find the debit card or return 404
        $debitCard = DebitCard::find($id);
    
        if (!$debitCard) {
            return response()->json(['message' => 'Debit card not found'], 404);
        }
    
        $this->authorize('view', $debitCard);
    
        // Load the transactions
        $debitCardData = $debitCard->load('transactions');
    
        // Convert data to JSON with UTF-8 encoding, using mb_convert_encoding if needed
        $debitCardData = $this->ensureUtf8Encoding($debitCardData);
    
        // Return response with UTF-8 encoding, remove utf8_encode to avoid issues
        return response()->json($debitCardData, 200, [], JSON_UNESCAPED_UNICODE);

    }
    
    private function ensureUtf8Encoding($data)
    {
        // Check if data is array or object
        if (is_array($data) || is_object($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->ensureUtf8Encoding($value);
            }
        } else {
            // Check and ensure UTF-8 encoding
            if (!mb_detect_encoding($data, 'UTF-8', true)) {
                $data = mb_convert_encoding($data, 'UTF-8', 'auto');
            }
        }
        return $data;
    }
    
    
    
    

    public function update(UpdateRequest $request, DebitCard $debitCard)
    {
        $this->authorize('update', $debitCard);
        $debitCard->update($request->validated());

        if ($request->expectsJson()) {
            return response()->json($debitCard);
        }

        return redirect()->route('debit-cards.index');
    }

    public function destroy(DebitCard $debitCard)
    {
        $this->authorize('delete', $debitCard);
        $debitCard->delete();

        return response()->json(['success' => true]);
    }

    public function transactions(DebitCard $debitCard)
    {
        $this->authorize('view', $debitCard);
        return response()->json($debitCard->transactions);
    }
}