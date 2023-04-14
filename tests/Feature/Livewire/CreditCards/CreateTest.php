<?php

namespace Tests\Feature\Livewire\CreditCards;

use App\Http\Livewire\CreditCards;
use App\Models\User;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->actingAs($this->user);
});

it('should be able to create a credit card', function () {

    livewire(CreditCards\Create::class)
        ->set('creditCard.bank', 'Test')
        ->set('creditCard.number', '1234567890123456')
        ->set('creditCard.expiration', '12/2030')
        ->set('creditCard.cvv', '123')
        ->set('creditCard.limit', 1000)
        ->call('save')
        ->assertEmitted('credit-card::created');

    assertDatabaseHas('credit_cards', [
        'user_id' => $this->user->id,
        'bank' => 'Test',
        'number' => '1234567890123456',
        'expiration' => '12/2030',
        'cvv' => '123',
        'limit' => 1000,
    ]);

});

test('bank is required', function () {

    livewire(CreditCards\Create::class)
        ->set('creditCard.bank', '')
        ->call('save')
        ->assertHasErrors(['creditCard.bank' => 'required'])
        ->assertNotEmitted('credit-card::created');
//        ->assertSee(__('validation.required', ['attribute' => 'bank']));

});

test('bank should be a string', function () {

    livewire(CreditCards\Create::class)
        ->set('creditCard.bank', 123)
        ->call('save')
        ->assertHasErrors(['creditCard.bank' => 'string'])
        ->assertNotEmitted('credit-card::created');

});

test('bank should be have a max of 100 characters', function () {

    livewire(CreditCards\Create::class)
        ->set('creditCard.bank', str_repeat('a', 101))
        ->call('save')
        ->assertHasErrors(['creditCard.bank' => 'max'])
        ->assertNotEmitted('credit-card::created');

});

test('bank should be have a min of 3 characters', function () {

    livewire(CreditCards\Create::class)
        ->set('creditCard.bank', 'ab')
        ->call('save')
        ->assertHasErrors(['creditCard.bank' => 'min'])
        ->assertNotEmitted('credit-card::created');

});

test('number is required', function () {

    livewire(CreditCards\Create::class)
        ->set('creditCard.number', '')
        ->call('save')
        ->assertHasErrors(['creditCard.number' => 'required'])
        ->assertNotEmitted('credit-card::created');

});

test('number should be a string', function () {

    livewire(CreditCards\Create::class)
        ->set('creditCard.number', 123)
        ->call('save')
        ->assertHasErrors(['creditCard.number' => 'string'])
        ->assertNotEmitted('credit-card::created');

});

test('number should be have a max of 16 characters', function () {

    livewire(CreditCards\Create::class)
        ->set('creditCard.number', str_repeat('1', 17))
        ->call('save')
        ->assertHasErrors(['creditCard.number' => 'max'])
        ->assertNotEmitted('credit-card::created');

});

test('number should be have a min of 16 characters', function () {

    livewire(CreditCards\Create::class)
        ->set('creditCard.number', str_repeat('1', 15))
        ->call('save')
        ->assertHasErrors(['creditCard.number' => 'min'])
        ->assertNotEmitted('credit-card::created');

});

test('expiration is required', function () {

    livewire(CreditCards\Create::class)
        ->set('creditCard.expiration', '')
        ->call('save')
        ->assertHasErrors(['creditCard.expiration' => 'required'])
        ->assertNotEmitted('credit-card::created');

});

test('expiration should be a string', function () {

    livewire(CreditCards\Create::class)
        ->set('creditCard.expiration', 123)
        ->call('save')
        ->assertHasErrors(['creditCard.expiration' => 'string'])
        ->assertNotEmitted('credit-card::created');

});

test('expiration should be have a max of 7 characters', function () {

    livewire(CreditCards\Create::class)
        ->set('creditCard.expiration', str_repeat('1', 8))
        ->call('save')
        ->assertHasErrors(['creditCard.expiration' => 'max'])
        ->assertNotEmitted('credit-card::created');

});

test('expiration should be have a min of 7 characters', function () {

    livewire(CreditCards\Create::class)
        ->set('creditCard.expiration', str_repeat('1', 6))
        ->call('save')
        ->assertHasErrors(['creditCard.expiration' => 'min'])
        ->assertNotEmitted('credit-card::created');

});

test('cvv is required', function () {

    livewire(CreditCards\Create::class)
        ->set('creditCard.cvv', '')
        ->call('save')
        ->assertHasErrors(['creditCard.cvv' => 'required'])
        ->assertNotEmitted('credit-card::created');

});

test('cvv should be a numeric', function () {

    livewire(CreditCards\Create::class)
        ->set('creditCard.cvv', 'abc')
        ->call('save')
        ->assertHasErrors(['creditCard.cvv' => 'numeric'])
        ->assertNotEmitted('credit-card::created');

});

test('cvv should be have a max of 3 digits', function () {

    livewire(CreditCards\Create::class)
        ->set('creditCard.cvv', 1111)
        ->call('save')
        ->assertHasErrors(['creditCard.cvv' => 'max_digits'])
        ->assertNotEmitted('credit-card::created');

});

test('cvv should be have a min of 3 digits', function () {

    livewire(CreditCards\Create::class)
        ->set('creditCard.cvv', 11)
        ->call('save')
        ->assertHasErrors(['creditCard.cvv' => 'min_digits'])
        ->assertNotEmitted('credit-card::created');

});

test('limit is required', function () {

    livewire(CreditCards\Create::class)
        ->set('creditCard.limit', '')
        ->call('save')
        ->assertHasErrors(['creditCard.limit' => 'required'])
        ->assertNotEmitted('credit-card::created');

});

test('limit should be a numeric', function () {

    livewire(CreditCards\Create::class)
        ->set('creditCard.limit', 'abc')
        ->call('save')
        ->assertHasErrors(['creditCard.limit' => 'numeric'])
        ->assertNotEmitted('credit-card::created');

});

test('limit should be have a max of 10 digits', function () {

    livewire(CreditCards\Create::class)
        ->set('creditCard.limit', 11111111111)
        ->call('save')
        ->assertHasErrors(['creditCard.limit' => 'max_digits'])
        ->assertNotEmitted('credit-card::created');

});

test('limit should be have a min of 2 digits', function () {

    livewire(CreditCards\Create::class)
        ->set('creditCard.limit', 1)
        ->call('save')
        ->assertHasErrors(['creditCard.limit' => 'min_digits'])
        ->assertNotEmitted('credit-card::created');

});
