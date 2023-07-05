@props([
    'id' => 'chart',
    'title' => '',
    'subtitle' => '',
    'width' => '350',
    'height' => '150',
    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
    'borderColor' => 'rgba(255, 99, 132, 1)',
    'labels' => [],
    'data' => [],
])

<div
    {{ $attributes->merge([
        'class' => 'relative w-full bg-white dark:bg-gray-800 p-5 shadow rounded-xl space-y-2 h-48 overflow-hidden',
    ]) }}
    x-data="{ labels: @js($labels), data: @js($data) }"
    x-init="
        ctx = document.getElementById(@js($id)).getContext('2d');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Line fill',
                    data: data,
                    backgroundColor: @js($backgroundColor),
                    borderColor: @js($borderColor),
                    borderWidth: 1,
                    fill: 'start',
                    tension: 0.5,
                }]
            },
            options: {
                elements: {
                    point: {
                        radius: 0,
                    },
                },
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                },
                scales: {
                    x:  {
                        display: false,
                    },
                    y:  {
                        display: false,
                    },
                },
                tooltips: {
                    enabled: false,
                },
            },
        });
    "
>

    <div class="px-2 space-y-6">
        <h2 class="text-lg text-gray-800 dark:text-white font-bold"> {{ $title }} </h2>

        <h2 class="text-3xl text-gray-800 dark:text-white font-bold">
            {{ $subtitle }}
        </h2>
    </div>

    <div class="absolute w-full bottom-0 overflow-hidden h-16 -mx-5">
        <canvas
            id="{{ $id }}"
            class="{{ $labels && $data ? '' : 'hidden' }}"
            width="{{ $width }}"
            height="{{ $height }}"
        ></canvas>

        @if(!$labels && !$data)
            <h2 class="text-3xl text-gray-500 font-bold ml-7">
                Nenhum gasto registrado
            </h2>
        @endif
    </div>
</div>

