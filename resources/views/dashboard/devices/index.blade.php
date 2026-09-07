@extends('layouts.dashboard')

@section('title', 'Daftar Device')
@section('page-title', 'Daftar device')
@section('timestamp', now()->format('d M Y, H:i'))

@push('styles')
<style>
    .modal-backdrop { position: fixed; inset: 0; background: rgba(0,0,0,.45); display: none; align-items: center; justify-content: center; z-index: 50; }
    .modal { background: var(--white); border-radius: 14px; width: min(720px, 95vw); max-height: 80vh; overflow: auto; box-shadow: 0 20px 50px rgba(0,0,0,.25); }
    .modal-header { display: flex; justify-content: space-between; align-items: center; padding: 18px 22px; border-bottom: 1px solid var(--line); }
    .modal-header h3 { margin: 0; font-size: 17px; }
    .modal-body { padding: 18px 22px; }
    .modal-row { display: grid; grid-template-columns: 150px 1fr; gap: 8px 14px; padding: 6px 0; border-bottom: 1px dashed var(--line); font-size: 13.5px; }
    .modal-row:last-child { border-bottom: none; }
    .modal-row .k { color: var(--muted); }
    .modal-row .v { font-weight: 600; word-break: break-word; }
    .action-link { color: var(--navy); font-size: 12px; font-weight: 700; text-decoration: none; margin-right: 10px; cursor: pointer; }
    .action-link.delete { color: #b91c1c; }
    .toast { position: fixed; bottom: 24px; right: 24px; background: var(--navy); color: var(--white); padding: 12px 18px; border-radius: 10px; font-size: 13px; box-shadow: 0 10px 25px rgba(0,0,0,.2); opacity: 0; transition: opacity .3s; pointer-events: none; z-index: 60; }
    .toast.show { opacity: 1; }
</style>
@endpush

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">Workspace</p>
            <h1>Daftar device</h1>
            <p>Semua device yang terdaftar dan pernah discan.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="primary-button">Kembali ke dashboard</a>
    </div>

    <form class="filter-bar" method="GET" action="{{ route('dashboard.devices.index') }}">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari device, nama pengguna, CPU, RAM..." />
        <select name="department">
            <option value="">Semua Bidang</option>
            @foreach ($departments as $d)
                <option value="{{ $d }}" {{ request('department') == $d ? 'selected' : '' }}>{{ $d }}</option>
            @endforeach
        </select>
        <button type="submit" class="filter-button">Terapkan</button>
        @if (request('search') || request('department'))
            <a href="{{ route('dashboard.devices.index') }}" class="reset-link">Reset</a>
        @endif
    </form>

    <section class="data-panel">
        <div class="panel-heading">
            <h2>Semua device ({{ $devices->total() }})</h2>
            <span>{{ $devices->count() }} ditampilkan</span>
        </div>
        <div class="table-wrap">
            <table class="scan-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Bidang</th>
                        <th>User</th>
                        <th>Device name</th>
                        <th>Manufacturer</th>
                        <th>Model</th>
                        <th>CPU</th>
                        <th>RAM</th>
                        <th>GPU</th>
                        <th>Storage</th>
                        <th>OS</th>
                        <th>Last seen</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="devices-tbody">
                    @forelse ($devices as $key => $d)
                        @php($did = $d->id)
                        <tr data-id="{{ $did }}">
                            <td>{{ $devices->firstItem() + $key }}</td>
                            <td><span class="dept-badge">{{ $d->department }}</span></td>
                            <td><strong>{{ $d->user_name }}</strong></td>
                            <td><strong>{{ $d->device_name }}</strong></td>
                            <td>{{ $d->manufacturer }}</td>
                            <td>{{ $d->model }}</td>
                            <td>{{ $d->cpu }}</td>
                            <td>{{ $d->ram }}</td>
                            <td>{{ $d->gpu ?? '-' }}</td>
                            <td>{!! nl2br(e($d->storage)) !!}</td>
                            <td>{{ $d->windows_version }}</td>
                            <td>{{ $d->last_seen?->format('d M Y, H:i') ?? '-' }}</td>
                            <td>
                                <a href="#" class="action-link" onclick="viewDevice({{ $did }}); return false;">Lihat</a>
                                <a href="#" class="action-link delete" onclick="deleteDevice({{ $did }}); return false;">Hapus</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="13" class="empty-state">Tidak ada device ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($devices->hasPages())
            <div class="pagination">{{ $devices->links() }}</div>
        @endif
    </section>

    <div id="detail-modal" class="modal-backdrop">
        <div class="modal">
            <div class="modal-header">
                <h3>Detail device</h3>
                <button class="close-btn" onclick="closeModal()">×</button>
            </div>
            <div class="modal-body" id="detail-body">
                <div class="modal-row"><span class="k">Memuat...</span></div>
            </div>
        </div>
    </div>
    <div id="toast" class="toast"></div>
@endsection

@push('scripts')
<script>
const apiBase = "{{ url('api/devices') }}";

function toast(msg) {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 2200);
}

async function viewDevice(id) {
    const body = document.getElementById('detail-body');
    body.innerHTML = '<div class="modal-row"><span class="k">Memuat...</span></div>';
    document.getElementById('detail-modal').style.display = 'flex';
    try {
        const res = await fetch(`${apiBase}/${id}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const json = await res.json();
        const d = json.data;
        const fields = ['user_name','department','device_name','manufacturer','model','cpu','ram','gpu','storage','windows_version','device_id','last_seen','created_at','updated_at'];
        let html = '';
        fields.forEach(k => {
            const labels = {
                user_name:'User', department:'Bidang', device_name:'Device name', manufacturer:'Manufacturer',
                model:'Model', cpu:'CPU', ram:'RAM', gpu:'GPU', storage:'Storage', windows_version:'OS',
                device_id:'Device ID', last_seen:'Last seen', created_at:'Created at', updated_at:'Updated at'
            };
            let v = d[k];
            if (v && (k === 'last_seen' || k === 'created_at' || k === 'updated_at')) {
                v = new Date(v).toLocaleString();
            }
            v = v === null || v === undefined || v === '' ? '-' : v;
            html += `<div class="modal-row"><span class="k">${labels[k]||k}</span><span class="v">${String(v)}</span></div>`;
        });
        body.innerHTML = html;
    } catch (err) {
        body.innerHTML = '<div class="modal-row"><span class="k">Gagal memuat detail</span></div>';
    }
}

function closeModal() {
    document.getElementById('detail-modal').style.display = 'none';
}

async function deleteDevice(id) {
    if (!confirm('Hapus device ini? Aksi tidak dapat dibatalkan.')) return;
    try {
        const res = await fetch(`${apiBase}/${id}`, { method: 'DELETE', headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const json = await res.json();
        if (res.ok) {
            const tr = document.querySelector(`tr[data-id="${id}"]`);
            if (tr) tr.remove();
            toast(json.message || 'Device dihapus');
        } else {
            toast('Gagal hapus: ' + (json.message || res.status));
        }
    } catch (err) {
        toast('Koneksi gagal: ' + err.message);
    }
}

document.getElementById('detail-modal').addEventListener('click', (e) => {
    if (e.target === document.getElementById('detail-modal')) closeModal();
});
</script>
@endpush
