<?php

namespace Tests\Feature\Livewire\CoupleSpendings;

use App\Http\Livewire\CoupleSpendings;
use App\Models\{CoupleSpending, CoupleSpendingCategory, User};

use function Pest\Laravel\{actingAs, assertDatabaseHas};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->create([
        'email' => 'teste@email.com',
    ]);

    $this->user->givePermissionTo('couple_spending_create');

    $this->user->coupleSpendingCategories()->save(
        $this->category = CoupleSpendingCategory::factory()->create()
    );

    actingAs($this->user);

});

it('should be able to create couple spending', function () {
    // Arrange
    $newData = CoupleSpending::factory()->makeOne();

    // Act
    $lw = livewire(CoupleSpendings\Create::class)
        ->set('coupleSpending.couple_spending_category_id', $this->category->id)
        ->set('coupleSpending.description', $newData->description)
        ->set('coupleSpending.amount', $newData->amount)
        ->set('coupleSpending.date', $newData->date)
        ->call('save');

    // Assert
    $lw->assertHasNoErrors()
        ->assertEmitted('couple-spending::created');

    assertDatabaseHas('couple_spendings', [
        'user_id'                     => $this->user->id,
        'couple_spending_category_id' => $this->category->id,
        'description'                 => $newData->description,
        'amount'                      => $newData->amount,
        'date'                        => $newData->date,
    ]);

});

it('should be not able to create couple spending if not category owner', function () {
    // Arrange
    $newData = CoupleSpending::factory()->makeOne();

    $category2 = CoupleSpendingCategory::factory()->create([
        'user_id' => User::factory(),
    ]);

    // Act
    livewire(CoupleSpendings\Create::class)
        ->set('coupleSpending.couple_spending_category_id', $category2->id)
        ->set('coupleSpending.description', $newData->description)
        ->set('coupleSpending.amount', $newData->amount)
        ->set('coupleSpending.date', $newData->date)
        ->call('save')
        ->assertForbidden();

});

it('should be not able to create couple spending if not has permission', function () {
    // Arrange
    $this->user->revokePermissionTo('couple_spending_create');

    // Act
    livewire(CoupleSpendings\Create::class)
        ->call('save')
        ->assertForbidden();

});

it('should be not able to create couple spending if not authenticated', function () {
    // Arrange
    \Auth::logout();

    // Act
    livewire(CoupleSpendings\Create::class)
        ->call('save')
        ->assertForbidden();

});

test('couple spending category is required', function () {

    // Act
    $lw = livewire(CoupleSpendings\Create::class)
        ->set('coupleSpending.couple_spending_category_id', null)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['coupleSpending.couple_spending_category_id' => 'required']);

});

test('couple spending category must exist', function () {

    // Act
    $lw = livewire(CoupleSpendings\Create::class)
        ->set('coupleSpending.couple_spending_category_id', 999)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['coupleSpending.couple_spending_category_id' => 'exists']);

});

test('description is required', function () {

    // Act
    $lw = livewire(CoupleSpendings\Create::class)
        ->set('coupleSpending.description', null)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['coupleSpending.description' => 'required']);

});

test('description must be a string', function () {

    // Act
    $lw = livewire(CoupleSpendings\Create::class)
        ->set('coupleSpending.description', 123)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['coupleSpending.description' => 'string']);

});

test('description cannot be more than 255 characters', function () {

    // Act
    $lw = livewire(CoupleSpendings\Create::class)
        ->set('coupleSpending.description', str_repeat('a', 256))
        ->call('save');

    // Assert
    $lw->assertHasErrors(['coupleSpending.description' => 'max']);

});

test('amount is required', function () {

    // Act
    $lw = livewire(CoupleSpendings\Create::class)
        ->set('coupleSpending.amount', null)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['coupleSpending.amount' => 'required']);

});

test('amount must be numeric', function () {

    // Act
    $lw = livewire(CoupleSpendings\Create::class)
        ->set('coupleSpending.amount', 'abc')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['coupleSpending.amount' => 'numeric']);

});

test('amount must be at least 1', function () {

    // Act
    $lw = livewire(CoupleSpendings\Create::class)
        ->set('coupleSpending.amount', 0)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['coupleSpending.amount' => 'min']);

});

test('date is required', function () {

    // Act
    $lw = livewire(CoupleSpendings\Create::class)
        ->set('coupleSpending.date', null)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['coupleSpending.date' => 'required']);

});

test('date must be a date', function () {

    // Act
    $lw = livewire(CoupleSpendings\Create::class)
        ->set('coupleSpending.date', 'abc')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['coupleSpending.date' => 'date']);

});
