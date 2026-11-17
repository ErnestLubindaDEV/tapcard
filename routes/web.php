<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkspaceController;
use App\Http\Controllers\WorkspaceMemberController;
use App\Http\Controllers\BusinessCardController;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\PublicBusinessCardController;
use App\Http\Controllers\PublicCardQrController;
use App\Http\Controllers\PublicCardContactController;
use App\Http\Controllers\CardSocialLinkController;
use App\Http\Controllers\CardAnalyticsController;
use App\Http\Controllers\PublicSocialLinkController;


Route::get('/media/{path}', function (string $path) {
    abort_if(str_contains($path, '..'), 404);

    $disk = Storage::disk('public');

    abort_unless($disk->exists($path), 404);

    return response()->file($disk->path($path), [
        'Cache-Control' => 'public, max-age=86400',
    ]);
})->where('path', '.*')->name('media.show');


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');




    Route::prefix('workspaces')
        ->name('workspaces.')
        ->controller(WorkspaceController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');

            Route::get('/{workspace}', 'show')->name('show');
            Route::get('/{workspace}/edit', 'edit')->name('edit');
            Route::put('/{workspace}', 'update')->name('update');
            Route::delete('/{workspace}', 'destroy')->name('destroy');
        });



    Route::prefix('workspaces/{workspace}/members')
        ->name('workspaces.members.')
        ->controller(WorkspaceMemberController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');

            Route::patch('/{member}/role', 'updateRole')
                ->name('role.update');

            Route::patch('/{member}/status', 'updateStatus')
                ->name('status.update');

            Route::delete('/{member}', 'destroy')
                ->name('destroy');
        });
});

require __DIR__ . '/auth.php';


Route::middleware(['auth'])
    ->prefix('workspaces/{workspace}/cards')
    ->name('business-cards.')
    ->controller(BusinessCardController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');

        Route::get('/create', 'create')->name('create');

        Route::post('/', 'store')->name('store');

        Route::get('/{businessCard}', 'show')->name('show');

        Route::get('/{businessCard}/edit', 'edit')->name('edit');

        Route::put('/{businessCard}', 'update')->name('update');

        Route::patch('/{businessCard}/publish', 'publish')->name('publish');

        Route::patch('/{businessCard}/unpublish', 'unpublish')->name('unpublish');

        Route::patch('/{businessCard}/primary', 'setPrimary')->name('primary');

        Route::delete('/{businessCard}', 'destroy')->name('destroy');
    });

Route::get('/c/{slug}', [PublicBusinessCardController::class, 'show'])
    ->name('cards.public');

Route::get('/c/{slug}/qr', [PublicCardQrController::class, 'show'])
    ->name('cards.qr');

Route::get('/c/{slug}/qr/download', [PublicCardQrController::class, 'download'])
    ->name('cards.qr.download');
Route::get('/c/{slug}/contact', [PublicCardContactController::class, 'download'])
    ->name('cards.contact.download');


Route::get(
    '/c/{slug}/social/{socialLink}',
    [PublicSocialLinkController::class, 'redirect']
)->name('cards.social.redirect');



Route::middleware(['auth'])
    ->prefix('workspaces/{workspace}/cards/{businessCard}/social-links')
    ->name('business-cards.social-links.')
    ->controller(CardSocialLinkController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::put('/{socialLink}', 'update')->name('update');
        Route::delete('/{socialLink}', 'destroy')->name('destroy');
    });


Route::middleware(['auth'])
    ->prefix('workspaces/{workspace}/cards/{businessCard}/analytics')
    ->name('business-cards.analytics.')
    ->controller(CardAnalyticsController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
    });



    