<?php

// Adicione este use no topo do routes/web.php:
// use App\Http\Controllers\CompanyController;

// Dentro do grupo Route::middleware(['auth', 'verified'])->group(function () { ... });
// substitua todas as rotas antigas de companies por esta linha:
Route::resource('companies', CompanyController::class);
