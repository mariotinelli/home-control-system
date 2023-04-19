<?php

namespace Tests\Feature\Livewire\Markets;

use App\Http\Livewire\Markets;
use App\Models\Market;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->create();

    $this->market = Market::factory()->create([
        'name' => 'Test Market',
    ]);

    actingAs($this->user);
});

it('should be able to delete market', function () {

    // Act
    $lw = livewire(Markets\Destroy::class, ['market' => $this->market])
        ->call('save');

    // Assert
    $lw->assertEmitted('market::deleted')
        ->assertHasNoErrors();

    assertDatabaseMissing('markets', [
        'id' => $this->market->id,
    ]);

});

todo('should be not able to delete market if contains market items');
