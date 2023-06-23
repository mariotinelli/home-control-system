<?php

use App\Http\Controllers\ProfileController;
use App\Http\Livewire\Filament;
use App\Http\Livewire\{Banks, Couple, Goals, Investments, Settings, Trips};
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {

    Auth::loginUsingId(3);

    return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ######### Start Couple ######### //

    // Couple Spending
    Route::get('couple/spending', Filament\CoupleSpendingResource\Index::class)->name('couple.spending.index');

    // Couple Spending Categories
    Route::get('couple/spending/categories', Filament\CoupleSpendingCategoryResource\Index::class)->name('couple.spending.categories.index');

    // ######### End Couple ######### //

    // ----------------------------------------------------------------------------------- //
    // ----------------------------------------------------------------------------------- //

    // ######### Start Banks ######### //

    // Bank Accounts
    Route::get('banks/accounts', Filament\BankAccountResource\Index::class)->name('banks.accounts.index');
    Route::get('banks/accounts/create', Filament\BankAccountResource\Create::class)->name('banks.accounts.create');
    Route::get('banks/accounts/{record}/edit', Filament\BankAccountResource\Edit::class)->name('banks.accounts.edit');

    // Credit Cards
    Route::get('banks/credit-cards', Filament\CreditCardResource\Index::class)->name('banks.credit-cards.index');
    Route::get('banks/credit-cards/create', Filament\CreditCardResource\Create::class)->name('banks.credit-cards.create');
    Route::get('banks/credit-cards/{record}/edit', Filament\CreditCardResource\Edit::class)->name('banks.credit-cards.edit');

    // ######### End Banks ######### //

    // ----------------------------------------------------------------------------------- //
    // ----------------------------------------------------------------------------------- //

    // ######### Start Stock ######### //

    // Market Stock
    Route::get('markets/stocks', \App\Http\Livewire\Markets\Stock\Index::class)->name('markets.stocks.index');

    // Markets
    Route::get('markets', \App\Http\Livewire\Markets\Index::class)->name('markets.index');

    // Market Items
    Route::get('markets/items', \App\Http\Livewire\Markets\Items\Index::class)->name('markets.items.index');

    // Market Items Categories
    Route::get('markets/items/categories', \App\Http\Livewire\Markets\Items\Categories\Index::class)->name('markets.items.categories.index');

    // ######### End Stock ######### //

    // ----------------------------------------------------------------------------------- //
    // ----------------------------------------------------------------------------------- //

    // Investments
    Route::get('investments', Investments\Index::class)->name('investments.index');

    // Goals
    Route::get('goals', Goals\Index::class)->name('goals.index');

    // Trips
    Route::get('trips', Trips\Index::class)->name('trips.index');

    // Settings
    Route::get('settings', Settings::class)->name('settings');

});

require __DIR__ . '/auth.php';
