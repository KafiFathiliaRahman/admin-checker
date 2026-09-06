@extends('layouts.dashboard')

@section('title', 'Dashboard Scan')
@section('page-title', 'Dashboard scan')
@section('timestamp', now()->format('d M Y, H:i'))

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">Overview</p>
            <h1>Device intelligence</h1>
            <p>Monitor and review device specifications collected by Admin Checker.</p>
        </div>
        <a href="{{ route('dashboard.live_monitoring.index') }}" class="primary-button">Open live monitor</a>
    </div>

    <div class="stats-grid">
        <article class="stat-card">
            <div>
                <p>Total devices scanned</p>
                <strong>{{ $scans->total() }}</strong><span class="stat-note">+12.5%</span>
            </div>
            <span class="stat-icon">#</span>
        </article>
        <article class="stat-card">
            <div>
                <p>Active monitoring</p>
                <strong>08</strong><span class="stat-note">Online</span>
            </div>
            <span class="stat-icon">+</span>
        </article>
        <article class="stat-card">
            <div>
                <p>Latest scan</p>
                <strong>{{ $scans->first()?->created_at?->format('H:i') ?? '--:--' }}</strong>

                <span class="stat-note">Today</span>
            </div>
            <span class="stat-icon">~</span>
        </article>
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
                        <th scope="col">Device name</th>
                        <th scope="col">Operating system</th>
                        <th scope="col">Processor</th>
                        <th scope="col">RAM</th>
                        <th scope="col">GPU</th>
                        <th scope="col">Scanned at</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($scans as $key => $laptop)
                        <tr>
                            <td>{{ $scans->firstItem() + $key }}</td>
                            <td><strong>{{ $laptop->device_name }}</strong></td>
                            <td>{{ $laptop->device_os }}</td>
                            <td>{{ $laptop->device_cpu }}</td>
                            <td><span class="ram-badge">{{ $laptop->device_ram }}</span></td>
                            <td>{{ $laptop->device_gpu ?? '-' }}</td>
                           <td>{{ $laptop->created_at?->format('d M Y, H:i') ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="empty-state">Belum ada data scan masuk.</td>
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
