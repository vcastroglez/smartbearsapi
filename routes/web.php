<?php

use App\Http\Controllers\TaskImageController;
use Illuminate\Support\Facades\Route;

Route::get('/task/upload', [TaskImageController::class, 'index'])->name('task.image.index');
Route::post('/task/upload', [TaskImageController::class, 'upload'])->name('task.image.upload');

Route::get('/', function () {
    return view('welcome');
});
