<?php

namespace App\Http\Livewire\Couple\Spending\Charts;

use App\Actions\Charts;
use App\Models\CoupleSpending;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use stdClass;

class TotalMonth extends Component
{
    public array $labels = [];

    public array $datasets = [];

    public function mount(): void
    {
        $this->labels   = $this->getLabels();
        $this->datasets = $this->getDatasets();
    }

    public function render(): View
    {
        return view('livewire.couple.spending.charts.total-month');
    }

    public function getLabels(): array
    {
        return Charts\PerDayForArea::makeLabels(
            arr: CoupleSpending::query()
                ->select(\DB::raw("DAY(date) AS days"))
                ->orderByRaw('DAY(date)')
                ->groupByRaw("DAY(date)")
                ->get()
                ->pluck('days')
                ->toArray()
        );
    }

    public function getDatasets(): array
    {
        return Charts\PerDayForArea::makeDatasets(
            values: CoupleSpending::query()
                ->select([
                    \DB::raw("DAY(date) AS days"),
                    \DB::raw("SUM(amount) AS total"),
                ])
                ->orderByRaw('DAY(date)')
                ->groupByRaw("DAY(date)")
                ->get()
                ->map(
                    callback: function ($item) {
                        /** @var stdClass $item */
                        return $item->total = (float)$item->total;
                    }
                )
                ->toArray()
        );
    }

}
