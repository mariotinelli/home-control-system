<?php

namespace Tests\Feature\Livewire\BankAccounts;

use App\Http\Livewire\Banks;
use App\Models\{BankAccount, User};
use Auth;

use function Pest\Laravel\{actingAs, get};
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
/* VALIDATE FORM FIELDS */
/* ###################################################################### */
it('can validate bank name', function () {

    // Required
    livewire(Banks\Accounts\Create::class)
        ->fillForm([
            'bank_name' => null,
        ])
        ->call('save')
        ->assertHasFormErrors(['bank_name' => 'required']);

    // String
    livewire(Banks\Accounts\Create::class)
        ->fillForm([
            'bank_name' => 123,
        ])
        ->call('save')
        ->assertHasFormErrors(['bank_name' => 'string']);

    // Min 3
    livewire(Banks\Accounts\Create::class)
        ->fillForm([
            'bank_name' => Str::random(2),
        ])
        ->call('save')
        ->assertHasFormErrors(['bank_name' => 'min']);

    // Max 100
    livewire(Banks\Accounts\Create::class)
        ->fillForm([
            'bank_name' => Str::random(101),
        ])
        ->call('save')
        ->assertHasFormErrors(['bank_name' => 'max']);

})->group('formFieldsValidation');

it('can validate type', function () {

    // Required
    livewire(Banks\Accounts\Create::class)
        ->fillForm([
            'type' => null,
        ])
        ->call('save')
        ->assertHasFormErrors(['type' => 'required']);

    // String
    livewire(Banks\Accounts\Create::class)
        ->fillForm([
            'type' => 123,
        ])
        ->call('save')
        ->assertHasFormErrors(['type' => 'string']);

    // In
    livewire(Banks\Accounts\Create::class)
        ->fillForm([
            'type' => 'invalid',
        ])
        ->call('save')
        ->assertHasFormErrors(['type' => 'in']);

})->group('formFieldsValidation');

it('can validate number', function () {

    // Required
    livewire(Banks\Accounts\Create::class)
        ->fillForm([
            'number' => null,
        ])
        ->call('save')
        ->assertHasFormErrors(['number' => 'required']);

    // Numeric
    livewire(Banks\Accounts\Create::class)
        ->fillForm([
            'number' => 'abc',
        ])
        ->call('save')
        ->assertHasFormErrors(['number' => 'numeric']);

    // Min digits 5
    livewire(Banks\Accounts\Create::class)
        ->fillForm([
            'number' => (float)str_repeat('1', 4),
        ])
        ->call('save')
        ->assertHasFormErrors(['number' => 'min_digits']);

    // Max digits 20
    livewire(Banks\Accounts\Create::class)
        ->fillForm([
            'number' => (float)str_repeat('1', 21),
        ])
        ->call('save')
        ->assertHasFormErrors(['number' => 'max_digits']);

    // Unique
    $bankAccount = BankAccount::factory()->createOne();

    livewire(Banks\Accounts\Create::class)
        ->fillForm([
            'number' => $bankAccount->number,
        ])
        ->call('save')
        ->assertHasFormErrors(['number' => 'unique']);

})->group('formFieldsValidation');

it('can validate digit', function () {

    // Required
    livewire(Banks\Accounts\Create::class)
        ->fillForm([
            'digit' => null,
        ])
        ->call('save')
        ->assertHasFormErrors(['digit' => 'required']);

    // Numeric
    livewire(Banks\Accounts\Create::class)
        ->fillForm([
            'digit' => 'abc',
        ])
        ->call('save')
        ->assertHasFormErrors(['digit' => 'numeric']);

    // Max digits 1
    livewire(Banks\Accounts\Create::class)
        ->fillForm([
            'digit' => 12,
        ])
        ->call('save')
        ->assertHasFormErrors(['digit' => 'max_digits']);

})->group('formFieldsValidation');

it('can validate agency number', function () {

    // Required
    livewire(Banks\Accounts\Create::class)
        ->fillForm([
            'agency_number' => null,
        ])
        ->call('save')
        ->assertHasFormErrors(['agency_number' => 'required']);

    // Numeric
    livewire(Banks\Accounts\Create::class)
        ->fillForm([
            'agency_number' => 'abc',
        ])
        ->call('save')
        ->assertHasFormErrors(['agency_number' => 'numeric']);

    // Min digits 4
    livewire(Banks\Accounts\Create::class)
        ->fillForm([
            'agency_number' => (float)str_repeat('1', 3),
        ])
        ->call('save')
        ->assertHasFormErrors(['agency_number' => 'min_digits']);

    // Max digits 4
    livewire(Banks\Accounts\Create::class)
        ->fillForm([
            'agency_number' => (float)str_repeat('1', 5),
        ])
        ->call('save')
        ->assertHasFormErrors(['agency_number' => 'max_digits']);

})->group('formFieldsValidation');

it('can validate agency digit', function () {

    // Nullable
    livewire(Banks\Accounts\Create::class)
        ->fillForm([
            'agency_digit' => null,
        ])
        ->call('save')
        ->assertHasNoFormErrors(['agency_digit']);

    // Numeric
    livewire(Banks\Accounts\Create::class)
        ->fillForm([
            'agency_digit' => 'abc',
        ])
        ->call('save')
        ->assertHasFormErrors(['agency_digit' => 'numeric']);

    // Max digits 1
    livewire(Banks\Accounts\Create::class)
        ->fillForm([
            'agency_digit' => 12,
        ])
        ->call('save')
        ->assertHasFormErrors(['agency_digit' => 'max_digits']);

})->group('formFieldsValidation');

it('can validate balance', function () {

    // Required
    livewire(Banks\Accounts\Create::class)
        ->fillForm([
            'balance' => null,
        ])
        ->call('save')
        ->assertHasFormErrors(['balance' => 'required']);

})->group('formFieldsValidation');

//
//it('should be able to create a bank account', function () {
//    // Arrange
//    $newData = BankAccount::factory()->makeOne();
//
//    // Act
//    livewire(\App\Http\Livewire\Banks\Accounts\Create::class)
//        ->set('bankAccount.bank_name', $newData->bank_name)
//        ->set('bankAccount.type', $newData->type)
//        ->set('bankAccount.number', $newData->number)
//        ->set('bankAccount.digit', $newData->digit)
//        ->set('bankAccount.agency_number', $newData->agency_number)
//        ->set('bankAccount.agency_digit', $newData->agency_digit)
//        ->set('bankAccount.balance', $newData->balance)
//        ->call('save')
//        ->assertEmitted('bank-account::created');
//
//    // Assert
//    assertDatabaseHas('bank_accounts', [
//        'user_id' => $this->user->id,
//        'bank_name' => $newData->bank_name,
//        'type' => $newData->type,
//        'number' => $newData->number,
//        'digit' => $newData->digit,
//        'agency_number' => $newData->agency_number,
//        'agency_digit' => $newData->agency_digit,
//        'balance' => $newData->balance,
//    ]);
//
//});
//
//it("should be not able to create a bank account if not has permission to this", function () {
//    // Arrange
//    $this->user->revokePermissionTo('bank_account_create');
//
//    // Act
//    livewire(\App\Http\Livewire\Banks\Accounts\Create::class)
//        ->call('save')
//        ->assertForbidden();
//
//});
//
//it("should be not able to create a bank account if not authenticated", function () {
//    // Arrange
//    \Auth::logout();
//
//    // Act
//    livewire(\App\Http\Livewire\Banks\Accounts\Create::class)
//        ->call('save')
//        ->assertForbidden();
//
//});
//
//test('bank name is required', function () {
//
//    livewire(\App\Http\Livewire\Banks\Accounts\Create::class)
//        ->set('bankAccount.bank_name', '')
//        ->call('save')
//        ->assertHasErrors(['bankAccount.bank_name' => 'required']);
//
//});
//
//test('bank name should be a string', function () {
//
//    livewire(\App\Http\Livewire\Banks\Accounts\Create::class)
//        ->set('bankAccount.bank_name', 123)
//        ->call('save')
//        ->assertHasErrors(['bankAccount.bank_name' => 'string']);
//
//});
//
//test('bank name should be have a max length of 100 characters', function () {
//
//    livewire(\App\Http\Livewire\Banks\Accounts\Create::class)
//        ->set('bankAccount.bank_name', str_repeat('a', 101))
//        ->call('save')
//        ->assertHasErrors(['bankAccount.bank_name' => 'max']);
//
//});
//
//test('type is required', function () {
//
//    livewire(\App\Http\Livewire\Banks\Accounts\Create::class)
//        ->set('bankAccount.type', null)
//        ->call('save')
//        ->assertHasErrors(['bankAccount.type' => 'required']);
//
//});
//
//test('number is required', function () {
//
//    livewire(\App\Http\Livewire\Banks\Accounts\Create::class)
//        ->set('bankAccount.number', '')
//        ->call('save')
//        ->assertHasErrors(['bankAccount.number' => 'required']);
//
//});
//
//test('number should be a numeric', function () {
//
//    livewire(\App\Http\Livewire\Banks\Accounts\Create::class)
//        ->set('bankAccount.number', 'abc')
//        ->call('save')
//        ->assertHasErrors(['bankAccount.number' => 'numeric']);
//
//});
//
//test('number should be unique', function () {
//    // Arrange
//    $newBankAccount = BankAccount::factory()->createOne([
//        'user_id' => $this->user->id,
//        'number' => '123456',
//    ]);
//
//    livewire(\App\Http\Livewire\Banks\Accounts\Create::class)
//        ->set('bankAccount.number', $newBankAccount->number)
//        ->call('save')
//        ->assertHasErrors(['bankAccount.number' => 'unique']);
//
//});
//
//test('number should be have a min digits of 5', function () {
//
//    livewire(\App\Http\Livewire\Banks\Accounts\Create::class)
//        ->set('bankAccount.number', (int)str_repeat('9', 4))
//        ->call('save')
//        ->assertHasErrors(['bankAccount.number' => 'min_digits']);
//
//});
//
//test('number should be have a max digits of 20', function () {
//
//    livewire(\App\Http\Livewire\Banks\Accounts\Create::class)
//        ->set('bankAccount.number', (float)str_repeat('9', 21))
//        ->call('save')
//        ->assertHasErrors(['bankAccount.number' => 'max_digits']);
//
//});
//
//test('digit is required', function () {
//
//    livewire(\App\Http\Livewire\Banks\Accounts\Create::class)
//        ->set('bankAccount.digit', null)
//        ->call('save')
//        ->assertHasErrors(['bankAccount.digit' => 'required']);
//
//});
//
//test('digit should be a numeric', function () {
//
//    livewire(\App\Http\Livewire\Banks\Accounts\Create::class)
//        ->set('bankAccount.digit', 'abc')
//        ->call('save')
//        ->assertHasErrors(['bankAccount.digit' => 'numeric']);
//
//});
//
//test('digit should be have a max digits of 1', function () {
//
//    livewire(\App\Http\Livewire\Banks\Accounts\Create::class)
//        ->set('bankAccount.digit', (int)str_repeat('9', 2))
//        ->call('save')
//        ->assertHasErrors(['bankAccount.digit' => 'max_digits']);
//
//});
//
////test('balance is required', function () {
////
////    livewire(\App\Http\Livewire\Banks\Accounts\Create::class)
////        ->set('bankAccount.balance', null)
////        ->call('save')
////        ->assertHasErrors(['bankAccount.balance' => 'required']);
////
////});
////
////test('balance should be a numeric', function () {
////
////    livewire(\App\Http\Livewire\Banks\Accounts\Create::class)
////        ->set('bankAccount.balance', 'abc')
////        ->call('save')
////        ->assertHasErrors(['bankAccount.balance' => 'numeric']);
////
////});
//
//test('agency number is required', function () {
//
//    livewire(\App\Http\Livewire\Banks\Accounts\Create::class)
//        ->set('bankAccount.agency_number', '')
//        ->call('save')
//        ->assertHasErrors(['bankAccount.agency_number' => 'required']);
//
//});
//
//test('agency number should be a numeric', function () {
//
//    livewire(\App\Http\Livewire\Banks\Accounts\Create::class)
//        ->set('bankAccount.agency_number', 'abc')
//        ->call('save')
//        ->assertHasErrors(['bankAccount.agency_number' => 'numeric']);
//
//});
//
//test('agency number should be have a min digits of 4', function () {
//
//    livewire(\App\Http\Livewire\Banks\Accounts\Create::class)
//        ->set('bankAccount.agency_number', (int)str_repeat('9', 3))
//        ->call('save')
//        ->assertHasErrors(['bankAccount.agency_number' => 'min_digits']);
//
//});
//
//test('agency number should be have a max digits of 4', function () {
//
//    livewire(\App\Http\Livewire\Banks\Accounts\Create::class)
//        ->set('bankAccount.agency_number', (int)str_repeat('9', 5))
//        ->call('save')
//        ->assertHasErrors(['bankAccount.agency_number' => 'max_digits']);
//
//});
//
//test('agency digit is nullable', function () {
//
//    livewire(\App\Http\Livewire\Banks\Accounts\Create::class)
//        ->set('bankAccount.agency_digit', '')
//        ->call('save')
//        ->assertHasNoErrors(['bankAccount.agency_digit']);
//
//});
//
//test('agency digit should be a numeric', function () {
//
//    livewire(\App\Http\Livewire\Banks\Accounts\Create::class)
//        ->set('bankAccount.agency_digit', 'a')
//        ->call('save')
//        ->assertHasErrors(['bankAccount.agency_digit' => 'numeric']);
//
//});
//
//test('agency digit should be have a max digits of 1', function () {
//
//    livewire(\App\Http\Livewire\Banks\Accounts\Create::class)
//        ->set('bankAccount.agency_digit', (int)str_repeat('9', 2))
//        ->call('save')
//        ->assertHasErrors(['bankAccount.agency_digit' => 'max_digits']);
//
//});
