@extends('layouts.guru')
@section('title', 'Kuis - SMK Mandalahayu 1')
@section('page-title', 'Kuis')
@section('content')
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
        <div class="hidden md:grid grid-cols-12 gap-4 bg-surface-container-low border-b border-surface-variant px-4 py-3 text-xs font-bold text-on-surface-variant uppercase tracking-wider">
            <div class="col-span-4">Kuis</div>
            <div class="col-span-2">Kelas</div>
            <div class="col-span-2">Durasi</div>
            <div class="col-span-2">Status</div>
            <div class="col-span-2 text-center">Aksi</div>
        </div>
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
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3 md:gap-4 px-4 py-4 border-b border-surface-variant last:border-b-0 hover:bg-surface-container transition-soft" data-kelas="{{ $kelasNama }}" data-status="{{ $status }}">
            <div class="md:col-span-4">
                <p class="font-bold text-on-surface">{{ $k->judul }}</p>
                <p class="text-xs text-on-surface-variant">{{ $soalCount }} soal • {{ $createdDate }}</p>
            </div>
            <div class="md:col-span-2 flex md:block items-center justify-between gap-3">
                <span class="md:hidden text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Kelas</span>
                <p class="text-sm text-on-surface-variant">{{ $kelasNama }}</p>
            </div>
            <div class="md:col-span-2 flex md:block items-center justify-between gap-3">
                <span class="md:hidden text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Durasi</span>
                <p class="text-sm text-on-surface-variant">{{ $durationText }}</p>
            </div>
            <div class="md:col-span-2 flex md:block items-center justify-between gap-3">
                <span class="md:hidden text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Status</span>
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700">
                    {{ ucfirst($status) }}
                </span>
            </div>
            <div class="md:col-span-2 flex justify-start md:justify-center gap-2">
                <a href="{{ route('guru.kuis.buat', ['mode' => 'edit', 'id' => $k->id]) }}" class="p-2 rounded-lg text-secondary hover:bg-secondary-container/30 transition-soft">
                    <span class="material-symbols-outlined text-base">edit</span>
                </a>
                <form action="{{ route('guru.kuis.destroy', $k->id) }}" method="POST" class="inline" onsubmit="event.preventDefault(); confirmDelete(this, 'kuis ini');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-2 rounded-lg text-error hover:bg-error-container/30 transition-soft" title="Hapus"><span class="material-symbols-outlined text-base">delete</span></button>
                </form>
            </div>
        </div>
        @endforeach
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

    function filterKuis() {
        const status = filterStatusInput.value;
        const kelas = filterKelasInput.value;

        rows.forEach(row => {
            const rowStatus = row.getAttribute('data-status');
            const rowKelas = row.getAttribute('data-kelas');
            const statusMatch = status === 'Semua' || rowStatus === status;
            const kelasMatch = kelas === 'Semua' || rowKelas === kelas;
            row.classList.toggle('hidden', !(statusMatch && kelasMatch));
        });
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
