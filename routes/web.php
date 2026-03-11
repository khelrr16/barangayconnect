<?php


use App\Http\Controllers\Admin\CommitteeController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OfficialController;
use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Committee\DashboardController as CommitteeDashboardController;
use App\Http\Controllers\Home\LandingController;
use App\Http\Controllers\HouseholdController;
use App\Http\Controllers\ImmunizationController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\ResidentUploadController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index']);

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/forgot-password', [LoginController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [LoginController::class, 'sendResetCode'])->name('password.email');
    Route::get('/reset-password', [LoginController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [LoginController::class, 'resetPassword'])->name('password.update');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [LandingController::class, 'index'])->name('dashboard');

    //Manage residents
    Route::middleware('permission:view residents')->group(function () {
        Route::get('/resident', [ResidentController::class, 'index'])->name('resident.index');
        Route::get('/resident/{resident}', [ResidentController::class, 'show'])->name('resident.show');
    });

    Route::middleware('permission:manage residents')->group(function () {
        Route::post('/resident', [ResidentController::class, 'store'])->name('resident.store');
        Route::get('/resident/create', [ResidentController::class, 'create'])->name('resident.create');
        Route::patch('/resident/{resident}', [ResidentController::class, 'update'])->name('resident.update');
        Route::delete('/resident/{resident}', [ResidentController::class, 'destroy'])->name('resident.destroy');
        Route::get('/resident/{resident}/edit', [ResidentController::class, 'edit'])->name('resident.edit');

        Route::get('resident/upload', [ResidentUploadController::class, 'index'])->name('resident.upload.index');
        Route::get('resident/upload/{csv}', [ResidentUploadController::class, 'failed'])->name('resident.upload.failed');
        Route::post('resident/upload/csv', [ResidentUploadController::class, 'uploadCsv'])->name('resident.upload.csv');
        Route::get('resident/upload/{importId}/status', [ResidentUploadController::class, 'importStatus'])->name('resident.upload.status');
        Route::get('resident/printable', [ResidentController::class, 'printable'])->name('resident.printable');
        Route::patch('resident/{resident}/restore', [ResidentController::class, 'restore'])->name('resident.restore');
    });

    //Manage households
    Route::middleware('permission:view households')->group(function () {
        Route::get('/household', [HouseholdController::class, 'index'])->name('household.index');
        Route::get('/household/{household}', [HouseholdController::class, 'show'])->name('household.show');
    });

    Route::middleware('permission:manage households')->group(function () {
        Route::post('/household', [HouseholdController::class, 'store'])->name('household.store');
        Route::get('/household/create', [HouseholdController::class, 'create'])->name('household.create');
        Route::patch('/household/{household}', [HouseholdController::class, 'update'])->name('household.update');
        Route::delete('/household/{household}', [HouseholdController::class, 'destroy'])->name('household.destroy');
        Route::get('/household/{household}/edit', [HouseholdController::class, 'edit'])->name('household.edit');
    });

    //Admin
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::middleware('permission:view admin_dashboard')->group(function () {
            Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
            Route::get('/dashboard/chart-data', [AdminDashboardController::class, 'getChartData'])->name('dashboard.chart-data');
            Route::get('/dashboard/testing', [AdminDashboardController::class, 'getChartData']);
        });

        Route::resource('users', UserController::class)->except(['show']);
        Route::resource('official', OfficialController::class);
        Route::resource('committee', CommitteeController::class);

        Route::get('roles-permissions', [RolePermissionController::class, 'index'])->name('roles-permissions.index');
        Route::patch('roles-permissions/{role}', [RolePermissionController::class, 'update'])->name('roles-permissions.update');
    });

    //Committees
    Route::middleware('role:committee_head,committee_member')->prefix('committee')->name('committee.')->group(function () {
        Route::middleware('permission:view committee_dashboard')->group(function () {
            Route::get('/dashboard', [CommitteeDashboardController::class, 'index'])->name('dashboard');
        });

        Route::middleware('committee:health_sanitation')->group(function () {
            Route::get('/immunization', [ImmunizationController::class, 'index'])->name('immunization.index');
            Route::post('/immunization', [ImmunizationController::class, 'store'])->name('immunization.store');
            Route::get('/immunization/create', [ImmunizationController::class, 'create'])->name('immunization.create');
            Route::get('/immunization/{infant}', [ImmunizationController::class, 'show'])->name('immunization.show');
            Route::get('/immunization/{infant}/edit', [ImmunizationController::class, 'edit'])->name('immunization.edit');
            
        });
    });
});