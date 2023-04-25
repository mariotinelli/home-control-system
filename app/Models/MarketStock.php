<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};
use Illuminate\Database\Eloquent\{Model, SoftDeletes};

class MarketStock extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function marketItem(): BelongsTo
    {
        return $this->belongsTo(MarketItem::class);
    }

    public function entries(): HasMany
    {
        return $this->hasMany(MarketStockEntry::class);
    }

    public function withdrawals(): HasMany
    {
        return $this->hasMany(MarketStockWithdrawal::class);
    }
}
