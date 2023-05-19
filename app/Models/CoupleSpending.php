<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CoupleSpending extends Model
{
    use HasFactory;

    public function category(): BelongsTo
    {
        return $this->belongsTo(CoupleSpendingCategory::class, 'couple_spending_category_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
