<?php

namespace App\Rules;

use App\Models\MarketItemCategory;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MarketItemCategoryOwnerRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value
            && (auth()->check() && MarketItemCategory::find($value))
            && (auth()->id() != MarketItemCategory::find($value)->user_id)
        ) {
            abort(403);
        }
    }
}
