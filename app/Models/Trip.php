<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class Trip extends Model
{
    use HasFactory;

    public function entries(): HasMany
    {
        return $this->hasMany(TripEntry::class);
    }

    public function withdraws(): HasMany
    {
        return $this->hasMany(TripWithdraw::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

}
