<?php

use App\Http\Livewire\Filament;
use App\Models\{CreditCard, User};
use Illuminate\Support\Str;

use function Pest\Laravel\{actingAs, assertDatabaseHas, get};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->createOne([
        'email' => 'teste@email.com',
    ]);

    $this->user->givePermissionTo('credit_card_update');

    $this->user->bankAccounts()->save(
        $this->creditCard = CreditCard::factory()->makeOne()
    );

    actingAs($this->user);

});

/* ###################################################################### */
/* RENDER PAGE */
/* ###################################################################### */
it('can render page', function () {

    livewire(Filament\CreditCardResource\Edit::class, ['record' => $this->creditCard])
        ->assertSuccessful();

})->group('renderPage');

it('can redirect to login if not authenticated', function () {

    Auth::logout();

    get(route('banks.credit-cards.edit', $this->creditCard->id))
        ->assertRedirect(route('login'));

})->group('renderPage');

/* ###################################################################### */
/* RENDER FORM */
/* ###################################################################### */
it('has a form', function () {

    livewire(Filament\CreditCardResource\Edit::class, ['record' => $this->creditCard])
        ->assertFormExists();

})->group('renderForm');

it('can display title of page', function () {

    livewire(Filament\CreditCardResource\Edit::class, ['record' => $this->creditCard])
        ->assertSeeHtml('Editar cartão de crédito');

})->group('renderForm');

/* ###################################################################### */
/* RENDER FORM FIELDS */
/* ###################################################################### */
it('has a bank field', function () {

    livewire(Filament\CreditCardResource\Edit::class, ['record' => $this->creditCard])
        ->assertFormFieldExists('bank');

})->group('renderFormFields');

it('has a number field', function () {

    livewire(Filament\CreditCardResource\Edit::class, ['record' => $this->creditCard])
        ->assertFormFieldExists('number');

})->group('renderFormFields');

it('has a expiration field', function () {

    livewire(Filament\CreditCardResource\Edit::class, ['record' => $this->creditCard])
        ->assertFormFieldExists('expiration');

})->group('renderFormFields');

it('has a cvv field', function () {

    livewire(Filament\CreditCardResource\Edit::class, ['record' => $this->creditCard])
        ->assertFormFieldExists('cvv');

})->group('renderFormFields');

it('has a limit field', function () {

    livewire(Filament\CreditCardResource\Edit::class, ['record' => $this->creditCard])
        ->assertFormFieldExists('limit');

})->group('renderFormFields');

/* ###################################################################### */
/* CORRECTLY FILL FORM */
/* ###################################################################### */
it('can fill bank field correctly', function () {

    livewire(Filament\CreditCardResource\Edit::class, ['record' => $this->creditCard])
        ->assertFormSet([
            'bank' => $this->creditCard->bank,
        ]);

})->group('canFillForm');

it('can fill number field correctly', function () {

    livewire(Filament\CreditCardResource\Edit::class, ['record' => $this->creditCard])
        ->assertFormSet([
            'number' => $this->creditCard->number,
        ]);

})->group('canFillForm');

it('can fill expiration field correctly', function () {

    livewire(Filament\CreditCardResource\Edit::class, ['record' => $this->creditCard])
        ->assertFormSet([
            'expiration' => $this->creditCard->expiration,
        ]);

})->group('canFillForm');

it('can fill cvv field correctly', function () {

    livewire(Filament\CreditCardResource\Edit::class, ['record' => $this->creditCard])
        ->assertFormSet([
            'cvv' => $this->creditCard->cvv,
        ]);

})->group('canFillForm');

it('can fill limit field correctly', function () {

    livewire(Filament\CreditCardResource\Edit::class, ['record' => $this->creditCard])
        ->assertFormSet([
            'limit' => number_format($this->creditCard->limit, 2, ',', '.'),
        ]);

})->group('canFillForm');

/* ###################################################################### */
/* RENDER FORM BUTTONS */
/* ###################################################################### */
it('can render save button', function () {

    livewire(Filament\CreditCardResource\Edit::class, ['record' => $this->creditCard])
        ->assertSeeHtml('Salvar');

})->group('renderFormButtons');

it('can render cancel button', function () {

    livewire(Filament\CreditCardResource\Edit::class, ['record' => $this->creditCard])
        ->assertSeeHtml('Cancelar');

})->group('renderFormButtons');

/* ###################################################################### */
/* VALIDATE FORM FIELDS */
/* ###################################################################### */
it('can validate bank', function () {

    // Required
    livewire(Filament\CreditCardResource\Edit::class, ['record' => $this->creditCard])
        ->fillForm([
            'bank' => null,
        ])
        ->call('update')
        ->assertHasFormErrors(['bank' => 'required']);

    // String
    livewire(Filament\CreditCardResource\Edit::class, ['record' => $this->creditCard])
        ->fillForm([
            'bank' => 123,
        ])
        ->call('update')
        ->assertHasFormErrors(['bank' => 'string']);

    // Min 3
    livewire(Filament\CreditCardResource\Edit::class, ['record' => $this->creditCard])
        ->fillForm([
            'bank' => Str::random(2),
        ])
        ->call('update')
        ->assertHasFormErrors(['bank' => 'min']);

    // Max 100
    livewire(Filament\CreditCardResource\Edit::class, ['record' => $this->creditCard])
        ->fillForm([
            'bank' => Str::random(101),
        ])
        ->call('update')
        ->assertHasFormErrors(['bank' => 'max']);

})->group('formFieldsValidation');

it('can validate number', function () {

    // Required
    livewire(Filament\CreditCardResource\Edit::class, ['record' => $this->creditCard])
        ->fillForm([
            'number' => null,
        ])
        ->call('update')
        ->assertHasFormErrors(['number' => 'required']);

    // Numeric
    livewire(Filament\CreditCardResource\Edit::class, ['record' => $this->creditCard])
        ->fillForm([
            'number' => 'abc',
        ])
        ->call('update')
        ->assertHasFormErrors(['number' => 'numeric']);

    // Min digits 16
    livewire(Filament\CreditCardResource\Edit::class, ['record' => $this->creditCard])
        ->fillForm([
            'number' => (float)str_repeat('1', 15),
        ])
        ->call('update')
        ->assertHasFormErrors(['number' => 'min_digits']);

    // Max digits 16
    livewire(Filament\CreditCardResource\Edit::class, ['record' => $this->creditCard])
        ->fillForm([
            'number' => (float)str_repeat('1', 17),
        ])
        ->call('update')
        ->assertHasFormErrors(['number' => 'max_digits']);

    // Unique
    $creditCard2 = CreditCard::factory()->createOne();

    livewire(Filament\CreditCardResource\Edit::class, ['record' => $this->creditCard])
        ->fillForm([
            'number' => str($creditCard2->number)->replaceMatches('/\D/', '')->toString(),
        ])
        ->call('update')
        ->assertHasFormErrors(['number' => 'unique']);

    // Ignore unique
    livewire(Filament\CreditCardResource\Edit::class, ['record' => $this->creditCard])
        ->fillForm([
            'number' => str($this->creditCard->number)->replaceMatches('/\D/', '')->toString(),
        ])
        ->call('update')
        ->assertHasNoFormErrors(['number' => 'unique']);

})->group('formFieldsValidation');

it('can validate expiration', function () {

    // Required
    livewire(Filament\CreditCardResource\Edit::class, ['record' => $this->creditCard])
        ->fillForm([
            'expiration' => null,
        ])
        ->call('update')
        ->assertHasFormErrors(['expiration' => 'required']);

    // String
    livewire(Filament\CreditCardResource\Edit::class, ['record' => $this->creditCard])
        ->fillForm([
            'expiration' => 123,
        ])
        ->call('update')
        ->assertHasFormErrors(['expiration' => 'string']);

    // Min 4
    livewire(Filament\CreditCardResource\Edit::class, ['record' => $this->creditCard])
        ->fillForm([
            'expiration' => str_repeat('1', 3),
        ])
        ->call('update')
        ->assertHasFormErrors(['expiration' => 'min']);

    // Max 4
    livewire(Filament\CreditCardResource\Edit::class, ['record' => $this->creditCard])
        ->fillForm([
            'expiration' => str_repeat('1', 5),
        ])
        ->call('update')
        ->assertHasFormErrors(['expiration' => 'max']);

})->group('formFieldsValidation');

it('can validate cvv', function () {

    // Required
    livewire(Filament\CreditCardResource\Edit::class, ['record' => $this->creditCard])
        ->fillForm([
            'cvv' => null,
        ])
        ->call('update')
        ->assertHasFormErrors(['cvv' => 'required']);

    // Numeric
    livewire(Filament\CreditCardResource\Edit::class, ['record' => $this->creditCard])
        ->fillForm([
            'cvv' => 'abc',
        ])
        ->call('update')
        ->assertHasFormErrors(['cvv' => 'numeric']);

    // Min digits 3
    livewire(Filament\CreditCardResource\Edit::class, ['record' => $this->creditCard])
        ->fillForm([
            'cvv' => (float)str_repeat('1', 2),
        ])
        ->call('update')
        ->assertHasFormErrors(['cvv' => 'min_digits']);

    // Max digits 3
    livewire(Filament\CreditCardResource\Edit::class, ['record' => $this->creditCard])
        ->fillForm([
            'cvv' => (float)str_repeat('1', 4),
        ])
        ->call('update')
        ->assertHasFormErrors(['cvv' => 'max_digits']);

})->group('formFieldsValidation');

it('can validate limit', function () {

    // Required
    livewire(Filament\CreditCardResource\Edit::class, ['record' => $this->creditCard])
        ->fillForm([
            'limit' => null,
        ])
        ->call('update')
        ->assertHasFormErrors(['limit' => 'required']);

})->group('formFieldsValidation');

/* ###################################################################### */
/* CANNOT HAS PERMISSION */
/* ###################################################################### */
it('cannot render page if not has permission', function () {

    // Arrange
    $this->user->revokePermissionTo('credit_card_update');

    // Act
    livewire(Filament\CreditCardResource\Edit::class, ['record' => $this->creditCard])
        ->assertForbidden();

})->group('cannotHasPermission');

/* ###################################################################### */
/* UPDATE CREDIT CARD */
/* ###################################################################### */
it('can update credit cards', function () {

    // Arrange
    $newData = CreditCard::factory()->makeOne();

    // Act
    livewire(Filament\CreditCardResource\Edit::class, ['record' => $this->creditCard])
        ->fillForm([
            'bank'       => $newData->bank,
            'number'     => str($newData->number)->replaceMatches('/\D/', '')->toString(),
            'expiration' => str($newData->expiration)->replaceMatches('/\D/', '')->toString(),
            'cvv'        => $newData->cvv,
            'limit'      => number_format($newData->limit, 2, ',', '.'),
        ])
        ->call('update')
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

})->group('updateCreditCard');

it('can update credit cards and update correctly remaining limit when has spending', function () {

    // Arrange
    $newData = CreditCard::factory()->makeOne([
        'limit' => 1000,
    ]);

    $this->creditCard->spendings()->create([
        'description' => 'Test',
        'amount'      => 100,
    ]);

    // Act
    livewire(Filament\CreditCardResource\Edit::class, ['record' => $this->creditCard])
        ->fillForm([
            'bank'       => $newData->bank,
            'number'     => str($newData->number)->replaceMatches('/\D/', '')->toString(),
            'expiration' => str($newData->expiration)->replaceMatches('/\D/', '')->toString(),
            'cvv'        => $newData->cvv,
            'limit'      => number_format($newData->limit, 2, ',', '.'),
        ])
        ->call('update')
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
        'remaining_limit' => 900,
    ]);

})->group('updateCreditCard');
