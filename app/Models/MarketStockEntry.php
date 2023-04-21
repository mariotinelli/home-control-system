<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketStockEntry extends Model
{
    use HasFactory;

    public function marketStock(): BelongsTo
    {
        return $this->belongsTo(MarketStock::class);
    }

    public function market(): BelongsTo
    {
        return $this->belongsTo(Market::class);
    }
}
