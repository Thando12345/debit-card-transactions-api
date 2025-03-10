<?php

namespace App\Http\Requests\DebitCard;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $cardId = $this->route('debitCard')->id;
        
        return [
            // Allow partial updates (make fields nullable)
            'card_number' => [
                'sometimes', // Only validate if present
                'string',
                'size:16',
                'unique:debit_cards,card_number,' . $cardId
            ],
            'expiry_date' => [
                'nullable',
                'date',
                'after:today'
            ],
            'cvv' => [
                'nullable',
                'string',
                'size:3'
            ],
            'daily_limit' => [
                'nullable',
                'numeric',
                'min:0'
            ],
            'status' => [
                'nullable',
                'in:active,inactive,blocked'
            ],
            'is_frozen' => [
                'nullable',
                'boolean'
            ]
        ];
    }
}