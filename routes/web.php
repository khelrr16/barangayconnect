<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Home\LandingController;
use App\Http\Controllers\Admin\HomeController;

Route::get('/', [LandingController::class, 'index']);

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
});