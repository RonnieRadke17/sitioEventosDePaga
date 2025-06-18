<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ActivityController;

Route::resource('activity', ActivityController::class);

Route::post('activity/{id}/restore', [ActivityController::class, 'restore'])->name('activity.restore');
Route::delete('activity/{id}/force', [ActivityController::class, 'forceDestroy'])->name('activity.force');

