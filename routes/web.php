<?php

use App\Domain\CRM\Http\Controllers\CrmLeadPageController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GlobalSearchController;
use App\Http\Controllers\NotificationCenterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::resource('companies', CompanyController::class);
    Route::resource('users', UserController::class);

    // Compatibilidade com URLs e nomes antigos. Toda a execução usa o domínio CRM.
    Route::get('/leads', [CrmLeadPageController::class, 'index'])->name('leads.index');
    Route::get('/leads/create', [CrmLeadPageController::class, 'create'])->name('leads.create');
    Route::post('/leads', [CrmLeadPageController::class, 'store'])->name('leads.store');
    Route::get('/leads/{lead}', [CrmLeadPageController::class, 'show'])->name('leads.show');
    Route::get('/leads/{lead}/edit', [CrmLeadPageController::class, 'edit'])->name('leads.edit');
    Route::put('/leads/{lead}', [CrmLeadPageController::class, 'update'])->name('leads.update');
    Route::delete('/leads/{lead}', [CrmLeadPageController::class, 'destroy'])->name('leads.destroy');

    Route::get('/global-search', GlobalSearchController::class)->name('global-search');
    Route::get('/notifications', [NotificationCenterController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/read-all', [NotificationCenterController::class, 'readAll'])->name('notifications.read-all');
    Route::patch('/notifications/{notification}/read', [NotificationCenterController::class, 'read'])->name('notifications.read');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
