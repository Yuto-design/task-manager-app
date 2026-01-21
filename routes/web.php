<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/tasks', [TaskController::class, 'index']);

require __DIR__.'/auth.php';
