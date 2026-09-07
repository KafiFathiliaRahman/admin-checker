<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class DeviceController extends Controller
{
    public function index(Request $request)
    {
        $scans = Device::orderBy('last_seen', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate($request->input('per_page', 15));

        return response()->json([
            'data' => $scans->items(),
            'meta' => [
                'current_page' => $scans->currentPage(),
                'last_page' => $scans->lastPage(),
                'per_page' => $scans->perPage(),
                'total' => $scans->total(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_name' => 'required|string|max:255',
            'device_id' => 'required|uuid',
            'department' => [
                'required',
                'string',
                'max:255',
                Rule::in(Device::DEPARTMENTS),
            ],

            'device_name' => 'nullable|string|max:255',
            'manufacturer' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',

            'cpu' => 'nullable|string',
            'ram' => 'nullable|string|max:100',
            'gpu' => 'nullable|string',
            'storage' => 'nullable|string',

            'windows_version' => 'nullable|string|max:255',
        ]);

        $device = DB::transaction(function () use ($validated) {
            return Device::updateOrCreate(
                ['device_id' => $validated['device_id']],
                array_merge($validated, ['last_seen' => now()])
            );
        });

        $status = $device->wasRecentlyCreated ? 'created' : 'updated';

        return response()->json([
            'message' => 'Device berhasil didaftarkan',
            'status' => $status,
            'data' => $device,
        ], $status === 'created' ? 201 : 200);
    }

    public function show(Request $request, Device $device)
    {
        return response()->json([
            'data' => $device,
        ]);
    }

    public function destroy(Device $device)
    {
        $device->delete();

        return response()->json([
            'message' => 'Device berhasil dihapus',
            'data' => $device,
        ], 200);
    }
}
