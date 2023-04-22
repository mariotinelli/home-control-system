<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    public function month()
    {
        return \Attribute::make(
            get: fn() => Carbon::create($this->month)->format('m/Y'),
        );
    }

}
