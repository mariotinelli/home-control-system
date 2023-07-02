<?php

use App\Http\Livewire\Couple;
use App\Models\{CoupleSpending, CoupleSpendingCategory, CoupleSpendingPlace, User};
use Filament\Notifications\Notification;

use function Pest\Laravel\{actingAs, assertDatabaseHas};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->create([
        'email' => 'teste@email.com',
    ]);

    $this->user->givePermissionTo('couple_spending_read');
    $this->user->givePermissionTo('couple_spending_create');
    $this->user->givePermissionTo('couple_spending_update');
    $this->user->givePermissionTo('couple_spending_delete');

    actingAs($this->user);

});

/* ###################################################################### */
/* CAN RENDER FORM */
/* ###################################################################### */
it('can render a form', function () {

    livewire(Couple\Spending\Create::class)
        ->assertFormExists();

})->group('form');

/* ###################################################################### */
/* RENDER FORM FIELDS */
/* ###################################################################### */
it('can render all form fields', function () {

    // Category
    livewire(Couple\Spending\Create::class)
        ->assertFormFieldExists('couple_spending_category_id');

    // Place Name
    livewire(Couple\Spending\Create::class)
        ->assertFormFieldExists('couple_spending_place_id');

    // Description
    livewire(Couple\Spending\Create::class)
        ->assertFormFieldExists('description');

    // Amount
    livewire(Couple\Spending\Create::class)
        ->assertFormFieldExists('amount');

    // Date
    livewire(Couple\Spending\Create::class)
        ->assertFormFieldExists('date');

})->group('form');

/* ###################################################################### */
/* VALIDATE FORM FIELDS */
/* ###################################################################### */
it('can validate category', function () {

    // Required
    livewire(Couple\Spending\Create::class)
        ->fillForm([
            'couple_spending_category_id' => null,
        ])
        ->call('store')
        ->assertHasFormErrors(['couple_spending_category_id' => 'required']);

    // Exists
    livewire(Couple\Spending\Create::class)
        ->fillForm([
            'couple_spending_category_id' => CoupleSpendingCategory::count() + 1,
        ])
        ->call('store')
        ->assertHasFormErrors(['couple_spending_category_id' => 'exists']);

    // Belongs to user
    $categoryNotOwner = CoupleSpendingCategory::factory()->createOne();

    livewire(Couple\Spending\Create::class)
        ->fillForm([
            'couple_spending_category_id' => $categoryNotOwner->id,
        ])
        ->call('store')
        ->assertForbidden();

})->group('form');

it('can validate place', function () {

    // Required
    livewire(Couple\Spending\Create::class)
        ->fillForm([
            'couple_spending_place_id' => null,
        ])
        ->call('store')
        ->assertHasFormErrors(['couple_spending_place_id' => 'required']);

    // Exists
    livewire(Couple\Spending\Create::class)
        ->fillForm([
            'couple_spending_place_id' => CoupleSpendingPlace::query()->count() + 1,
        ])
        ->call('store')
        ->assertHasFormErrors(['couple_spending_place_id' => 'exists']);

    // Belongs to user
    $placeNotOwner = CoupleSpendingPlace::factory()->createOne();

    livewire(Couple\Spending\Create::class)
        ->fillForm([
            'couple_spending_place_id' => $placeNotOwner->id,
        ])
        ->call('store')
        ->assertForbidden();

})->group('form');

it('can validate description', function () {

    // Required
    livewire(Couple\Spending\Create::class)
        ->fillForm([
            'description' => null,
        ])
        ->call('store')
        ->assertHasFormErrors(['description' => 'required']);

    // String
    livewire(Couple\Spending\Create::class)
        ->fillForm([
            'description' => 123,
        ])
        ->call('store')
        ->assertHasFormErrors(['description' => 'string']);

    // Min 3
    livewire(Couple\Spending\Create::class)
        ->fillForm([
            'description' => 'aa',
        ])
        ->call('store')
        ->assertHasFormErrors(['description' => 'min']);

    // Max 255
    livewire(Couple\Spending\Create::class)
        ->fillForm([
            'description' => str_repeat('a', 256),
        ])
        ->call('store')
        ->assertHasFormErrors(['description' => 'max']);

})->group('form');

it('can validate amount', function () {

    // Required
    livewire(Couple\Spending\Create::class)
        ->fillForm([
            'amount' => null,
        ])
        ->call('store')
        ->assertHasFormErrors(['amount' => 'required']);

})->group('form');

it('can validate date', function () {

    // Required
    livewire(Couple\Spending\Create::class)
        ->fillForm([
            'date' => null,
        ])
        ->call('store')
        ->assertHasFormErrors(['date' => 'required']);

    // Date
    livewire(Couple\Spending\Create::class)
        ->fillForm([
            'date' => 'abc',
        ])
        ->call('store')
        ->assertHasFormErrors(['date' => 'date']);

})->group('form');

/* ###################################################################### */
/* CREATE */
/* ###################################################################### */
it('can create spending', function () {

    // Arrange
    $newData = CoupleSpending::factory()->makeOne([
        'user_id'                     => $this->user->id,
        'couple_spending_category_id' => CoupleSpendingCategory::factory()->createOne([
            'user_id' => $this->user->id,
        ])->id,
        'couple_spending_place_id' => CoupleSpendingPlace::factory()->createOne([
            'user_id' => $this->user->id,
        ])->id,
    ]);

    livewire(Couple\Spending\Create::class)
        ->fillForm([
            'couple_spending_category_id' => $newData->couple_spending_category_id,
            'couple_spending_place_id'    => $newData->couple_spending_place_id,
            'description'                 => $newData->description,
            'amount'                      => $newData->amount,
            'date'                        => $newData->date,
        ])
        ->call('store')
        ->assertHasNoFormErrors()
        ->assertEmitted('couple::spending::created')
        ->assertDispatchedBrowserEvent('close-modal', ['id' => 'couple-spending-create'])
        ->assertNotified(
            Notification::make()
                ->success()
                ->body('Gasto criado com sucesso.'),
        )
        ->assertFormSet([
            'couple_spending_category_id' => null,
            'couple_spending_place_id'    => null,
            'description'                 => null,
            'amount'                      => '0,00',
            'date'                        => null,
        ]);

    assertDatabaseHas('couple_spendings', [
        'user_id'                     => $this->user->id,
        'couple_spending_category_id' => $newData->couple_spending_category_id,
        'couple_spending_place_id'    => $newData->couple_spending_place_id,
        'description'                 => $newData->description,
        'amount'                      => $newData->amount,
        'date'                        => $newData->date,
    ]);

})->group('create');

it('can create spending and stay', function () {

    // Arrange
    $newData = CoupleSpending::factory()->makeOne([
        'user_id'                     => $this->user->id,
        'couple_spending_category_id' => CoupleSpendingCategory::factory()->createOne([
            'user_id' => $this->user->id,
        ])->id,
        'couple_spending_place_id' => CoupleSpendingPlace::factory()->createOne([
            'user_id' => $this->user->id,
        ])->id,
    ]);

    livewire(Couple\Spending\Create::class)
        ->fillForm([
            'couple_spending_category_id' => $newData->couple_spending_category_id,
            'couple_spending_place_id'    => $newData->couple_spending_place_id,
            'description'                 => $newData->description,
            'amount'                      => $newData->amount,
            'date'                        => $newData->date,
        ])
        ->call('storeAndStay')
        ->assertHasNoFormErrors()
        ->assertEmitted('couple::spending::created')
        ->assertNotDispatchedBrowserEvent('close-modal')
        ->assertNotified(
            Notification::make()
                ->success()
                ->body('Gasto criado com sucesso.'),
        )
        ->assertFormSet([
            'couple_spending_category_id' => null,
            'couple_spending_place_id'    => null,
            'description'                 => null,
            'amount'                      => '0,00',
            'date'                        => null,
        ]);

    assertDatabaseHas('couple_spendings', [
        'user_id'                     => $this->user->id,
        'couple_spending_category_id' => $newData->couple_spending_category_id,
        'couple_spending_place_id'    => $newData->couple_spending_place_id,
        'description'                 => $newData->description,
        'amount'                      => $newData->amount,
        'date'                        => $newData->date,
    ]);

})->group('create');
