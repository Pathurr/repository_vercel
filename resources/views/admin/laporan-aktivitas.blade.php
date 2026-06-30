@extends('layouts.admin')
@section('title', 'Laporan Aktivitas - Admin Panel SMK Mandalahayu 1 Bekasi')

@section('content')
<style>
    @media (max-width: 767px) {
        .responsive-activity-table thead { display: none; }
        .responsive-activity-table,
        .responsive-activity-table tbody,
        .responsive-activity-table tr,
        .responsive-activity-table td { display: block; width: 100%; }
        .responsive-activity-table tr {
            margin: 12px;
            border: 1px solid #d6c3b8;
            border-radius: 12px;
            overflow: hidden;
            background: #ffffff;
        }
        .responsive-activity-table td {
            padding: 10px 14px;
            border-bottom: 1px solid rgba(214, 195, 184, 0.55);
        }
        .responsive-activity-table td:last-child { border-bottom: 0; }
        .responsive-activity-table td::before {
            content: attr(data-label);
            display: block;
            margin-bottom: 4px;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #84746b;
        }
    }
</style>
<!-- Page Header -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4 border-b border-outline-variant pb-6">
    <div>
        <h1 class="font-h2 text-h2 text-primary">Log & Laporan Aktivitas</h1>
        <p class="font-body-md text-body-md text-on-surface-variant mt-1">Lacak rekam jejak digital, pantau upaya otorisasi, dan pertahankan tingkat keamanan sistem.</p>
    </div>
</div>

<!-- Controls: Filters & Search -->
<form method="GET" action="{{ route('admin.aktivitas') }}" id="filterForm" class="bg-surface rounded-xl border border-outline-variant/30 shadow-sm mb-6 z-10 relative">
    <div class="p-4 bg-surface-container-low border-b border-surface-variant flex flex-col md:flex-row gap-4 items-center rounded-t-xl">
        <!-- Search Input -->
        <div class="relative flex-1 w-full group">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-secondary pointer-events-none transition-soft" style="font-size: 20px">search</span>
            <input id="searchInput" name="search" value="{{ request('search') }}" onchange="document.getElementById('filterForm').submit()" class="w-full bg-surface border-2 border-outline-variant/50 rounded-xl pl-10 pr-4 py-2.5 text-sm text-on-surface focus:outline-none focus:border-secondary focus:ring-0 transition-soft hover:border-secondary/50 placeholder-on-surface-variant/50" placeholder="Cari nama user atau email..." type="text">
        </div>
        <!-- Filter Role -->
        <div class="relative w-full md:w-auto md:min-w-[170px] shrink-0">
            <input type="hidden" name="role" id="filterRole" value="{{ request('role') }}">
            <button type="button" onclick="toggleDropdown('role')" class="w-full bg-surface border-2 border-outline-variant/50 rounded-xl pl-10 pr-10 py-2.5 text-sm font-medium text-on-surface text-left focus:outline-none focus:border-secondary focus:ring-0 transition-soft hover:border-secondary/50">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none transition-soft" style="font-size: 20px">badge</span>
                <span id="filterRoleLabel">{{ request('role') ?: 'Semua Role' }}</span>
                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none transition-soft" style="font-size: 20px">expand_more</span>
            </button>
            <div id="dropdownRole" class="hidden absolute z-20 mt-2 w-full md:min-w-[170px] bg-surface rounded-xl border border-outline-variant/30 shadow-xl overflow-hidden">
                <button type="button" onclick="selectDropdown('role','','Semua Role')" class="w-full text-left px-4 py-2.5 text-sm text-on-surface hover:bg-surface-variant/60 transition-soft">Semua Role</button>
                <button type="button" onclick="selectDropdown('role','siswa','Siswa')" class="w-full text-left px-4 py-2.5 text-sm text-on-surface hover:bg-surface-variant/60 transition-soft">Siswa</button>
                <button type="button" onclick="selectDropdown('role','guru','Guru')" class="w-full text-left px-4 py-2.5 text-sm text-on-surface hover:bg-surface-variant/60 transition-soft">Guru</button>
                <button type="button" onclick="selectDropdown('role','admin','Admin')" class="w-full text-left px-4 py-2.5 text-sm text-on-surface hover:bg-surface-variant/60 transition-soft">Admin</button>
            </div>
        </div>
        <!-- Filter Status -->
        <div class="relative w-full md:w-auto md:min-w-[170px] shrink-0">
            <input type="hidden" name="status" id="filterStatus" value="{{ request('status') }}">
            <button type="button" onclick="toggleDropdown('status')" class="w-full bg-surface border-2 border-outline-variant/50 rounded-xl pl-10 pr-10 py-2.5 text-sm font-medium text-on-surface text-left focus:outline-none focus:border-secondary focus:ring-0 transition-soft hover:border-secondary/50">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none transition-soft" style="font-size: 20px">login</span>
                <span id="filterStatusLabel">{{ request('status') ?: 'Semua Status' }}</span>
                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none transition-soft" style="font-size: 20px">expand_more</span>
            </button>
            <div id="dropdownStatus" class="hidden absolute z-20 mt-2 w-full md:min-w-[170px] bg-surface rounded-xl border border-outline-variant/30 shadow-xl overflow-hidden">
                <button type="button" onclick="selectDropdown('status','','Semua Status')" class="w-full text-left px-4 py-2.5 text-sm text-on-surface hover:bg-surface-variant/60 transition-soft">Semua Status</button>
                <button type="button" onclick="selectDropdown('status','Berhasil','Berhasil')" class="w-full text-left px-4 py-2.5 text-sm text-on-surface hover:bg-surface-variant/60 transition-soft">Berhasil</button>
                <button type="button" onclick="selectDropdown('status','Gagal','Gagal')" class="w-full text-left px-4 py-2.5 text-sm text-on-surface hover:bg-surface-variant/60 transition-soft">Gagal</button>
                <button type="button" onclick="selectDropdown('status','Blocked','Blocked')" class="w-full text-left px-4 py-2.5 text-sm text-on-surface hover:bg-surface-variant/60 transition-soft">Blocked</button>
            </div>
        </div>
        <button type="submit" class="hidden">Submit</button>
    </div>
</form>

<!-- Log Login Table Area -->
<div class="bg-surface-container-lowest border border-outline-variant/30 rounded-xl shadow-sm shadow-primary/5 flex-1 flex flex-col">
    <div class="w-full">
        <table class="responsive-card-table responsive-activity-table w-full table-fixed text-left font-body-md text-[13px]">
            <thead class="bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm border-b border-outline-variant">
                <tr>
                    <th class="px-4 py-4 w-[24%] font-semibold">Profil Pengguna</th>
                    <th class="px-4 py-4 w-[14%] font-semibold">Tingkat Akses</th>
                    <th class="px-4 py-4 w-[20%] font-semibold">Stempel Waktu</th>
                    <th class="px-4 py-4 w-[18%] font-semibold">Status Otorisasi</th>
                    <th class="px-4 py-4 w-[24%] font-semibold">Identifikasi Perangkat</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/50 text-on-surface">
                @forelse($logs as $log)
                    @php
                        $name = $log->user ? $log->user->name : ($log->email ?: 'Unknown');
                        $initials = strtoupper(substr($name, 0, 2));
                        $isError = in_array($log->status, ['Gagal', 'Blocked']);
                        $bgRow = $isError ? 'bg-error-container/10' : 'hover:bg-surface-container-low/50';
                    @endphp
                    <tr class="activity-row {{ $bgRow }} transition-colors" data-role="{{ $log->role }}" data-status="{{ $log->status }}">
                        <td data-label="Profil Pengguna" class="px-4 py-4">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-8 h-8 rounded-full {{ $isError ? 'bg-outline-variant text-on-surface-variant' : 'bg-secondary-container text-on-secondary-container' }} flex items-center justify-center font-bold text-sm">
                                    {{ $initials }}
                                </div>
                                <div class="flex flex-col min-w-0">
                                    <span class="font-medium activity-name truncate {{ $isError && !$log->user ? 'text-error' : '' }}">{{ $name }}</span>
                                    @if($log->user && $log->email !== $log->user->email)
                                        <span class="text-xs text-on-surface-variant truncate">{{ $log->email }}</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td data-label="Tingkat Akses" class="px-4 py-4">
                            @if($log->role === 'admin')
                                <span class="px-2 py-1 rounded-full bg-tertiary-container/10 text-tertiary-container text-xs font-bold">Admin</span>
                            @elseif($log->role === 'guru')
                                <span class="px-2 py-1 rounded-full bg-surface-variant text-on-surface-variant text-xs font-bold border border-outline-variant">Guru</span>
                            @elseif($log->role === 'siswa' || $log->role === 'murid')
                                <span class="px-2 py-1 rounded-full bg-surface-variant text-on-surface-variant text-xs font-bold border border-outline-variant">Siswa</span>
                            @else
                                <span class="px-2 py-1 rounded-full bg-outline-variant text-on-surface-variant text-xs font-bold">{{ $log->role ?: 'N/A' }}</span>
                            @endif
                        </td>
                        <td data-label="Stempel Waktu" class="px-4 py-4 text-on-surface-variant">{{ \Carbon\Carbon::parse($log->created_at)->isoFormat('D MMM Y, HH:mm') }} WIB</td>
                        <td data-label="Status Otorisasi" class="px-4 py-4">
                            @if($log->status === 'Berhasil')
                                <span class="inline-flex items-center gap-1 text-green-700 bg-green-50 px-2 py-1 rounded-md text-sm border border-green-200">
                                    <span class="material-symbols-outlined text-[16px]" data-icon="check_circle">check_circle</span> Berhasil
                                </span>
                            @elseif($log->status === 'Gagal')
                                <span class="inline-flex items-center gap-1 text-on-error-container bg-error-container px-2 py-1 rounded-md text-sm border border-error/20">
                                    <span class="material-symbols-outlined text-[16px]" data-icon="error">error</span> Gagal
                                </span>
                            @elseif($log->status === 'Blocked')
                                <span class="inline-flex items-center gap-1 text-on-error bg-error px-2 py-1 rounded-md text-sm shadow-sm">
                                    <span class="material-symbols-outlined text-[16px]" data-icon="block">block</span> Blocked
                                </span>
                            @else
                                <span class="px-2 py-1 rounded-md text-sm border border-outline-variant">{{ $log->status }}</span>
                            @endif
                        </td>
                        <td data-label="Identifikasi Perangkat" class="px-4 py-4 text-on-surface-variant"><span class="block truncate" title="{{ $log->user_agent }}">{{ $log->user_agent ? \Illuminate\Support\Str::limit($log->user_agent, 40) : 'Unknown' }}</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-on-surface-variant italic">Belum ada catatan aktivitas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="p-4 border-t border-outline-variant bg-surface-container-lowest flex flex-col sm:flex-row items-center justify-between gap-3 text-sm text-on-surface-variant">
        {{ $logs->links() }}
    </div>
</div>

@push('scripts')
<script>
    function toggleDropdown(type) {
        const targetId = type === 'role' ? 'dropdownRole' : 'dropdownStatus';
        const target = document.getElementById(targetId);
        const otherId = type === 'role' ? 'dropdownStatus' : 'dropdownRole';
        const other = document.getElementById(otherId);
        if (other && !other.classList.contains('hidden')) {
            other.classList.add('hidden');
        }
        target.classList.toggle('hidden');
    }

    function selectDropdown(type, value, label) {
        if (type === 'role') {
            document.getElementById('filterRole').value = value;
            document.getElementById('filterRoleLabel').textContent = label;
            document.getElementById('dropdownRole').classList.add('hidden');
        } else {
            document.getElementById('filterStatus').value = value;
            document.getElementById('filterStatusLabel').textContent = label;
            document.getElementById('dropdownStatus').classList.add('hidden');
        }
        document.getElementById('filterForm').submit();
    }

    document.addEventListener('click', function (event) {
        const roleWrapper = document.getElementById('dropdownRole');
        const statusWrapper = document.getElementById('dropdownStatus');
        if (!event.target.closest('[onclick="toggleDropdown(\'role\')"]') && roleWrapper && !roleWrapper.classList.contains('hidden')) {
            roleWrapper.classList.add('hidden');
        }
        if (!event.target.closest('[onclick="toggleDropdown(\'status\')"]') && statusWrapper && !statusWrapper.classList.contains('hidden')) {
            statusWrapper.classList.add('hidden');
        }
    });
</script>
@endpush
@endsection
