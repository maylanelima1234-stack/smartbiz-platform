<?php

use App\Domain\CRM\Http\Controllers\CrmActivityController;
use App\Domain\CRM\Http\Controllers\CrmDashboardController;
use App\Domain\CRM\Http\Controllers\CrmLeadPageController;
use App\Domain\CRM\Http\Controllers\CrmMoveLeadController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])
    ->prefix('crm')
    ->name('crm.')
    ->group(function (): void {
        Route::get('/', CrmDashboardController::class)
            ->name('dashboard');

        Route::get('/kanban', [CrmLeadPageController::class, 'kanban'])
            ->name('kanban');

        Route::patch(
            '/kanban/leads/{lead}/move',
            CrmMoveLeadController::class
        )->name('kanban.move');

        Route::get('/leads', [CrmLeadPageController::class, 'index'])
            ->name('leads.index');

        Route::get('/leads/{lead}', [CrmLeadPageController::class, 'show'])
            ->name('leads.show');

        Route::post(
            '/leads/{lead}/activities',
            [CrmActivityController::class, 'store']
        )->name('activities.store');

        Route::patch(
            '/activities/{activity}/complete',
            [CrmActivityController::class, 'complete']
        )->name('activities.complete');
    });