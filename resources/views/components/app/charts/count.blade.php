@props([
    'title',
    'subtitle',
    'emptyMessage' => 'Nenhum registro encontrado',
    'value',
])

<div
    {{ $attributes->merge([
        'class' => 'relative w-full bg-white dark:bg-gray-800 p-5 shadow rounded-xl space-y-2 h-48 overflow-hidden',
    ]) }}
>

    <div class="px-2 flex flex-col justify-between h-full">

        <div class="space-y-3">

            <h2 class="text-lg text-gray-800 dark:text-white font-bold"> {{ $title }} </h2>

            <div class="w-full border-gray-700 border-b border-1"></div>

        </div>

        <div class="space-y-2">

            <h2 class="text-2xl text-gray-500 font-bold"> {{ $subtitle ?? $emptyMessage }} </h2>

            <h2 class="text-3xl text-gray-800 dark:text-white font-bold">
                {{ 'R$' . (number_format((float)$value, 2, ',', '.') ?? '0,00') }}
            </h2>

        </div>

    </div>
</div>

