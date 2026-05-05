<?php

use App\Http\Middleware\TenantMiddleware;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified', TenantMiddleware::class])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::livewire('/users', 'pages::users.index')->name('users.index');
    Route::livewire('/articles', 'pages::articles.index')->name('articles.index');
});


require __DIR__ . '/settings.php';
