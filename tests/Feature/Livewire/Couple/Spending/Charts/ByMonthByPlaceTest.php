<?php

namespace Tests\Feature\Livewire\Couple\Spending\Charts;

use App\Http\Livewire\Couple;
use App\Models\{CoupleSpending, User};
use Carbon\Carbon;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->create([
        'email' => 'teste@email.com',
    ]);

    actingAs($this->user);

});

it('can get correctly labels', function () {

    // Arrange
    for ($i = 1; $i <= 12; $i++) {
        CoupleSpending::factory()->count(2)->create([
            'user_id' => $this->user->id,
            'date'    => Carbon::create(2023, $i, $i),
        ]);
    }

    $canSeeLabels = [
        'Jan',
        'Fev',
        'Mar',
        'Abr',
        'Mai',
        'Jun',
        'Jul',
        'Ago',
        'Set',
        'Out',
        'Nov',
        'Dez',
    ];

    // Act
    $lw = livewire(Couple\Spending\Charts\ByMonthByPlace::class);

    // Assert
    expect($lw->labels)
        ->toHaveCount(12)
        ->toBe($canSeeLabels);

});

it('can get correctly datasets', function () {

    // Arrange
    for ($i = 1; $i <= 12; $i++) {
        CoupleSpending::factory()->count(2)->create([
            'user_id' => $this->user->id,
            'date'    => Carbon::create(2023, $i, $i),
        ]);
    }

    // Act
    $lw = livewire(Couple\Spending\Charts\ByMonthByPlace::class);

    // Assert
    foreach ($lw->datasets as $dataset) {
        expect($dataset)
            ->toHaveKeys([
                "label",
                "data",
                "borderWidth",
                "borderColor",
                "backgroundColor",
            ]);
    }

});
