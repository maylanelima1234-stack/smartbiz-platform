<?php

// Cole dentro do grupo Route::middleware(['auth', 'verified'])->group(function () { ... });

use App\Http\Controllers\CrmController;
use App\Http\Controllers\LeadController;

Route::get('/crm', [CrmController::class, 'index'])->name('crm.index');
Route::get('/leads', [LeadController::class, 'index'])->name('leads.index');
Route::get('/leads/create', [LeadController::class, 'create'])->name('leads.create');
Route::post('/leads', [LeadController::class, 'store'])->name('leads.store');
Route::get('/leads/{lead}', [LeadController::class, 'show'])->name('leads.show');
Route::get('/leads/{lead}/edit', [LeadController::class, 'edit'])->name('leads.edit');
Route::put('/leads/{lead}', [LeadController::class, 'update'])->name('leads.update');
Route::delete('/leads/{lead}', [LeadController::class, 'destroy'])->name('leads.destroy');
