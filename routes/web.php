<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Device;

Route::get('/', [Device::class, 'index'])->name('dashboard');

Route::prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/Dashboard-scan', [Device::class, 'index'])->name('table.index');
    Route::get('/live-monitoring', [Device::class, 'index'])->name('live_monitoring.index');
    Route::get('/employees', [Device::class, 'index'])->name('employee.index');
    Route::get('/camera', [Device::class, 'index'])->name('camera.index');
    Route::get('/detection-history', [Device::class, 'index'])->name('detection.index');
    Route::get('/settings', [Device::class, 'index'])->name('setting.index');
});
