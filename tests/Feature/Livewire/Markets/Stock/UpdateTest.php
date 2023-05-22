<?php

namespace Tests\Feature\Livewire\MarketStock;

use App\Http\Livewire\MarketStock;
use App\Models\{MarketItem, MarketStockEntry, MarketStockWithdrawal, User};
use function Pest\Laravel\{actingAs, assertDatabaseHas};
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->user->givePermissionTo(getUserGoldPermissions());

    $this->marketItem = MarketItem::factory()->create();

    $this->marketStock = \App\Models\MarketStock::factory()->create([
        'market_item_id' => $this->marketItem->id,
    ]);

    actingAs($this->user);
});

it('should be able to update a market stock', function () {
    // Arrange
    $marketItem2 = MarketItem::factory()->create();

    // Act
    $lw = livewire(\App\Http\Livewire\Markets\Stock\Update::class, ['marketStock' => $this->marketStock])
        ->set('marketStock.market_item_id', $marketItem2->id)
        ->set('marketStock.quantity', 20)
        ->call('save');

    // Assert
    $lw->assertHasNoErrors()
        ->assertEmitted('market-stock::updated');

    assertDatabaseHas('market_stocks', [
        'market_item_id' => $marketItem2->id,
        'quantity' => 20,
    ]);

});

it('should be not able to update a market stock if that has entries', function () {
    // Arrange
    $marketItem2 = MarketItem::factory()->create();

    MarketStockEntry::factory()->create([
        'market_stock_id' => $this->marketStock->id,
    ]);

    // Act
    $lw = livewire(\App\Http\Livewire\Markets\Stock\Update::class, ['marketStock' => $this->marketStock])
        ->set('marketStock.market_item_id', $marketItem2)
        ->set('marketStock.quantity', 20)
        ->call('save');

    // Assert
    $lw->assertForbidden();

    assertDatabaseHas('market_stocks', [
        'market_item_id' => $this->marketItem->id,
        'quantity' => $this->marketStock->quantity,
    ]);

});

it('should be not able to update a market stock if that has withdrawals', function () {
    // Arrange
    $marketItem2 = MarketItem::factory()->create();

    MarketStockWithdrawal::factory()->create([
        'market_stock_id' => $this->marketStock->id,
    ]);

    // Act
    $lw = livewire(\App\Http\Livewire\Markets\Stock\Update::class, ['marketStock' => $this->marketStock])
        ->set('marketStock.market_item_id', $marketItem2)
        ->set('marketStock.quantity', 20)
        ->call('save');

    // Assert
    $lw->assertForbidden();

    assertDatabaseHas('market_stocks', [
        'market_item_id' => $this->marketItem->id,
        'quantity' => $this->marketStock->quantity,
    ]);

});

it('market item is required', function () {

    // Act
    $lw = livewire(\App\Http\Livewire\Markets\Stock\Update::class, ['marketStock' => $this->marketStock])
        ->set('marketStock.market_item_id', null)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketStock.market_item_id' => 'required']);

});

it('market item is unique', function () {
    // Arrange
    $marketItem2 = MarketItem::factory()->create();

    \App\Models\MarketStock::factory()->create([
        'market_item_id' => $marketItem2->id,
    ]);

    // Act
    $lw = livewire(\App\Http\Livewire\Markets\Stock\Update::class, ['marketStock' => $this->marketStock])
        ->set('marketStock.market_item_id', $marketItem2->id)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketStock.market_item_id' => 'unique']);

});

it('market item must be exists', function () {

    // Act
    $lw = livewire(\App\Http\Livewire\Markets\Stock\Update::class, ['marketStock' => $this->marketStock])
        ->set('marketStock.market_item_id', 999)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketStock.market_item_id' => 'exists']);

});

it('market item must be integer', function () {

    // Act
    $lw = livewire(\App\Http\Livewire\Markets\Stock\Update::class, ['marketStock' => $this->marketStock])
        ->set('marketStock.market_item_id', 'test')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketStock.market_item_id' => 'integer']);

});

it('quantity is required', function () {

    // Act
    $lw = livewire(\App\Http\Livewire\Markets\Stock\Update::class, ['marketStock' => $this->marketStock])
        ->set('marketStock.quantity', null)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketStock.quantity' => 'required']);

});

it('quantity must be integer', function () {

    // Act
    $lw = livewire(\App\Http\Livewire\Markets\Stock\Update::class, ['marketStock' => $this->marketStock])
        ->set('marketStock.quantity', 'test')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketStock.quantity' => 'integer']);

});

it('quantity must be greater than zero', function () {

    // Act
    $lw = livewire(\App\Http\Livewire\Markets\Stock\Update::class, ['marketStock' => $this->marketStock])
        ->set('marketStock.quantity', 0)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketStock.quantity' => 'min']);

});
