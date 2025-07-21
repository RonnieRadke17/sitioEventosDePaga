<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\SportController;

/* Route::resource('category', CategoryController::class);

Route::post('category/{id}/restore', [CategoryController::class, 'restore'])->name('category.restore');
Route::delete('category/{id}/force', [CategoryController::class, 'forceDestroy'])->name('category.force');

Route::resource('activity', ActivityController::class);

Route::post('activity/{id}/restore', [ActivityController::class, 'restore'])->name('activity.restore');
Route::delete('activity/{id}/force', [ActivityController::class, 'forceDestroy'])->name('activity.force');

Route::resource('sports', SportController::class);

Route::post('sports/{id}/restore', [SportController::class, 'restore'])->name('sport.restore');
Route::delete('sports/{id}/force', [SportController::class, 'forceDestroy'])->name('sport.force'); */