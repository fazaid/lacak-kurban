<?php

use App\Http\Controllers\AdminSettingsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SacrificeAdminController;
use Illuminate\Support\Facades\Route;

// ── Auth routes (unauthenticated) ────────────────────────────────────────
Route::get('/login',  [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/login', [AuthController::class, 'login'])->name('admin.login.post');
Route::post('/logout',[AuthController::class, 'logout'])->name('admin.logout');

// ── Protected routes (must be authenticated) ─────────────────────────────
// All roles (admin, staff, viewer) can access the group.
// Restricted actions add ->middleware('role:…') individually.
// Static segment routes come BEFORE {sacrifice} to prevent shadowing.
Route::middleware(['auth', 'admin'])->group(function () {

    // ── Dashboard ──────────────────────────────────────────────────
    Route::get('/', [SacrificeAdminController::class, 'dashboard'])->name('admin.dashboard');

    // ── Sacrifices: static routes first ───────────────────────────
    Route::get('/sacrifices',        [SacrificeAdminController::class, 'index'])->name('admin.sacrifices.index');
    Route::get('/sacrifices/search', [SacrificeAdminController::class, 'index'])->name('admin.sacrifices.search');

    // staff + admin only
    Route::get('/sacrifices/create', [SacrificeAdminController::class, 'create'])
         ->middleware('role:admin,staff')->name('admin.sacrifices.create');
    Route::post('/sacrifices',        [SacrificeAdminController::class, 'store'])
         ->middleware('role:admin,staff')->name('admin.sacrifices.store');

    // admin only
    Route::get('/sacrifices/export/csv', [SacrificeAdminController::class, 'export'])
         ->middleware('role:admin')->name('admin.export');

    // ── Sacrifices: dynamic {sacrifice} routes ─────────────────────
    Route::get('/sacrifices/{sacrifice}', [SacrificeAdminController::class, 'show'])
         ->name('admin.sacrifices.show');

    // staff + admin only
    Route::get('/sacrifices/{sacrifice}/edit', [SacrificeAdminController::class, 'edit'])
         ->middleware('role:admin,staff')->name('admin.sacrifices.edit');
    Route::put('/sacrifices/{sacrifice}',       [SacrificeAdminController::class, 'update'])
         ->middleware('role:admin,staff')->name('admin.sacrifices.update');

    Route::get('/sacrifices/{sacrifice}/progress', [SacrificeAdminController::class, 'editProgress'])
         ->middleware('role:admin,staff')->name('admin.sacrifices.progress.edit');
    Route::put('/sacrifices/{sacrifice}/progress', [SacrificeAdminController::class, 'updateProgress'])
         ->middleware('role:admin,staff')->name('admin.sacrifices.progress.update');

    Route::post('/sacrifices/{sacrifice}/upload-photos', [SacrificeAdminController::class, 'uploadPhotos'])
         ->middleware('role:admin,staff')->name('admin.sacrifices.photos.upload');

    Route::post('/sacrifices/{sacrifice}/generate-certificate', [SacrificeAdminController::class, 'generateCertificate'])
         ->middleware('role:admin,staff')->name('admin.sacrifices.certificate.generate');

    // admin only
    Route::delete('/sacrifices/{sacrifice}', [SacrificeAdminController::class, 'destroy'])
         ->middleware('role:admin')->name('admin.sacrifices.destroy');

    // ── Gallery ────────────────────────────────────────────────────
    // admin only
    Route::delete('/gallery/{photo}', [SacrificeAdminController::class, 'deletePhoto'])
         ->middleware('role:admin')->name('admin.galleries.delete');

    // ── Statistics (all roles) ─────────────────────────────────────
    Route::get('/statistics', [SacrificeAdminController::class, 'statistics'])->name('admin.statistics');

    // ── Settings: admin only ───────────────────────────────────────
    Route::middleware('role:admin')->group(function () {
        Route::get('/settings',                          [AdminSettingsController::class, 'index'])->name('admin.settings');
        Route::get('/settings/signature/{position}',     [AdminSettingsController::class, 'serveSignature'])->name('admin.settings.signature.serve');
        Route::post('/settings/signature',               [AdminSettingsController::class, 'uploadSignature'])->name('admin.settings.signature.upload');
        Route::delete('/settings/signature',             [AdminSettingsController::class, 'deleteSignature'])->name('admin.settings.signature.delete');
    });
});
