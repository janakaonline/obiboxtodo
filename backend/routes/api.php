<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskOverviewController;
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

Route::get('tasks/due-today', [TaskController::class, 'tasksDueToday']);
Route::get('tasks/overdue', [TaskController::class, 'overdueTasks']);

Route::patch('tasks/{id}/complete', [TaskController::class, 'makeAsComplete'])->whereNumber('id');
Route::patch('tasks/{id}/incomplete', [TaskController::class, 'makeAsIncomplete'])->whereNumber('id');
Route::apiResource('tasks', TaskController::class);



Route::get('overview/task-completion', [TaskOverviewController::class, 'taskCompletionOverview']);
Route::get('overview/tasks-by-priority', [TaskOverviewController::class, 'tasksByPriorityOverview']);