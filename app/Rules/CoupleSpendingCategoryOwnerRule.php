<?php

namespace App\Rules;

use App\Models\CoupleSpendingCategory;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CoupleSpendingCategoryOwnerRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value
            && CoupleSpendingCategory::find($value)
            && (auth()->id() != CoupleSpendingCategory::find($value)->user_id)
        ) {
            abort(403);
        }
    }
}
