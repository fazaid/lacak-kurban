<?php

use App\Http\Controllers\AdminSettingsController;
use App\Http\Controllers\AdminUserController;
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

    // staff + admin only
    Route::get('/sacrifices/export/csv', [SacrificeAdminController::class, 'export'])
         ->middleware('role:admin,staff')->name('admin.export');

    // staff + admin only
    Route::get('/sacrifices/import',          [SacrificeAdminController::class, 'importForm'])
         ->middleware('role:admin,staff')->name('admin.sacrifices.import');
    Route::post('/sacrifices/import',         [SacrificeAdminController::class, 'import'])
         ->middleware('role:admin,staff')->name('admin.sacrifices.import.process');
    Route::get('/sacrifices/import/template', [SacrificeAdminController::class, 'importTemplate'])
         ->middleware('role:admin,staff')->name('admin.sacrifices.import.template');

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

    // staff + admin only
    Route::delete('/sacrifices/{sacrifice}', [SacrificeAdminController::class, 'destroy'])
         ->middleware('role:admin,staff')->name('admin.sacrifices.destroy');

    // ── Gallery ────────────────────────────────────────────────────
    // staff + admin only
    Route::delete('/gallery/{photo}', [SacrificeAdminController::class, 'deletePhoto'])
         ->middleware('role:admin,staff')->name('admin.galleries.delete');

    // ── Statistics (all roles) ─────────────────────────────────────
    Route::get('/statistics', [SacrificeAdminController::class, 'statistics'])->name('admin.statistics');

    // ── User management: admin only ───────────────────────────────
    Route::middleware('role:admin')->group(function () {
        Route::get('/users',                        [AdminUserController::class, 'index'])->name('admin.users.index');
        Route::get('/users/create',                 [AdminUserController::class, 'create'])->name('admin.users.create');
        Route::post('/users',                       [AdminUserController::class, 'store'])->name('admin.users.store');
        Route::get('/users/{user}/edit',            [AdminUserController::class, 'edit'])->name('admin.users.edit');
        Route::put('/users/{user}',                 [AdminUserController::class, 'update'])->name('admin.users.update');
        Route::put('/users/{user}/reset-password',  [AdminUserController::class, 'resetPassword'])->name('admin.users.reset-password');
        Route::delete('/users/{user}',              [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
    });

    // ── Settings: admin only ───────────────────────────────────────
    Route::middleware('role:admin')->group(function () {
        Route::get('/settings',                          [AdminSettingsController::class, 'index'])->name('admin.settings');
        Route::get('/settings/signature/{position}',     [AdminSettingsController::class, 'serveSignature'])->name('admin.settings.signature.serve');
        Route::post('/settings/signature',               [AdminSettingsController::class, 'uploadSignature'])->name('admin.settings.signature.upload');
        Route::delete('/settings/signature',             [AdminSettingsController::class, 'deleteSignature'])->name('admin.settings.signature.delete');
    });
});
