@extends('layouts.admin')
@section('title', 'Manajemen Akun - Admin Panel SMK Mandalahayu 1 Bekasi')

@section('content')
<!-- Page Header & Primary Action -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-outline-variant pb-6 mb-6">
    <div>
        <h1 class="font-h2 text-h2 text-primary">Manajemen Akses & Pengguna</h1>
        <p class="font-body-md text-body-md text-on-surface-variant mt-1">Kelola otorisasi, status keanggotaan, dan hak akses seluruh civitas akademika.</p>
    </div>
</div>

<!-- Controls: Filters & Search -->
<div class="bg-surface rounded-xl border border-outline-variant/30 shadow-sm mb-6 z-10 relative">
    <div class="p-4 bg-surface-container-low border-b border-surface-variant flex flex-col md:flex-row gap-4 items-center rounded-t-xl">
        <!-- Search Input -->
        <div class="relative flex-1 w-full group">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-secondary pointer-events-none transition-soft" style="font-size: 20px">search</span>
            <input id="searchInput" onkeyup="filterTable()" class="w-full bg-surface border-2 border-outline-variant/50 rounded-xl pl-10 pr-4 py-2.5 text-sm text-on-surface focus:outline-none focus:border-secondary focus:ring-0 transition-soft hover:border-secondary/50 placeholder-on-surface-variant/50" placeholder="Cari nama, email..." type="text">
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
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none transition-soft" style="font-size: 20px">inventory_2</span>
                <span id="filterStatusLabel">Semua Status</span>
                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none transition-soft" style="font-size: 20px">expand_more</span>
            </button>
            <div id="dropdownStatus" class="hidden absolute z-20 mt-2 w-full md:min-w-[170px] bg-surface rounded-xl border border-outline-variant/30 shadow-xl overflow-hidden">
                <button type="button" onclick="selectDropdown('status','Semua','Semua Status')" class="w-full text-left px-4 py-2.5 text-sm text-on-surface hover:bg-surface-variant/60 transition-soft">Semua Status</button>
                <button type="button" onclick="selectDropdown('status','Active','Active')" class="w-full text-left px-4 py-2.5 text-sm text-on-surface hover:bg-surface-variant/60 transition-soft">Active</button>
                <button type="button" onclick="selectDropdown('status','Pending','Pending')" class="w-full text-left px-4 py-2.5 text-sm text-on-surface hover:bg-surface-variant/60 transition-soft">Pending</button>
                <button type="button" onclick="selectDropdown('status','Suspended','Suspended')" class="w-full text-left px-4 py-2.5 text-sm text-on-surface hover:bg-surface-variant/60 transition-soft">Suspended</button>
                <button type="button" onclick="selectDropdown('status','Inactive','Inactive')" class="w-full text-left px-4 py-2.5 text-sm text-on-surface hover:bg-surface-variant/60 transition-soft">Inactive</button>
            </div>
        </div>
        
        <!-- Bulk Actions -->
        <div class="relative w-full md:w-auto md:min-w-[180px] shrink-0 ml-auto">
            <button type="button" onclick="toggleDropdown('bulk')" class="w-full bg-primary text-on-primary rounded-xl px-4 py-2.5 text-sm font-bold flex items-center justify-between hover:bg-primary/90 transition-soft shadow-sm opacity-50 cursor-not-allowed" id="btnBulkAction" disabled>
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[20px]">checklist</span>
                    Aksi Massal (<span id="selectedCount">0</span>)
                </div>
                <span class="material-symbols-outlined text-[20px]">expand_more</span>
            </button>
            <div id="dropdownBulk" class="hidden absolute right-0 z-20 mt-2 w-full bg-surface rounded-xl border border-outline-variant/30 shadow-xl overflow-hidden">
                <button type="button" onclick="executeBulkAction('aktifkan')" class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-green-600 hover:bg-green-50 transition-soft font-bold"><span class="material-symbols-outlined text-[18px]">check_circle</span> Aktifkan</button>
                <button type="button" onclick="executeBulkAction('suspend')" class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-orange-600 hover:bg-orange-50 transition-soft font-bold"><span class="material-symbols-outlined text-[18px]">block</span> Suspend</button>
                <button type="button" onclick="executeBulkAction('hapus')" class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-soft font-bold border-t border-outline-variant/30"><span class="material-symbols-outlined text-[18px]">delete</span> Hapus Permanen</button>
            </div>
        </div>
        
        <!-- Form Bulk Action (Hidden) -->
        <form id="bulkForm" method="POST" action="{{ route('admin.akun.bulk') }}" class="hidden">
            @csrf
            <input type="hidden" name="user_ids" id="bulk_user_ids">
            <input type="hidden" name="action" id="bulk_action">
        </form>
    </div>
</div>

<!-- Data Table Card -->
<div class="bg-surface-container-lowest border border-outline-variant/30 rounded-xl shadow-sm shadow-primary/5 overflow-hidden flex-1 flex flex-col">
    <div class="overflow-x-auto table-scrollbar">
        <table class="w-full text-left whitespace-nowrap">
            <thead class="bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm border-b border-outline-variant">
                <tr>
                    <th class="px-3 py-4 w-10 text-center"><input type="checkbox" id="selectAll" class="w-4 h-4 rounded text-primary focus:ring-primary border-outline-variant" onchange="toggleSelectAll(this)"></th>
                    <th class="px-3 py-4 font-semibold">No</th>
                    <th class="px-3 py-4 font-semibold">Nama Lengkap</th>
                    <th class="px-3 py-4 font-semibold">Identitas (NIS/NRG)</th>
                    <th class="px-3 py-4 font-semibold">Email Utama</th>
                    <th class="px-3 py-4 font-semibold">Peran</th>
                    <th class="px-3 py-4 font-semibold">Otorisasi</th>
                    <th class="px-3 py-4 font-semibold">Terdaftar</th>
                    <th class="px-3 py-4 font-semibold">Login Terakhir</th>
                    <th class="px-3 py-4 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="font-body-md text-body-md text-on-surface divide-y divide-outline-variant/50">
                @foreach($users as $i => $u)
                @php
                    $roleLabel = ucfirst($u->role);
                    if($u->role == 'murid') $roleLabel = 'Siswa';
                    
                    $statusConfig = [
                        'active' => ['bg' => 'bg-green-100', 'text' => 'text-green-600', 'border' => 'border-green-200', 'label' => 'Active'],
                        'pending' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-600', 'border' => 'border-blue-200', 'label' => 'Pending'],
                        'suspended' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-600', 'border' => 'border-orange-200', 'label' => 'Suspended'],
                        'inactive' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-600', 'border' => 'border-gray-200', 'label' => 'Inactive'],
                        'rejected' => ['bg' => 'bg-red-100', 'text' => 'text-red-600', 'border' => 'border-red-200', 'label' => 'Rejected'],
                    ];
                    $sc = $statusConfig[$u->status ?? 'pending'];
                    
                    $rowClass = "account-row hover:bg-surface-container-lowest/80 transition-colors group";
                    if($u->status == 'suspended' || $u->status == 'rejected') $rowClass .= " bg-error-container/10";
                @endphp
                <tr class="{{ $rowClass }}" data-role="{{ $roleLabel }}" data-status="{{ $sc['label'] }}">
                    <td class="px-3 py-3 text-center"><input type="checkbox" class="user-checkbox w-4 h-4 rounded text-primary focus:ring-primary border-outline-variant" value="{{ $u->id }}" onchange="updateBulkActionState()"></td>
                    <td class="px-3 py-3">{{ $i + 1 }}</td>
                    <td class="px-3 py-3 font-medium account-name">{{ $u->name }}</td>
                    <td class="px-3 py-3 text-on-surface-variant">{{ $u->role == 'guru' ? $u->nrg : $u->nis }}</td>
                    <td class="px-3 py-3 text-on-surface-variant account-email truncate max-w-[150px]" title="{{ $u->email }}">{{ $u->email }}</td>
                    <td class="px-3 py-3">{{ $roleLabel }}</td>
                    <td class="px-3 py-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $sc['bg'] }} {{ $sc['text'] }} border {{ $sc['border'] }}">{{ $sc['label'] }}</span>
                    </td>
                    <td class="px-3 py-3 text-on-surface-variant">{{ $u->created_at->format('d M Y') }}</td>
                    <td class="px-3 py-3 text-on-surface-variant">-</td>
                    <td class="px-3 py-3 text-right relative">
                        <button onclick="toggleActionMenu({{ $u->id }})" class="text-on-surface-variant hover:text-primary p-1 rounded-full hover:bg-surface-container transition-colors">
                            <span class="material-symbols-outlined text-[20px]" data-icon="more_vert">more_vert</span>
                        </button>
                        <div id="actionMenu{{ $u->id }}" class="hidden absolute right-6 top-8 w-36 bg-surface-container-lowest rounded-lg border border-outline-variant/30 shadow-lg py-1 z-10 text-left">
                            @if($u->status != 'active')
                            <button onclick="showActionModal('aktifkan', {{ $u->id }})" class="w-full text-left px-4 py-2 text-sm hover:bg-green-50 text-green-600 transition-colors">Aktifkan</button>
                            @endif
                            @if($u->status == 'pending')
                            <button onclick="showActionModal('tolak', {{ $u->id }})" class="w-full text-left px-4 py-2 text-sm hover:bg-red-50 text-red-600 transition-colors">Tolak</button>
                            @endif
                            @if($u->status == 'active')
                            <button onclick="showActionModal('nonaktif', {{ $u->id }})" class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50 text-gray-700 transition-colors">Nonaktif</button>
                            <button onclick="showActionModal('suspend', {{ $u->id }})" class="w-full text-left px-4 py-2 text-sm hover:bg-orange-50 text-orange-600 transition-colors">Suspend (Block)</button>
                            @endif
                            <button onclick="showActionModal('hapus', {{ $u->id }})" class="w-full text-left px-4 py-2 text-sm hover:bg-red-50 text-red-600 transition-colors">Hapus Akun</button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Pagination Footer -->
    <div id="pagination-container" class="bg-surface-container-low border-t border-outline-variant p-4 flex items-center justify-between">
        <!-- Will be populated by JS -->
    </div>
</div>

<!-- Global Modals for Actions -->
<div id="modal-action" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/50 hidden backdrop-blur-sm transition-opacity">
    <!-- Dynamic Content will be injected here -->
</div>

<!-- Global Toasts for Actions -->
<div id="toast-action" class="fixed top-5 left-1/2 -translate-x-1/2 z-[70] flex items-center gap-3 px-6 py-3 rounded-lg shadow-lg transition-all duration-300 transform {{ session('success') ? 'opacity-100 visible translate-y-0 bg-green-100 border border-green-300 text-green-800' : 'opacity-0 invisible -translate-y-4' }}">
    <span class="material-symbols-outlined" id="toast-icon">{{ session('success') ? 'check_circle' : '' }}</span>
    <span class="font-bold text-sm" id="toast-text">{{ session('success') ?? '' }}</span>
</div>

@push('scripts')
<script>
    function toggleDropdown(type) {
        const targetId = type === 'role' ? 'dropdownRole' : (type === 'status' ? 'dropdownStatus' : 'dropdownBulk');
        const target = document.getElementById(targetId);
        
        ['dropdownRole', 'dropdownStatus', 'dropdownBulk'].forEach(id => {
            if (id !== targetId) {
                const el = document.getElementById(id);
                if (el) el.classList.add('hidden');
            }
        });
        
        target.classList.toggle('hidden');
    }

    function selectDropdown(type, value, label) {
        if (type === 'role') {
            document.getElementById('filterRole').value = value;
            document.getElementById('filterRoleLabel').textContent = label;
            document.getElementById('dropdownRole').classList.add('hidden');
        } else if (type === 'status') {
            document.getElementById('filterStatus').value = value;
            document.getElementById('filterStatusLabel').textContent = label;
            document.getElementById('dropdownStatus').classList.add('hidden');
        }
        filterTable();
        currentPage = 1; // reset page
    }
    
    // --- Bulk Action Logic ---
    function toggleSelectAll(source) {
        const checkboxes = document.querySelectorAll('.user-checkbox');
        checkboxes.forEach(cb => {
            // Only toggle visible rows
            if (cb.closest('tr').style.display !== 'none') {
                cb.checked = source.checked;
            }
        });
        updateBulkActionState();
    }

    function updateBulkActionState() {
        const checkboxes = document.querySelectorAll('.user-checkbox:checked');
        const count = checkboxes.length;
        const btn = document.getElementById('btnBulkAction');
        document.getElementById('selectedCount').textContent = count;
        
        if (count > 0) {
            btn.removeAttribute('disabled');
            btn.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            btn.setAttribute('disabled', 'disabled');
            btn.classList.add('opacity-50', 'cursor-not-allowed');
            document.getElementById('dropdownBulk').classList.add('hidden');
        }
        
        // Update selectAll checkbox state
        const visibleCheckboxes = Array.from(document.querySelectorAll('.user-checkbox')).filter(cb => cb.closest('tr').style.display !== 'none');
        const allChecked = visibleCheckboxes.length > 0 && visibleCheckboxes.every(cb => cb.checked);
        document.getElementById('selectAll').checked = allChecked;
    }

    function executeBulkAction(action) {
        const checkboxes = document.querySelectorAll('.user-checkbox:checked');
        if (checkboxes.length === 0) return;
        
        const confirmMsg = action === 'hapus' 
            ? "Peringatan: Anda akan menghapus permanen " + checkboxes.length + " akun. Lanjutkan?" 
            : "Terapkan aksi " + action + " pada " + checkboxes.length + " akun?";
            
        if (confirm(confirmMsg)) {
            const ids = Array.from(checkboxes).map(cb => cb.value).join(',');
            document.getElementById('bulk_user_ids').value = ids;
            document.getElementById('bulk_action').value = action;
            document.getElementById('bulkForm').submit();
        }
    }
    // ------------------------

    let currentPage = 1;
    const rowsPerPage = 10;

    function filterTable() {
        const query = document.getElementById('searchInput').value.toLowerCase();
        const role = document.getElementById('filterRole').value;
        const status = document.getElementById('filterStatus').value;
        
        const rows = document.querySelectorAll('.account-row');
        let visibleRows = [];
        
        rows.forEach(row => {
            const rowRole = row.getAttribute('data-role');
            const rowStatus = row.getAttribute('data-status');
            const rowName = row.querySelector('.account-name').textContent.toLowerCase();
            const rowEmail = row.querySelector('.account-email').textContent.toLowerCase();
            
            const matchSearch = query === '' || rowName.includes(query) || rowEmail.includes(query);
            const matchRole = role === 'Semua' || rowRole === role;
            const matchStatus = status === 'Semua' || rowStatus === status;
            
            if(matchSearch && matchRole && matchStatus) {
                visibleRows.push(row);
            } else {
                row.style.display = 'none';
            }
        });
        
        updateBulkActionState(); // Update checkboxes logic after filter
        
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
        
        let html = `<span class="font-body-md text-body-md text-on-surface-variant text-sm">Menampilkan ${startText}-${endText} dari ${totalRows} akun (Maksimal 10 per halaman)</span>`;
        html += `<div class="flex items-center gap-1">`;
        
        // Prev
        if (currentPage === 1) {
            html += `<button class="p-1 rounded text-outline hover:bg-surface-container opacity-50 cursor-not-allowed"><span class="material-symbols-outlined" data-icon="chevron_left">chevron_left</span></button>`;
        } else {
            html += `<button onclick="changePage(${currentPage - 1})" class="p-1 rounded text-on-surface-variant hover:text-primary hover:bg-surface-container"><span class="material-symbols-outlined" data-icon="chevron_left">chevron_left</span></button>`;
        }
        
        // Numbers
        for (let i = 1; i <= totalPages; i++) {
            if (i === currentPage) {
                html += `<button class="w-8 h-8 rounded bg-primary text-on-primary font-label-sm text-sm flex items-center justify-center">${i}</button>`;
            } else {
                html += `<button onclick="changePage(${i})" class="w-8 h-8 rounded text-on-surface-variant hover:bg-surface-container font-label-sm text-sm flex items-center justify-center">${i}</button>`;
            }
        }
        
        // Next
        if (currentPage === totalPages) {
            html += `<button class="p-1 rounded text-outline hover:bg-surface-container opacity-50 cursor-not-allowed"><span class="material-symbols-outlined" data-icon="chevron_right">chevron_right</span></button>`;
        } else {
            html += `<button onclick="changePage(${currentPage + 1})" class="p-1 rounded text-on-surface-variant hover:text-primary hover:bg-surface-container"><span class="material-symbols-outlined" data-icon="chevron_right">chevron_right</span></button>`;
        }
        
        html += `</div>`;
        container.innerHTML = html;
    }

    document.addEventListener('DOMContentLoaded', () => {
        filterTable();
    });
    
    function toggleActionMenu(id) {
        const menus = document.querySelectorAll('[id^="actionMenu"]');
        menus.forEach(menu => {
            if(menu.id !== 'actionMenu'+id) menu.classList.add('hidden');
        });
        const target = document.getElementById('actionMenu'+id);
        target.classList.toggle('hidden');
    }

    document.addEventListener('click', function (event) {
        const roleWrapper = document.getElementById('dropdownRole');
        const statusWrapper = document.getElementById('dropdownStatus');
        const bulkWrapper = document.getElementById('dropdownBulk');
        
        if (!event.target.closest('[onclick="toggleDropdown(\'role\')"]') && roleWrapper && !roleWrapper.classList.contains('hidden')) {
            roleWrapper.classList.add('hidden');
        }
        if (!event.target.closest('[onclick="toggleDropdown(\'status\')"]') && statusWrapper && !statusWrapper.classList.contains('hidden')) {
            statusWrapper.classList.add('hidden');
        }
        if (!event.target.closest('#btnBulkAction') && !event.target.closest('#dropdownBulk') && bulkWrapper && !bulkWrapper.classList.contains('hidden')) {
            bulkWrapper.classList.add('hidden');
        }
        
        const actionMenus = document.querySelectorAll('[id^="actionMenu"]');
        actionMenus.forEach(menu => {
            const userId = menu.id.replace('actionMenu', '');
            const btn = document.querySelector(`[onclick="toggleActionMenu(${userId})"]`);
            if (!event.target.closest(`#actionMenu${userId}`) && event.target !== btn && (btn && !btn.contains(event.target))) {
                menu.classList.add('hidden');
            }
        });
    });

    // --- Action Modal Logic ---
    const modalAction = document.getElementById('modal-action');
    const toastAction = document.getElementById('toast-action');
    let currentAction = '';

    const actionData = {
        'aktifkan': {
            icon: 'how_to_reg', iconColor: 'text-[#feae2c]', title: 'Aktifkan Akun?', titleColor: 'text-[#50290b]',
            desc: 'Akun akan aktif dan user dapat login ke dalam sistem.', bg: 'bg-[#fef9f3]', border: 'border-[#d6c3b8]',
            btnBg: 'bg-[#feae2c]', btnText: 'text-[#6b4500]', btnHover: 'hover:brightness-110', label: 'Ya, Aktifkan',
            toastBg: 'bg-green-100', toastBorder: 'border-green-300', toastText: 'text-green-800', toastIcon: 'check_circle', toastMsg: 'Akun berhasil diaktifkan!'
        },
        'tolak': {
            icon: 'person_off', iconColor: 'text-red-500', title: 'Tolak Akun?', titleColor: 'text-red-700',
            desc: 'Apakah Anda yakin ingin menolak pendaftaran akun ini?', bg: 'bg-red-50', border: 'border-red-200',
            btnBg: 'bg-red-500', btnText: 'text-white', btnHover: 'hover:bg-red-600', label: 'Ya, Tolak',
            toastBg: 'bg-red-100', toastBorder: 'border-red-300', toastText: 'text-red-800', toastIcon: 'delete', toastMsg: 'Pendaftaran ditolak.'
        },
        'suspend': {
            icon: 'block', iconColor: 'text-orange-500', title: 'Suspend Akun?', titleColor: 'text-orange-700',
            desc: 'User tidak akan bisa login sampai akun diaktifkan kembali.', bg: 'bg-orange-50', border: 'border-orange-200',
            btnBg: 'bg-orange-500', btnText: 'text-white', btnHover: 'hover:bg-orange-600', label: 'Ya, Suspend',
            toastBg: 'bg-orange-100', toastBorder: 'border-orange-300', toastText: 'text-orange-800', toastIcon: 'block', toastMsg: 'Akun berhasil disuspend.'
        },
        'nonaktif': {
            icon: 'person_remove', iconColor: 'text-gray-500', title: 'Nonaktifkan Akun?', titleColor: 'text-gray-700',
            desc: 'Akun akan dinonaktifkan sementara.', bg: 'bg-gray-50', border: 'border-gray-200',
            btnBg: 'bg-gray-500', btnText: 'text-white', btnHover: 'hover:bg-gray-600', label: 'Ya, Nonaktifkan',
            toastBg: 'bg-gray-100', toastBorder: 'border-gray-300', toastText: 'text-gray-800', toastIcon: 'info', toastMsg: 'Akun dinonaktifkan.'
        },
        'hapus': {
            icon: 'delete_forever', iconColor: 'text-red-600', title: 'Hapus Permanen?', titleColor: 'text-red-800',
            desc: 'Seluruh data akun ini akan dihapus secara permanen.', bg: 'bg-red-50', border: 'border-red-200',
            btnBg: 'bg-red-600', btnText: 'text-white', btnHover: 'hover:bg-red-700', label: 'Ya, Hapus',
            toastBg: 'bg-red-100', toastBorder: 'border-red-300', toastText: 'text-red-800', toastIcon: 'delete', toastMsg: 'Akun berhasil dihapus permanen.'
        }
    };

    function showActionModal(action, userId) {
        currentAction = action;
        const data = actionData[action];
        
        modalAction.innerHTML = `
            <div class="${data.bg} rounded-xl shadow-2xl p-6 w-full max-w-sm border ${data.border} text-center">
                <span class="material-symbols-outlined ${data.iconColor} text-5xl mb-4">${data.icon}</span>
                <h3 class="text-xl font-bold ${data.titleColor} mb-2" style="font-family: var(--font-serif)">${data.title}</h3>
                <p class="text-xs text-on-surface-variant mb-6 opacity-80">${data.desc}</p>
                
                <form method="POST" action="{{ route('admin.akun.status') }}">
                    @csrf
                    <input type="hidden" name="user_id" value="${userId}">
                    <input type="hidden" name="action" value="${action}">
                    
                    <div class="flex gap-2 justify-center">
                        <button type="button" onclick="closeActionModal()" class="px-4 py-2 rounded-lg font-bold text-xs text-on-surface-variant border ${data.border} hover:bg-black/5 transition-colors">Batal</button>
                        <button type="submit" class="px-4 py-2 rounded-lg font-bold text-xs ${data.btnBg} ${data.btnText} ${data.btnHover} transition-all">${data.label}</button>
                    </div>
                </form>
            </div>
        `;
        
        modalAction.classList.remove('hidden');
        const menus = document.querySelectorAll('[id^="actionMenu"]');
        menus.forEach(menu => menu.classList.add('hidden'));
    }

    function closeActionModal() {
        modalAction.classList.add('hidden');
    }

    // Auto hide toast if success
    if (document.getElementById('toast-text').textContent !== '') {
        setTimeout(() => {
            toastAction.classList.remove('opacity-100', 'visible', 'translate-y-0');
            toastAction.classList.add('opacity-0', 'invisible', '-translate-y-4');
        }, 3000);
    }
</script>
@endpush


@endsection
