<?php

use App\Domain\CRM\Http\Controllers\CrmDashboardController;
use App\Domain\CRM\Http\Controllers\CrmLeadPageController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])
    ->prefix('crm')
    ->name('crm.')
    ->group(function (): void {
        Route::get('/', CrmDashboardController::class)
            ->name('dashboard');

        Route::get('/kanban', [CrmLeadPageController::class, 'kanban'])
            ->name('kanban');

        Route::get('/leads', [CrmLeadPageController::class, 'index'])
            ->name('leads.index');

        Route::get('/leads/{lead}', [CrmLeadPageController::class, 'show'])
            ->name('leads.show');
    });
