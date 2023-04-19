<?php

namespace Tests\Feature\Livewire\MarketItems;

use App\Http\Livewire\MarketItems;
use App\Models\MarketItem;
use App\Models\MarketItemCategory;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->marketItemCategory = MarketItemCategory::factory()->create([
        'name' => 'Test Category',
    ]);

    $this->marketItem = MarketItem::factory()->create([
        'market_item_category_id' => $this->marketItemCategory->id,
    ]);

    actingAs($this->user);
});

it('should be able to delete market item', function () {
    // Act
    $lw = livewire(MarketItems\Destroy::class, [
        'marketItem' => $this->marketItem,
    ])->call('save');

    // Assert
    $lw->assertEmitted('market-item::deleted')
        ->assertHasNoErrors();

    assertDatabaseMissing('market_items', [
        'id' => $this->marketItem->id,
    ]);
});

todo('should not be able to delete market item if it is used in market stock');
