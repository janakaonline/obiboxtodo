<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::apiResource('tasks', TaskController::class);
Route::patch('tasks/{id}/complete', [TaskController::class, 'makeAsComplete'])->whereNumber('id');
Route::patch('tasks/{id}/incomplete', [TaskController::class, 'makeAsIncomplete'])->whereNumber('id');