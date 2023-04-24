<?php

namespace Tests\Feature\Livewire\CreditCards\Spendings;

use App\Http\Livewire\CreditCards\Spendings;
use App\Models\{CreditCard, User};

use function Pest\Laravel\{actingAs, assertDatabaseHas};
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->creditCard = CreditCard::factory()->create([
        'user_id' => $this->user->id,
    ]);

    actingAs($this->user);
});

it('should be able create a spending', function () {

    livewire(Spendings\Create::class, ['creditCard' => $this->creditCard])
        ->set('spending.amount', 100)
        ->set('spending.description', 'Test')
        ->call('save')
        ->assertEmitted('credit-card::spending::created');

    assertDatabaseHas('spendings', [
        'credit_card_id' => $this->creditCard->id,
        'amount'         => 100,
        'description'    => 'Test',
    ]);

    assertDatabaseHas('credit_cards', [
        'id'              => $this->creditCard->id,
        'remaining_limit' => $this->creditCard->remaining_limit - 100,
    ]);

});

it('should be able create a spending in credit card only owner', function () {

    actingAs(User::factory()->create());

    livewire(Spendings\Create::class, ['creditCard' => $this->creditCard])
        ->call('save')
        ->assertForbidden();

    actingAs($this->user);

    livewire(Spendings\Create::class, ['creditCard' => $this->creditCard])
        ->set('spending.amount', 100)
        ->set('spending.description', 'Test')
        ->call('save')
        ->assertEmitted('credit-card::spending::created');

    assertDatabaseHas('spendings', [
        'credit_card_id' => $this->creditCard->id,
        'amount'         => 100,
        'description'    => 'Test',
    ]);

    assertDatabaseHas('credit_cards', [
        'id'              => $this->creditCard->id,
        'remaining_limit' => $this->creditCard->remaining_limit - 100,
    ]);
});

test('amount is required', function () {
    livewire(Spendings\Create::class, ['creditCard' => $this->creditCard])
        ->set('spending.amount', '')
        ->call('save')
        ->assertHasErrors(['spending.amount' => 'required']);
});

test('amount is numeric', function () {
    livewire(Spendings\Create::class, ['creditCard' => $this->creditCard])
        ->set('spending.amount', 'abc')
        ->call('save')
        ->assertHasErrors(['spending.amount' => 'numeric']);
});

test('amount should be have a max of 10 digits', function () {
    livewire(Spendings\Create::class, ['creditCard' => $this->creditCard])
        ->set('spending.amount', 12345678901)
        ->call('save')
        ->assertHasErrors(['spending.amount' => 'max_digits']);
});

test('description is required', function () {
    livewire(Spendings\Create::class, ['creditCard' => $this->creditCard])
        ->set('spending.description', '')
        ->call('save')
        ->assertHasErrors(['spending.description' => 'required']);
});

test('description is string', function () {
    livewire(Spendings\Create::class, ['creditCard' => $this->creditCard])
        ->set('spending.description', 123)
        ->call('save')
        ->assertHasErrors(['spending.description' => 'string']);
});

test('description should be have a max of 255 characters', function () {
    livewire(Spendings\Create::class, ['creditCard' => $this->creditCard])
        ->set('spending.description', str_repeat('a', 256))
        ->call('save')
        ->assertHasErrors(['spending.description' => 'max']);
});
