<?php

namespace Tests\Feature\Livewire\CreditCards;

use App\Models\{CreditCard, User};

use function Pest\Laravel\{actingAs, assertDatabaseHas};
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->user->givePermissionTo('credit_card_create');

    actingAs($this->user);
});

it('should be able to create a credit card', function () {
    // Arrange
    $newData = CreditCard::factory()->makeOne();

    // Act
    livewire(\App\Http\Livewire\Banks\CreditCards\Create::class)
        ->set('creditCard.bank', $newData->bank)
        ->set('creditCard.number', $newData->number)
        ->set('creditCard.expiration', $newData->expiration)
        ->set('creditCard.cvv', $newData->cvv)
        ->set('creditCard.limit', $newData->limit)
        ->call('save')
        ->assertHasNoErrors()
        ->assertEmitted('credit-card::created');

    // Assert
    assertDatabaseHas('credit_cards', [
        'user_id'         => $this->user->id,
        'bank'            => $newData->bank,
        'number'          => $newData->number,
        'expiration'      => $newData->expiration,
        'cvv'             => $newData->cvv,
        'limit'           => $newData->limit,
        'remaining_limit' => $newData->limit,
    ]);

});

it('should be not able to create a credit card if not have permission to this', function () {
    // Arrange
    $this->user->revokePermissionTo('credit_card_create');

    // Act
    livewire(\App\Http\Livewire\Banks\CreditCards\Create::class)
        ->call('save')
        ->assertForbidden();

});

it('should not be able to create a credit card if not authenticated', function () {
    // Arrange
    \Auth::logout();

    // Act
    livewire(\App\Http\Livewire\Banks\CreditCards\Create::class)
        ->call('save')
        ->assertForbidden();

});

test('bank is required', function () {

    livewire(\App\Http\Livewire\Banks\CreditCards\Create::class)
        ->set('creditCard.bank', '')
        ->call('save')
        ->assertHasErrors(['creditCard.bank' => 'required'])
        ->assertNotEmitted('credit-card::created');

});

test('bank should be a string', function () {

    livewire(\App\Http\Livewire\Banks\CreditCards\Create::class)
        ->set('creditCard.bank', 123)
        ->call('save')
        ->assertHasErrors(['creditCard.bank' => 'string'])
        ->assertNotEmitted('credit-card::created');

});

test('bank should be have a max of 100 characters', function () {

    livewire(\App\Http\Livewire\Banks\CreditCards\Create::class)
        ->set('creditCard.bank', str_repeat('a', 101))
        ->call('save')
        ->assertHasErrors(['creditCard.bank' => 'max'])
        ->assertNotEmitted('credit-card::created');

});

test('bank should be have a min of 3 characters', function () {

    livewire(\App\Http\Livewire\Banks\CreditCards\Create::class)
        ->set('creditCard.bank', 'ab')
        ->call('save')
        ->assertHasErrors(['creditCard.bank' => 'min'])
        ->assertNotEmitted('credit-card::created');

});

test('number is required', function () {

    livewire(\App\Http\Livewire\Banks\CreditCards\Create::class)
        ->set('creditCard.number', '')
        ->call('save')
        ->assertHasErrors(['creditCard.number' => 'required'])
        ->assertNotEmitted('credit-card::created');

});

test('number should be a numeric', function () {

    livewire(\App\Http\Livewire\Banks\CreditCards\Create::class)
        ->set('creditCard.number', 'abc')
        ->call('save')
        ->assertHasErrors(['creditCard.number' => 'numeric'])
        ->assertNotEmitted('credit-card::created');

});

test('number should be have a max of 16 digits', function () {

    livewire(\App\Http\Livewire\Banks\CreditCards\Create::class)
        ->set('creditCard.number', (int)str_repeat('1', 17))
        ->call('save')
        ->assertHasErrors(['creditCard.number' => 'max_digits'])
        ->assertNotEmitted('credit-card::created');

});

test('number should be have a min of 16 digits', function () {

    livewire(\App\Http\Livewire\Banks\CreditCards\Create::class)
        ->set('creditCard.number', (int)str_repeat('1', 15))
        ->call('save')
        ->assertHasErrors(['creditCard.number' => 'min_digits'])
        ->assertNotEmitted('credit-card::created');

});

test('expiration is required', function () {

    livewire(\App\Http\Livewire\Banks\CreditCards\Create::class)
        ->set('creditCard.expiration', '')
        ->call('save')
        ->assertHasErrors(['creditCard.expiration' => 'required'])
        ->assertNotEmitted('credit-card::created');

});

test('expiration should be a string', function () {

    livewire(\App\Http\Livewire\Banks\CreditCards\Create::class)
        ->set('creditCard.expiration', 123)
        ->call('save')
        ->assertHasErrors(['creditCard.expiration' => 'string'])
        ->assertNotEmitted('credit-card::created');

});

test('expiration should be have a max of 7 characters', function () {

    livewire(\App\Http\Livewire\Banks\CreditCards\Create::class)
        ->set('creditCard.expiration', str_repeat('1', 8))
        ->call('save')
        ->assertHasErrors(['creditCard.expiration' => 'max'])
        ->assertNotEmitted('credit-card::created');

});

test('expiration should be have a min of 7 characters', function () {

    livewire(\App\Http\Livewire\Banks\CreditCards\Create::class)
        ->set('creditCard.expiration', str_repeat('1', 6))
        ->call('save')
        ->assertHasErrors(['creditCard.expiration' => 'min'])
        ->assertNotEmitted('credit-card::created');

});

test('cvv is required', function () {

    livewire(\App\Http\Livewire\Banks\CreditCards\Create::class)
        ->set('creditCard.cvv', '')
        ->call('save')
        ->assertHasErrors(['creditCard.cvv' => 'required'])
        ->assertNotEmitted('credit-card::created');

});

test('cvv should be a numeric', function () {

    livewire(\App\Http\Livewire\Banks\CreditCards\Create::class)
        ->set('creditCard.cvv', 'abc')
        ->call('save')
        ->assertHasErrors(['creditCard.cvv' => 'numeric'])
        ->assertNotEmitted('credit-card::created');

});

test('cvv should be have a max of 3 digits', function () {

    livewire(\App\Http\Livewire\Banks\CreditCards\Create::class)
        ->set('creditCard.cvv', 1111)
        ->call('save')
        ->assertHasErrors(['creditCard.cvv' => 'max_digits'])
        ->assertNotEmitted('credit-card::created');

});

test('cvv should be have a min of 3 digits', function () {

    livewire(\App\Http\Livewire\Banks\CreditCards\Create::class)
        ->set('creditCard.cvv', 11)
        ->call('save')
        ->assertHasErrors(['creditCard.cvv' => 'min_digits'])
        ->assertNotEmitted('credit-card::created');

});

test('limit is required', function () {

    livewire(\App\Http\Livewire\Banks\CreditCards\Create::class)
        ->set('creditCard.limit', '')
        ->call('save')
        ->assertHasErrors(['creditCard.limit' => 'required'])
        ->assertNotEmitted('credit-card::created');

});

test('limit should be a numeric', function () {

    livewire(\App\Http\Livewire\Banks\CreditCards\Create::class)
        ->set('creditCard.limit', 'abc')
        ->call('save')
        ->assertHasErrors(['creditCard.limit' => 'numeric'])
        ->assertNotEmitted('credit-card::created');

});

test('limit should be have a max of 10 digits', function () {

    livewire(\App\Http\Livewire\Banks\CreditCards\Create::class)
        ->set('creditCard.limit', 11111111111)
        ->call('save')
        ->assertHasErrors(['creditCard.limit' => 'max_digits'])
        ->assertNotEmitted('credit-card::created');

});

test('limit should be have a min of 2 digits', function () {

    livewire(\App\Http\Livewire\Banks\CreditCards\Create::class)
        ->set('creditCard.limit', 1)
        ->call('save')
        ->assertHasErrors(['creditCard.limit' => 'min_digits'])
        ->assertNotEmitted('credit-card::created');

});
