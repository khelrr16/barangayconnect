<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\RBIController;
use App\Http\Controllers\Admin\UAController;
use App\Http\Controllers\Home\LandingController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index']);
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/rbi', [RBIController::class, 'index'])->name('rbi');
    Route::get('/ua', [UAController::class, 'index'])->name('ua');
    Route::post('/ua', [UAController::class, 'store'])->name('ua.store');
    Route::put('/ua/{id}', [UAController::class, 'update'])->name('ua.update');
    Route::delete('/ua/{id}', [UAController::class, 'destroy'])->name('ua.destroy');
});

