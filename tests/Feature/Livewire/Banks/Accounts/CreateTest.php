<?php

namespace Tests\Feature\Livewire\BankAccounts;

use App\Http\Livewire\Banks;
use App\Models\{BankAccount, User};
use Auth;

use function Pest\Laravel\{actingAs, assertDatabaseHas, get};
use function Pest\Livewire\livewire;

use Str;

beforeEach(function () {

    $this->user = User::factory()->createOne([
        'email' => 'teste@email.com',
    ]);

    $this->user->givePermissionTo('bank_account_create');

    actingAs($this->user);

});

/* ###################################################################### */
/* RENDER PAGE */
/* ###################################################################### */
it('can render page', function () {

    livewire(Banks\Accounts\Create::class)
        ->assertSuccessful();

})->group('renderPage');

it('can redirect to login if not authenticated', function () {

    Auth::logout();

    get(route('banks.accounts.create'))
        ->assertRedirect(route('login'));

})->group('renderPage');

/* ###################################################################### */
/* RENDER FORM */
/* ###################################################################### */
it('has a form', function () {

    livewire(Banks\Accounts\Create::class)
        ->assertFormExists();

})->group('renderForm');

it('can display title of page', function () {

    livewire(Banks\Accounts\Create::class)
        ->assertSeeHtml('Criar conta bancária');

})->group('renderForm');

/* ###################################################################### */
/* RENDER FORM FIELDS */
/* ###################################################################### */
it('has a bank name field', function () {

    livewire(Banks\Accounts\Create::class)
        ->assertFormFieldExists('bank_name');

})->group('renderFormFields');

it('has a type field', function () {

    livewire(Banks\Accounts\Create::class)
        ->assertFormFieldExists('type');

})->group('renderFormFields');

it('has a number field', function () {

    livewire(Banks\Accounts\Create::class)
        ->assertFormFieldExists('number');

})->group('renderFormFields');

it('has a digit field', function () {

    livewire(Banks\Accounts\Create::class)
        ->assertFormFieldExists('digit');

})->group('renderFormFields');

it('has a agency number field', function () {

    livewire(Banks\Accounts\Create::class)
        ->assertFormFieldExists('agency_number');

})->group('renderFormFields');

it('has a agency digit field', function () {

    livewire(Banks\Accounts\Create::class)
        ->assertFormFieldExists('agency_digit');

})->group('renderFormFields');

it('has a balance field', function () {

    livewire(Banks\Accounts\Create::class)
        ->assertFormFieldExists('balance');

})->group('renderFormFields');

/* ###################################################################### */
/* RENDER FORM BUTTONS */
/* ###################################################################### */
it('can render create button', function () {

    livewire(Banks\Accounts\Create::class)
        ->assertSeeHtml('Criar');

})->group('renderFormButtons');

it('can render create and stay button', function () {

    livewire(Banks\Accounts\Create::class)
        ->assertSeeHtml('Criar e criar novo');

})->group('renderFormButtons');

it('can render cancel button', function () {

    livewire(Banks\Accounts\Create::class)
        ->assertSeeHtml('Cancelar');

})->group('renderFormButtons');

/* ###################################################################### */
/* VALIDATE FORM FIELDS */
/* ###################################################################### */
it('can validate bank name', function () {

    // Required
    livewire(Banks\Accounts\Create::class)
        ->fillForm([
            'bank_name' => null,
        ])
        ->call('store')
        ->assertHasFormErrors(['bank_name' => 'required']);

    // String
    livewire(Banks\Accounts\Create::class)
        ->fillForm([
            'bank_name' => 123,
        ])
        ->call('store')
        ->assertHasFormErrors(['bank_name' => 'string']);

    // Min 3
    livewire(Banks\Accounts\Create::class)
        ->fillForm([
            'bank_name' => Str::random(2),
        ])
        ->call('store')
        ->assertHasFormErrors(['bank_name' => 'min']);

    // Max 100
    livewire(Banks\Accounts\Create::class)
        ->fillForm([
            'bank_name' => Str::random(101),
        ])
        ->call('store')
        ->assertHasFormErrors(['bank_name' => 'max']);

})->group('formFieldsValidation');

it('can validate type', function () {

    // Required
    livewire(Banks\Accounts\Create::class)
        ->fillForm([
            'type' => null,
        ])
        ->call('store')
        ->assertHasFormErrors(['type' => 'required']);

    // String
    livewire(Banks\Accounts\Create::class)
        ->fillForm([
            'type' => 123,
        ])
        ->call('store')
        ->assertHasFormErrors(['type' => 'string']);

    // In
    livewire(Banks\Accounts\Create::class)
        ->fillForm([
            'type' => 'invalid',
        ])
        ->call('store')
        ->assertHasFormErrors(['type' => 'in']);

})->group('formFieldsValidation');

it('can validate number', function () {

    // Required
    livewire(Banks\Accounts\Create::class)
        ->fillForm([
            'number' => null,
        ])
        ->call('store')
        ->assertHasFormErrors(['number' => 'required']);

    // Numeric
    livewire(Banks\Accounts\Create::class)
        ->fillForm([
            'number' => 'abc',
        ])
        ->call('store')
        ->assertHasFormErrors(['number' => 'numeric']);

    // Min digits 5
    livewire(Banks\Accounts\Create::class)
        ->fillForm([
            'number' => (float)str_repeat('1', 4),
        ])
        ->call('store')
        ->assertHasFormErrors(['number' => 'min_digits']);

    // Max digits 20
    livewire(Banks\Accounts\Create::class)
        ->fillForm([
            'number' => (float)str_repeat('1', 21),
        ])
        ->call('store')
        ->assertHasFormErrors(['number' => 'max_digits']);

    // Unique
    $bankAccount = BankAccount::factory()->createOne();

    livewire(Banks\Accounts\Create::class)
        ->fillForm([
            'number' => $bankAccount->number,
        ])
        ->call('store')
        ->assertHasFormErrors(['number' => 'unique']);

})->group('formFieldsValidation');

it('can validate digit', function () {

    // Required
    livewire(Banks\Accounts\Create::class)
        ->fillForm([
            'digit' => null,
        ])
        ->call('store')
        ->assertHasFormErrors(['digit' => 'required']);

    // Numeric
    livewire(Banks\Accounts\Create::class)
        ->fillForm([
            'digit' => 'abc',
        ])
        ->call('store')
        ->assertHasFormErrors(['digit' => 'numeric']);

    // Max digits 1
    livewire(Banks\Accounts\Create::class)
        ->fillForm([
            'digit' => 12,
        ])
        ->call('store')
        ->assertHasFormErrors(['digit' => 'max_digits']);

})->group('formFieldsValidation');

it('can validate agency number', function () {

    // Required
    livewire(Banks\Accounts\Create::class)
        ->fillForm([
            'agency_number' => null,
        ])
        ->call('store')
        ->assertHasFormErrors(['agency_number' => 'required']);

    // Numeric
    livewire(Banks\Accounts\Create::class)
        ->fillForm([
            'agency_number' => 'abc',
        ])
        ->call('store')
        ->assertHasFormErrors(['agency_number' => 'numeric']);

    // Min digits 4
    livewire(Banks\Accounts\Create::class)
        ->fillForm([
            'agency_number' => (float)str_repeat('1', 3),
        ])
        ->call('store')
        ->assertHasFormErrors(['agency_number' => 'min_digits']);

    // Max digits 4
    livewire(Banks\Accounts\Create::class)
        ->fillForm([
            'agency_number' => (float)str_repeat('1', 5),
        ])
        ->call('store')
        ->assertHasFormErrors(['agency_number' => 'max_digits']);

})->group('formFieldsValidation');

it('can validate agency digit', function () {

    // Nullable
    livewire(Banks\Accounts\Create::class)
        ->fillForm([
            'agency_digit' => null,
        ])
        ->call('store')
        ->assertHasNoFormErrors(['agency_digit']);

    // Numeric
    livewire(Banks\Accounts\Create::class)
        ->fillForm([
            'agency_digit' => 'abc',
        ])
        ->call('store')
        ->assertHasFormErrors(['agency_digit' => 'numeric']);

    // Max digits 1
    livewire(Banks\Accounts\Create::class)
        ->fillForm([
            'agency_digit' => 12,
        ])
        ->call('store')
        ->assertHasFormErrors(['agency_digit' => 'max_digits']);

})->group('formFieldsValidation');

it('can validate balance', function () {

    // Required
    livewire(Banks\Accounts\Create::class)
        ->fillForm([
            'balance' => null,
        ])
        ->call('store')
        ->assertHasFormErrors(['balance' => 'required']);

})->group('formFieldsValidation');

/* ###################################################################### */
/* CANNOT HAS PERMISSION */
/* ###################################################################### */
it('cannot render page if not has permission', function () {

    // Arrange
    $this->user->revokePermissionTo('bank_account_create');

    // Act
    livewire(Banks\Accounts\Create::class)
        ->assertForbidden();

})->group('cannotHasPermission');

it("cannot able to create a bank account if not has permission", function () {

    // Act
    $lw = livewire(Banks\Accounts\Create::class);

    $this->user->revokePermissionTo('bank_account_create');

    $lw->call('store')
        ->assertForbidden();

})->group('cannotHasPermission');

/* ###################################################################### */
/* CREATE BANK ACCOUNT */
/* ###################################################################### */
it('can create a bank accounts', function () {

    // Arrange
    $newData = BankAccount::factory()->makeOne();

    // Act
    livewire(Banks\Accounts\Create::class)
        ->fillForm([
            'bank_name'     => $newData->bank_name,
            'type'          => $newData->type->value,
            'number'        => $newData->number,
            'digit'         => $newData->digit,
            'agency_number' => $newData->agency_number,
            'agency_digit'  => $newData->agency_digit,
            'balance'       => number_format($newData->balance, 2, ',', '.'),
        ])
        ->call('store')
        ->assertHasNoFormErrors()
        ->assertNotified()
        ->assertRedirect(route('banks.accounts.index'));

    // Assert
    assertDatabaseHas('bank_accounts', [
        'user_id'       => $this->user->id,
        'bank_name'     => $newData->bank_name,
        'type'          => $newData->type,
        'number'        => $newData->number,
        'digit'         => $newData->digit,
        'agency_number' => $newData->agency_number,
        'agency_digit'  => $newData->agency_digit,
        'balance'       => $newData->balance,
    ]);

})->group('createBankAccount');

it('can create a bank accounts and continue in this page', function () {

    // Arrange
    $newData = BankAccount::factory()->makeOne();

    // Act
    livewire(Banks\Accounts\Create::class)
        ->fillForm([
            'bank_name'     => $newData->bank_name,
            'type'          => $newData->type->value,
            'number'        => $newData->number,
            'digit'         => $newData->digit,
            'agency_number' => $newData->agency_number,
            'agency_digit'  => $newData->agency_digit,
            'balance'       => number_format($newData->balance, 2, ',', '.'),
        ])
        ->call('storeAndStay')
        ->assertHasNoFormErrors()
        ->assertNotified()
        ->assertFormSet([
            'bank_name'     => null,
            'type'          => null,
            'number'        => null,
            'digit'         => null,
            'agency_number' => null,
            'agency_digit'  => null,
            'balance'       => '0,00',
        ]);

    // Assert
    assertDatabaseHas('bank_accounts', [
        'user_id'       => $this->user->id,
        'bank_name'     => $newData->bank_name,
        'type'          => $newData->type,
        'number'        => $newData->number,
        'digit'         => $newData->digit,
        'agency_number' => $newData->agency_number,
        'agency_digit'  => $newData->agency_digit,
        'balance'       => $newData->balance,
    ]);

})->group('createBankAccount');
