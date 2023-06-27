<?php

use App\Http\Livewire\Couple;
use App\Models\{CoupleSpending, CoupleSpendingCategory, CoupleSpendingPlace, User};

use function Pest\Laravel\{actingAs, get};
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
/* RENDER PAGE */
/* ###################################################################### */
it('can render page', function () {

    get(route('couple.spending.index'))
        ->assertSuccessful();

})->group('renderPage');

it('can render livewire component', function () {

    get(route('couple.spending.index'))
        ->assertSeeLivewire(Couple\Spending\Index::class);

})->group('renderPage');

/* ###################################################################### */
/* RENDER VALUES */
/* ###################################################################### */
it('can get place with more spending', function () {

    // Arrange
    $places = CoupleSpendingPlace::factory()->count(3)->create([
        'user_id' => $this->user->id,
    ]);

    foreach ($places as $place) {
        $place->spendings()->save(CoupleSpending::factory()->make([
            'user_id' => $this->user->id,
            'amount'  => 100,
        ]));
    }

    $places->last()->spendings()->save(
        CoupleSpending::factory()->make([
            'user_id' => $this->user->id,
            'amount'  => 1000,
        ])
    );

    livewire(Couple\Spending\Index::class)
        ->assertSet('placeWithMoreSpending.couple_spending_place_id', $places->last()->id)
        ->assertSet('placeWithMoreSpending.total', 1100);

})->group('renderValues');

it('can get category with more spending', function () {

    // Arrange
    $categories = CoupleSpendingCategory::factory()->count(3)->create([
        'user_id' => $this->user->id,
    ]);

    foreach ($categories as $category) {
        $category->spendings()->save(CoupleSpending::factory()->make([
            'user_id' => $this->user->id,
            'amount'  => 100,
        ]));
    }

    $categories->last()->spendings()->save(
        CoupleSpending::factory()->make([
            'user_id' => $this->user->id,
            'amount'  => 1000,
        ])
    );

    livewire(Couple\Spending\Index::class)
        ->assertSet('categoryWithMoreSpending.couple_spending_category_id', $categories->last()->id)
        ->assertSet('categoryWithMoreSpending.total', 1100);

})->group('renderValues');

/* ###################################################################### */
/* UNAUTHENTICATED */
/* ###################################################################### */
it('can redirect to login if not authenticated', function () {

    Auth::logout();

    get(route('couple.spending.index'))
        ->assertRedirect(route('login'));

})->group('unauthenticated');

/* ###################################################################### */
/* CAN RENDER PAGE COMPONENTS */
/* ###################################################################### */
todo('can render page header')->group('canRenderPageComponents');

todo('can render the creation livewire component')->group('canRenderPageComponents');

/* ###################################################################### */
/* CAN RENDER CHART COMPONENTS */
/* ###################################################################### */
todo('can render chart ByMonthByCategory', function () {

    $lw = livewire(Couple\Spending\Index::class)
        ->assertSeeLivewire(Couple\Spending\Charts\ByMonthByCategory::class);

})->group('canRenderChartComponents');

todo('can render chart ByMonthByPlace', function () {

    $lw = livewire(Couple\Spending\Index::class)
        ->assertSeeLivewire(Couple\Spending\Charts\ByMonthByPlace::class);

})->group('canRenderChartComponents');

/* ###################################################################### */
/* CANNOT RENDER CHART COMPONENTS */
/* ###################################################################### */
todo('cannot be able to see the chart ByMonthByCategory if you do not have permission', function () {

    $this->user->revokePermissionTo('couple_spending_read');

    $lw = livewire(Couple\Spending\Index::class)
        ->assertDontSeeLivewire(Couple\Spending\Charts\ByMonthByCategory::class);

});

todo('cannot be able to see the chart ByMonthByPlace if you do not have permission', function () {

    $this->user->revokePermissionTo('couple_spending_read');

    $lw = livewire(Couple\Spending\Index::class)
        ->assertDontSeeLivewire(Couple\Spending\Charts\ByMonthByPlace::class);

});
