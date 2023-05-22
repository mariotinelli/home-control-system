<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Menu extends Model
{
    public function links(): BelongsToMany
    {
        return $this->belongsToMany(Link::class)->withPivot(['order', 'route', 'icon'])->withTimestamps();
    }

}
