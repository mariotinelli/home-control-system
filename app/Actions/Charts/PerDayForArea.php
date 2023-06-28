<?php

namespace App\Actions\Charts;

class PerDayForArea
{
    public static function makeLabels(array $arr): array
    {
        return $arr;
    }

    public static function makeDatasets(array $values): array
    {
        return [
            'data'        => $values,
            'borderWidth' => 2,
            'fill'        => 'start',
            'tension'     => 0.5,
        ];
    }

}
