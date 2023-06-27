<?php

namespace App\Rules;

use App\Models\CoupleSpendingPlace;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CoupleSpendingPlaceOwnerRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value
            && (auth()->check() && CoupleSpendingPlace::query()->find($value))
            && (auth()->id() != CoupleSpendingPlace::query()->find($value)->user_id)
        ) {
            abort(403);
        }
    }
}
