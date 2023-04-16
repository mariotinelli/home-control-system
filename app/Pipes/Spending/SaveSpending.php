<?php

namespace App\Pipes\Spending;

use App\Models\Spending;
use Closure;

class SaveSpending
{

    public function handle(Spending $spending, Closure $next)
    {
        $spending->save();

        return $next($spending);
    }

}
