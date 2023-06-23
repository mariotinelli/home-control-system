<?php

namespace Tests\Feature\Livewire\CreditCards;

use App\Http\Livewire\Filament;
use App\Models\{CreditCard, User};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use function Pest\Laravel\{actingAs, assertDatabaseHas, get};
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->user->givePermissionTo('credit_card_create');

    actingAs($this->user);
});

/* ###################################################################### */
/* RENDER PAGE */
/* ###################################################################### */
it('can render page', function () {

    livewire(Filament\CreditCardResource\Create::class)
        ->assertSuccessful();

})->group('renderPage');

it('can redirect to login if not authenticated', function () {

    Auth::logout();

    get(route('banks.credit-cards.create'))
        ->assertRedirect(route('login'));

})->group('renderPage');

/* ###################################################################### */
/* RENDER FORM */
/* ###################################################################### */
it('has a form', function () {

    livewire(Filament\CreditCardResource\Create::class)
        ->assertFormExists();

})->group('renderForm');

it('can display title of page', function () {

    livewire(Filament\CreditCardResource\Create::class)
        ->assertSeeHtml('Criar cartão de crédito');

})->group('renderForm');

/* ###################################################################### */
/* RENDER FORM FIELDS */
/* ###################################################################### */
it('has a bank field', function () {

    livewire(Filament\CreditCardResource\Create::class)
        ->assertFormFieldExists('bank');

})->group('renderFormFields');

it('has a number field', function () {

    livewire(Filament\CreditCardResource\Create::class)
        ->assertFormFieldExists('number');

})->group('renderFormFields');

it('has a expiration field', function () {

    livewire(Filament\CreditCardResource\Create::class)
        ->assertFormFieldExists('expiration');

})->group('renderFormFields');

it('has a cvv field', function () {

    livewire(Filament\CreditCardResource\Create::class)
        ->assertFormFieldExists('cvv');

})->group('renderFormFields');

it('has a limit field', function () {

    livewire(Filament\CreditCardResource\Create::class)
        ->assertFormFieldExists('limit');

})->group('renderFormFields');

/* ###################################################################### */
/* RENDER FORM BUTTONS */
/* ###################################################################### */
it('can render create button', function () {

    livewire(Filament\CreditCardResource\Create::class)
        ->assertSeeHtml('Criar');

})->group('renderFormButtons');

it('can render create and stay button', function () {

    livewire(Filament\CreditCardResource\Create::class)
        ->assertSeeHtml('Criar e criar novo');

})->group('renderFormButtons');

it('can render cancel button', function () {

    livewire(Filament\CreditCardResource\Create::class)
        ->assertSeeHtml('Cancelar');

})->group('renderFormButtons');

/* ###################################################################### */
/* VALIDATE FORM FIELDS */
/* ###################################################################### */
it('can validate bank', function () {

    // Required
    livewire(Filament\CreditCardResource\Create::class)
        ->fillForm([
            'bank' => null,
        ])
        ->call('store')
        ->assertHasFormErrors(['bank' => 'required']);

    // String
    livewire(Filament\CreditCardResource\Create::class)
        ->fillForm([
            'bank' => 123,
        ])
        ->call('store')
        ->assertHasFormErrors(['bank' => 'string']);

    // Min 3
    livewire(Filament\CreditCardResource\Create::class)
        ->fillForm([
            'bank' => Str::random(2),
        ])
        ->call('store')
        ->assertHasFormErrors(['bank' => 'min']);

    // Max 100
    livewire(Filament\CreditCardResource\Create::class)
        ->fillForm([
            'bank' => Str::random(101),
        ])
        ->call('store')
        ->assertHasFormErrors(['bank' => 'max']);

})->group('formFieldsValidation');

it('can validate number', function () {

    // Required
    livewire(Filament\CreditCardResource\Create::class)
        ->fillForm([
            'number' => null,
        ])
        ->call('store')
        ->assertHasFormErrors(['number' => 'required']);

    // Numeric
    livewire(Filament\CreditCardResource\Create::class)
        ->fillForm([
            'number' => 'abc',
        ])
        ->call('store')
        ->assertHasFormErrors(['number' => 'numeric']);

    // Min digits 16
    livewire(Filament\CreditCardResource\Create::class)
        ->fillForm([
            'number' => (float)str_repeat('1', 15),
        ])
        ->call('store')
        ->assertHasFormErrors(['number' => 'min_digits']);

    // Max digits 16
    livewire(Filament\CreditCardResource\Create::class)
        ->fillForm([
            'number' => (float)str_repeat('1', 17),
        ])
        ->call('store')
        ->assertHasFormErrors(['number' => 'max_digits']);

    // Unique
    $creditCard = CreditCard::factory()->createOne();

    livewire(Filament\CreditCardResource\Create::class)
        ->fillForm([
            'number' => str($creditCard->number)->replaceMatches('/\D/', '')->toString(),
        ])
        ->call('store')
        ->assertHasFormErrors(['number' => 'unique']);

})->group('formFieldsValidation');

it('can validate expiration', function () {

    // Required
    livewire(Filament\CreditCardResource\Create::class)
        ->fillForm([
            'expiration' => null,
        ])
        ->call('store')
        ->assertHasFormErrors(['expiration' => 'required']);

    // String
    livewire(Filament\CreditCardResource\Create::class)
        ->fillForm([
            'expiration' => 123,
        ])
        ->call('store')
        ->assertHasFormErrors(['expiration' => 'string']);

    // Min 4
    livewire(Filament\CreditCardResource\Create::class)
        ->fillForm([
            'expiration' => str_repeat('1', 3),
        ])
        ->call('store')
        ->assertHasFormErrors(['expiration' => 'min']);

    // Max 4
    livewire(Filament\CreditCardResource\Create::class)
        ->fillForm([
            'expiration' => str_repeat('1', 5),
        ])
        ->call('store')
        ->assertHasFormErrors(['expiration' => 'max']);

})->group('formFieldsValidation');

it('can validate cvv', function () {

    // Required
    livewire(Filament\CreditCardResource\Create::class)
        ->fillForm([
            'cvv' => null,
        ])
        ->call('store')
        ->assertHasFormErrors(['cvv' => 'required']);

    // Numeric
    livewire(Filament\CreditCardResource\Create::class)
        ->fillForm([
            'cvv' => 'abc',
        ])
        ->call('store')
        ->assertHasFormErrors(['cvv' => 'numeric']);

    // Min digits 3
    livewire(Filament\CreditCardResource\Create::class)
        ->fillForm([
            'cvv' => (float)str_repeat('1', 2),
        ])
        ->call('store')
        ->assertHasFormErrors(['cvv' => 'min_digits']);

    // Max digits 3
    livewire(Filament\CreditCardResource\Create::class)
        ->fillForm([
            'cvv' => (float)str_repeat('1', 4),
        ])
        ->call('store')
        ->assertHasFormErrors(['cvv' => 'max_digits']);

})->group('formFieldsValidation');

it('can validate limit', function () {

    // Required
    livewire(Filament\CreditCardResource\Create::class)
        ->fillForm([
            'limit' => null,
        ])
        ->call('store')
        ->assertHasFormErrors(['limit' => 'required']);

})->group('formFieldsValidation');

/* ###################################################################### */
/* CANNOT HAS PERMISSION */
/* ###################################################################### */
it('cannot render page if not has permission', function () {

    // Arrange
    $this->user->revokePermissionTo('credit_card_create');

    // Act
    livewire(Filament\CreditCardResource\Create::class)
        ->assertForbidden();

})->group('cannotHasPermission');

it("cannot able to create a credit card if not has permission", function () {

    // Act
    $lw = livewire(Filament\CreditCardResource\Create::class);

    $this->user->revokePermissionTo('credit_card_create');

    $lw->call('store')
        ->assertForbidden();

})->group('cannotHasPermission');

/* ###################################################################### */
/* CREATE CREDIT CARD */
/* ###################################################################### */
it('can create a credit cards', function () {

    // Arrange
    $newData = CreditCard::factory()->makeOne();

    // Act
    livewire(Filament\CreditCardResource\Create::class)
        ->fillForm([
            'bank'       => $newData->bank,
            'number'     => str($newData->number)->replaceMatches('/\D/', '')->toString(),
            'expiration' => str($newData->expiration)->replaceMatches('/\D/', '')->toString(),
            'cvv'        => $newData->cvv,
            'limit'      => number_format($newData->limit, 2, ',', '.'),
        ])
        ->call('store')
        ->assertHasNoFormErrors()
        ->assertNotified()
        ->assertRedirect(route('banks.credit-cards.index'));

    // Assert
    assertDatabaseHas('credit_cards', [
        'user_id'         => $this->user->id,
        'bank'            => $newData->bank,
        'number'          => str($newData->number)->replaceMatches('/\D/', '')->toString(),
        'expiration'      => $newData->expiration,
        'cvv'             => $newData->cvv,
        'limit'           => $newData->limit,
        'remaining_limit' => $newData->limit,
    ]);

})->group('createCreditCard');

it('can create a credit card and continue in this page', function () {

    // Arrange
    $newData = CreditCard::factory()->makeOne();

    // Act
    livewire(Filament\CreditCardResource\Create::class)
        ->fillForm([
            'bank'       => $newData->bank,
            'number'     => str($newData->number)->replaceMatches('/\D/', '')->toString(),
            'expiration' => str($newData->expiration)->replaceMatches('/\D/', '')->toString(),
            'cvv'        => $newData->cvv,
            'limit'      => number_format($newData->limit, 2, ',', '.'),
        ])
        ->call('storeAndStay')
        ->assertHasNoFormErrors()
        ->assertNotified()
        ->assertFormSet([
            'bank'       => null,
            'number'     => null,
            'expiration' => null,
            'cvv'        => null,
            'limit'      => '0,00',
        ]);

    // Assert
    assertDatabaseHas('credit_cards', [
        'user_id'         => $this->user->id,
        'bank'            => $newData->bank,
        'number'          => str($newData->number)->replaceMatches('/\D/', '')->toString(),
        'expiration'      => $newData->expiration,
        'cvv'             => $newData->cvv,
        'limit'           => $newData->limit,
        'remaining_limit' => $newData->limit,
    ]);

})->group('createCreditCard');
