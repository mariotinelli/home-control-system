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

    $this->user->givePermissionTo('couple_spending_update');

    $this->user->coupleSpendingCategories()->save(
        $this->category = CoupleSpendingCategory::factory()->create()
    );

    $this->user->coupleSpendings()->save(
        $this->coupleSpending = $this->category->spendings()->save(
            CoupleSpending::factory()->create()
        )
    );

    actingAs($this->user);

});

it('should be able to update a couple spending', function () {
    // Arrange
    $newData = CoupleSpending::factory()->makeOne([
        'couple_spending_category_id' => CoupleSpendingCategory::factory()->create([
            'user_id' => $this->user->id,
        ])->id,
    ]);

    // Act
    $lw = livewire(\App\Http\Livewire\Couple\Spending\Update::class, ['coupleSpending' => $this->coupleSpending])
        ->set('coupleSpending.couple_spending_category_id', $newData->couple_spending_category_id)
        ->set('coupleSpending.description', $newData->description)
        ->set('coupleSpending.amount', $newData->amount)
        ->set('coupleSpending.date', $newData->date)
        ->call('save');

    // Assert
    $lw->assertHasNoErrors()
        ->assertEmitted('couple-spending::updated');

    assertDatabaseHas('couple_spendings', [
        'id'                          => $this->coupleSpending->id,
        'user_id'                     => $this->user->id,
        'couple_spending_category_id' => $newData->couple_spending_category_id,
        'description'                 => $newData->description,
        'amount'                      => $newData->amount,
        'date'                        => $newData->date,
    ]);

});

it('should not be able to change category to a category that it doesnt own', function () {
    // Arrange
    $newData = CoupleSpending::factory()->makeOne();

    $category2 = CoupleSpendingCategory::factory()->create([
        'user_id' => User::factory(),
    ]);

    // Act
    livewire(\App\Http\Livewire\Couple\Spending\Update::class, ['coupleSpending' => $this->coupleSpending])
        ->set('coupleSpending.couple_spending_category_id', $category2->id)
        ->set('coupleSpending.description', $newData->description)
        ->set('coupleSpending.amount', $newData->amount)
        ->set('coupleSpending.date', $newData->date)
        ->call('save')
        ->assertForbidden();

});

it('should be not able to update a couple spending if not owner', function () {
    // Arrange
    $coupleSpending2 = CoupleSpending::factory()->create();

    // Act
    livewire(\App\Http\Livewire\Couple\Spending\Update::class, ['coupleSpending' => $coupleSpending2])
        ->call('save')
        ->assertForbidden();

});

it('should be not able to update a couple spending if not has permission', function () {
    // Arrange
    $this->user->revokePermissionTo('couple_spending_update');

    // Act
    livewire(\App\Http\Livewire\Couple\Spending\Update::class, ['coupleSpending' => $this->coupleSpending])
        ->call('save')
        ->assertForbidden();

});

it('should be not able to update a couple spending if not authenticated', function () {
    // Arrange
    \Auth::logout();

    // Act
    livewire(\App\Http\Livewire\Couple\Spending\Update::class, ['coupleSpending' => $this->coupleSpending])
        ->call('save')
        ->assertForbidden();

});

test('couple spending category is required', function () {

    // Act
    $lw = livewire(\App\Http\Livewire\Couple\Spending\Update::class, ['coupleSpending' => $this->coupleSpending])
        ->set('coupleSpending.couple_spending_category_id', null)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['coupleSpending.couple_spending_category_id' => 'required']);

});

test('couple spending category must exist', function () {

    // Act
    $lw = livewire(\App\Http\Livewire\Couple\Spending\Update::class, ['coupleSpending' => $this->coupleSpending])
        ->set('coupleSpending.couple_spending_category_id', 999)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['coupleSpending.couple_spending_category_id' => 'exists']);

});

test('description is required', function () {

    // Act
    $lw = livewire(\App\Http\Livewire\Couple\Spending\Update::class, ['coupleSpending' => $this->coupleSpending])
        ->set('coupleSpending.description', null)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['coupleSpending.description' => 'required']);

});

test('description must be a string', function () {

    // Act
    $lw = livewire(\App\Http\Livewire\Couple\Spending\Update::class, ['coupleSpending' => $this->coupleSpending])
        ->set('coupleSpending.description', 123)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['coupleSpending.description' => 'string']);

});

test('description cannot be more than 255 characters', function () {

    // Act
    $lw = livewire(\App\Http\Livewire\Couple\Spending\Update::class, ['coupleSpending' => $this->coupleSpending])
        ->set('coupleSpending.description', str_repeat('a', 256))
        ->call('save');

    // Assert
    $lw->assertHasErrors(['coupleSpending.description' => 'max']);

});

test('amount is required', function () {

    // Act
    $lw = livewire(\App\Http\Livewire\Couple\Spending\Update::class, ['coupleSpending' => $this->coupleSpending])
        ->set('coupleSpending.amount', null)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['coupleSpending.amount' => 'required']);

});

test('amount must be numeric', function () {

    // Act
    $lw = livewire(\App\Http\Livewire\Couple\Spending\Update::class, ['coupleSpending' => $this->coupleSpending])
        ->set('coupleSpending.amount', 'abc')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['coupleSpending.amount' => 'numeric']);

});

test('amount must be at least 1', function () {

    // Act
    $lw = livewire(\App\Http\Livewire\Couple\Spending\Update::class, ['coupleSpending' => $this->coupleSpending])
        ->set('coupleSpending.amount', 0)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['coupleSpending.amount' => 'min']);

});

test('date is required', function () {

    // Act
    $lw = livewire(\App\Http\Livewire\Couple\Spending\Update::class, ['coupleSpending' => $this->coupleSpending])
        ->set('coupleSpending.date', null)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['coupleSpending.date' => 'required']);

});

test('date must be a date', function () {

    // Act
    $lw = livewire(\App\Http\Livewire\Couple\Spending\Update::class, ['coupleSpending' => $this->coupleSpending])
        ->set('coupleSpending.date', 'abc')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['coupleSpending.date' => 'date']);

});