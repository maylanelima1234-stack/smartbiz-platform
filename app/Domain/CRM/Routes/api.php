<?php

use App\Domain\CRM\Http\Controllers\CrmMoveLeadController;
use App\Domain\CRM\Http\Controllers\LeadController;
use App\Domain\CRM\Http\Controllers\PipelineController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'auth'])
    ->prefix('crm')
    ->name('crm.api.')
    ->group(function (): void {
        Route::apiResource('leads', LeadController::class);

        Route::get('/pipelines', [PipelineController::class, 'index'])
            ->name('pipelines.index');

        Route::patch(
            '/kanban/leads/{lead}/move',
            CrmMoveLeadController::class
        )->name('kanban.move');
    });