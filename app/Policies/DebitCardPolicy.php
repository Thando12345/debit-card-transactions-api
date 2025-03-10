<?php

namespace App\Policies;

use App\Models\User;
use App\Models\DebitCard;

class DebitCardPolicy
{
    public function view(User $user, DebitCard $card)
    {
        return $user->id === $card->user_id;
    }

    public function update(User $user, DebitCard $card)
    {
        return $user->id === $card->user_id;
    }

    public function delete(User $user, DebitCard $card)
    {
        return $user->id === $card->user_id;
    }
}
