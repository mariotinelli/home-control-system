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

    $this->user->coupleSpendings()->save(
        $this->coupleSpending = CoupleSpending::factory()->make()
    );

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

    livewire(Couple\Spending\Edit::class, ['coupleSpending' => $this->coupleSpending])
        ->assertFormExists();

})->group('form');

/* ###################################################################### */
/* RENDER FORM FIELDS */
/* ###################################################################### */
it('can render all form fields', function () {

    // Category
    livewire(Couple\Spending\Edit::class, ['coupleSpending' => $this->coupleSpending])
        ->assertFormFieldExists('couple_spending_category_id');

    // Place Name
    livewire(Couple\Spending\Edit::class, ['coupleSpending' => $this->coupleSpending])
        ->assertFormFieldExists('couple_spending_place_id');

    // Description
    livewire(Couple\Spending\Edit::class, ['coupleSpending' => $this->coupleSpending])
        ->assertFormFieldExists('description');

    // Amount
    livewire(Couple\Spending\Edit::class, ['coupleSpending' => $this->coupleSpending])
        ->assertFormFieldExists('amount');

    // Date
    livewire(Couple\Spending\Edit::class, ['coupleSpending' => $this->coupleSpending])
        ->assertFormFieldExists('date');

})->group('form');

/* ###################################################################### */
/* CORRECTLY FILL FORM */
/* ###################################################################### */
it('can fill category field correctly', function () {

    livewire(Couple\Spending\Edit::class, ['coupleSpending' => $this->coupleSpending])
        ->assertFormSet([
            'couple_spending_category_id' => $this->coupleSpending->couple_spending_category_id,
        ]);

})->group('canFillForm');

it('can fill place field correctly', function () {

    livewire(Couple\Spending\Edit::class, ['coupleSpending' => $this->coupleSpending])
        ->assertFormSet([
            'couple_spending_place_id' => $this->coupleSpending->couple_spending_place_id,
        ]);

})->group('canFillForm');

it('can fill description field correctly', function () {

    livewire(Couple\Spending\Edit::class, ['coupleSpending' => $this->coupleSpending])
        ->assertFormSet([
            'description' => $this->coupleSpending->description,
        ]);

})->group('canFillForm');

it('can fill amount field correctly', function () {

    livewire(Couple\Spending\Edit::class, ['coupleSpending' => $this->coupleSpending])
        ->assertFormSet([
            'amount' => $this->coupleSpending->amount,
        ]);

})->group('canFillForm');

it('can fill date field correctly', function () {

    livewire(Couple\Spending\Edit::class, ['coupleSpending' => $this->coupleSpending])
        ->assertFormSet([
            'date' => $this->coupleSpending->date,
        ]);

})->group('canFillForm');

/* ###################################################################### */
/* VALIDATE FORM FIELDS */
/* ###################################################################### */
todo('can validate category', function () {

    // Required
    livewire(Couple\Spending\Edit::class, ['coupleSpending' => $this->coupleSpending])
        ->fillForm([
            'couple_spending_category_id' => null,
        ])
        ->call('update')
        ->assertHasFormErrors(['couple_spending_category_id' => 'required']);

    // Exists
    livewire(Couple\Spending\Edit::class, ['coupleSpending' => $this->coupleSpending])
        ->fillForm([
            'couple_spending_category_id' => CoupleSpendingCategory::count() + 1,
        ])
        ->call('update')
        ->assertHasFormErrors(['couple_spending_category_id' => 'exists']);

    // Belongs to user
    $categoryNotOwner = CoupleSpendingCategory::factory()->createOne();

    livewire(Couple\Spending\Edit::class, ['coupleSpending' => $this->coupleSpending])
        ->fillForm([
            'couple_spending_category_id' => $categoryNotOwner->id,
        ])
        ->call('update')
        ->assertForbidden();

})->group('form');

todo('can validate place', function () {

    // Required
    livewire(Couple\Spending\Edit::class, ['coupleSpending' => $this->coupleSpending])
        ->fillForm([
            'couple_spending_place_id' => null,
        ])
        ->call('update')
        ->assertHasFormErrors(['couple_spending_place_id' => 'required']);

    // Exists
    livewire(Couple\Spending\Edit::class, ['coupleSpending' => $this->coupleSpending])
        ->fillForm([
            'couple_spending_place_id' => CoupleSpendingPlace::query()->count() + 1,
        ])
        ->call('update')
        ->assertHasFormErrors(['couple_spending_place_id' => 'exists']);

    // Belongs to user
    $placeNotOwner = CoupleSpendingPlace::factory()->createOne();

    livewire(Couple\Spending\Edit::class, ['coupleSpending' => $this->coupleSpending])
        ->fillForm([
            'couple_spending_place_id' => $placeNotOwner->id,
        ])
        ->call('update')
        ->assertForbidden();

})->group('form');

todo('can validate description', function () {

    // Required
    livewire(Couple\Spending\Edit::class, ['coupleSpending' => $this->coupleSpending])
        ->fillForm([
            'description' => null,
        ])
        ->call('update')
        ->assertHasFormErrors(['description' => 'required']);

    // String
    livewire(Couple\Spending\Edit::class, ['coupleSpending' => $this->coupleSpending])
        ->fillForm([
            'description' => 123,
        ])
        ->call('update')
        ->assertHasFormErrors(['description' => 'string']);

    // Min 3
    livewire(Couple\Spending\Edit::class, ['coupleSpending' => $this->coupleSpending])
        ->fillForm([
            'description' => 'aa',
        ])
        ->call('update')
        ->assertHasFormErrors(['description' => 'min']);

    // Max 255
    livewire(Couple\Spending\Edit::class, ['coupleSpending' => $this->coupleSpending])
        ->fillForm([
            'description' => str_repeat('a', 256),
        ])
        ->call('update')
        ->assertHasFormErrors(['description' => 'max']);

})->group('form');

todo('can validate amount', function () {

    // Required
    livewire(Couple\Spending\Edit::class, ['coupleSpending' => $this->coupleSpending])
        ->fillForm([
            'amount' => null,
        ])
        ->call('update')
        ->assertHasFormErrors(['amount' => 'required']);

})->group('form');

todo('can validate date', function () {

    // Required
    livewire(Couple\Spending\Edit::class, ['coupleSpending' => $this->coupleSpending])
        ->fillForm([
            'date' => null,
        ])
        ->call('update')
        ->assertHasFormErrors(['date' => 'required']);

    // Date
    livewire(Couple\Spending\Edit::class, ['coupleSpending' => $this->coupleSpending])
        ->fillForm([
            'date' => 'abc',
        ])
        ->call('update')
        ->assertHasFormErrors(['date' => 'date']);

})->group('form');

/* ###################################################################### */
/* UPDATE */
/* ###################################################################### */
it('can update spending', function () {

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

    livewire(Couple\Spending\Edit::class, ['coupleSpending' => $this->coupleSpending])
        ->fillForm([
            'couple_spending_category_id' => $newData->couple_spending_category_id,
            'couple_spending_place_id'    => $newData->couple_spending_place_id,
            'description'                 => $newData->description,
            'amount'                      => $newData->amount,
            'date'                        => $newData->date,
        ])
        ->call('update')
        ->assertHasNoFormErrors()
        ->assertEmitted('couple::spending::updated')
        ->assertNotified(
            Notification::make()
                ->success()
                ->body('Gasto atualizado com sucesso.'),
        );

    assertDatabaseHas('couple_spendings', [
        'user_id'                     => $this->user->id,
        'couple_spending_category_id' => $newData->couple_spending_category_id,
        'couple_spending_place_id'    => $newData->couple_spending_place_id,
        'description'                 => $newData->description,
        'amount'                      => $newData->amount,
        'date'                        => $newData->date,
    ]);

})->group('update');
