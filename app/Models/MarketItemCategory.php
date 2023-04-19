<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MarketItemCategory extends Model
{
    use HasFactory;

    public function marketItems(): HasMany
    {
        return $this->hasMany(MarketItem::class);
    }
}
