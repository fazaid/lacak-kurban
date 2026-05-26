<?php

use App\Http\Controllers\SacrificeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SacrificeController::class, 'index'])->name('home');

Route::post('/search', [SacrificeController::class, 'search'])
    ->name('sacrifice.search')
    ->middleware('throttle:search');

Route::prefix('sacrifice/{slug}')
    ->where(['slug' => 'sac_[a-f0-9]{28}'])
    ->middleware('throttle:view')
    ->group(function () {
        Route::get('/', [SacrificeController::class, 'show'])->name('sacrifice.show');
        Route::get('/profile', [SacrificeController::class, 'profile'])->name('sacrifice.profile');
        Route::get('/progress', [SacrificeController::class, 'progress'])->name('sacrifice.progress');
        Route::get('/gallery', [SacrificeController::class, 'gallery'])->name('sacrifice.gallery');
        Route::get('/certificate', [SacrificeController::class, 'certificate'])->name('sacrifice.certificate');
        Route::get('/download-certificate', [SacrificeController::class, 'downloadCertificate'])->name('sacrifice.certificate.download');
        Route::get('/print-certificate', [SacrificeController::class, 'printCertificate'])->name('sacrifice.certificate.print');
    });

Route::fallback(fn () => response()->view('errors.404', [], 404));
