<?php

use App\Domain\CRM\Http\Controllers\CrmActivityController;
use App\Domain\CRM\Http\Controllers\CrmAgendaController;
use App\Domain\CRM\Http\Controllers\CrmDashboardController;
use App\Domain\CRM\Http\Controllers\CrmEngagementController;
use App\Domain\CRM\Http\Controllers\CrmLeadPageController;
use App\Domain\CRM\Http\Controllers\CrmLeadFileController;
use App\Domain\CRM\Http\Controllers\CrmMoveLeadController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', 'verified'])
    ->prefix('crm')
    ->name('crm.')
    ->group(function (): void {
        Route::get('/', CrmDashboardController::class)->middleware('permission:crm.view')->name('index');
        Route::get('/dashboard', CrmDashboardController::class)->middleware('permission:crm.view')->name('dashboard');
        Route::get('/agenda', CrmAgendaController::class)->middleware('permission:agenda.view')->name('agenda');
        Route::get('/kanban', [CrmLeadPageController::class, 'kanban'])->middleware('permission:crm.view')->name('kanban');
        Route::patch('/kanban/leads/{lead}/move', CrmMoveLeadController::class)->middleware('permission:crm.update')->name('kanban.move');

        Route::get('/leads', [CrmLeadPageController::class, 'index'])->middleware('permission:crm.view')->name('leads.index');
        Route::get('/leads/create', [CrmLeadPageController::class, 'create'])->middleware('permission:crm.create')->name('leads.create');
        Route::post('/leads', [CrmLeadPageController::class, 'store'])->middleware('permission:crm.create')->name('leads.store');
        Route::get('/leads/{lead}', [CrmLeadPageController::class, 'show'])->middleware('permission:crm.view')->name('leads.show');
        Route::get('/leads/{lead}/edit', [CrmLeadPageController::class, 'edit'])->middleware('permission:crm.update')->name('leads.edit');
        Route::put('/leads/{lead}', [CrmLeadPageController::class, 'update'])->middleware('permission:crm.update')->name('leads.update');
        Route::delete('/leads/{lead}', [CrmLeadPageController::class, 'destroy'])->middleware('permission:crm.delete')->name('leads.destroy');

        Route::post('/leads/{lead}/activities', [CrmActivityController::class, 'store'])->name('activities.store');
        Route::patch('/activities/{activity}/complete', [CrmActivityController::class, 'complete'])->name('activities.complete');
        Route::post('/leads/{lead}/comments', [CrmEngagementController::class, 'comment'])->name('comments.store');
        Route::put('/leads/{lead}/tags', [CrmEngagementController::class, 'tags'])->name('tags.sync');
        Route::post('/leads/{lead}/files', [CrmLeadFileController::class, 'store'])->middleware('permission:crm.update')->name('files.store');
        Route::get('/files/{file}/download', [CrmLeadFileController::class, 'download'])->middleware('permission:crm.view')->name('files.download');
        Route::delete('/files/{file}', [CrmLeadFileController::class, 'destroy'])->middleware('permission:crm.update')->name('files.destroy');
        Route::patch('/leads/{lead}/favorite', [CrmEngagementController::class, 'favorite'])->name('favorite.toggle');
    });
