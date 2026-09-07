<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DeviceController;

Route::post('/devices', [DeviceController::class, 'store']);