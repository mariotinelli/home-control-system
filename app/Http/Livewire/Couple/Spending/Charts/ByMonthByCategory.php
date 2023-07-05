<?php

namespace App\Http\Livewire\Couple\Spending\Charts;

use App\Actions\Charts;
use App\Models\CoupleSpending;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ByMonthByCategory extends Component
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
        return view('livewire.couple.spending.charts.by-month-by-category');
    }

    public function getLabels(): array
    {
        return Charts\PerMonthAndAnother::makeLabels(
            arr: CoupleSpending::query()
            ->select(\DB::raw("DATE_FORMAT(date, '%M') AS months"))
            ->whereYear('date', date('Y'))
            ->orderByRaw('MONTH(date)')
            ->groupByRaw("MONTH(date), DATE_FORMAT(date, '%M')")
            ->get()
            ->pluck('months')
            ->toArray()
        );
    }

    public function getDatasets(): array
    {
        return Charts\PerMonthAndAnother::makeDatasets(
            values: CoupleSpending::query()
            ->join('couple_spending_categories as cat', 'cat.id', '=', 'couple_spendings.couple_spending_category_id')
            ->where('couple_spendings.user_id', auth()->user()->id)
            ->whereYear('couple_spendings.date', date('Y'))
            ->selectRaw(
                "DATE_FORMAT(couple_spendings.date, '%M') AS months,
                    cat.name as label,
                    sum(amount) AS count"
            )
            ->orderByRaw('MONTH(couple_spendings.date)')
            ->groupByRaw("MONTH(couple_spendings.date), DATE_FORMAT(couple_spendings.date, '%M')")
            ->orderBy('cat.name')
            ->groupBy('cat.name')
            ->get()
        );
    }
}
