<?php

namespace Tests\Feature\Livewire\CreditCards\Spendings;

use App\Models\{CreditCard, Spending, User};

use function Pest\Laravel\{actingAs, assertDatabaseHas};
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->user->givePermissionTo('credit_card_spending_create');

    $this->user->creditCards()->save(
        $this->creditCard = CreditCard::factory()->makeOne()
    );

    actingAs($this->user);
});

it('should be able to create a spending', function () {
    // Arrange
    $newData = Spending::factory()->makeOne([
        'amount' => $this->creditCard->remaining_limit - 1,
    ]);

    // Act
    livewire(\App\Http\Livewire\Banks\CreditCards\Spendings\Create::class, ['creditCard' => $this->creditCard])
        ->set('spending.amount', $newData->amount)
        ->set('spending.description', $newData->description)
        ->call('save')
        ->assertEmitted('credit-card::spending::created');

    assertDatabaseHas('spendings', [
        'credit_card_id' => $this->creditCard->id,
        'amount'         => $newData->amount,
        'description'    => $newData->description,
    ]);

    assertDatabaseHas('credit_cards', [
        'id'              => $this->creditCard->id,
        'remaining_limit' => $this->creditCard->remaining_limit - $newData->amount,
    ]);

});

it('should be not able to create a spending greater than remaining limit', function () {
    // Arrange
    $newData = Spending::factory()->makeOne([
        'amount' => $this->creditCard->remaining_limit + 1,
    ]);

    // Act
    livewire(\App\Http\Livewire\Banks\CreditCards\Spendings\Create::class, ['creditCard' => $this->creditCard])
        ->set('spending.amount', $newData->amount)
        ->call('save')
        ->assertHasErrors(['spending.amount']);

});

it('should be not able to create a spending if not credit card owner', function () {
    // Arrange
    $creditCard2 = CreditCard::factory()->create();

    // Act
    livewire(\App\Http\Livewire\Banks\CreditCards\Spendings\Create::class, ['creditCard' => $creditCard2])
        ->call('save')
        ->assertForbidden();

});

it('should be not able to create a spending if not has permission to this', function () {
    // Arrange
    $this->user->revokePermissionTo('credit_card_spending_create');

    // Act
    livewire(\App\Http\Livewire\Banks\CreditCards\Spendings\Create::class, ['creditCard' => $this->creditCard])
        ->call('save')
        ->assertForbidden();

});

it('should be not able to create a spending if not authenticated', function () {
    // Arrange
    \Auth::logout();

    // Act
    livewire(\App\Http\Livewire\Banks\CreditCards\Spendings\Create::class, ['creditCard' => $this->creditCard])
        ->call('save')
        ->assertForbidden();

});

test('amount is required', function () {
    livewire(\App\Http\Livewire\Banks\CreditCards\Spendings\Create::class, ['creditCard' => $this->creditCard])
        ->set('spending.amount', null)
        ->call('save')
        ->assertHasErrors(['spending.amount' => 'required']);
});

test('amount is numeric', function () {
    livewire(\App\Http\Livewire\Banks\CreditCards\Spendings\Create::class, ['creditCard' => $this->creditCard])
        ->set('spending.amount', 'abc')
        ->call('save')
        ->assertHasErrors(['spending.amount' => 'numeric']);
});

test('amount should be have a max of 10 digits', function () {
    livewire(\App\Http\Livewire\Banks\CreditCards\Spendings\Create::class, ['creditCard' => $this->creditCard])
        ->set('spending.amount', 12345678901)
        ->call('save')
        ->assertHasErrors(['spending.amount' => 'max_digits']);
});

test('description is required', function () {
    livewire(\App\Http\Livewire\Banks\CreditCards\Spendings\Create::class, ['creditCard' => $this->creditCard])
        ->set('spending.description', '')
        ->call('save')
        ->assertHasErrors(['spending.description' => 'required']);
});

test('description is string', function () {
    livewire(\App\Http\Livewire\Banks\CreditCards\Spendings\Create::class, ['creditCard' => $this->creditCard])
        ->set('spending.description', 123)
        ->call('save')
        ->assertHasErrors(['spending.description' => 'string']);
});

test('description should be have a max of 255 characters', function () {
    livewire(\App\Http\Livewire\Banks\CreditCards\Spendings\Create::class, ['creditCard' => $this->creditCard])
        ->set('spending.description', str_repeat('a', 256))
        ->call('save')
        ->assertHasErrors(['spending.description' => 'max']);
});
