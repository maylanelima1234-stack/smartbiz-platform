// Cole dentro do grupo Route::middleware(['auth', 'verified'])->group(function () { ... });
Route::get('/users', [\App\Http\Controllers\UserManagementController::class, 'index'])->name('users.index');
Route::get('/users/create', [\App\Http\Controllers\UserManagementController::class, 'create'])->name('users.create');
Route::post('/users', [\App\Http\Controllers\UserManagementController::class, 'store'])->name('users.store');
Route::get('/users/{user}', [\App\Http\Controllers\UserManagementController::class, 'show'])->name('users.show');
Route::get('/users/{user}/edit', [\App\Http\Controllers\UserManagementController::class, 'edit'])->name('users.edit');
Route::put('/users/{user}', [\App\Http\Controllers\UserManagementController::class, 'update'])->name('users.update');
Route::delete('/users/{user}', [\App\Http\Controllers\UserManagementController::class, 'destroy'])->name('users.destroy');
