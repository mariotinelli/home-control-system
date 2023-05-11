<?php

namespace Tests\Feature\Livewire\MarketStock\Entries;

use App\Http\Livewire\MarketStock;
use App\Models\{MarketStockEntry, User};

use function Pest\Laravel\{actingAs, assertDatabaseHas, assertDatabaseMissing};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->create([
        'email' => 'teste@email.com',
    ]);

    $this->user->givePermissionTo(getUserGoldPermissions());

    $this->marketStock = \App\Models\MarketStock::factory()->create([
        'quantity' => 100,
    ]);

    $this->marketStockEntry = MarketStockEntry::factory()->create([
        'market_stock_id' => $this->marketStock->id,
        'quantity'        => 50,
    ]);

    $this->marketStock->increment('quantity', $this->marketStockEntry->quantity);

    actingAs($this->user);

});

it('should be able to delete a entry of the market stock', function () {

    expect($this->marketStock->quantity)
        ->toBe(150);

    // Arrange
    $lw = livewire(MarketStock\Entries\Destroy::class, [
        'marketStockEntry' => $this->marketStockEntry,
        'marketStock'      => $this->marketStock,
    ])
        ->call('save');

    // Assert
    $lw->assertHasNoErrors()
        ->assertEmitted('market-stock::entry::deleted');

    assertDatabaseMissing('market_stock_entries', [
        'id' => $this->marketStockEntry->id,
    ]);

    assertDatabaseHas('market_stocks', [
        'id'       => $this->marketStock->id,
        'quantity' => 100,
    ]);

});
