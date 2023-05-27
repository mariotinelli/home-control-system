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
        'type' => BankAccountTypeEnum::class,
    ];

    protected $appends = [
        'formatted_number',
        'formatted_agency',
        'formatted_balance',
    ];

    public function getFormattedNumberAttribute(): string
    {
        return $this->digit >= 0 ? "{$this->number}-{$this->digit}" : $this->number;
    }

    public function getFormattedAgencyAttribute(): string
    {
        return $this->agency_number >= 0 ? "{$this->agency_number}-{$this->agency_digit}" : $this->agency_number;
    }

    public function getFormattedBalanceAttribute(): string
    {
        return number_format($this->balance, 2, ',', '.');
    }

    public function entries(): HasMany
    {
        return $this->hasMany(BankAccountEntry::class);
    }

    public function withdrawals(): HasMany
    {
        return $this->hasMany(BankAccountWithdraw::class);
    }
}
