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
    $canSeeLabels = [];

    for ($i = 1; $i <= 31; $i++) {
        CoupleSpending::factory()->count(2)->create([
            'user_id' => $this->user->id,
            'date'    => Carbon::create(2023, 01, $i),
        ]);

        $canSeeLabels[] = $i;
    }

    // Act
    $lw = livewire(Couple\Spending\Charts\TotalMonth::class);

    // Assert
    expect($lw->labels)
        ->toHaveCount(31)
        ->toBe($canSeeLabels);

});

it('can get correctly datasets', function () {

    // Arrange
    $canSeeDatasets = [];

    for ($i = 1; $i <= 31; $i++) {
        $spending = CoupleSpending::factory()->count(2)->create([
            'user_id' => $this->user->id,
            'date'    => Carbon::create(2023, 01, $i),
        ]);

        $canSeeDatasets[] = $spending->sum(function ($item) {
            return (float)$item->amount;
        });
    }

    // Act
    $lw = livewire(Couple\Spending\Charts\TotalMonth::class);

    // Assert
    expect($lw->datasets)
        ->toHaveCount(4)
        ->toHaveKeys(['data', 'borderWidth', 'fill', 'tension'])
        ->and($lw->datasets['data'])
        ->toHaveCount(31)
        ->toBe($canSeeDatasets);

});
