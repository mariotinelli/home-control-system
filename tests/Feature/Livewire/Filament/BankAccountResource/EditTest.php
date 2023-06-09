<?php

use App\Http\Livewire\Filament;
use App\Models\{BankAccount, User};

use function Pest\Laravel\{actingAs, assertDatabaseHas, get};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->createOne([
        'email' => 'teste@email.com',
    ]);

    $this->user->givePermissionTo('bank_account_update');

    $this->user->bankAccounts()->save(
        $this->bankAccount = BankAccount::factory()->makeOne()
    );

    actingAs($this->user);

});

/* ###################################################################### */
/* RENDER PAGE */
/* ###################################################################### */
it('can render page', function () {

    livewire(Filament\BankAccountResource\Edit::class, ['record' => $this->bankAccount])
        ->assertSuccessful();

})->group('renderPage');

it('can redirect to login if not authenticated', function () {

    Auth::logout();

    get(route('banks.accounts.edit', $this->bankAccount->id))
        ->assertRedirect(route('login'));

})->group('renderPage');

/* ###################################################################### */
/* RENDER FORM */
/* ###################################################################### */
it('has a form', function () {

    livewire(Filament\BankAccountResource\Edit::class, ['record' => $this->bankAccount])
        ->assertFormExists();

})->group('renderForm');

it('can display title of page', function () {

    livewire(Filament\BankAccountResource\Edit::class, ['record' => $this->bankAccount])
        ->assertSeeHtml('Editar conta bancária');

})->group('renderForm');

/* ###################################################################### */
/* RENDER FORM FIELDS */
/* ###################################################################### */
it('has a bank name field', function () {

    livewire(Filament\BankAccountResource\Edit::class, ['record' => $this->bankAccount])
        ->assertFormFieldExists('bank_name');

})->group('renderFormFields');

it('has a type field', function () {

    livewire(Filament\BankAccountResource\Edit::class, ['record' => $this->bankAccount])
        ->assertFormFieldExists('type');

})->group('renderFormFields');

it('has a number field', function () {

    livewire(Filament\BankAccountResource\Edit::class, ['record' => $this->bankAccount])
        ->assertFormFieldExists('number');

})->group('renderFormFields');

it('has a digit field', function () {

    livewire(Filament\BankAccountResource\Edit::class, ['record' => $this->bankAccount])
        ->assertFormFieldExists('digit');

})->group('renderFormFields');

it('has a agency number field', function () {

    livewire(Filament\BankAccountResource\Edit::class, ['record' => $this->bankAccount])
        ->assertFormFieldExists('agency_number');

})->group('renderFormFields');

it('has a agency digit field', function () {

    livewire(Filament\BankAccountResource\Edit::class, ['record' => $this->bankAccount])
        ->assertFormFieldExists('agency_digit');

})->group('renderFormFields');

it('has a balance field', function () {

    livewire(Filament\BankAccountResource\Edit::class, ['record' => $this->bankAccount])
        ->assertFormFieldExists('balance');

})->group('renderFormFields');

/* ###################################################################### */
/* CORRECTLY FILL FORM */
/* ###################################################################### */
it('can fill bank name field correctly', function () {

    livewire(Filament\BankAccountResource\Edit::class, ['record' => $this->bankAccount])
        ->assertFormSet([
            'bank_name' => $this->bankAccount->bank_name,
        ]);

})->group('canFillForm');

it('can fill type field correctly', function () {

    livewire(Filament\BankAccountResource\Edit::class, ['record' => $this->bankAccount])
        ->assertFormSet([
            'type' => $this->bankAccount->type->value,
        ]);

})->group('canFillForm');

it('can fill number field correctly', function () {

    livewire(Filament\BankAccountResource\Edit::class, ['record' => $this->bankAccount])
        ->assertFormSet([
            'number' => $this->bankAccount->number,
        ]);

})->group('canFillForm');

it('can fill digit field correctly', function () {

    livewire(Filament\BankAccountResource\Edit::class, ['record' => $this->bankAccount])
        ->assertFormSet([
            'digit' => $this->bankAccount->digit,
        ]);

})->group('canFillForm');

it('can fill agency number field correctly', function () {

    livewire(Filament\BankAccountResource\Edit::class, ['record' => $this->bankAccount])
        ->assertFormSet([
            'agency_number' => $this->bankAccount->agency_number,
        ]);

})->group('canFillForm');

it('can fill agency digit field correctly', function () {

    livewire(Filament\BankAccountResource\Edit::class, ['record' => $this->bankAccount])
        ->assertFormSet([
            'agency_digit' => $this->bankAccount->agency_digit,
        ]);

})->group('canFillForm');

it('can fill balance field correctly', function () {

    livewire(Filament\BankAccountResource\Edit::class, ['record' => $this->bankAccount])
        ->assertFormSet([
            'balance' => number_format($this->bankAccount->balance, 2, ',', '.'),
        ]);

})->group('canFillForm');

/* ###################################################################### */
/* RENDER FORM BUTTONS */
/* ###################################################################### */
it('can render save button', function () {

    livewire(Filament\BankAccountResource\Edit::class, ['record' => $this->bankAccount])
        ->assertSeeHtml('Salvar');

})->group('renderFormButtons');

it('can render cancel button', function () {

    livewire(Filament\BankAccountResource\Edit::class, ['record' => $this->bankAccount])
        ->assertSeeHtml('Cancelar');

})->group('renderFormButtons');

/* ###################################################################### */
/* VALIDATE FORM FIELDS */
/* ###################################################################### */
it('can validate bank name', function () {

    // Required
    livewire(Filament\BankAccountResource\Edit::class, ['record' => $this->bankAccount])
        ->fillForm([
            'bank_name' => null,
        ])
        ->call('update')
        ->assertHasFormErrors(['bank_name' => 'required']);

    // String
    livewire(Filament\BankAccountResource\Edit::class, ['record' => $this->bankAccount])
        ->fillForm([
            'bank_name' => 123,
        ])
        ->call('update')
        ->assertHasFormErrors(['bank_name' => 'string']);

    // Min 3
    livewire(Filament\BankAccountResource\Edit::class, ['record' => $this->bankAccount])
        ->fillForm([
            'bank_name' => Str::random(2),
        ])
        ->call('update')
        ->assertHasFormErrors(['bank_name' => 'min']);

    // Max 100
    livewire(Filament\BankAccountResource\Edit::class, ['record' => $this->bankAccount])
        ->fillForm([
            'bank_name' => Str::random(101),
        ])
        ->call('update')
        ->assertHasFormErrors(['bank_name' => 'max']);

})->group('formFieldsValidation');

it('can validate type', function () {

    // Required
    livewire(Filament\BankAccountResource\Edit::class, ['record' => $this->bankAccount])
        ->fillForm([
            'type' => null,
        ])
        ->call('update')
        ->assertHasFormErrors(['type' => 'required']);

    // String
    livewire(Filament\BankAccountResource\Edit::class, ['record' => $this->bankAccount])
        ->fillForm([
            'type' => 123,
        ])
        ->call('update')
        ->assertHasFormErrors(['type' => 'string']);

    // In
    livewire(Filament\BankAccountResource\Edit::class, ['record' => $this->bankAccount])
        ->fillForm([
            'type' => 'invalid',
        ])
        ->call('update')
        ->assertHasFormErrors(['type' => 'in']);

})->group('formFieldsValidation');

it('can validate number', function () {

    // Required
    livewire(Filament\BankAccountResource\Edit::class, ['record' => $this->bankAccount])
        ->fillForm([
            'number' => null,
        ])
        ->call('update')
        ->assertHasFormErrors(['number' => 'required']);

    // Numeric
    livewire(Filament\BankAccountResource\Edit::class, ['record' => $this->bankAccount])
        ->fillForm([
            'number' => 'abc',
        ])
        ->call('update')
        ->assertHasFormErrors(['number' => 'numeric']);

    // Min digits 5
    livewire(Filament\BankAccountResource\Edit::class, ['record' => $this->bankAccount])
        ->fillForm([
            'number' => (float)str_repeat('1', 4),
        ])
        ->call('update')
        ->assertHasFormErrors(['number' => 'min_digits']);

    // Max digits 20
    livewire(Filament\BankAccountResource\Edit::class, ['record' => $this->bankAccount])
        ->fillForm([
            'number' => (float)str_repeat('1', 21),
        ])
        ->call('update')
        ->assertHasFormErrors(['number' => 'max_digits']);

    // Unique
    $newBankAccount = BankAccount::factory()->createOne();

    livewire(Filament\BankAccountResource\Edit::class, ['record' => $this->bankAccount])
        ->fillForm([
            'number' => $newBankAccount->number,
        ])
        ->call('update')
        ->assertHasFormErrors(['number' => 'unique']);

    // Unique ignore
    livewire(Filament\BankAccountResource\Edit::class, ['record' => $this->bankAccount])
        ->fillForm([
            'number' => $this->bankAccount->number,
        ])
        ->call('update')
        ->assertHasNoFormErrors(['number' => 'unique']);

})->group('formFieldsValidation');

it('can validate digit', function () {

    // Required
    livewire(Filament\BankAccountResource\Edit::class, ['record' => $this->bankAccount])
        ->fillForm([
            'digit' => null,
        ])
        ->call('update')
        ->assertHasFormErrors(['digit' => 'required']);

    // Numeric
    livewire(Filament\BankAccountResource\Edit::class, ['record' => $this->bankAccount])
        ->fillForm([
            'digit' => 'abc',
        ])
        ->call('update')
        ->assertHasFormErrors(['digit' => 'numeric']);

    // Max digits 1
    livewire(Filament\BankAccountResource\Edit::class, ['record' => $this->bankAccount])
        ->fillForm([
            'digit' => 12,
        ])
        ->call('update')
        ->assertHasFormErrors(['digit' => 'max_digits']);

})->group('formFieldsValidation');

it('can validate agency number', function () {

    // Required
    livewire(Filament\BankAccountResource\Edit::class, ['record' => $this->bankAccount])
        ->fillForm([
            'agency_number' => null,
        ])
        ->call('update')
        ->assertHasFormErrors(['agency_number' => 'required']);

    // Numeric
    livewire(Filament\BankAccountResource\Edit::class, ['record' => $this->bankAccount])
        ->fillForm([
            'agency_number' => 'abc',
        ])
        ->call('update')
        ->assertHasFormErrors(['agency_number' => 'numeric']);

    // Min digits 4
    livewire(Filament\BankAccountResource\Edit::class, ['record' => $this->bankAccount])
        ->fillForm([
            'agency_number' => (float)str_repeat('1', 3),
        ])
        ->call('update')
        ->assertHasFormErrors(['agency_number' => 'min_digits']);

    // Max digits 4
    livewire(Filament\BankAccountResource\Edit::class, ['record' => $this->bankAccount])
        ->fillForm([
            'agency_number' => (float)str_repeat('1', 5),
        ])
        ->call('update')
        ->assertHasFormErrors(['agency_number' => 'max_digits']);

})->group('formFieldsValidation');

it('can validate agency digit', function () {

    // Nullable
    livewire(Filament\BankAccountResource\Edit::class, ['record' => $this->bankAccount])
        ->fillForm([
            'agency_digit' => null,
        ])
        ->call('update')
        ->assertHasNoFormErrors(['agency_digit']);

    // Numeric
    livewire(Filament\BankAccountResource\Edit::class, ['record' => $this->bankAccount])
        ->fillForm([
            'agency_digit' => 'abc',
        ])
        ->call('update')
        ->assertHasFormErrors(['agency_digit' => 'numeric']);

    // Max digits 1
    livewire(Filament\BankAccountResource\Edit::class, ['record' => $this->bankAccount])
        ->fillForm([
            'agency_digit' => 12,
        ])
        ->call('update')
        ->assertHasFormErrors(['agency_digit' => 'max_digits']);

})->group('formFieldsValidation');

it('can validate balance', function () {

    // Required
    livewire(Filament\BankAccountResource\Edit::class, ['record' => $this->bankAccount])
        ->fillForm([
            'balance' => null,
        ])
        ->call('update')
        ->assertHasFormErrors(['balance' => 'required']);

})->group('formFieldsValidation');

/* ###################################################################### */
/* CANNOT HAS PERMISSION */
/* ###################################################################### */
it('cannot render page if not has permission', function () {

    // Arrange
    $this->user->revokePermissionTo('bank_account_update');

    // Act
    livewire(Filament\BankAccountResource\Edit::class, ['record' => $this->bankAccount])
        ->assertForbidden();

})->group('cannotHasPermission');

/* ###################################################################### */
/* UPDATE BANK ACCOUNT */
/* ###################################################################### */
it('can update bank accounts', function () {

    // Arrange
    $newData = BankAccount::factory()->makeOne();

    // Act
    livewire(Filament\BankAccountResource\Edit::class, ['record' => $this->bankAccount])
        ->fillForm([
            'bank_name'     => $newData->bank_name,
            'type'          => $newData->type->value,
            'number'        => $newData->number,
            'digit'         => $newData->digit,
            'agency_number' => $newData->agency_number,
            'agency_digit'  => $newData->agency_digit,
            'balance'       => number_format($newData->balance, 2, ',', '.'),
        ])
        ->call('update')
        ->assertHasNoFormErrors()
        ->assertNotified()
        ->assertRedirect(route('banks.accounts.index'));

    // Assert
    assertDatabaseHas('bank_accounts', [
        'id'            => $this->bankAccount->id,
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
