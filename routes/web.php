<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ActivityUpdateController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('activities.index');
})->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('activities/report', [ActivityController::class, 'report'])
        ->name('activities.report');

    Route::resource('activities', ActivityController::class)
        ->only(['index', 'store', 'show']);

    Route::post('activities/{activity}/updates', [ActivityUpdateController::class, 'store'])
        ->name('activity-updates.store');

    Route::delete('activity-updates/{activityUpdate}', [ActivityUpdateController::class, 'destroy'])
        ->name('activity-updates.destroy');
});

require __DIR__.'/settings.php';
