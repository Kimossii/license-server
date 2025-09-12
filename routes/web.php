<?php
use Illuminate\Support\Facades\Route;
use LicenseServer\Http\Controllers\LicenseServerController; 
Route::prefix('license-server')->group(function () {
    Route::get('/', [LicenseServerController::class, 'createForm'])->name('server.create');
    Route::get('/generate', [LicenseServerController::class, 'generateKeyForm'])->name('generate.keys');
    Route::get('/download-key/{tipo}', [LicenseServerController::class, 'downloadKey'])->name('keys.download');

    Route::post('/license/store', [LicenseServerController::class, 'store'])->name('server.store');
    Route::post('/generate-keys', [LicenseServerController::class, 'generateKeys'])->name('keys.generate');
});
