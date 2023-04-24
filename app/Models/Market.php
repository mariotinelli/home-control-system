<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Market extends Model
{
    use HasFactory;

    public function marketStockEntries(): HasMany
    {
        return $this->hasMany(MarketStockEntry::class);
    }
}
