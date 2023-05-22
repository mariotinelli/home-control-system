<?php

namespace Tests\Feature\Livewire\MarketItems;

use App\Http\Livewire\MarketItems;
use App\Models\{MarketItem, MarketItemCategory, User};
use function Pest\Laravel\{actingAs, assertDatabaseHas, assertDatabaseMissing};
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->user->givePermissionTo(getUserGoldPermissions());

    $this->user->marketItemCategories()->save(
        $this->marketItemCategory = MarketItemCategory::factory()->make([
            'name' => 'Test Category',
        ])
    );

    $this->user->marketItems()->save(
        $this->marketItem = MarketItem::factory()->make([
            'market_item_category_id' => $this->marketItemCategory->id,
        ])
    );

    actingAs($this->user);
});

it('should be able to delete market item', function () {
    // Act
    $lw = livewire(\App\Http\Livewire\Markets\Items\Destroy::class, [
        'marketItem' => $this->marketItem,
    ])->call('save');

    // Assert
    $lw->assertEmitted('market-item::deleted')
        ->assertHasNoErrors();

    assertDatabaseMissing('market_items', [
        'id' => $this->marketItem->id,
    ]);
});

it('should not be able to delete market item if it is used in market stock', function () {
    // Arrange
    $this->marketItem->marketStock()->create([
        'quantity' => 1,
    ]);

    // Act
    $lw = livewire(\App\Http\Livewire\Markets\Items\Destroy::class, [
        'marketItem' => $this->marketItem,
    ])->call('save');

    // Assert
    $lw->assertHasErrors(['marketItem'])
        ->assertEmitted('market-item::delete-failed');

    assertDatabaseHas('market_items', [
        'id' => $this->marketItem->id,
    ]);
});
