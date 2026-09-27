<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\PermitController;
use App\Http\Controllers\OperationsJobController;
use App\Http\Controllers\OperationsJobCrewController;
use App\Http\Controllers\OperationsJobStatusController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', App\Http\Controllers\DashboardController::class)->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'dept:quote'])->group(function () {
    Route::resource('quotes', QuoteController::class);
});

Route::middleware(['auth', 'dept:permit'])->group(function () {
    Route::resource('permits', PermitController::class);
    Route::get('permits/{permit}/document', [PermitController::class, 'download'])->name('permits.document');
});

Route::middleware(['auth', 'dept:operations'])->group(function () {
    Route::resource('jobs', OperationsJobController::class);
    Route::post('jobs/{job}/crew', [OperationsJobCrewController::class, 'store'])->name('jobs.crew.store');
    Route::delete('jobs/{job}/crew/{user}', [OperationsJobCrewController::class, 'destroy'])->name('jobs.crew.destroy');
    Route::patch('jobs/{job}/status', [OperationsJobStatusController::class, 'update'])->name('jobs.status');
});

require __DIR__.'/auth.php';
