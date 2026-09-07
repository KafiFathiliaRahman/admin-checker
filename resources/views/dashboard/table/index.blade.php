@extends('layouts.dashboard')

@section('title', 'Dashboard Scan')
@section('page-title', 'Dashboard scan')
@section('timestamp', now()->format('d M Y, H:i'))

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">Overview</p>
            <h1>Device intelligence</h1>
            <p>Monitor and review device specifications collected by the Desktop Device Checker.</p>
        </div>
        <a href="{{ route('dashboard.devices.index') }}" class="primary-button">Lihat semua device</a>
    </div>

    <form class="filter-bar" method="GET" action="{{ route('dashboard') }}">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari device, nama pengguna, CPU, RAM..." />
        <select name="department">
            <option value="">Semua Bidang</option>
            @foreach ($departments as $d)
                <option value="{{ $d }}" {{ request('department') == $d ? 'selected' : '' }}>{{ $d }}</option>
            @endforeach
        </select>
        <button type="submit" class="filter-button">Terapkan</button>
        @if (request('search') || request('department'))
            <a href="{{ route('dashboard') }}" class="reset-link">Reset</a>
        @endif
    </form>

    <div class="stats-grid">
        <article class="stat-card">
            <div>
                <p>Total device unik</p>
                <strong>{{ $totalDevices }}</strong>
                <span class="stat-note">{{ $scans->total() }} records</span>
            </div>
            <span class="stat-icon">#</span>
        </article>
        <article class="stat-card">
            <div>
                <p>Active monitoring</p>
                <strong>{{ $onlineDevices }}</strong>
                <span class="stat-note">Online</span>
            </div>
            <span class="stat-icon">+</span>
        </article>
        <article class="stat-card">
            <div>
                <p>Latest scan</p>
                <strong>{{ $scans->first()?->last_seen?->format('H:i') ?? '--:--' }}</strong>
                <span class="stat-note">Today</span>
            </div>
            <span class="stat-icon">~</span>
        </article>
        @foreach ($departments as $d)
            <article class="stat-card">
                <div>
                    <p>{{ $d }}</p>
                    <strong>{{ $departmentTotals[$d] ?? 0 }}</strong>
                    <span class="stat-note">device</span>
                </div>
                <span class="stat-icon">#</span>
            </article>
        @endforeach
    </div>

    <section class="data-panel">
        <div class="panel-heading">
            <h2>Recent scan results</h2>
            <span>{{ $scans->total() }} devices recorded</span>
        </div>
        <div class="table-wrap">
            <table class="scan-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Bidang</th>
                        <th scope="col">User</th>
                        <th scope="col">Device name</th>
                        <th scope="col">Operating system</th>
                        <th scope="col">Manufacturer</th>
                        <th scope="col">Model</th>
                        <th scope="col">Processor</th>
                        <th scope="col">RAM</th>
                        <th scope="col">GPU</th>
                        <th scope="col">Scanned at</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($scans as $key => $device)
                        <tr>
                            <td>{{ $scans->firstItem() + $key }}</td>
                            <td><span class="dept-badge">{{ $device->department }}</span></td>
                            <td><strong>{{ $device->user_name }}</strong></td>
                            <td><strong>{{ $device->device_name }}</strong></td>
                            <td>{{ $device->windows_version }}</td>
                            <td>{{ $device->manufacturer }}</td>
                            <td>{{ $device->model }}</td>
                            <td>{{ $device->cpu }}</td>
                            <td><span class="ram-badge">{{ $device->ram }}</span></td>
                            <td>{{ $device->gpu ?? '-' }}</td>
                            <td>{{ $device->last_seen?->format('d M Y, H:i') ?? $device->created_at?->format('d M Y, H:i') ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="empty-state">Belum ada data scan masuk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($scans->hasPages())
            <div class="pagination">{{ $scans->links() }}</div>
        @endif
    </section>
@endsection
