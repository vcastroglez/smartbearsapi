<?php

use App\Http\Controllers\TaskImageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
	return $request->user();
})->middleware('auth:sanctum');

Route::get('/tasks/images', [TaskImageController::class, 'getImagesList'])->name('task.images.list');
Route::get('/tasks/images/{filename}', [TaskImageController::class, 'getImage'])->name('task.image.get');
