<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_name' => 'required|string|max:255',
            'device_id' => 'required|uuid|unique:devices,device_id',

            'device_name' => 'nullable|string|max:255',
            'manufacturer' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',

            'cpu' => 'nullable|string',
            'ram' => 'nullable|string|max:100',
            'gpu' => 'nullable|string',
            'storage' => 'nullable|string',

            'windows_version' => 'nullable|string|max:255',
        ]);

        $validated['last_seen'] = now();

        $device = Device::create($validated);

        return response()->json([
            'message' => 'Device berhasil didaftarkan',
            'data' => $device,
        ], 201);
    }
}
