<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CoupleSpending extends Model
{
    use HasFactory;

    public function amount(): Attribute
    {
        return new Attribute(
            get: fn ($value) => number_format($value, 2, ',', '.'),
        );
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(CoupleSpendingCategory::class, 'couple_spending_category_id');
    }

    public function place(): BelongsTo
    {
        return $this->belongsTo(CoupleSpendingPlace::class, 'couple_spending_place_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
