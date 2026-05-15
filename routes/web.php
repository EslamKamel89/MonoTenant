<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::livewire('/tenants', 'pages::tenants.index')->name('tenants.index');
    Route::livewire('/my-tenants', 'pages::tenants.my-tenants')->name('tenants.my-tenants');
});

require __DIR__ . '/settings.php';
