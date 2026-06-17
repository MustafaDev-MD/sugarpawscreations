<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\CategoryController;
use App\Http\Middleware\EnsureTeamMembership;
use App\Livewire\Admin\Categories;
use App\Livewire\Admin\Categories as AdminCategories;
use App\Livewire\Admin\Portfolios;


/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    view('welcome');
})->name('dashboard');

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::get('/category/{category:slug}', [CategoryController::class, 'show'])
    ->name('category.show');

/*
|--------------------------------------------------------------------------
| Admin / Dashboard (Starter Kit)
|--------------------------------------------------------------------------
*/

Route::prefix('{current_team}')
    ->middleware(['auth', 'verified', EnsureTeamMembership::class])
    ->group(function () {
        Route::view('dashboard', 'dashboard')->name('dashboard');
    });

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    Route::livewire(
        'invitations/{invitation}/accept',
        'pages::teams.accept-invitation'
    )->name('invitations.accept');
});

Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/categories', Categories::class)->name('categories');
    Route::get('/portfolios', Portfolios::class)->name('portfolios');
});

require __DIR__ . '/settings.php';
