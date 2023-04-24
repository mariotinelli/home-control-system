<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasOne};

class MarketItem extends Model
{
    use HasFactory;

    public function category(): BelongsTo
    {
        return $this->belongsTo(MarketItemCategory::class);
    }

    public function marketStock(): HasOne
    {
        return $this->hasOne(MarketStock::class);
    }
}
