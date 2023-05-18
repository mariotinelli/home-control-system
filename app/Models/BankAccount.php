<?php

namespace App\Models;

use App\Enums\BankAccountTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BankAccount extends Model
{
    use HasFactory;

    protected $casts = [
        'type'    => BankAccountTypeEnum::class,
        'balance' => 'decimal:2',
    ];

    public function entries(): HasMany
    {
        return $this->hasMany(BankAccountEntry::class);
    }

    public function withdrawals(): HasMany
    {
        return $this->hasMany(BankAccountWithdraw::class);
    }
}
