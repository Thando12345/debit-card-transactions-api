<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'debit_card_id'    => ['required', 'exists:debit_cards,id'],
            'amount'           => ['required', 'numeric', 'min:0'],
            'type'             => ['required', 'in:credit,debit'],
            'status'           => ['required', 'in:pending,completed,failed'],
            'transaction_date' => ['nullable', 'date']
        ];
    }
}
