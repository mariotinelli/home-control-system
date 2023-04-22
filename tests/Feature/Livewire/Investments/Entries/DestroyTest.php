<?php

namespace Tests\Feature\Livewire\Investments\Entries;

use App\Http\Livewire\Investments;
use App\Models\Investment;
use App\Models\InvestmentEntry;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->create();

    $this->investment = Investment::factory()->create();

    $this->investmentEntry = InvestmentEntry::factory()->create([
        'investment_id' => $this->investment->id,
        'amount' => 100,
    ]);

    actingAs($this->user);

});

it('should be to delete an investment entry', function () {

    assertDatabaseCount('investment_entries', 1);

    // Act
    $lw = livewire(Investments\Entries\Destroy::class, [
        'investment' => $this->investment,
        'investmentEntry' => $this->investmentEntry
    ])
        ->call('save');

    // Assert
    $lw->assertHasNoErrors()
        ->assertEmitted('investment::entry::deleted');

    assertDatabaseMissing('investment_entries', [
        'id' => $this->investmentEntry->id,
    ]);

});
