<?php

namespace Tests\Feature\Livewire\CoupleSpendingCategories;

use App\Http\Livewire\CoupleSpendingCategories;
use App\Models\{CoupleSpendingCategory, User};
use Auth;

use function Pest\Laravel\{actingAs, assertDatabaseHas};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->create([
        'email' => 'teste@email.com',
    ]);

    $this->user->givePermissionTo('couple_spending_category_create');

    actingAs($this->user);

});

it('should be able to create a new couple spending category', function () {
    // Arrange
    $newData = CoupleSpendingCategory::factory()->makeOne();

    // Act
    $lw = livewire(CoupleSpendingCategories\Create::class)
        ->set('category.name', $newData->name)
        ->call('save');

    // Assert
    $lw->assertHasNoErrors()
        ->assertEmitted('couple-spending-category::created');

    assertDatabaseHas('couple_spending_categories', [
        'user_id' => $this->user->id,
        'name'    => $newData->name,
    ]);

});

it('should be not able to create a new couple spending category if no has permission', function () {
    // Arrange
    $newData = CoupleSpendingCategory::factory()->makeOne();

    $this->user->revokePermissionTo('couple_spending_category_create');

    // Act - No has permission
    livewire(CoupleSpendingCategories\Create::class)
        ->call('save')
        ->assertForbidden();
});

it('should be not able to create a new couple spending category if not authenticated', function () {
    // Arrange
    Auth::logout();

    // Act - Not authenticated
    livewire(CoupleSpendingCategories\Create::class)
        ->call('save')
        ->assertForbidden();

});

test('name is required', function () {

    // Act
    $lw = livewire(CoupleSpendingCategories\Create::class)
        ->set('category.name', null)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['category.name' => 'required']);

});

test('name must be a string', function () {

    // Act
    $lw = livewire(CoupleSpendingCategories\Create::class)
        ->set('category.name', 123)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['category.name' => 'string']);

});

test('name must be unique', function () {

    // Arrange
    $category = CoupleSpendingCategory::factory()->create();

    // Act
    $lw = livewire(CoupleSpendingCategories\Create::class)
        ->set('category.name', $category->name)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['category.name' => 'unique']);

});

test('name must not be greater than 255 characters', function () {

    // Act
    $lw = livewire(CoupleSpendingCategories\Create::class)
        ->set('category.name', str_repeat('a', 256))
        ->call('save');

    // Assert
    $lw->assertHasErrors(['category.name' => 'max']);

});
