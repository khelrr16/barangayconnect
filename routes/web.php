<?php


use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\CertificateGenerationController;
use App\Http\Controllers\Admin\CertificateRequestController;
use App\Http\Controllers\Admin\CommitteeController;
use App\Http\Controllers\Admin\ResidentLinkVerificationController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OfficialController;
use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BudgetFinance\BudgetOverviewController;
use App\Http\Controllers\BudgetFinance\DisbursementController;
use App\Http\Controllers\BudgetFinance\FundSourceController;
use App\Http\Controllers\BudgetFinance\FinancialReportController;
use App\Http\Controllers\Committee\DashboardController as CommitteeDashboardController;
use App\Http\Controllers\Committee\PermissionsController as CommitteePermissionsController;
use App\Http\Controllers\Committee\Health\DashboardController as CommitteeHealthDashboardController;
use App\Http\Controllers\Health\ImmunizationController;
use App\Http\Controllers\Health\InfantController;
use App\Http\Controllers\Health\MedicineBatchController;
use App\Http\Controllers\Health\MedicineController;
use App\Http\Controllers\Health\NutritionalAssessmentController;
use App\Http\Controllers\Home\LandingController;
use App\Http\Controllers\HouseholdController;
use App\Http\Controllers\Peace\BlotterController;
use App\Http\Controllers\Peace\DashboardController as CommitteePeaceDashboardController;
use App\Http\Controllers\Resident\PortalController as ResidentPortalController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\ResidentUploadController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index']);

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [LoginController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [LoginController::class, 'registerStore'])->name('register.store');
    Route::get('/forgot-password', [LoginController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [LoginController::class, 'sendResetCode'])->name('password.email');
    Route::get('/reset-password', [LoginController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [LoginController::class, 'resetPassword'])->name('password.update');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/', [LandingController::class, 'index'])->name('index');

    // Resident lookup endpoints used by reusable modal components.
    Route::get('/api/residents/search', [ResidentController::class, 'apiSearch']);
    Route::get('/api/residents/{resident}', [ResidentController::class, 'apiShow']);
    Route::get('/api/residents/{resident}/address', [ResidentController::class, 'apiAddress']);

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
            Route::get('/', [AdminDashboardController::class, 'index'])->name('index');
            Route::get('/dashboard/chart-data', [AdminDashboardController::class, 'getChartData'])->name('dashboard.chart-data');
            Route::get('/dashboard/testing', [AdminDashboardController::class, 'getChartData']);
        });

        Route::resource('users', UserController::class)->except(['show']);
        Route::resource('official', OfficialController::class);
        Route::resource('committee', CommitteeController::class);
        Route::get('committee/{committee}/open', [CommitteeController::class, 'openDashboard'])->name('committee.open');


        Route::get('/resident', [ResidentController::class, 'index'])->name('resident.index');
        Route::post('/resident', [ResidentController::class, 'store'])->name('resident.store');
        Route::get('/resident/create', [ResidentController::class, 'create'])->name('resident.create');

        Route::get('resident/upload', [ResidentUploadController::class, 'index'])->name('resident.upload.index');
        Route::get('resident/upload/{csv}', [ResidentUploadController::class, 'failed'])->name('resident.upload.failed');
        Route::post('resident/upload/csv', [ResidentUploadController::class, 'uploadCsv'])->name('resident.upload.csv');
        Route::get('resident/upload/{importId}/status', [ResidentUploadController::class, 'importStatus'])->name('resident.upload.status');
        Route::get('/resident/{resident}', [ResidentController::class, 'show'])->name('resident.show');
        Route::patch('/resident/{resident}', [ResidentController::class, 'update'])->name('resident.update');
        Route::delete('/resident/{resident}', [ResidentController::class, 'destroy'])->name('resident.destroy');
        Route::get('/resident/{resident}/edit', [ResidentController::class, 'edit'])->name('resident.edit');
        Route::patch('resident/{resident}/restore', [ResidentController::class, 'restore'])->name('resident.restore');


        //Manage households
        Route::middleware('permission:view households')->group(function () {
            Route::get('/household', [HouseholdController::class, 'index'])->name('household.index');
            Route::get('/household/{household}', [HouseholdController::class, 'show'])->name('household.show');
        });

        Route::get('roles-permissions', [RolePermissionController::class, 'index'])->name('roles-permissions.index');
        Route::get('roles-permissions/create', [RolePermissionController::class, 'create'])->name('roles-permissions.create');
        Route::post('roles-permissions', [RolePermissionController::class, 'store'])->name('roles-permissions.store');
        Route::patch('roles-permissions/{role}', [RolePermissionController::class, 'update'])->name('roles-permissions.update');
        Route::delete('roles-permissions/{role}', [RolePermissionController::class, 'destroy'])->name('roles-permissions.destroy');

        Route::get('certificate-requests', [CertificateRequestController::class, 'index'])->name('certificate-requests.index');
        Route::get('certificate-requests/{certificate_request}', [CertificateRequestController::class, 'show'])->name('certificate-requests.show');
        Route::patch('certificate-requests/{certificate_request}', [CertificateRequestController::class, 'update'])->name('certificate-requests.update');

        Route::get('certificates/indigency', [CertificateGenerationController::class, 'createIndigency'])->name('certificates.indigency.create');
        Route::post('certificates/indigency', [CertificateGenerationController::class, 'generateIndigency'])->name('certificates.indigency.generate');
        Route::get('certificates/residents-search', [CertificateGenerationController::class, 'searchResidents'])->name('certificates.residents-search');

        Route::get('verification-requests', [ResidentLinkVerificationController::class, 'index'])->name('verification-requests.index');
        Route::get('verification-requests/{verification}', [ResidentLinkVerificationController::class, 'show'])->name('verification-requests.show');
        Route::patch('verification-requests/{verification}/approve', [ResidentLinkVerificationController::class, 'approve'])->name('verification-requests.approve');
        Route::patch('verification-requests/{verification}/reject', [ResidentLinkVerificationController::class, 'reject'])->name('verification-requests.reject');

        Route::get('announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
        Route::get('announcements/create', [AnnouncementController::class, 'create'])->name('announcements.create');
        Route::post('announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
        Route::get('announcements/{announcement}/edit', [AnnouncementController::class, 'edit'])->name('announcements.edit');
        Route::patch('announcements/{announcement}', [AnnouncementController::class, 'update'])->name('announcements.update');
        Route::delete('announcements/{announcement}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');
    });

    // Resident portal
    Route::middleware(['role:resident'])->prefix('resident')->name('resident.')->group(function () {
        Route::get('/', [ResidentPortalController::class, 'index'])->name('index');
        Route::get('/profile', [ResidentPortalController::class, 'profile'])->name('profile');
        Route::post('/profile/send-verification', [ResidentPortalController::class, 'sendVerification'])->name('profile.send-verification');
        Route::get('/request-document', [ResidentPortalController::class, 'requestDocument'])->name('request-document');
        Route::post('/request-document', [ResidentPortalController::class, 'storeDocumentRequest'])->name('request-document.store');
        Route::get('/my-requests', [ResidentPortalController::class, 'myRequests'])->name('my-requests');
        Route::get('/announcements', [ResidentPortalController::class, 'announcements'])->name('announcements');
        Route::get('/contact', [ResidentPortalController::class, 'contact'])->name('contact');
    });

    //Committees
    Route::middleware('committee_role')->prefix('committee')->name('committee.')->group(function () {
        Route::get('/', [CommitteeDashboardController::class, 'index'])->name('index');

        Route::get('/permissions', [CommitteePermissionsController::class, 'index'])->name('permissions.index');
        Route::post('/permissions', [CommitteePermissionsController::class, 'store'])->name('permissions.store');
        Route::delete('/permissions/{access}', [CommitteePermissionsController::class, 'destroy'])->name('permissions.destroy');

        Route::middleware('committee:peace_order')->prefix('peace')->name('peace.')->group(function () {
            Route::get('/', [CommitteePeaceDashboardController::class, 'index'])->name('index');
            Route::get('/blotter', [BlotterController::class, 'index'])->name('blotter.index');
            Route::post('/blotter', [BlotterController::class, 'store'])->name('blotter.store');
            Route::get('/blotter/create', [BlotterController::class, 'create'])->name('blotter.create');
            Route::get('/blotter/{blotter}', [BlotterController::class, 'show'])->name('blotter.show');
            Route::patch('/blotter/{blotter}', [BlotterController::class, 'update'])->name('blotter.update');
            Route::delete('/blotter/{blotter}', [BlotterController::class, 'destroy'])->name('blotter.destroy');
            Route::get('/blotter/{blotter}/edit', [BlotterController::class, 'edit'])->name('blotter.edit');
            Route::post('/blotter/{blotter}/generate/hearing', [BlotterController::class, 'generateHearing'])->name('blotter.generateHearing');
            Route::patch('/blotter/{blotter}/update-status', [BlotterController::class, 'updateStatus'])->name('blotter.update-status');

        });

        Route::middleware('committee:health_sanitation')->prefix('health')->name('health.')->group(function () {
            Route::get('/', [CommitteeHealthDashboardController::class, 'index'])->name('index');
            Route::get('/dashboard/print', [CommitteeHealthDashboardController::class, 'print'])->name('dashboard.print');
            Route::get('/dashboard/pdf', [CommitteeHealthDashboardController::class, 'pdf'])->name('dashboard.pdf');

            Route::get('/immunization', [ImmunizationController::class, 'index'])->name('immunization.index');
            Route::get('/immunization/{infant}', [ImmunizationController::class, 'show'])->name('immunization.show');
            Route::patch('/immunization/{infant}', [ImmunizationController::class, 'update'])->name('immunization.update');
            Route::patch('/immunization/{infant}/addInfo', [ImmunizationController::class, 'addInfo'])->name('immunization.addInfo.update');
            Route::patch('/immunization/{infant}/assessment', [NutritionalAssessmentController::class, 'update'])->name('immunization.assessment.update');

            Route::post('/infant', [InfantController::class, 'store'])->name('infant.store');
            Route::get('/infant/create', [InfantController::class, 'create'])->name('infant.create');
            Route::patch('/infant/{infant}', [InfantController::class, 'update'])->name('infant.update');

            Route::get('/medicine', [MedicineController::class, 'index'])->name('medicine.index');
            Route::post('/medicine', [MedicineController::class, 'store'])->name('medicine.store');
            Route::get('/medicine/{medicine}', [MedicineController::class, 'show'])->name('medicine.show');
            Route::delete('/medicine/{medicine}', [MedicineController::class, 'destroy'])->name('medicine.destroy');
            Route::get('/medicine/create', [MedicineController::class, 'create'])->name('medicine.create');

            Route::post('/medicine/batch', [MedicineBatchController::class, 'store'])->name('medicine.batch.store');
            Route::patch('/medicine/batch/{medicine_batch}', [MedicineBatchController::class, 'update'])->name('medicine.batch.update');
            Route::delete('/medicine/batch/{medicine_batch}', [MedicineBatchController::class, 'destroy'])->name('medicine.batch.destroy');
        });

        Route::middleware('committee:budget_finance')->group(function () {
            Route::get('/budget', [BudgetOverviewController::class, 'index'])->name('budget.index');
            Route::get('/disbursements', [DisbursementController::class, 'index'])->name('disbursements.index');
            Route::get('/disbursements/create', [DisbursementController::class, 'create'])->name('disbursements.create');
            Route::post('/disbursements', [DisbursementController::class, 'store'])->name('disbursements.store');
            Route::get('/fund-sources', [FundSourceController::class, 'index'])->name('fund-sources.index');
            Route::get('/fund-sources/create', [FundSourceController::class, 'create'])->name('fund-sources.create');
            Route::post('/fund-sources', [FundSourceController::class, 'store'])->name('fund-sources.store');
            Route::get('/reports', [FinancialReportController::class, 'index'])->name('reports.index');
        });
    });
});
