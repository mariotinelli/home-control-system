<?php

namespace App\Pipes\Spending;

use App\Models\Spending;
use Closure;

class DeleteSpending
{
    public function handle(Spending $spending, Closure $next): Spending
    {
        $spending->delete();

        return $next($spending);
    }
}
