<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};

class Investment extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function entries(): HasMany
    {
        return $this->hasMany(InvestmentEntry::class);
    }

    public function withdrawals(): HasMany
    {
        return $this->hasMany(InvestmentWithdraw::class);
    }
}
