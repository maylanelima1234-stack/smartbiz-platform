<?php

use App\Domain\CRM\Http\Controllers\CrmLeadPageController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompanyWorkspaceController;
use App\Http\Controllers\AutomationController;
use App\Http\Controllers\AuditCenterController;
use App\Http\Controllers\AutomationRunController;
use App\Http\Controllers\ExecutiveDashboardController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GlobalSearchController;
use App\Http\Controllers\NotificationCenterController;
use App\Http\Controllers\OperationsFeedController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SwitchCompanyController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::get('/dashboard', DashboardController::class)
        ->middleware('permission:dashboard.view')
        ->name('dashboard');

    Route::get('/companies', [CompanyController::class, 'index'])
        ->middleware('permission:companies.view')
        ->name('companies.index');
    Route::get('/companies/create', [CompanyController::class, 'create'])
        ->middleware('permission:companies.create')
        ->name('companies.create');
    Route::post('/companies', [CompanyController::class, 'store'])
        ->middleware('permission:companies.create')
        ->name('companies.store');
    Route::get('/companies/{company}', [CompanyController::class, 'show'])
        ->middleware('permission:companies.view')
        ->name('companies.show');
    Route::get('/companies/{company}/workspace', CompanyWorkspaceController::class)
        ->middleware('permission:companies.view')
        ->name('companies.workspace');
    Route::post('/companies/{company}/activate', SwitchCompanyController::class)
        ->middleware('permission:companies.view')
        ->name('companies.activate');
    Route::get('/companies/{company}/edit', [CompanyController::class, 'edit'])
        ->middleware('permission:companies.update')
        ->name('companies.edit');
    Route::put('/companies/{company}', [CompanyController::class, 'update'])
        ->middleware('permission:companies.update')
        ->name('companies.update');
    Route::delete('/companies/{company}', [CompanyController::class, 'destroy'])
        ->middleware('permission:companies.delete')
        ->name('companies.destroy');

    Route::get('/users', [UserController::class, 'index'])
        ->middleware('permission:users.view')
        ->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])
        ->middleware('permission:users.create')
        ->name('users.create');
    Route::post('/users', [UserController::class, 'store'])
        ->middleware('permission:users.create')
        ->name('users.store');
    Route::get('/users/{user}', [UserController::class, 'show'])
        ->middleware('permission:users.view')
        ->name('users.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])
        ->middleware('permission:users.update')
        ->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])
        ->middleware('permission:users.update')
        ->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])
        ->middleware('permission:users.delete')
        ->name('users.destroy');

    Route::get('/automations', [AutomationController::class, 'index'])->name('automations.index');
    Route::get('/automations/create', [AutomationController::class, 'create'])->name('automations.create');
    Route::post('/automations', [AutomationController::class, 'store'])->name('automations.store');
    Route::post('/automations/{workflow}/test', [AutomationController::class, 'test'])->name('automations.test');
    Route::patch('/automations/{workflow}/toggle', [AutomationController::class, 'toggle'])->name('automations.toggle');
    Route::delete('/automations/{workflow}', [AutomationController::class, 'destroy'])->name('automations.destroy');
    Route::get('/audit', AuditCenterController::class)->name('audit.index');
    Route::get('/automations/runs', AutomationRunController::class)->name('automations.runs');
    Route::get('/executive', ExecutiveDashboardController::class)
        ->middleware('permission:dashboard.view')
        ->name('executive.index');

    Route::get('/global-search', GlobalSearchController::class)->name('global-search');
    Route::get('/operations/feed', OperationsFeedController::class)
        ->middleware('permission:dashboard.view')
        ->name('operations.feed');
    Route::get('/notifications', [NotificationCenterController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/read-all', [NotificationCenterController::class, 'readAll'])->name('notifications.read-all');
    Route::patch('/notifications/{notification}/read', [NotificationCenterController::class, 'read'])->name('notifications.read');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require app_path('Domain/CRM/Routes/web.php');
require __DIR__.'/auth.php';
