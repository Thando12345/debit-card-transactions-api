<?php

namespace App\Http\Controllers;

use App\Http\Requests\DebitCard\StoreRequest;
use App\Http\Requests\DebitCard\UpdateRequest;
use App\Models\DebitCard;
use Illuminate\Http\Request;

class DebitCardController extends Controller
{
    /**
     * Recursively sanitize data to ensure UTF-8 compatibility
     * 
     * @param mixed $data The data to sanitize
     * @return mixed The sanitized data
     */
    protected function sanitizeData($data)
    {
        if (is_string($data)) {
            // Handle string values - replace invalid UTF-8 sequences
            return mb_convert_encoding($data, 'UTF-8', 'UTF-8');
        } else if (is_array($data)) {
            // Handle arrays by recursively sanitizing each element
            foreach ($data as $key => $value) {
                $data[$key] = $this->sanitizeData($value);
            }
        } else if (is_object($data)) {
            // Handle objects by converting to array, sanitizing, and converting back
            $array = (array) $data;
            foreach ($array as $key => $value) {
                $array[$key] = $this->sanitizeData($value);
            }
            $data = (object) $array;
        }
        
        return $data;
    }

    public function __construct()
    {
        $this->middleware('auth')->only(['indexView', 'createView', 'transactionsView']);
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
            return response()->json($debitCard, 201, [], JSON_UNESCAPED_UNICODE);
        }
    
        return redirect()->route('debit-cards.index');
    }
    
    public function show($id)
    {
        $debitCard = DebitCard::find($id);
        
        if (!$debitCard) {
            return response()->json(['message' => 'Debit card not found'], 404);
        }
        
        $this->authorize('view', $debitCard);
        
        // Load the debit card with transactions
        $debitCard->load('transactions');
        
        // Convert to array to process and sanitize data
        $data = $debitCard->toArray();
        
        // Recursively sanitize all string values to ensure UTF-8 compatibility
        $sanitizedData = $this->sanitizeData($data);
        
        return response()->json($sanitizedData);
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
