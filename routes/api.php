<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TaskController;


Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::get('me', 'me');
    Route::post('login', 'login');
    Route::delete('logout', 'logout');
    Route::post('refresh', 'refresh');
});


Route::controller(TaskController::class)->group(function () {
    Route::get('tasks', 'index');
    Route::post('tasks', 'store');
    Route::get('tasks/{id}', 'show');
    Route::put('tasks/{id}', 'update');
    Route::delete('tasks/{id}', 'destroy');
})->middleware('auth:api');
