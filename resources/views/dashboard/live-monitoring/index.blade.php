@extends('layouts.dashboard')

@section('title', 'Live Monitoring')
@section('page-title', 'Live monitoring')
@section('timestamp', now()->format('d M Y, H:i'))

@push('styles')
<style>
    .online-dot { display: inline-block; width: 10px; height: 10px; border-radius: 50%; background: #22c55e; margin-right: 6px; }
    .offline-dot { display: inline-block; width: 10px; height: 10px; border-radius: 50%; background: #ef4444; margin-right: 6px; }
    #live-tbody .row-fresh { background: rgba(34,197,94,0.08); }
</style>
@endpush

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">Workspace</p>
            <h1>Live monitoring</h1>
            <p>Real-time incoming device scans. Data refreshes automatically every 5 seconds.</p>
        </div>
        <div class="live-toolbar">
            <select id="dept-filter" class="filter-select">
                <option value="">Semua Bidang</option>
                @foreach ($departments as $d)
                    <option value="{{ $d }}">{{ $d }}</option>
                @endforeach
            </select>
            <span id="live-badge" class="live-pill"><span></span>LIVE</span>
        </div>
    </div>

    <div class="stats-grid">
        <article class="stat-card">
            <div>
                <p>Total devices</p>
                <strong id="stat-total">0</strong>
            </div>
            <span class="stat-icon">#</span>
        </article>
        <article class="stat-card">
            <div>
                <p>Online now</p>
                <strong id="stat-online">0</strong>
                <span class="stat-note">Last 5 min</span>
            </div>
            <span class="stat-icon">+</span>
        </article>
        <article class="stat-card">
            <div>
                <p>Latest scan</p>
                <strong id="stat-latest">--:--</strong>
                <span class="stat-note" id="stat-latest-date">Today</span>
            </div>
            <span class="stat-icon">~</span>
        </article>
    </div>

    <section class="data-panel">
        <div class="panel-heading">
            <h2>Connected devices</h2>
            <span id="scan-count">0 devices</span>
        </div>
        <div class="table-wrap">
            <table class="scan-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Status</th>
                        <th scope="col">Bidang</th>
                        <th scope="col">User</th>
                        <th scope="col">Device name</th>
                        <th scope="col">OS</th>
                        <th scope="col">CPU</th>
                        <th scope="col">RAM</th>
                        <th scope="col">GPU</th>
                        <th scope="col">Last seen</th>
                    </tr>
                </thead>
                <tbody id="live-tbody">
                    <tr><td colspan="9" class="empty-state">Memuat data...</td></tr>
                </tbody>
            </table>
        </div>
    </section>
@endsection

@push('scripts')
<script>
(function () {
    const statsUrl = "{{ route('dashboard.stats') }}";
const tbody = document.getElementById('live-tbody');
    const totalEl = document.getElementById('stat-total');
    const onlineEl = document.getElementById('stat-online');
    const latestEl = document.getElementById('stat-latest');
    const latestDateEl = document.getElementById('stat-latest-date');
    const countEl = document.getElementById('scan-count');

    function onlineBadge(lastSeen) {
        if (!lastSeen) return '<span class="offline-dot"></span>Offline';
        const diff = (Date.now() - new Date(lastSeen).getTime()) / 1000;
        if (diff <= 300) {
            return '<span class="online-dot"></span>Online';
        }
        return '<span class="offline-dot"></span>Offline';
    }

    function renderRows(devices) {
        if (!devices.length) {
            tbody.innerHTML = '<tr><td colspan="9" class="empty-state">Belum ada data scan masuk.</td></tr>';
            return;
        }

        const now = Date.now();
        tbody.innerHTML = devices.map((d, i) => {
            const fresh = d.last_seen && (now - new Date(d.last_seen).getTime()) < 10000;
            return `<tr class="${fresh ? 'row-fresh' : ''}">
                <td>${i + 1}</td>
                <td>${onlineBadge(d.last_seen)}</td>
                <td>${d.department ?? '-'}</td>
                <td><strong>${d.user_name ?? '-'}</strong></td>
                <td>${d.device_name ?? '-'}</td>
                <td>${d.windows_version ?? '-'}</td>
                <td>${d.cpu ?? '-'}</td>
                <td>${d.ram ?? '-'}</td>
                <td>${d.gpu ?? '-'}</td>
                <td>${d.last_seen ? new Date(d.last_seen).toLocaleString() : '-'}</td>
            </tr>`;
        }).join('');
    }

    async function fetchStats() {
        const dept = document.getElementById('dept-filter').value;
        const url = dept ? statsUrl + '?department=' + encodeURIComponent(dept) : statsUrl;
        try {
            const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            if (!res.ok) throw new Error('HTTP ' + res.status);
            const json = await res.json();
            totalEl.textContent = json.total_devices ?? 0;
            onlineEl.textContent = json.online_devices ?? 0;
            const latest = json.latest_scan;
            if (latest) {
                const d = new Date(latest);
                latestEl.textContent = d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                latestDateEl.textContent = d.toLocaleDateString();
            }
            countEl.textContent = (json.devices ? json.devices.length : 0) + ' devices';
            renderRows(json.devices ?? []);
        } catch (err) {
            tbody.innerHTML = '<tr><td colspan="9" class="empty-state">Gagal memuat data dari server.</td></tr>';
            console.error(err);
        }
    }

    fetchStats();
    setInterval(fetchStats, 5000);
})();
</script>
@endpush
