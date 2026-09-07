<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/live-monitoring', [DashboardController::class, 'liveMonitoring'])->name('live_monitoring.index');
        Route::get('/devices', [DashboardController::class, 'devicesIndex'])->name('devices.index');

        Route::get('/employees', function () {
            return view('dashboard.placeholder', ['pageTitle' => 'Employees', 'pageDesc' => 'Kelola data karyawan will be available here.']);
        })->name('employee.index');

        Route::get('/camera', function () {
            return view('dashboard.placeholder', ['pageTitle' => 'Camera Feed', 'pageDesc' => 'Live camera feed will be available here.']);
        })->name('camera.index');

        Route::get('/detection-history', function () {
            return view('dashboard.placeholder', ['pageTitle' => 'Detection History', 'pageDesc' => 'Riwayat deteksi will be available here.']);
        })->name('detection.index');

        Route::get('/settings', function () {
            return view('dashboard.placeholder', ['pageTitle' => 'Settings', 'pageDesc' => 'Pengaturan aplikasi will be available here.']);
        })->name('setting.index');

        Route::get('/api/stats', [DashboardController::class, 'stats'])->name('stats');
    });
});
