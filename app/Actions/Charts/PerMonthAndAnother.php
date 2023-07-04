<?php

namespace App\Actions\Charts;

use Illuminate\Database\Eloquent\Collection;
use stdClass;

class PerMonthAndAnother
{
    private static array $months = [
        'January'   => 'Jan',
        'February'  => 'Fev',
        'March'     => 'Mar',
        'April'     => 'Abr',
        'May'       => 'Mai',
        'June'      => 'Jun',
        'July'      => 'Jul',
        'August'    => 'Ago',
        'September' => 'Set',
        'October'   => 'Out',
        'November'  => 'Nov',
        'December'  => 'Dez',
    ];

    private static array $colors = [
        'rgba(255, 99, 132, 0.7)',
        'rgba(224, 76, 160, 0.7)',
        'rgba(75, 192, 192, 0.5)',
        'rgba(153, 102, 255, 0.5)',
        'rgba(255, 159, 64, 0.5)',
        'rgba(255, 99, 132, 0.5)',
        'rgba(54, 162, 235, 0.5)',
        'rgba(255, 206, 86, 0.5)',
        'rgba(194, 206, 252, 0.9)',
        'rgba(172, 116, 242, 0.9)',
        'rgba(219, 201, 8, 0.9)',
        'rgba(2, 122, 68, 0.7)',
        'rgba(4, 61, 112, 0.9)',
        'rgba(145, 5, 15, 0.8)',
    ];

    public static function makeLabels(array $arr): array
    {
        return self::normalizeMonths($arr);
    }

    public static function makeDatasets(Collection $values): array
    {
        $datasets    = [];
        $countColors = 0;

        /** @var stdClass $value */
        foreach ($values as $value) {
            $countColors = $countColors > count(self::$colors) - 1 ? 0 : $countColors;

            if (!isset($datasets[$value->label])) {
                $datasets[$value->label] = [
                    'label'           => $value->label,
                    'data'            => [],
                    'borderWidth'     => 1,
                    'borderColor'     => data_get(self::$colors, $countColors),
                    'backgroundColor' => data_get(self::$colors, $countColors),
                ];

                $countColors++;
            }

            $datasets[$value->label]['data'][] = $value->count;
        }

        return array_values($datasets);
    }

    private static function normalizeMonths(array $months): array
    {
        return array_map(function ($month) {
            return self::$months[$month];
        }, $months);
    }

}
