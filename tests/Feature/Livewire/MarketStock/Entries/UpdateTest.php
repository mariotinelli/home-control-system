<?php

namespace Tests\Feature\Livewire\MarketStock\Entries;

use App\Http\Livewire\MarketStock;
use App\Models\{Market, MarketStockEntry, User};

use function Pest\Laravel\{actingAs, assertDatabaseHas};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->create();

    $this->marketStock = \App\Models\MarketStock::factory()->create([
        'quantity' => 100,
    ]);

    $this->marketStockEntry = MarketStockEntry::factory()->create([
        'market_stock_id' => $this->marketStock->id,
        'quantity'        => 10,
    ]);

    $this->marketStock->increment('quantity', $this->marketStockEntry->quantity);

    actingAs($this->user);

});

it('should be able to update a entry of the market item in to market stock', function () {

    expect($this->marketStock->quantity)
        ->toBe(110);

    // Arrange
    $market2 = Market::factory()->create([
        'name' => 'Test Market 2',
    ]);

    $lw = livewire(MarketStock\Entries\Update::class, [
        'marketStockEntry' => $this->marketStockEntry,
        'marketStock'      => $this->marketStock,
    ])
        ->set('marketStockEntry.market_id', $market2->id)
        ->set('marketStockEntry.price', 20)
        ->set('marketStockEntry.quantity', 20)
        ->call('save');

    // Assert
    $lw->assertHasNoErrors()
        ->assertEmitted('market-stock::entry::updated');

    assertDatabaseHas('market_stock_entries', [
        'market_stock_id' => $this->marketStock->id,
        'market_id'       => $market2->id,
        'price'           => 20,
        'quantity'        => 20,
    ]);

    assertDatabaseHas('market_stocks', [
        'market_item_id' => $this->marketStock->market_item_id,
        'quantity'       => 120,
    ]);

});

it('should be able to change market stock that entry', function () {

    expect($this->marketStock->quantity)
        ->toBe(110);

    // Arrange
    $marketStock2 = \App\Models\MarketStock::factory()->create([
        'quantity' => 20,
    ]);

    $market2 = Market::factory()->create();

    // Act
    $lw = livewire(MarketStock\Entries\Update::class, [
        'marketStockEntry' => $this->marketStockEntry,
        'marketStock'      => $this->marketStock,
    ])
        ->set('marketStockEntry.market_stock_id', $marketStock2->id)
        ->set('marketStockEntry.market_id', $market2->id)
        ->set('marketStockEntry.price', 20)
        ->set('marketStockEntry.quantity', 20)
        ->call('save');

    // Assert
    $lw->assertHasNoErrors()
        ->assertEmitted('market-stock::entry::updated');

    assertDatabaseHas('market_stock_entries', [
        'market_stock_id' => $marketStock2->id,
        'market_id'       => $market2->id,
        'price'           => 20,
        'quantity'        => 20,
    ]);

    assertDatabaseHas('market_stocks', [
        'market_item_id' => $this->marketStock->market_item_id,
        'quantity'       => 100,
    ]);

    assertDatabaseHas('market_stocks', [
        'market_item_id' => $marketStock2->market_item_id,
        'quantity'       => 40,
    ]);

});

test('market stock id is required', function () {

    // Arrange
    $lw = livewire(MarketStock\Entries\Update::class, [
        'marketStockEntry' => $this->marketStockEntry,
        'marketStock'      => $this->marketStock,
    ])
        ->set('marketStockEntry.market_stock_id', '')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketStockEntry.market_stock_id' => 'required']);

});

test('market stock id should be a integer', function () {

    // Arrange
    $lw = livewire(MarketStock\Entries\Update::class, [
        'marketStockEntry' => $this->marketStockEntry,
        'marketStock'      => $this->marketStock,
    ])
        ->set('marketStockEntry.market_stock_id', 'test')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketStockEntry.market_stock_id' => 'integer']);
});

test('market stock id should be exists in to market stocks table', function () {

    // Arrange
    $lw = livewire(MarketStock\Entries\Update::class, [
        'marketStockEntry' => $this->marketStockEntry,
        'marketStock'      => $this->marketStock,
    ])
        ->set('marketStockEntry.market_stock_id', 999)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketStockEntry.market_stock_id' => 'exists']);

});

test('market id is required', function () {

    // Arrange
    $lw = livewire(MarketStock\Entries\Update::class, [
        'marketStockEntry' => $this->marketStockEntry,
        'marketStock'      => $this->marketStock,
    ])
        ->set('marketStockEntry.market_id', '')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketStockEntry.market_id' => 'required']);

});

test('market id should be a integer', function () {

    // Arrange
    $lw = livewire(MarketStock\Entries\Update::class, [
        'marketStockEntry' => $this->marketStockEntry,
        'marketStock'      => $this->marketStock,
    ])
        ->set('marketStockEntry.market_id', 'test')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketStockEntry.market_id' => 'integer']);
});

test('market id should be exists in to markets table', function () {

    // Arrange
    $lw = livewire(MarketStock\Entries\Update::class, [
        'marketStockEntry' => $this->marketStockEntry,
        'marketStock'      => $this->marketStock,
    ])
        ->set('marketStockEntry.market_id', 999)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketStockEntry.market_id' => 'exists']);

});

test('price is required', function () {

    // Arrange
    $lw = livewire(MarketStock\Entries\Update::class, [
        'marketStockEntry' => $this->marketStockEntry,
        'marketStock'      => $this->marketStock,
    ])
        ->set('marketStockEntry.price', '')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketStockEntry.price' => 'required']);

});

test('price must be a numeric', function () {

    // Arrange
    $lw = livewire(MarketStock\Entries\Update::class, [
        'marketStockEntry' => $this->marketStockEntry,
        'marketStock'      => $this->marketStock,
    ])
        ->set('marketStockEntry.price', 'test')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketStockEntry.price' => 'numeric']);

});

test('price must be greater than zero', function () {

    // Arrange
    $lw = livewire(MarketStock\Entries\Update::class, [
        'marketStockEntry' => $this->marketStockEntry,
        'marketStock'      => $this->marketStock,
    ])
        ->set('marketStockEntry.price', 0)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketStockEntry.price' => 'min']);

});

test('quantity is required', function () {

    // Arrange
    $lw = livewire(MarketStock\Entries\Update::class, [
        'marketStockEntry' => $this->marketStockEntry,
        'marketStock'      => $this->marketStock,
    ])
        ->set('marketStockEntry.quantity', '')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketStockEntry.quantity' => 'required']);

});

test('quantity must be integer', function () {

    // Arrange
    $lw = livewire(MarketStock\Entries\Update::class, [
        'marketStockEntry' => $this->marketStockEntry,
        'marketStock'      => $this->marketStock,
    ])
        ->set('marketStockEntry.quantity', 'test')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketStockEntry.quantity' => 'integer']);

});

test('quantity must be greater than zero', function () {

    // Arrange
    $lw = livewire(MarketStock\Entries\Update::class, [
        'marketStockEntry' => $this->marketStockEntry,
        'marketStock'      => $this->marketStock,
    ])
        ->set('marketStockEntry.quantity', 0)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketStockEntry.quantity' => 'min']);

});
