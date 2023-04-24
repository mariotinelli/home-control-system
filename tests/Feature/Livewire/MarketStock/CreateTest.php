<?php

namespace Tests\Feature\Livewire\MarketStock;

use App\Http\Livewire\MarketStock;
use App\Models\{MarketItem, User};

use function Pest\Laravel\{actingAs, assertDatabaseHas};
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->marketItem = MarketItem::factory()->create();

    actingAs($this->user);
});

it('should be able to create a new market stock', function () {

    // Act
    $lw = livewire(MarketStock\Create::class)
        ->set('marketStock.market_item_id', $this->marketItem->id)
        ->set('marketStock.quantity', 10)
        ->call('save');

    // Assert
    $lw->assertHasNoErrors()
        ->assertEmitted('market-stock::created');

    assertDatabaseHas('market_stocks', [
        'market_item_id' => $this->marketItem->id,
        'quantity'       => 10,
    ]);

});

it('market item is required', function () {

    // Act
    $lw = livewire(MarketStock\Create::class)
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
    $lw = livewire(MarketStock\Create::class)
        ->set('marketStock.market_item_id', $marketItem2->id)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketStock.market_item_id' => 'unique']);

});

it('market item must be exists', function () {

    // Act
    $lw = livewire(MarketStock\Create::class)
        ->set('marketStock.market_item_id', 999)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketStock.market_item_id' => 'exists']);

});

it('market item must be integer', function () {

    // Act
    $lw = livewire(MarketStock\Create::class)
        ->set('marketStock.market_item_id', 'test')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketStock.market_item_id' => 'integer']);

});

it('quantity is required', function () {

    // Act
    $lw = livewire(MarketStock\Create::class)
        ->set('marketStock.quantity', null)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketStock.quantity' => 'required']);

});

it('quantity must be integer', function () {

    // Act
    $lw = livewire(MarketStock\Create::class)
        ->set('marketStock.quantity', 'test')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketStock.quantity' => 'integer']);

});

it('quantity must be greater than zero', function () {

    // Act
    $lw = livewire(MarketStock\Create::class)
        ->set('marketStock.quantity', 0)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketStock.quantity' => 'min']);

});
