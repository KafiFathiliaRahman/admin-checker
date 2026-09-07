<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private function applyFilters(Request $request, $query)
    {
        $search = $request->input('search');
        $department = $request->input('department');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('device_name', 'like', "%{$search}%")
                    ->orWhere('user_name', 'like', "%{$search}%")
                    ->orWhere('cpu', 'like', "%{$search}%")
                    ->orWhere('ram', 'like', "%{$search}%")
                    ->orWhere('device_id', 'like', "%{$search}%");
            });
        }

        if ($department) {
            $query->where('department', $department);
        }

        return $query;
    }

    public function index(Request $request)
    {
        $baseQuery = Device::query();
        $baseQuery = $this->applyFilters($request, $baseQuery);

        $scans = $baseQuery
            ->orderBy('last_seen', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        $totalDevices = Device::distinct('device_id')->count('device_id');
        $onlineDevices = Device::where('last_seen', '>=', now()->subMinutes(5))->count();

        $departmentTotals = Device::selectRaw('department, count(*) as total')
            ->groupBy('department')
            ->pluck('total', 'department');

        $departments = Device::DEPARTMENTS;

        $search = $request->input('search');
        $department = $request->input('department');

        return view('dashboard.table.index', compact(
            'scans',
            'totalDevices',
            'onlineDevices',
            'departmentTotals',
            'departments',
            'search',
            'department'
        ));
    }

    public function devicesIndex(Request $request)
    {
        $baseQuery = Device::query();
        $baseQuery = $this->applyFilters($request, $baseQuery);

        $devices = $baseQuery
            ->orderBy('last_seen', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        $departments = Device::DEPARTMENTS;

        $search = $request->input('search');
        $department = $request->input('department');

        return view('dashboard.devices.index', compact(
            'devices',
            'departments',
            'search',
            'department'
        ));
    }

    public function liveMonitoring(Request $request)
    {
        return view('dashboard.live-monitoring.index', [
            'departments' => Device::DEPARTMENTS,
        ]);
    }

    public function stats(Request $request)
    {
        $query = $this->applyFilters($request, Device::query());

        $latest = $query->orderBy('last_seen', 'desc')->get();

        $totalDevices = Device::distinct('device_id')->count('device_id');
        $onlineDevices = Device::where('last_seen', '>=', now()->subMinutes(5))->count();
        $latestScan = Device::latest('created_at')->first()?->created_at;

        $byDepartment = Device::selectRaw('department, count(*) as total')
            ->groupBy('department')
            ->pluck('total', 'department');

        return response()->json([
            'total_devices' => $totalDevices,
            'online_devices' => $onlineDevices,
            'latest_scan' => $latestScan,
            'by_department' => $byDepartment,
            'devices' => $latest,
        ]);
    }
}
