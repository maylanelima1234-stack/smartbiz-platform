<?php

use App\Domain\CRM\Http\Controllers\LeadController;
use App\Domain\CRM\Http\Controllers\PipelineController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])
    ->prefix('api/crm')
    ->name('crm.api.')
    ->group(function (): void {
        Route::get('/pipelines', [PipelineController::class, 'index']);

        Route::patch('/leads/{lead}/move', [
            LeadController::class,
            'move',
        ]);

        Route::apiResource('leads', LeadController::class);
    });
