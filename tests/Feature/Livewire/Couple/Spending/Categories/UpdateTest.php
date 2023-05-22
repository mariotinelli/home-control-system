<?php

namespace Tests\Feature\Livewire\CoupleSpendingCategories;

use App\Http\Livewire\CoupleSpendingCategories;
use App\Models\{CoupleSpendingCategory, User};
use function Pest\Laravel\{actingAs};
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->user->givePermissionTo('couple_spending_category_update');

    $this->user->coupleSpendingCategories()->save(
        $this->category = CoupleSpendingCategory::factory()->create()
    );

    actingAs($this->user);
});

it('should be able to update a couple spending category', function () {
    // Arrange
    $newData = CoupleSpendingCategory::factory()->makeOne();

    // Act
    $lw = livewire(\App\Http\Livewire\Couple\Spending\Categories\Update::class, ['category' => $this->category])
        ->set('category.name', $newData->name)
        ->call('save');

    // Assert
    $lw->assertHasNoErrors()
        ->assertEmitted('couple-spending-category::updated');

    expect($this->category->refresh())
        ->user_id->toBe($this->user->id)
        ->name->toBe($newData->name);

});

it('should be not able to update a couple spending category if not owner', function () {
    // Arrange
    $category2 = CoupleSpendingCategory::factory()->create();

    // Act
    livewire(\App\Http\Livewire\Couple\Spending\Categories\Update::class, ['category' => $category2])
        ->call('save')
        ->assertForbidden();

});

it('should be not able to update a couple spending category if not has permission', function () {
    // Arrange
    $this->user->revokePermissionTo('couple_spending_category_update');

    // Act
    livewire(\App\Http\Livewire\Couple\Spending\Categories\Update::class, ['category' => $this->category])
        ->call('save')
        ->assertForbidden();

});

it('should be not able to update a couple spending category if not authenticated', function () {

    // Arrange
    \Auth::logout();

    // Act
    livewire(\App\Http\Livewire\Couple\Spending\Categories\Update::class, ['category' => $this->category])
        ->call('save')
        ->assertForbidden();

});

test('name is required', function () {

    // Act
    $lw = livewire(\App\Http\Livewire\Couple\Spending\Categories\Update::class, ['category' => $this->category])
        ->set('category.name', null)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['category.name' => 'required']);

});

test('name must be a string', function () {

    // Act
    $lw = livewire(\App\Http\Livewire\Couple\Spending\Categories\Update::class, ['category' => $this->category])
        ->set('category.name', 123)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['category.name' => 'string']);

});

test('name must be unique', function () {

    // Arrange
    $category = CoupleSpendingCategory::factory()->create();

    // Act
    $lw = livewire(\App\Http\Livewire\Couple\Spending\Categories\Update::class, ['category' => $this->category])
        ->set('category.name', $category->name)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['category.name' => 'unique']);

});

test('name must not be greater than 255 characters', function () {

    // Act
    $lw = livewire(\App\Http\Livewire\Couple\Spending\Categories\Update::class, ['category' => $this->category])
        ->set('category.name', str_repeat('a', 256))
        ->call('save');

    // Assert
    $lw->assertHasErrors(['category.name' => 'max']);

});
