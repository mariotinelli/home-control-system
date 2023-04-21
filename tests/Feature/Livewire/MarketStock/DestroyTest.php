<?php

namespace Tests\Feature\Livewire\MarketStock;

use App\Http\Livewire\MarketStock;
use App\Models\MarketItem;
use App\Models\MarketStockEntry;
use App\Models\MarketStockWithdrawal;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->marketItem = MarketItem::factory()->create();

    $this->marketStock = \App\Models\MarketStock::factory()->create([
        'market_item_id' => $this->marketItem->id,
    ]);

    actingAs($this->user);
});

it('should be able to delete a market stock', function () {

    // Act
    $lw = livewire(MarketStock\Destroy::class, ['marketStock' => $this->marketStock])
        ->call('save');

    // Assert
    $lw->assertHasNoErrors()
        ->assertEmitted('market-stock::deleted');

    assertDatabaseMissing('market_stocks', [
        'id' => $this->marketStock->id,
    ]);

});

it('should be able to disable a market stock if that has withdrawals and entries', function () {

    // Arrange
    $withdrawal = MarketStockWithdrawal::factory()->create([
        'market_stock_id' => $this->marketStock->id,
    ]);

    $entry = MarketStockEntry::factory()->create([
        'market_stock_id' => $this->marketStock->id,
    ]);

    // Act
    $lw = livewire(MarketStock\Destroy::class, ['marketStock' => $this->marketStock])
        ->call('save');

    // Assert
    $lw->assertHasNoErrors()
        ->assertEmitted('market-stock::deleted');

    assertDatabaseHas('market_stocks', [
        'id' => $this->marketStock->id,
        'deleted_at' => now()
    ]);

    assertDatabaseHas('market_stock_withdrawals', [
        'id' => $withdrawal->id,
        'market_stock_id' => $this->marketStock->id,
    ]);

    assertDatabaseHas('market_stock_entries', [
        'id' => $entry->id,
        'market_stock_id' => $this->marketStock->id,
    ]);

});

