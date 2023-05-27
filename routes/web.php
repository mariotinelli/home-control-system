<?php

use App\Http\Controllers\ProfileController;
use App\Http\Livewire\{Banks, Couple, Goals, Investments, Settings, Stock, Trips};
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

    // Couple Spending Categories
    Route::get('couple/spending/categories', Couple\Spending\Categories\Index::class)->name('couple.spending.categories.index');

    // Couple Spending
    Route::get('couple/spending', Couple\Spending\Index::class)->name('couple.spending.index');

    // ######### End Couple ######### //

    // ----------------------------------------------------------------------------------- //
    // ----------------------------------------------------------------------------------- //

    // ######### Start Banks ######### //

    // Bank Accounts
    Route::get('banks/accounts', Banks\Accounts\Index::class)->name('banks.accounts.index');
    Route::get('banks/accounts/create', Banks\Accounts\Create::class)->name('banks.accounts.create');

    // Credit Cards
    Route::get('banks/credit-cards', Banks\CreditCards\Index::class)->name('banks.credit-cards.index');

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
