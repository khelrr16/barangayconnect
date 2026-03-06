<?php


use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HHController;
use App\Http\Controllers\Admin\RBIController;
use App\Http\Controllers\Admin\RBIUploadController;
use App\Http\Controllers\Admin\UAController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Home\LandingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index']);

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // Guests
    Route::get('/dashboard', [LandingController::class, 'index'])->name('dashboard');
});

// Admin routes - full access
Route::middleware(['auth', 'role:staff'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])->name('dashboard.chart-data');

    Route::get('/dashboard/testing', [DashboardController::class, 'getChartData']);

    Route::get('rbi/upload', [RBIUploadController::class, 'index'])->name('rbi.upload.index');
    Route::get('rbi/upload/{csv}', [RBIUploadController::class, 'failed'])->name('rbi.upload.failed');
    Route::post('rbi/upload/csv', [RBIUploadController::class, 'uploadCsv'])->name('rbi.upload.csv');
    Route::get('rbi/upload/{importId}/status', [RBIUploadController::class, 'importStatus'])->name('rbi.upload.status');
    Route::get('rbi/printable', [RBIController::class, 'printable'])->name('rbi.printable');

    Route::resource('rbi', RBIController::class);
    Route::resource('hh', HHController::class);

    Route::middleware('role:admin')->group(function () {
        Route::resource('ua', UAController::class)->except(['show']);
    });
});

Route::middleware(['auth', 'role:staff'])->prefix('staff')->name('staff.')->group(function () {
    // Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // // Staff can only view their own data
    // Route::get('/profile', [App\Http\Controllers\Staff\ProfileController::class, 'index'])->name('profile');
    // Route::get('/reports', [App\Http\Controllers\Staff\ReportController::class, 'index'])->name('reports');
    
    // Staff cannot access user management routes - will get 403
});

