<?php

use App\Http\Controllers\RouteOptimizationController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware(['auth'])->group(function () {
    Route::view('dashboard', 'dashboard') ->name('dashboard');

    /** Trucks */
    Route::get('trucks', App\Livewire\Trucks\Index::class)->name('trucks');
    Route::get('trucks/{truck}', App\Livewire\Trucks\Show::class)->name('trucks.show');

    /** Production Sites */
    Route::get('production-sites', App\Livewire\ProductionSites\Index::class)->name('production-sites');
    Route::get('production-sites/{production_site}', App\Livewire\ProductionSites\Show::class)->name('production-sites.show');
    Route::get('production-sites/{production_site}/events', App\Livewire\ProductionSites\Events::class)->name('production-sites.events');

    /** Delivery Companies */
    Route::get('delivery-companies', App\Livewire\DeliveryCompanies\Index::class)->name('delivery-companies');

    /** Delivery Companies */
    Route::get('credit-companies', App\Livewire\CreditCompanies\Index::class)->name('credit-companies');

    /** Settings */
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

Route::post('/routes/optimise', [RouteOptimizationController::class, 'optimise'])->name('routes.optimise');
