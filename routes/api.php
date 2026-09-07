<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DeviceController;

Route::prefix('devices')->group(function () {
    Route::get('/', [DeviceController::class, 'index'])->name('api.devices.index');
    Route::post('/', [DeviceController::class, 'store'])->name('api.devices.store');
    Route::get('/{device}', [DeviceController::class, 'show'])->name('api.devices.show');
    Route::delete('/{device}', [DeviceController::class, 'destroy'])->name('api.devices.destroy');
});
