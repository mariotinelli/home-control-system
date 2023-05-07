<?php

namespace Tests\Feature\Livewire\MarketItemCategories;

use App\Http\Livewire\MarketItemCategories;
use App\Models\{MarketItemCategory, User};

use function Pest\Laravel\{actingAs, assertDatabaseHas};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->create();

    $this->user->givePermissionTo(getUserGoldPermissions());

    $this->marketItemCategory = MarketItemCategory::factory()->create([
        'name' => 'Test Market Item Category',
    ]);

    actingAs($this->user);

});

it('should be able to update market item marketItemCategory', function () {

    // Act
    $lw = livewire(MarketItemCategories\Update::class, ['marketItemCategory' => $this->marketItemCategory])
        ->set('marketItemCategory.name', 'Test Market Item Category Update')
        ->call('save');

    // Assert
    $lw->assertEmitted('market-item-category::updated')
        ->assertHasNoErrors();

    assertDatabaseHas('market_item_categories', [
        'name' => 'Test Market Item Category Update',
    ]);

});

it('name is required', function () {

    // Act
    $lw = livewire(MarketItemCategories\Update::class, ['marketItemCategory' => $this->marketItemCategory])
        ->set('marketItemCategory.name', '')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketItemCategory.name' => 'required']);

});

it('name is string', function () {

    // Act
    $lw = livewire(MarketItemCategories\Update::class, ['marketItemCategory' => $this->marketItemCategory])
        ->set('marketItemCategory.name', 123)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketItemCategory.name' => 'string']);

});

it('name is max 100', function () {

    // Act
    $lw = livewire(MarketItemCategories\Update::class, ['marketItemCategory' => $this->marketItemCategory])
        ->set('marketItemCategory.name', str_repeat('a', 101))
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketItemCategory.name' => 'max']);

});
