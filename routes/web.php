<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/tasks', [TaskController::class, 'index'])
    ->middleware('auth')
    ->name('tasks.index');
