<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BankAccount extends Model
{
    use HasFactory;

    public function entries(): HasMany
    {
        return $this->hasMany(BankAccountEntry::class);
    }

    public function withdrawals(): HasMany
    {
        return $this->hasMany(BankAccountWithdraw::class);
    }
}
