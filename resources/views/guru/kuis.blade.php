@extends('layouts.guru')
@section('title', 'Kuis - SMK Mandalahayu 1')
@section('page-title', 'Kuis')
@section('content')
<style>
    @media (max-width: 767px) {
        .kuis-table thead {
            display: none;
        }

        .kuis-table,
        .kuis-table tbody,
        .kuis-table tr,
        .kuis-table td {
            display: block;
            width: 100%;
        }

        .kuis-table tbody {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            padding: 0.75rem;
        }

        .kuis-table tr {
            border: 1px solid rgba(132, 116, 107, 0.28);
            border-radius: 0.75rem;
            overflow: hidden;
            background: var(--color-surface);
        }

        .kuis-table td {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 0.75rem 1rem;
            text-align: right;
        }

        .kuis-table td::before {
            content: attr(data-label);
            color: var(--color-on-surface-variant);
            font-size: 0.68rem;
            font-weight: 700;
            text-align: left;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }
    }
</style>
@php
    $kelasList = $kuis->map(fn($item) => $item->kelas?->nama_kelas)
        ->filter()
        ->unique()
        ->values();
    $statusList = collect(['aktif']);
@endphp

<div class="max-w-[1200px] mx-auto space-y-6">
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <h2 class="font-bold text-4xl text-primary" style="font-family: var(--font-serif)">Manajemen Kuis</h2>
            <p class="text-on-surface-variant mt-1">Buat dan kelola kuis interaktif untuk siswa.</p>
        </div>
        <a href="{{ route('guru.kuis.buat') }}" class="bg-secondary text-on-secondary font-bold py-3 px-6 rounded-xl flex items-center gap-2 transition-soft hover:brightness-110">
            <span class="material-symbols-outlined">add</span> Buat Kuis
        </a>
    </div>

    <div class="bg-surface rounded-xl border border-outline-variant/30 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-surface-variant bg-surface-container-low flex flex-col md:flex-row gap-3 justify-end">
            {{-- Filter Kelas --}}
            <div class="relative w-full md:w-auto md:min-w-[200px] shrink-0">
                <input type="hidden" id="filterKelas" value="Semua">
                <button type="button" onclick="toggleDropdown('kelas')" class="w-full bg-surface border border-outline-variant/50 rounded-xl pl-10 pr-10 py-2 text-sm font-medium text-on-surface text-left focus:outline-none focus:border-secondary transition-soft hover:border-secondary/50">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none transition-soft" style="font-size: 20px">school</span>
                    <span id="filterKelasLabel">Semua Kelas</span>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none transition-soft" style="font-size: 20px">expand_more</span>
                </button>
                <div id="dropdownKelas" class="hidden absolute z-20 mt-2 w-full md:min-w-[200px] bg-surface rounded-xl border border-outline-variant/30 shadow-xl overflow-hidden">
                    <button type="button" onclick="selectDropdown('kelas','Semua','Semua Kelas')" class="w-full text-left px-4 py-2 text-sm text-on-surface hover:bg-surface-variant/60 transition-soft">Semua Kelas</button>
                    @foreach($kelasList as $kelas)
                    <button type="button" onclick="selectDropdown('kelas','{{ $kelas }}','{{ $kelas }}')" class="w-full text-left px-4 py-2 text-sm text-on-surface hover:bg-surface-variant/60 transition-soft">{{ $kelas }}</button>
                    @endforeach
                </div>
            </div>
            {{-- Filter Status --}}
            <div class="relative w-full md:w-auto md:min-w-[170px] shrink-0">
                <input type="hidden" id="filterStatus" value="Semua">
                <button type="button" onclick="toggleDropdown('status')" class="w-full bg-surface border border-outline-variant/50 rounded-xl pl-10 pr-10 py-2 text-sm font-medium text-on-surface text-left focus:outline-none focus:border-secondary transition-soft hover:border-secondary/50">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none transition-soft" style="font-size: 20px">inventory_2</span>
                    <span id="filterStatusLabel">Semua Status</span>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none transition-soft" style="font-size: 20px">expand_more</span>
                </button>
                <div id="dropdownStatus" class="hidden absolute z-20 mt-2 w-full md:min-w-[170px] bg-surface rounded-xl border border-outline-variant/30 shadow-xl overflow-hidden">
                    <button type="button" onclick="selectDropdown('status','Semua','Semua Status')" class="w-full text-left px-4 py-2 text-sm text-on-surface hover:bg-surface-variant/60 transition-soft">Semua Status</button>
                    @foreach($statusList as $status)
                    <button type="button" onclick="selectDropdown('status','{{ $status }}','{{ ucfirst($status) }}')" class="w-full text-left px-4 py-2 text-sm text-on-surface hover:bg-surface-variant/60 transition-soft">{{ ucfirst($status) }}</button>
                    @endforeach
                </div>
            </div>
        </div>
        <table class="responsive-card-table kuis-table w-full text-left border-collapse table-fixed">
            <thead class="bg-surface-container-low border-b border-surface-variant text-xs font-bold text-on-surface-variant uppercase tracking-wider">
                <tr>
                    <th class="px-4 py-3 w-[35%]">Kuis</th>
                    <th class="px-4 py-3 w-[20%]">Kelas</th>
                    <th class="px-4 py-3 w-[15%]">Durasi</th>
                    <th class="px-4 py-3 w-[15%] text-center">Status</th>
                    <th class="px-4 py-3 w-[15%] text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-surface-variant">
                @foreach($kuis as $k)
                @php
                    $kelasNama = $k->kelas?->nama_kelas ?? 'Belum ditentukan';
                    $soalCount = $k->soal->count();
                    $jawabanCount = $k->jawaban->count();
                    $totalSiswa = $k->kelas?->siswa->count() ?? 0;
                    $status = 'aktif';
                    $durationText = $k->durasi_menit ? $k->durasi_menit.' menit' : '-';
                    $createdDate = $k->created_at ? $k->created_at->format('d M Y') : '-';
                @endphp
                <tr class="hover:bg-surface-container transition-soft" data-kelas="{{ $kelasNama }}" data-status="{{ $status }}">
                    <td data-label="Kuis" class="p-4">
                        <div class="text-right md:text-left">
                            <p class="font-bold text-on-surface">{{ $k->judul }}</p>
                            <p class="text-xs text-on-surface-variant">{{ $soalCount }} soal • {{ $createdDate }}</p>
                        </div>
                    </td>
                    <td data-label="Kelas" class="p-4 text-sm text-on-surface-variant">{{ $kelasNama }}</td>
                    <td data-label="Durasi" class="p-4 text-sm text-on-surface-variant">{{ $durationText }}</td>
                    <td data-label="Status" class="p-4 text-center">
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700 inline-block">
                            {{ ucfirst($status) }}
                        </span>
                    </td>
                    <td data-label="Aksi" class="p-4 text-center">
                        <div class="flex gap-2 justify-end lg:justify-center w-full">
                            <a href="{{ route('guru.kuis.buat', ['mode' => 'edit', 'id' => $k->id]) }}" class="p-2 rounded-lg text-secondary hover:bg-secondary-container/30 transition-soft">
                                <span class="material-symbols-outlined text-base">edit</span>
                            </a>
                            <form action="{{ route('guru.kuis.destroy', $k->id) }}" method="POST" class="inline" onsubmit="event.preventDefault(); confirmDelete(this, 'kuis ini');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 rounded-lg text-error hover:bg-error-container/30 transition-soft" title="Hapus"><span class="material-symbols-outlined text-base">delete</span></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div id="pagination-container" class="bg-surface-container-low border-t border-surface-variant p-4 flex flex-col sm:flex-row items-center justify-between gap-3 rounded-b-xl">
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div id="modal-confirm-hapus" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/50 hidden backdrop-blur-sm transition-opacity">
    <div class="bg-red-50 rounded-xl shadow-2xl p-6 w-full max-w-sm border border-red-200 text-center">
        <span class="material-symbols-outlined text-red-500 text-5xl mb-4">delete_forever</span>
        <h3 class="text-xl font-bold text-red-700 mb-2" style="font-family: var(--font-serif)">Hapus Kuis?</h3>
        <p class="text-xs text-red-600/80 mb-6" id="hapus-modal-text">Apakah Anda yakin ingin menghapus kuis ini? Tindakan ini tidak dapat dibatalkan.</p>
        <div class="flex gap-2 justify-center">
            <button type="button" onclick="closeHapusModal()" class="px-4 py-2 rounded-lg font-bold text-xs text-red-700 border border-red-200 hover:bg-red-100 transition-colors">Batal</button>
            <button type="button" id="btn-confirm-hapus" class="px-4 py-2 rounded-lg font-bold text-xs bg-red-500 text-white hover:bg-red-600 transition-all shadow-md hover:shadow-lg">Ya, Hapus</button>
        </div>
    </div>
</div>

<!-- Toast Success (Popup Hijau) -->
<div id="toast-action" class="fixed top-5 left-1/2 -translate-x-1/2 z-[70] flex items-center gap-3 bg-green-100 border border-green-300 text-green-800 px-6 py-3 rounded-lg shadow-lg transition-all duration-300 transform {{ session('success') ? 'opacity-100 visible translate-y-0' : 'opacity-0 invisible -translate-y-4' }}">
    <span class="material-symbols-outlined">check_circle</span>
    <span class="font-bold text-sm">{{ session('success') ?? '' }}</span>
</div>

@endsection
@push('scripts')
<script>
    const filterStatusInput = document.getElementById('filterStatus');
    const filterKelasInput = document.getElementById('filterKelas');
    const rows = document.querySelectorAll('div[data-status][data-kelas]');
    let currentPage = 1;
    const rowsPerPage = 10;

    function filterKuis() {
        const status = filterStatusInput.value;
        const kelas = filterKelasInput.value;
        let visibleRows = [];

        rows.forEach(row => {
            const rowStatus = row.getAttribute('data-status');
            const rowKelas = row.getAttribute('data-kelas');
            const statusMatch = status === 'Semua' || rowStatus === status;
            const kelasMatch = kelas === 'Semua' || rowKelas === kelas;
            if (statusMatch && kelasMatch) {
                visibleRows.push(row);
            } else {
                row.style.display = 'none';
            }
        });

        const totalRows = visibleRows.length;
        const totalPages = Math.ceil(totalRows / rowsPerPage) || 1;
        if (currentPage > totalPages) currentPage = totalPages;
        const start = (currentPage - 1) * rowsPerPage;
        const end = start + rowsPerPage;

        visibleRows.forEach((row, index) => {
            row.style.display = (index >= start && index < end) ? '' : 'none';
        });

        renderPagination(totalRows, totalPages, start, end);
    }

    function changePage(page) {
        currentPage = page;
        filterKuis();
    }

    function renderPagination(totalRows, totalPages, start, end) {
        const container = document.getElementById('pagination-container');
        if (!container) return;
        const startText = totalRows === 0 ? 0 : start + 1;
        const endText = Math.min(end, totalRows);

        let html = `<span class="text-on-surface-variant text-sm text-center sm:text-left">Menampilkan ${startText}-${endText} dari ${totalRows} data (Maksimal 10 per halaman)</span>`;
        html += `<div class="flex flex-wrap items-center justify-center gap-1">`;
        if (currentPage === 1) {
            html += `<button class="p-1 rounded text-outline hover:bg-surface-container opacity-50 cursor-not-allowed"><span class="material-symbols-outlined" style="font-size:20px">chevron_left</span></button>`;
        } else {
            html += `<button onclick="changePage(${currentPage - 1})" class="p-1 rounded text-on-surface-variant hover:text-primary hover:bg-surface-container"><span class="material-symbols-outlined" style="font-size:20px">chevron_left</span></button>`;
        }
        for (let i = 1; i <= totalPages; i++) {
            if (i === currentPage) {
                html += `<button class="w-8 h-8 rounded bg-primary text-on-primary font-bold text-sm flex items-center justify-center">${i}</button>`;
            } else {
                html += `<button onclick="changePage(${i})" class="w-8 h-8 rounded text-on-surface-variant hover:bg-surface-container font-bold text-sm flex items-center justify-center">${i}</button>`;
            }
        }
        if (currentPage === totalPages) {
            html += `<button class="p-1 rounded text-outline hover:bg-surface-container opacity-50 cursor-not-allowed"><span class="material-symbols-outlined" style="font-size:20px">chevron_right</span></button>`;
        } else {
            html += `<button onclick="changePage(${currentPage + 1})" class="p-1 rounded text-on-surface-variant hover:text-primary hover:bg-surface-container"><span class="material-symbols-outlined" style="font-size:20px">chevron_right</span></button>`;
        }
        html += `</div>`;
        container.innerHTML = html;
    }

    function toggleDropdown(type) {
        const targetId = type === 'kelas' ? 'dropdownKelas' : 'dropdownStatus';
        const target = document.getElementById(targetId);
        const otherId = type === 'kelas' ? 'dropdownStatus' : 'dropdownKelas';
        const other = document.getElementById(otherId);
        if (other && !other.classList.contains('hidden')) {
            other.classList.add('hidden');
        }
        target.classList.toggle('hidden');
    }

    function selectDropdown(type, value, label) {
        if (type === 'kelas') {
            document.getElementById('filterKelas').value = value;
            document.getElementById('filterKelasLabel').textContent = label;
            document.getElementById('dropdownKelas').classList.add('hidden');
        } else {
            document.getElementById('filterStatus').value = value;
            document.getElementById('filterStatusLabel').textContent = label;
            document.getElementById('dropdownStatus').classList.add('hidden');
        }
        currentPage = 1;
        filterKuis();
    }

    document.addEventListener('click', function (event) {
        const kelasWrapper = document.getElementById('dropdownKelas');
        const statusWrapper = document.getElementById('dropdownStatus');
        if (!event.target.closest('[onclick="toggleDropdown(\'kelas\')"]') && kelasWrapper && !kelasWrapper.classList.contains('hidden')) {
            kelasWrapper.classList.add('hidden');
        }
        if (!event.target.closest('[onclick="toggleDropdown(\'status\')"]') && statusWrapper && !statusWrapper.classList.contains('hidden')) {
            statusWrapper.classList.add('hidden');
        }
    });

    filterKuis();

    // --- Modal Hapus ---
    let formToSubmit = null;

    function confirmDelete(form, itemName) {
        formToSubmit = form;
        document.getElementById('hapus-modal-text').innerText = `Apakah Anda yakin ingin menghapus ${itemName}? Tindakan ini tidak dapat dibatalkan.`;
        document.getElementById('modal-confirm-hapus').classList.remove('hidden');
    }

    function closeHapusModal() {
        document.getElementById('modal-confirm-hapus').classList.add('hidden');
        formToSubmit = null;
    }

    document.getElementById('btn-confirm-hapus').addEventListener('click', function() {
        if (formToSubmit) {
            formToSubmit.submit();
        }
    });

    // --- Hide Toast Automatically ---
    @if(session('success'))
    setTimeout(() => {
        const toast = document.getElementById('toast-action');
        if(toast) {
            toast.classList.remove('opacity-100', 'visible', 'translate-y-0');
            toast.classList.add('opacity-0', 'invisible', '-translate-y-4');
        }
    }, 3000);
    @endif
</script>
@endpush
