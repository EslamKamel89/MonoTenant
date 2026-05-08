<?php

use App\Http\Middleware\EnsureValidTenantAccess;
use App\Http\Middleware\InitializeTenantContext;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth', 'verified', InitializeTenantContext::class])->group(function () {
    Route::view('/', 'welcome')->name('home');
    Route::prefix('/{tenant:slug}')->middleware([EnsureValidTenantAccess::class])->group(function () {
        Route::view('dashboard', 'dashboard')->name('dashboard');
        Route::livewire('/users', 'pages::users.index')->name('users.index');
        Route::livewire('/articles', 'pages::articles.index')->name('articles.index');
    });
});




require __DIR__ . '/settings.php';
