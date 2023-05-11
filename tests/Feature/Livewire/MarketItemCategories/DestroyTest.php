<?php

namespace Tests\Feature\Livewire\MarketItemCategories;

use App\Http\Livewire\MarketItemCategories;
use App\Models\{MarketItem, MarketItemCategory, User};

use function Pest\Laravel\{actingAs, assertDatabaseHas, assertDatabaseMissing};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->create([
        'email' => 'teste@email.com',
    ]);

    $this->user->givePermissionTo(getUserGoldPermissions());

    $this->user->marketItemCategories()->save(
        $this->marketItemCategory = MarketItemCategory::factory()->create([
            'name' => 'Test Market Item Category',
        ])
    );

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
    $this->marketItemCategory->marketItems()->save(
        MarketItem::factory()->create()
    );

    // Act
    $lw = livewire(MarketItemCategories\Destroy::class, [
        'marketItemCategory' => $this->marketItemCategory,
    ])->call('save');

    // Assert
    $lw->assertForbidden();

    assertDatabaseHas('market_item_categories', [
        'user_id' => $this->user->id,
        'name'    => 'Test Market Item Category',
    ]);

});
