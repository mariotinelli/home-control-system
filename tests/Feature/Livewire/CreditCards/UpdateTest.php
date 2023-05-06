<?php

namespace Tests\Feature\Livewire\CreditCards;

use App\Http\Livewire\CreditCards;
use App\Models\{CreditCard, User};

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->user->givePermissionTo(getUserPermissions());

    $this->creditCard = CreditCard::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user);
});

it('should be able edit a credit card', function () {

    livewire(CreditCards\Update::class, ['creditCard' => $this->creditCard])
        ->set('creditCard.bank', 'Test Update')
        ->set('creditCard.number', '2547896321456987')
        ->set('creditCard.expiration', '11/2025')
        ->set('creditCard.cvv', '234')
        ->set('creditCard.limit', 5000)
        ->call('save')
        ->assertEmitted('credit-card::updated');

    assertDatabaseHas('credit_cards', [
        'user_id'         => $this->user->id,
        'bank'            => 'Test Update',
        'number'          => '2547896321456987',
        'expiration'      => '11/2025',
        'cvv'             => '234',
        'limit'           => 5000,
        'remaining_limit' => $this->creditCard->remaining_limit,
    ]);

});

it('should be able to edit a credit card only the card owner', function () {

    $this->actingAs(User::factory()->create());

    livewire(CreditCards\Update::class, ['creditCard' => $this->creditCard])
        ->call('save')
        ->assertForbidden();

    $this->actingAs($this->user);

    livewire(CreditCards\Update::class, ['creditCard' => $this->creditCard])
        ->set('creditCard.bank', 'Test Update')
        ->set('creditCard.number', '2547896321456987')
        ->set('creditCard.expiration', '11/2025')
        ->set('creditCard.cvv', '234')
        ->set('creditCard.limit', 5000)
        ->call('save')
        ->assertEmitted('credit-card::updated');

    assertDatabaseHas('credit_cards', [
        'user_id'    => $this->user->id,
        'bank'       => 'Test Update',
        'number'     => '2547896321456987',
        'expiration' => '11/2025',
        'cvv'        => '234',
        'limit'      => 5000,
    ]);
});

test('bank is required', function () {

    livewire(CreditCards\Update::class, ['creditCard' => $this->creditCard])
        ->set('creditCard.bank', '')
        ->call('save')
        ->assertHasErrors(['creditCard.bank' => 'required'])
        ->assertNotEmitted('credit-card::created');

});

test('bank should be a string', function () {

    livewire(CreditCards\Update::class, ['creditCard' => $this->creditCard])
        ->set('creditCard.bank', 123)
        ->call('save')
        ->assertHasErrors(['creditCard.bank' => 'string'])
        ->assertNotEmitted('credit-card::created');

});

test('bank should be have a max of 100 characters', function () {

    livewire(CreditCards\Update::class, ['creditCard' => $this->creditCard])
        ->set('creditCard.bank', str_repeat('a', 101))
        ->call('save')
        ->assertHasErrors(['creditCard.bank' => 'max'])
        ->assertNotEmitted('credit-card::created');

});

test('bank should be have a min of 3 characters', function () {

    livewire(CreditCards\Update::class, ['creditCard' => $this->creditCard])
        ->set('creditCard.bank', 'ab')
        ->call('save')
        ->assertHasErrors(['creditCard.bank' => 'min'])
        ->assertNotEmitted('credit-card::created');

});

test('number is required', function () {

    livewire(CreditCards\Update::class, ['creditCard' => $this->creditCard])
        ->set('creditCard.number', '')
        ->call('save')
        ->assertHasErrors(['creditCard.number' => 'required'])
        ->assertNotEmitted('credit-card::created');

});

test('number should be a string', function () {

    livewire(CreditCards\Update::class, ['creditCard' => $this->creditCard])
        ->set('creditCard.number', 123)
        ->call('save')
        ->assertHasErrors(['creditCard.number' => 'string'])
        ->assertNotEmitted('credit-card::created');

});

test('number should be have a max of 16 characters', function () {

    livewire(CreditCards\Update::class, ['creditCard' => $this->creditCard])
        ->set('creditCard.number', str_repeat('1', 17))
        ->call('save')
        ->assertHasErrors(['creditCard.number' => 'max'])
        ->assertNotEmitted('credit-card::created');

});

test('number should be have a min of 16 characters', function () {

    livewire(CreditCards\Update::class, ['creditCard' => $this->creditCard])
        ->set('creditCard.number', str_repeat('1', 15))
        ->call('save')
        ->assertHasErrors(['creditCard.number' => 'min'])
        ->assertNotEmitted('credit-card::created');

});

test('expiration is required', function () {

    livewire(CreditCards\Update::class, ['creditCard' => $this->creditCard])
        ->set('creditCard.expiration', '')
        ->call('save')
        ->assertHasErrors(['creditCard.expiration' => 'required'])
        ->assertNotEmitted('credit-card::created');

});

test('expiration should be a string', function () {

    livewire(CreditCards\Update::class, ['creditCard' => $this->creditCard])
        ->set('creditCard.expiration', 123)
        ->call('save')
        ->assertHasErrors(['creditCard.expiration' => 'string'])
        ->assertNotEmitted('credit-card::created');

});

test('expiration should be have a max of 7 characters', function () {

    livewire(CreditCards\Update::class, ['creditCard' => $this->creditCard])
        ->set('creditCard.expiration', str_repeat('1', 8))
        ->call('save')
        ->assertHasErrors(['creditCard.expiration' => 'max'])
        ->assertNotEmitted('credit-card::created');

});

test('expiration should be have a min of 7 characters', function () {

    livewire(CreditCards\Update::class, ['creditCard' => $this->creditCard])
        ->set('creditCard.expiration', str_repeat('1', 6))
        ->call('save')
        ->assertHasErrors(['creditCard.expiration' => 'min'])
        ->assertNotEmitted('credit-card::created');

});

test('cvv is required', function () {

    livewire(CreditCards\Update::class, ['creditCard' => $this->creditCard])
        ->set('creditCard.cvv', '')
        ->call('save')
        ->assertHasErrors(['creditCard.cvv' => 'required'])
        ->assertNotEmitted('credit-card::created');

});

test('cvv should be a numeric', function () {

    livewire(CreditCards\Update::class, ['creditCard' => $this->creditCard])
        ->set('creditCard.cvv', 'abc')
        ->call('save')
        ->assertHasErrors(['creditCard.cvv' => 'numeric'])
        ->assertNotEmitted('credit-card::created');

});

test('cvv should be have a max of 3 digits', function () {

    livewire(CreditCards\Update::class, ['creditCard' => $this->creditCard])
        ->set('creditCard.cvv', 1111)
        ->call('save')
        ->assertHasErrors(['creditCard.cvv' => 'max_digits'])
        ->assertNotEmitted('credit-card::created');

});

test('cvv should be have a min of 3 digits', function () {

    livewire(CreditCards\Update::class, ['creditCard' => $this->creditCard])
        ->set('creditCard.cvv', 11)
        ->call('save')
        ->assertHasErrors(['creditCard.cvv' => 'min_digits'])
        ->assertNotEmitted('credit-card::created');

});

test('limit is required', function () {

    livewire(CreditCards\Update::class, ['creditCard' => $this->creditCard])
        ->set('creditCard.limit', '')
        ->call('save')
        ->assertHasErrors(['creditCard.limit' => 'required'])
        ->assertNotEmitted('credit-card::created');

});

test('limit should be a numeric', function () {

    livewire(CreditCards\Update::class, ['creditCard' => $this->creditCard])
        ->set('creditCard.limit', 'abc')
        ->call('save')
        ->assertHasErrors(['creditCard.limit' => 'numeric'])
        ->assertNotEmitted('credit-card::created');

});

test('limit should be have a max of 10 digits', function () {

    livewire(CreditCards\Update::class, ['creditCard' => $this->creditCard])
        ->set('creditCard.limit', 11111111111)
        ->call('save')
        ->assertHasErrors(['creditCard.limit' => 'max_digits'])
        ->assertNotEmitted('credit-card::created');

});

test('limit should be have a min of 2 digits', function () {

    livewire(CreditCards\Update::class, ['creditCard' => $this->creditCard])
        ->set('creditCard.limit', 1)
        ->call('save')
        ->assertHasErrors(['creditCard.limit' => 'min_digits'])
        ->assertNotEmitted('credit-card::created');

});
