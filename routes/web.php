<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PatientDatasetController;
use App\Http\Controllers\PredictionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dataset Management (Admin)
    // Dataset Management (Admin)
    Route::middleware(\App\Http\Middleware\AdminOnly::class)->group(function () {
        Route::get('/dataset', [PatientDatasetController::class, 'index'])->name('dataset.index');
        Route::post('/dataset/import', [PatientDatasetController::class, 'import'])->name('dataset.import');
        Route::patch('/dataset/{dataset}/split', [PatientDatasetController::class, 'setSplit'])->name('dataset.split');
        Route::delete('/dataset/{dataset}', [PatientDatasetController::class, 'destroy'])->name('dataset.destroy');
        Route::delete('/dataset', [PatientDatasetController::class, 'truncate'])->name('dataset.truncate');
    });

    // Prediction Form (Medical)
    Route::get('/prediction', [PredictionController::class, 'index'])->name('prediction.index');
    Route::post('/prediction', [PredictionController::class, 'predict'])->name('prediction.predict');
});

require __DIR__.'/auth.php';
