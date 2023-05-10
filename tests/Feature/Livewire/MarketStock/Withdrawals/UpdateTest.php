<?php

namespace Tests\Feature\Livewire\MarketStock\Entries;

use App\Http\Livewire\MarketStock;
use App\Models\{Market, MarketStockWithdrawal, User};

use function Pest\Laravel\{actingAs, assertDatabaseHas};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->create();

    $this->user->givePermissionTo(getUserGoldPermissions());

    $this->marketStock = \App\Models\MarketStock::factory()->create([
        'quantity' => 100,
    ]);

    $this->marketStockWithdraw = MarketStockWithdrawal::factory()->create([
        'market_stock_id' => $this->marketStock->id,
        'quantity'        => 20,
    ]);

    $this->marketStock->decrement('quantity', $this->marketStockWithdraw->quantity);

    actingAs($this->user);

});

it('should be update a withdraw of the market stock', function () {

    expect($this->marketStock->quantity)
        ->toBe(100 - 20); // Actual Market Stock Quantity - Actual Withdraw Quantity

    // Arrange
    $market2 = Market::factory()->create();

    // Act
    $lw = livewire(MarketStock\Withdrawals\Update::class, [
        'marketStockWithdraw' => $this->marketStockWithdraw,
        'marketStock'         => $this->marketStock,
    ])
        ->set('marketStockWithdraw.market_id', $market2->id)
        ->set('marketStockWithdraw.price', 10)
        ->set('marketStockWithdraw.quantity', 50)
        ->call('save');

    // Assert
    $lw->assertHasNoErrors()
        ->assertEmitted('market-stock::withdrawal::updated');

    assertDatabaseHas('market_stock_withdrawals', [
        'market_stock_id' => $this->marketStock->id,
        'market_id'       => $market2->id,
        'price'           => 10,
        'quantity'        => 50,
    ]);

    assertDatabaseHas('market_stocks', [
        'id'       => $this->marketStock->id,
        'quantity' => (80 + 20) - 50,
    ]);

});

it('should be able to change market stock that withdraw', function () {

    expect($this->marketStock->quantity)
        ->toBe(100 - 20); // Actual Market Stock Quantity - Actual Withdraw Quantity

    // Arrange
    $marketStock2 = \App\Models\MarketStock::factory()->create([
        'quantity' => 100,
    ]);

    // Act
    $lw = livewire(MarketStock\Withdrawals\Update::class, [
        'marketStockWithdraw' => $this->marketStockWithdraw,
        'marketStock'         => $this->marketStock,
    ])
        ->set('marketStockWithdraw.market_stock_id', $marketStock2->id)
        ->set('marketStockWithdraw.price', 10)
        ->set('marketStockWithdraw.quantity', 50)
        ->call('save');

    // Assert
    $lw->assertHasNoErrors()
        ->assertEmitted('market-stock::withdrawal::updated');

    assertDatabaseHas('market_stocks', [
        'id'       => $this->marketStock->id,
        'quantity' => (80 + 20),
    ]);

    assertDatabaseHas('market_stock_withdrawals', [
        'market_stock_id' => $marketStock2->id,
        'price'           => 10,
        'quantity'        => 50,
    ]);

    assertDatabaseHas('market_stocks', [
        'id'       => $marketStock2->id,
        'quantity' => (100 - 50),
    ]);

});

it('should be not able to update quantity of the withdraw to greater than market stock quantity', function () {

    expect($this->marketStock->quantity)
        ->toBe(100 - 20); // Actual Market Stock Quantity - Actual Withdraw Quantity

    // Act
    $lw = livewire(MarketStock\Withdrawals\Update::class, [
        'marketStockWithdraw' => $this->marketStockWithdraw,
        'marketStock'         => $this->marketStock,
    ])
        ->set('marketStockWithdraw.price', 10)
        ->set('marketStockWithdraw.quantity', 101)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketStockWithdraw.quantity']);

    assertDatabaseHas('market_stock_withdrawals', [
        'market_stock_id' => $this->marketStock->id,
        'quantity'        => 20,
    ]);

    assertDatabaseHas('market_stocks', [
        'id'       => $this->marketStock->id,
        'quantity' => $this->marketStock->quantity,
    ]);

});

it('should be not able to change market stock that withdraw to quantity greater than a market stock quantity', function () {

    expect($this->marketStock->quantity)
        ->toBe(100 - 20); // Actual Market Stock Quantity - Actual Withdraw Quantity

    // Arrange
    $marketStock2 = \App\Models\MarketStock::factory()->create([
        'quantity' => 50,
    ]);

    // Act
    $lw = livewire(MarketStock\Withdrawals\Update::class, [
        'marketStockWithdraw' => $this->marketStockWithdraw,
        'marketStock'         => $this->marketStock,
    ])
        ->set('marketStockWithdraw.market_stock_id', $marketStock2->id)
        ->set('marketStockWithdraw.price', 10)
        ->set('marketStockWithdraw.quantity', 51)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketStockWithdraw.quantity']);

    assertDatabaseHas('market_stocks', [
        'id'       => $this->marketStock->id,
        'quantity' => (100 - 20),
    ]);

    assertDatabaseHas('market_stock_withdrawals', [
        'market_stock_id' => $this->marketStock->id,
        'quantity'        => 20,
    ]);

});

test('market stock id is required', function () {

    // Arrange
    $lw = livewire(MarketStock\Entries\Create::class, ['marketStock' => $this->marketStock])
        ->set('marketStockEntry.market_stock_id', '')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketStockEntry.market_stock_id' => 'required']);

});

test('market stock id should be a integer', function () {

    // Arrange
    $lw = livewire(MarketStock\Entries\Create::class, ['marketStock' => $this->marketStock])
        ->set('marketStockEntry.market_stock_id', 'test')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketStockEntry.market_stock_id' => 'integer']);
});

test('market stock id should be exists in to market stocks table', function () {

    // Arrange
    $lw = livewire(MarketStock\Entries\Create::class, ['marketStock' => $this->marketStock])
        ->set('marketStockEntry.market_stock_id', 999)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketStockEntry.market_stock_id' => 'exists']);

});

test('market id is required', function () {

    // Arrange
    $lw = livewire(MarketStock\Entries\Create::class, ['marketStock' => $this->marketStock])
        ->set('marketStockEntry.market_id', '')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketStockEntry.market_id' => 'required']);

});

test('market id should be a integer', function () {

    // Arrange
    $lw = livewire(MarketStock\Entries\Create::class, ['marketStock' => $this->marketStock])
        ->set('marketStockEntry.market_id', 'test')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketStockEntry.market_id' => 'integer']);
});

test('market id should be exists in to markets table', function () {

    // Arrange
    $lw = livewire(MarketStock\Entries\Create::class, ['marketStock' => $this->marketStock])
        ->set('marketStockEntry.market_id', 999)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketStockEntry.market_id' => 'exists']);

});

test('price is required', function () {

    // Arrange
    $lw = livewire(MarketStock\Entries\Create::class, ['marketStock' => $this->marketStock])
        ->set('marketStockEntry.price', '')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketStockEntry.price' => 'required']);

});

test('price must be a numeric', function () {

    // Arrange
    $lw = livewire(MarketStock\Entries\Create::class, ['marketStock' => $this->marketStock])
        ->set('marketStockEntry.price', 'test')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketStockEntry.price' => 'numeric']);

});

test('price must be greater than zero', function () {

    // Arrange
    $lw = livewire(MarketStock\Entries\Create::class, ['marketStock' => $this->marketStock])
        ->set('marketStockEntry.price', 0)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketStockEntry.price' => 'min']);

});

test('quantity is required', function () {

    // Arrange
    $lw = livewire(MarketStock\Entries\Create::class, ['marketStock' => $this->marketStock])
        ->set('marketStockEntry.quantity', '')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketStockEntry.quantity' => 'required']);

});

test('quantity must be integer', function () {

    // Arrange
    $lw = livewire(MarketStock\Entries\Create::class, ['marketStock' => $this->marketStock])
        ->set('marketStockEntry.quantity', 'test')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketStockEntry.quantity' => 'integer']);

});

test('quantity must be greater than zero', function () {

    // Arrange
    $lw = livewire(MarketStock\Entries\Create::class, ['marketStock' => $this->marketStock])
        ->set('marketStockEntry.quantity', 0)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketStockEntry.quantity' => 'min']);

});
