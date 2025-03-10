<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'debit_card_id',
        'amount',
        'type',
        'status',
        'description',
        'merchant_name',
        'transaction_date'
    ];

    protected $casts = [
        'amount'           => 'decimal:2',
        'transaction_date' => 'datetime'
    ];
    
    protected $attributes = [
        'transaction_date' => null
    ];

    public function debitCard()
    {
        return $this->belongsTo(DebitCard::class);
    }
}