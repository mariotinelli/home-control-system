<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Goal extends Model
{
    use SoftDeletes, HasFactory;

    public function entries(): HasMany
    {
        return $this->hasMany(GoalEntry::class);
    }

    public function withdrawals(): HasMany
    {
        return $this->hasMany(GoalWithdraw::class);
    }
}
