<?php

namespace App\Http\Requests\DebitCard;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize()
    {
        // Allow only authenticated users; adjust if needed.
        return true;
    }

    public function rules()
    {
        return [
            // Card number (16-digit, unique)
            'card_number' => [
                'required',
                'string',
                'size:16',
                'unique:debit_cards,card_number'
            ],
            
            // Card holder name
            'card_holder' => [
                'required',
                'string',
                'max:255'
            ],
            
            // Expiry date (month/year format)
            'expiry_date' => [
                'required',
                'string',
                'date_format:m/y',
                'regex:/^(0[1-9]|1[0-2])\/([0-9]{2})$/'
            ],
            
            // CVV (3-4 digits)
            'cvv' => [
                'required',
                'string',
                'max:4'
            ],
            
            // Daily spending limit
            'daily_limit' => [
                'nullable',
                'numeric',
                'min:0'
            ],
            
            // Card status
            'status' => [
                'nullable',
                'in:active,inactive,blocked'
            ]
        ];
    }
}