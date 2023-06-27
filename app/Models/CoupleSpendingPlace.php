<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CoupleSpendingPlace extends Model
{
    use HasFactory;

    public function spendings(): HasMany
    {
        return $this->hasMany(CoupleSpending::class);
    }

    public function user(): HasMany
    {
        return $this->hasMany(User::class);
    }

}
