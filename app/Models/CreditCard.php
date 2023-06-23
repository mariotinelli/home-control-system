<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CreditCard extends Model
{
    use HasFactory;

    protected $appends = [
        'formatted_limit',
        'formatted_remaining_limit',
    ];

    public function spendings(): HasMany
    {
        return $this->hasMany(Spending::class);
    }

    public function number(): Attribute
    {
        return new Attribute(
            get: fn ($value) => str($value)->replaceMatches('/(\d{4})(\d{4})(\d{4})(\d{4})/', '$1 $2 $3 $4')->toString(),
            set: fn ($value) => str($value)->replaceMatches('/\D/', '')->toString(),
        );
    }

    public function expiration(): Attribute
    {
        return new Attribute(
            set: fn ($value) => str($value)->replaceMatches('/(\d{2})(\d{2})/', '$1/$2')->toString()
        );
    }

    public function getFormattedLimitAttribute(): string
    {
        return number_format($this->limit, 2, ',', '.');
    }

    public function getFormattedRemainingLimitAttribute(): string
    {
        return number_format($this->remaining_limit, 2, ',', '.');
    }

}
