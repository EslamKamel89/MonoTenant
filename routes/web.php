<?php

use App\Context\TenantContext;
use App\Http\Middleware\TenantMiddleware;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::livewire('/tenants', 'pages::tenants.index')->name('tenants.index');
    Route::livewire('/my-tenants', 'pages::tenants.my-tenants')->name('tenants.my-tenants');
});
Route::prefix('/{tenant}')->name('tenant.')->middleware([TenantMiddleware::class])->group(function () {
    Route::view('/', 'welcome')->name('home');
    Route::get('/test', function () {
        dump(TenantContext::get());
        dump(User::all());
    });
});
require __DIR__ . '/settings.php';
