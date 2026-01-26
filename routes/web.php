<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

// Public routes for job viewing and applications
Route::get('/jobs', [App\Http\Controllers\PublicJobController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{job}', [App\Http\Controllers\PublicJobController::class, 'show'])->name('jobs.show');
Route::post('/jobs/{job}/apply', [App\Http\Controllers\ApplicationController::class, 'store'])->name('applications.store');
Route::get('/application-success', [App\Http\Controllers\ApplicationController::class, 'success'])->name('applications.success');

Route::post('/contact', App\Http\Controllers\ContactController::class)->name('contact');

Route::middleware(['auth', 'verified'])->prefix('dashboard')->group(function () {
    Route::get('/', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    Route::name('dashboard.')->group(function () {
        Route::resource('job-positions', App\Http\Controllers\JobPositionController::class);
        
        Route::get('applications', [App\Http\Controllers\ApplicationController::class, 'index'])->name('applications.index');
        Route::patch('applications/{application}/note', [App\Http\Controllers\ApplicationController::class, 'updateNote'])->name('applications.updateNote');
        Route::post('applications/{application}/interview', [App\Http\Controllers\InterviewController::class, 'store'])->name('applications.interview.store');
    });
});

Route::get('/interview/{token}', [App\Http\Controllers\InterviewController::class, 'show'])->name('interview.book');
Route::post('/interview/{token}', [App\Http\Controllers\InterviewController::class, 'update'])->name('interview.update');

require __DIR__.'/settings.php';
