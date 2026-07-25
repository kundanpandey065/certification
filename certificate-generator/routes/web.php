<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BulkCertificateController;
use App\Http\Controllers\BulkExportController;
use App\Http\Controllers\Auth\LoginController;



// When someone visits "/", redirect them:
// - If logged in → dashboard
// - If guest     → login form
Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});
// Guest only: login
Route::middleware('guest')->group(function () {
    Route::get('/login',  [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Authenticated users only
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Certificates CRUD & filtering
    Route::get('certificates-list',                     [CertificateController::class, 'index'])->name('certificates.index');
    Route::post('certificates/import',                  [CertificateController::class, 'import'])->name('certificates.import');
    Route::post('certificates/{certificate}/delete',    [CertificateController::class, 'destroy'])->name('certificates.delete');
    Route::post('certificates/delete-all',              [CertificateController::class, 'destroyAll'])->name('certificates.deleteAll');
    Route::get('certificates/{id}/pdf/{type?}',         [CertificateController::class, 'download'])
         ->where('type', 'view|download')
         ->name('certificates.download');

    // Dependent dropdown endpoints
    Route::get('certificates/districts', [CertificateController::class, 'districts'])->name('certificates.districts');
    Route::get('certificates/schools',   [CertificateController::class, 'schools'])->name('certificates.schools');
    Route::get('certificates/standards', [CertificateController::class, 'standards'])->name('certificates.standards');

    // Bulk‐download filter + download
    Route::get('certificates/bulk-download',                  [BulkCertificateController::class, 'index'])->name('certificates.bulk');
    Route::get('certificates/bulk-download/districts',        [BulkCertificateController::class, 'districts']);
    Route::get('certificates/bulk-download/schools',          [BulkCertificateController::class, 'schools']);
    Route::get('certificates/bulk-download/standards',        [BulkCertificateController::class, 'standards'])->name('certificates.bulk.standards');
    Route::post('certificates/bulk-download/download',        [BulkCertificateController::class, 'download'])->name('certificates.bulk.download');

    // Bulk exports history
    Route::get('bulk-exports',               [BulkExportController::class, 'index'])->name('bulk-exports.index');
    Route::post('bulk-exports/generate',     [BulkExportController::class, 'generate'])->name('bulk-exports.generate');
    Route::get('bulk-exports/{id}/download', [BulkExportController::class, 'download'])->name('bulk-exports.download');
    Route::delete('bulk-exports/{id}',       [BulkExportController::class, 'destroy'])->name('bulk-exports.destroy');
    Route::delete('bulk-exports',            [BulkExportController::class, 'destroyAll'])->name('bulk-exports.destroyAll');
});
