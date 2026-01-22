<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/tasks', [TaskController::class, 'index'])
    ->name('tasks.index');


Route::middleware('auth')->group(function () {
    Route::get('/tasks', [TaskController::class, 'index'])
        ->name('tasks.index');
});

require __DIR__.'/auth.php';
