<?php

use App\Domain\CRM\Http\Controllers\CrmActivityController;
use App\Domain\CRM\Http\Controllers\CrmAgendaController;
use App\Domain\CRM\Http\Controllers\CrmDashboardController;
use App\Domain\CRM\Http\Controllers\CrmEngagementController;
use App\Domain\CRM\Http\Controllers\CrmLeadPageController;
use App\Domain\CRM\Http\Controllers\CrmMoveLeadController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', 'verified'])
    ->prefix('crm')
    ->name('crm.')
    ->group(function (): void {
        Route::get('/', CrmDashboardController::class)->name('index');
        Route::get('/dashboard', CrmDashboardController::class)->name('dashboard');
        Route::get('/agenda', CrmAgendaController::class)->name('agenda');
        Route::get('/kanban', [CrmLeadPageController::class, 'kanban'])->name('kanban');
        Route::patch('/kanban/leads/{lead}/move', CrmMoveLeadController::class)->name('kanban.move');

        Route::get('/leads', [CrmLeadPageController::class, 'index'])->name('leads.index');
        Route::get('/leads/create', [CrmLeadPageController::class, 'create'])->name('leads.create');
        Route::post('/leads', [CrmLeadPageController::class, 'store'])->name('leads.store');
        Route::get('/leads/{lead}', [CrmLeadPageController::class, 'show'])->name('leads.show');
        Route::get('/leads/{lead}/edit', [CrmLeadPageController::class, 'edit'])->name('leads.edit');
        Route::put('/leads/{lead}', [CrmLeadPageController::class, 'update'])->name('leads.update');
        Route::delete('/leads/{lead}', [CrmLeadPageController::class, 'destroy'])->name('leads.destroy');

        Route::post('/leads/{lead}/activities', [CrmActivityController::class, 'store'])->name('activities.store');
        Route::patch('/activities/{activity}/complete', [CrmActivityController::class, 'complete'])->name('activities.complete');
        Route::post('/leads/{lead}/comments', [CrmEngagementController::class, 'comment'])->name('comments.store');
        Route::put('/leads/{lead}/tags', [CrmEngagementController::class, 'tags'])->name('tags.sync');
        Route::patch('/leads/{lead}/favorite', [CrmEngagementController::class, 'favorite'])->name('favorite.toggle');
    });
