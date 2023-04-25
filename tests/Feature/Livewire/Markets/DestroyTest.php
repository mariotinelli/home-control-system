<?php

namespace Tests\Feature\Livewire\Markets;

use App\Http\Livewire\Markets;
use App\Models\{Market, MarketStock, User};

use function Pest\Laravel\{actingAs, assertDatabaseHas, assertDatabaseMissing};
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

it('should be not able to delete market if contains entries', function () {

    // Arrange
    $this->market->marketStockEntries()->create([
        'market_stock_id' => MarketStock::factory()->create()->id,
        'price'           => 100,
        'quantity'        => 1,
    ]);

    assertDatabaseHas('market_stock_entries', [
        'market_id' => $this->market->id,
    ]);

    // Act
    $lw = livewire(Markets\Destroy::class, ['market' => $this->market])
        ->call('save');

    // Assert
    $lw->assertHasErrors(['market'])
        ->assertEmitted('market::delete-failed');

    assertDatabaseHas('markets', [
        'id' => $this->market->id,
    ]);

    assertDatabaseHas('market_stock_entries', [
        'market_id' => $this->market->id,
    ]);

});
