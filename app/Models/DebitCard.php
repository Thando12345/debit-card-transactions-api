<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class DebitCard extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'card_number',
        'card_holder',
        'expiry_date',
        'cvv',
        'status',
        'daily_limit',
        'is_frozen'
    ];
    protected $casts = [
        'expiry_date' => 'date',
        'is_frozen'   => 'boolean'
    ];
    
    protected $hidden = [
        'card_number',
        'cvv',
        'created_at',
        'updated_at'
    ];
    
    protected $appends = [
        'masked_number',
        'expiry_date_formatted'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function getBalanceAttribute()
    {
        return $this->transactions()
            ->where('status', 'completed')
            ->sum(function ($transaction) {
                return $transaction->type === 'credit' 
                    ? $transaction->amount 
                    : -$transaction->amount;
            });
    }
    
    public function getCardNumberAttribute($value)
    {
        try {
            return decrypt($value);
        } catch (\Exception $e) {
            return 'Invalid Card';
        }
    }
    
    public function getExpiryDateFormattedAttribute()
    {
        return $this->expiry_date->format('m/Y');
    }
    
    public function getMaskedNumberAttribute()
    {
        try {
            return '**** **** **** ' . substr(decrypt($this->attributes['card_number']), -4);
        } catch (\Exception $e) {
            return '**** **** **** ****';
        }
    }
}