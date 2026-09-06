<?php

namespace App\Http\Controllers;

use App\Models\DeviceModel;
use Illuminate\Http\Request;

class Device extends Controller
{
    // Tampilan Dashboard Web Admin
    public function index()
    {
        $scans = DeviceModel::latest()->paginate(10);

        return view('dashboard.table.index', compact('scans'));
    }

    // Endpoint API untuk menerima data input dari App Scanner
    public function store(Request $request)
    {
        $validated = $request->validate([
            'device_name' => 'required|string',
            'device_os'   => 'required|string',
            'device_cpu'  => 'required|string',
            'device_gpu'  => 'nullable|string',
            'device_ram'  => 'required|string',
        ]);

        $data = DeviceModel::create($validated);

        return response()->json([
            'message' => 'Data spek berhasil disimpan',
            'data' => $data,
        ], 201);
    }
}