<?php

namespace Tests\Feature\Livewire\MarketItemCategories;

use App\Http\Livewire\MarketItemCategories;
use App\Models\{MarketItem, MarketItemCategory, User};

use function Pest\Laravel\{actingAs, assertDatabaseHas, assertDatabaseMissing};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->create();

    $this->user->givePermissionTo(getUserGoldPermissions());

    $this->marketItemCategory = MarketItemCategory::factory()->create([
        'name' => 'Test Category',
    ]);

    actingAs($this->user);

});

it('should be able to delete market item category', function () {

    // Act
    $lw = livewire(MarketItemCategories\Destroy::class, [
        'marketItemCategory' => $this->marketItemCategory,
    ])
        ->call('save');

    // Assert
    $lw->assertEmitted('market-item-category::deleted')
        ->assertHasNoErrors();

    assertDatabaseMissing('market_item_categories', [
        'name' => 'Test Category',
    ]);

});

it('should not be able to delete market item category if it has market items', function () {

    // Arrange
    MarketItem::factory()->create([
        'market_item_category_id' => $this->marketItemCategory->id,
    ]);

    // Act
    $lw = livewire(MarketItemCategories\Destroy::class, [
        'marketItemCategory' => $this->marketItemCategory,
    ])->call('save');

    // Assert
    $lw->assertForbidden();

    assertDatabaseHas('market_item_categories', [
        'name' => 'Test Category',
    ]);

});
