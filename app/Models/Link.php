<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Link extends Model
{
    public function menus(): BelongsToMany
    {
        return $this->belongsToMany(Menu::class)->withPivot('order', 'route', 'icon');
    }

}
