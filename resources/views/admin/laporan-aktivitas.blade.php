@extends('layouts.admin')
@section('title', 'Laporan Aktivitas - Admin Panel SMK Mandalahayu 1 Bekasi')

@section('content')
<!-- Page Header -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4 border-b border-outline-variant pb-6">
    <div>
        <h1 class="font-h2 text-h2 text-primary">Log & Laporan Aktivitas</h1>
        <p class="font-body-md text-body-md text-on-surface-variant mt-1">Lacak rekam jejak digital, pantau upaya otorisasi, dan pertahankan tingkat keamanan sistem.</p>
    </div>
</div>

<!-- Controls: Filters & Search -->
<div class="bg-surface rounded-xl border border-outline-variant/30 shadow-sm mb-6 z-10 relative">
    <div class="p-4 bg-surface-container-low border-b border-surface-variant flex flex-col md:flex-row gap-4 items-center rounded-t-xl">
        <!-- Search Input -->
        <div class="relative flex-1 w-full group">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-secondary pointer-events-none transition-soft" style="font-size: 20px">search</span>
            <input id="searchInput" onkeyup="filterTable()" class="w-full bg-surface border-2 border-outline-variant/50 rounded-xl pl-10 pr-4 py-2.5 text-sm text-on-surface focus:outline-none focus:border-secondary focus:ring-0 transition-soft hover:border-secondary/50 placeholder-on-surface-variant/50" placeholder="Cari nama user..." type="text">
        </div>
        <!-- Filter Role -->
        <div class="relative w-full md:w-auto md:min-w-[170px] shrink-0">
            <input type="hidden" id="filterRole" value="Semua">
            <button type="button" onclick="toggleDropdown('role')" class="w-full bg-surface border-2 border-outline-variant/50 rounded-xl pl-10 pr-10 py-2.5 text-sm font-medium text-on-surface text-left focus:outline-none focus:border-secondary focus:ring-0 transition-soft hover:border-secondary/50">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none transition-soft" style="font-size: 20px">badge</span>
                <span id="filterRoleLabel">Semua Role</span>
                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none transition-soft" style="font-size: 20px">expand_more</span>
            </button>
            <div id="dropdownRole" class="hidden absolute z-20 mt-2 w-full md:min-w-[170px] bg-surface rounded-xl border border-outline-variant/30 shadow-xl overflow-hidden">
                <button type="button" onclick="selectDropdown('role','Semua','Semua Role')" class="w-full text-left px-4 py-2.5 text-sm text-on-surface hover:bg-surface-variant/60 transition-soft">Semua Role</button>
                <button type="button" onclick="selectDropdown('role','Siswa','Siswa')" class="w-full text-left px-4 py-2.5 text-sm text-on-surface hover:bg-surface-variant/60 transition-soft">Siswa</button>
                <button type="button" onclick="selectDropdown('role','Guru','Guru')" class="w-full text-left px-4 py-2.5 text-sm text-on-surface hover:bg-surface-variant/60 transition-soft">Guru</button>
                <button type="button" onclick="selectDropdown('role','Admin','Admin')" class="w-full text-left px-4 py-2.5 text-sm text-on-surface hover:bg-surface-variant/60 transition-soft">Admin</button>
            </div>
        </div>
        <!-- Filter Status -->
        <div class="relative w-full md:w-auto md:min-w-[170px] shrink-0">
            <input type="hidden" id="filterStatus" value="Semua">
            <button type="button" onclick="toggleDropdown('status')" class="w-full bg-surface border-2 border-outline-variant/50 rounded-xl pl-10 pr-10 py-2.5 text-sm font-medium text-on-surface text-left focus:outline-none focus:border-secondary focus:ring-0 transition-soft hover:border-secondary/50">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none transition-soft" style="font-size: 20px">login</span>
                <span id="filterStatusLabel">Semua Status</span>
                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none transition-soft" style="font-size: 20px">expand_more</span>
            </button>
            <div id="dropdownStatus" class="hidden absolute z-20 mt-2 w-full md:min-w-[170px] bg-surface rounded-xl border border-outline-variant/30 shadow-xl overflow-hidden">
                <button type="button" onclick="selectDropdown('status','Semua','Semua Status')" class="w-full text-left px-4 py-2.5 text-sm text-on-surface hover:bg-surface-variant/60 transition-soft">Semua Status</button>
                <button type="button" onclick="selectDropdown('status','Berhasil','Berhasil')" class="w-full text-left px-4 py-2.5 text-sm text-on-surface hover:bg-surface-variant/60 transition-soft">Berhasil</button>
                <button type="button" onclick="selectDropdown('status','Gagal (Pass)','Gagal (Pass)')" class="w-full text-left px-4 py-2.5 text-sm text-on-surface hover:bg-surface-variant/60 transition-soft">Gagal (Pass)</button>
                <button type="button" onclick="selectDropdown('status','Blocked','Blocked')" class="w-full text-left px-4 py-2.5 text-sm text-on-surface hover:bg-surface-variant/60 transition-soft">Blocked</button>
            </div>
        </div>
    </div>
</div>

<!-- Log Login Table Area -->
<div class="bg-surface-container-lowest border border-outline-variant/30 rounded-xl shadow-sm shadow-primary/5 flex-1 flex flex-col">
    <div class="overflow-x-auto">
        <table class="w-full text-left font-body-md text-body-md whitespace-nowrap">
            <thead class="bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm border-b border-outline-variant">
                <tr>
                    <th class="px-6 py-4 font-semibold">Profil Pengguna</th>
                    <th class="px-6 py-4 font-semibold">Tingkat Akses</th>
                    <th class="px-6 py-4 font-semibold">Stempel Waktu</th>
                    <th class="px-6 py-4 font-semibold">Status Otorisasi</th>
                    <th class="px-6 py-4 font-semibold">Identifikasi Perangkat</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/50 text-on-surface">
                <tr class="activity-row hover:bg-surface-container-low/50 transition-colors" data-role="Admin" data-status="Berhasil">
                    <td class="px-6 py-4 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-secondary-container text-on-secondary-container flex items-center justify-center font-bold text-sm">AS</div>
                        <span class="font-medium activity-name">Ahmad Santoso</span>
                    </td>
                    <td class="px-6 py-4"><span class="px-2 py-1 rounded-full bg-tertiary-container/10 text-tertiary-container text-xs font-bold">Admin</span></td>
                    <td class="px-6 py-4 text-on-surface-variant">24 Okt 2023, 08:15 WIB</td>
                    <td class="px-6 py-4">
                        <span class="flex items-center gap-1 text-green-700 bg-green-50 px-2 py-1 rounded-md text-sm border border-green-200">
                            <span class="material-symbols-outlined text-[16px]" data-icon="check_circle">check_circle</span> Berhasil
                        </span>
                    </td>
                    <td class="px-6 py-4 text-on-surface-variant">Chrome / Windows 11</td>
                </tr>
                <tr class="activity-row hover:bg-surface-container-low/50 transition-colors" data-role="Guru" data-status="Gagal (Pass)">
                    <td class="px-6 py-4 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center font-bold text-sm">BW</div>
                        <span class="font-medium activity-name">Budi Wijaya</span>
                    </td>
                    <td class="px-6 py-4"><span class="px-2 py-1 rounded-full bg-surface-variant text-on-surface-variant text-xs font-bold border border-outline-variant">Guru</span></td>
                    <td class="px-6 py-4 text-on-surface-variant">24 Okt 2023, 07:45 WIB</td>
                    <td class="px-6 py-4">
                        <span class="flex items-center gap-1 text-on-error-container bg-error-container px-2 py-1 rounded-md text-sm border border-error/20">
                            <span class="material-symbols-outlined text-[16px]" data-icon="error">error</span> Gagal (Pass)
                        </span>
                    </td>
                    <td class="px-6 py-4 text-on-surface-variant">Safari / iOS</td>
                </tr>
                <tr class="activity-row hover:bg-surface-container-low/50 transition-colors bg-error-container/10" data-role="Siswa" data-status="Blocked">
                    <td class="px-6 py-4 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-outline-variant text-on-surface-variant flex items-center justify-center font-bold text-sm">XX</div>
                        <span class="font-medium text-error activity-name">Unknown User</span>
                    </td>
                    <td class="px-6 py-4"><span class="px-2 py-1 rounded-full bg-surface-variant text-on-surface-variant text-xs font-bold border border-outline-variant">Siswa</span></td>
                    <td class="px-6 py-4 text-on-surface-variant">24 Okt 2023, 03:12 WIB</td>
                    <td class="px-6 py-4">
                        <span class="flex items-center gap-1 text-on-error bg-error px-2 py-1 rounded-md text-sm shadow-sm">
                            <span class="material-symbols-outlined text-[16px]" data-icon="block">block</span> Blocked
                        </span>
                    </td>
                    <td class="px-6 py-4 text-on-surface-variant">Firefox / Linux</td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div id="pagination-container" class="p-4 border-t border-outline-variant bg-surface-container-lowest flex items-center justify-between text-sm text-on-surface-variant">
        <!-- Will be populated by JS -->
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
        filterTable();
    }
    
    let currentPage = 1;
    const rowsPerPage = 10;

    function filterTable() {
        const query = document.getElementById('searchInput').value.toLowerCase();
        const role = document.getElementById('filterRole').value;
        const status = document.getElementById('filterStatus').value;
        
        const rows = document.querySelectorAll('.activity-row');
        let visibleRows = [];
        
        rows.forEach(row => {
            const rowRole = row.getAttribute('data-role');
            const rowStatus = row.getAttribute('data-status');
            const rowName = row.querySelector('.activity-name').textContent.toLowerCase();
            
            const matchSearch = query === '' || rowName.includes(query);
            const matchRole = role === 'Semua' || rowRole === role;
            const matchStatus = status === 'Semua' || rowStatus === status;
            
            if(matchSearch && matchRole && matchStatus) {
                visibleRows.push(row);
            } else {
                row.style.display = 'none';
            }
        });
        
        // Pagination logic
        const totalRows = visibleRows.length;
        const totalPages = Math.ceil(totalRows / rowsPerPage) || 1;
        
        if (currentPage > totalPages) currentPage = totalPages;
        
        const start = (currentPage - 1) * rowsPerPage;
        const end = start + rowsPerPage;
        
        visibleRows.forEach((row, index) => {
            if (index >= start && index < end) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
        
        renderPagination(totalRows, totalPages, start, end);
    }
    
    function changePage(page) {
        currentPage = page;
        filterTable();
    }
    
    function renderPagination(totalRows, totalPages, start, end) {
        const container = document.getElementById('pagination-container');
        if (!container) return;
        
        const startText = totalRows === 0 ? 0 : start + 1;
        const endText = Math.min(end, totalRows);
        
        let html = `<div>Menampilkan ${startText}-${endText} dari ${totalRows} data</div>`;
        html += `<div class="flex gap-1">`;
        
        // Prev
        if (currentPage === 1) {
            html += `<button class="p-1 rounded bg-surface-container text-outline opacity-50 cursor-not-allowed"><span class="material-symbols-outlined" data-icon="chevron_left">chevron_left</span></button>`;
        } else {
            html += `<button onclick="changePage(${currentPage - 1})" class="p-1 rounded hover:bg-surface-container text-outline"><span class="material-symbols-outlined" data-icon="chevron_left">chevron_left</span></button>`;
        }
        
        // Numbers
        for (let i = 1; i <= totalPages; i++) {
            if (i === currentPage) {
                html += `<button class="px-3 py-1 rounded bg-secondary-container text-on-secondary-container font-bold">${i}</button>`;
            } else {
                html += `<button onclick="changePage(${i})" class="px-3 py-1 rounded hover:bg-surface-container">${i}</button>`;
            }
        }
        
        // Next
        if (currentPage === totalPages) {
            html += `<button class="p-1 rounded bg-surface-container text-outline opacity-50 cursor-not-allowed"><span class="material-symbols-outlined" data-icon="chevron_right">chevron_right</span></button>`;
        } else {
            html += `<button onclick="changePage(${currentPage + 1})" class="p-1 rounded hover:bg-surface-container text-outline"><span class="material-symbols-outlined" data-icon="chevron_right">chevron_right</span></button>`;
        }
        
        html += `</div>`;
        container.innerHTML = html;
    }

    document.addEventListener('DOMContentLoaded', () => {
        filterTable();
    });

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
