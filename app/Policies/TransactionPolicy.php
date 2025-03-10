<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Transaction;

class TransactionPolicy
{
    public function view(User $user, Transaction $transaction)
    {
        // Ensure the transaction belongs to a debit card owned by the user.
        return $transaction->debitCard->user_id === $user->id;
    }
}
