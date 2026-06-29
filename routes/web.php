<?php

use App\Http\Controllers\ContactFormController;
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

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::get('/category/{category:slug}', [CategoryController::class, 'show'])
    ->name('category.show');

Route::get('/img/{path}', function ($path) {
    $fullPath = storage_path('app/public/' . $path);
    
    if (!file_exists($fullPath)) {
        abort(404);
    }
    
    return response()->file($fullPath, [
        'Cache-Control' => 'public, max-age=604800',
    ]);
})->where('path', '.*');

Route::get('/contact-us', [HomeController::class, 'contactUs'])
    ->name('contact-us');

Route::post('/contact-submit', [ContactFormController::class, 'submitContact'])
    ->name('contact.submit');

// Route::get('/img/{path}', function ($path) {
    
//     $fullPath = public_path('uploads/' . $path);
    
//     if (!file_exists($fullPath)) {
//         $fullPath = storage_path('app/public/' . $path);
//     }
    
//     if (!file_exists($fullPath)) {
//         abort(404);
//     }
    
//     return response()->file($fullPath, [
//         'Cache-Control' => 'public, max-age=604800',
//     ]);
// })->where('path', '.*');

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

Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/admin-test', function () {
        return 'Admin Access Granted';
    });

    Route::view('/admin/register-user', 'admin.register-user');

});

require __DIR__ . '/settings.php';
