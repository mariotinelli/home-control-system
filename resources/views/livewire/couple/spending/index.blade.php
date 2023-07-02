<div class="space-y-6">

    <div class="flex justify-between items-center">

        <h2 class="text-2xl text-gray-800 dark:text-white font-bold"> Gastos </h2>

        @can('couple_spending_create')
            <livewire:couple.spending.create/>
        @endcan

    </div>

    <div class="grid grid-cols-3 gap-4">

        <livewire:couple.spending.charts.total-month/>

        <x-app.charts.count
            title="Local com maior gasto total"
            empty-message="Nenhum gasto registrado"
            :subtitle="$this->placeWithMoreSpending?->place->name"
            :value="$this->placeWithMoreSpending?->total"
        />

        <x-app.charts.count
            title="Local com maior gasto total"
            empty-message="Nenhum gasto registrado"
            :subtitle="$this->categoryWithMoreSpending?->category->name"
            :value="$this->categoryWithMoreSpending?->total"
        />

    </div>

    <div class="grid grid-cols-2 gap-4">

        <livewire:couple.spending.charts.by-month-by-category/>

        <livewire:couple.spending.charts.by-month-by-place/>

    </div>

    {{ $this->table }}

</div>
