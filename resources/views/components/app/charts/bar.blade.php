@props([
    'id' => 'chart',
    'title' => '',
    'width' => '400',
    'height' => '200',
    'labels' => [],
    'datasets' => [],
])

<div
    {{ $attributes->merge([
        'class' => 'w-full bg-white dark:bg-gray-800 p-5 shadow rounded-xl space-y-2',
    ]) }}
    x-data="{ labels: @js($labels), datasets: @js($datasets) }"
    x-init="
        ctx = document.getElementById(@js($id)).getContext('2d');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels,
                datasets
            },
            options: {
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: true
                        }
                    }
                }
            }
        });
    "
>

    <div class="px-3 space-y-2">
        <h2 class="text-lg text-gray-800 dark:text-white font-bold"> {{ $title }} </h2>

        <div class="w-full border-gray-700 border-b border-1"></div>
    </div>

    <canvas
        id="{{$id}}"
        width="{{ $width }}"
        height="{{ $height }}">
    </canvas>
</div>

