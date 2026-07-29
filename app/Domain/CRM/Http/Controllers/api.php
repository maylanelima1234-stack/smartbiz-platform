<?php

use App\Domain\CRM\Http\Controllers\CrmMoveLeadController;
use App\Domain\CRM\Http\Controllers\LeadController;
use App\Domain\CRM\Http\Controllers\PipelineController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'auth'])
    ->prefix('crm')
    ->name('crm.api.')
    ->group(function (): void {

        /*
        |--------------------------------------------------------------------------
        | Leads
        |--------------------------------------------------------------------------
        */

        Route::apiResource('leads', LeadController::class);

        /*
        |--------------------------------------------------------------------------
        | Pipelines
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/pipelines',
            [PipelineController::class, 'index']
        )->name('pipelines.index');

        /*
        |--------------------------------------------------------------------------
        | Kanban
        |--------------------------------------------------------------------------
        */

        Route::patch(
            '/kanban/leads/{lead}/move',
            CrmMoveLeadController::class
        )->name('kanban.move');
    });