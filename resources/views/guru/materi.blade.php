@extends('layouts.guru')
@section('title', 'Materi - SMK Mandalahayu 1')
@section('content')
//Materi dummy untuk demo, nanti diganti dengan data dari database

<div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h2 class="font-bold text-4xl text-primary" style="font-family: var(--font-serif)">Materi Pembelajaran</h2>
        <p class="text-on-surface-variant mt-1">Kelola semua materi yang telah Anda buat.</p>
    </div>
    <a href="{{ route('guru.materi.tambah') }}" class="bg-secondary text-on-secondary font-bold py-3 px-6 rounded-xl flex items-center gap-2 transition-soft hover:brightness-110">
        <span class="material-symbols-outlined">add</span> Tambah Materi
    </a>
</div>

<div class="bg-surface rounded-xl border border-outline-variant/30 shadow-sm overflow-hidden">
    <div class="p-4 bg-surface-container-low border-b border-surface-variant flex flex-col md:flex-row gap-4 items-center">
        {{-- Search Input --}}
        <div class="relative flex-1 w-full group">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-secondary pointer-events-none transition-soft" style="font-size: 20px">search</span>
            <input id="searchInput" class="w-full bg-surface border-2 border-outline-variant/50 rounded-xl pl-10 pr-4 py-2.5 text-sm text-on-surface focus:outline-none focus:border-secondary focus:ring-0 transition-soft hover:border-secondary/50 placeholder-on-surface-variant/50" placeholder="Cari judul materi..." type="text" onkeyup="filterMateri()"/>
        </div>
        {{-- Filter Kelas --}}
        <div class="relative w-full md:w-auto md:min-w-[200px] shrink-0">
            <input type="hidden" id="filterKelas" value="Semua">
            <button type="button" onclick="toggleDropdown('kelas')" class="w-full bg-surface border-2 border-outline-variant/50 rounded-xl pl-10 pr-10 py-2.5 text-sm font-medium text-on-surface text-left focus:outline-none focus:border-secondary focus:ring-0 transition-soft hover:border-secondary/50">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none transition-soft" style="font-size: 20px">school</span>
                <span id="filterKelasLabel">Semua Kelas</span>
                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none transition-soft" style="font-size: 20px">expand_more</span>
            </button>
            <div id="dropdownKelas" class="hidden absolute z-20 mt-2 w-full md:min-w-[200px] bg-surface rounded-xl border border-outline-variant/30 shadow-xl overflow-hidden">
                <button type="button" onclick="selectDropdown('kelas','Semua','Semua Kelas')" class="w-full text-left px-4 py-2.5 text-sm text-on-surface hover:bg-surface-variant/60 transition-soft">Semua Kelas</button>
                <button type="button" onclick="selectDropdown('kelas','X TKJ 1','X TKJ 1')" class="w-full text-left px-4 py-2.5 text-sm text-on-surface hover:bg-surface-variant/60 transition-soft">X TKJ 1</button>
                <button type="button" onclick="selectDropdown('kelas','XI TKJ 1','XI TKJ 1')" class="w-full text-left px-4 py-2.5 text-sm text-on-surface hover:bg-surface-variant/60 transition-soft">XI TKJ 1</button>
                <button type="button" onclick="selectDropdown('kelas','XII TKJ 1','XII TKJ 1')" class="w-full text-left px-4 py-2.5 text-sm text-on-surface hover:bg-surface-variant/60 transition-soft">XII TKJ 1</button>
            </div>
        </div>
        {{-- Filter Status --}}
        <div class="relative w-full md:w-auto md:min-w-[170px] shrink-0">
            <input type="hidden" id="filterStatus" value="Semua">
            <button type="button" onclick="toggleDropdown('status')" class="w-full bg-surface border-2 border-outline-variant/50 rounded-xl pl-10 pr-10 py-2.5 text-sm font-medium text-on-surface text-left focus:outline-none focus:border-secondary focus:ring-0 transition-soft hover:border-secondary/50">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none transition-soft" style="font-size: 20px">inventory_2</span>
                <span id="filterStatusLabel">Semua Status</span>
                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none transition-soft" style="font-size: 20px">expand_more</span>
            </button>
            <div id="dropdownStatus" class="hidden absolute z-20 mt-2 w-full md:min-w-[170px] bg-surface rounded-xl border border-outline-variant/30 shadow-xl overflow-hidden">
                <button type="button" onclick="selectDropdown('status','Semua','Semua Status')" class="w-full text-left px-4 py-2.5 text-sm text-on-surface hover:bg-surface-variant/60 transition-soft">Semua Status</button>
                <button type="button" onclick="selectDropdown('status','Published','Published')" class="w-full text-left px-4 py-2.5 text-sm text-on-surface hover:bg-surface-variant/60 transition-soft">Published</button>
                <button type="button" onclick="selectDropdown('status','Terjadwal','Terjadwal')" class="w-full text-left px-4 py-2.5 text-sm text-on-surface hover:bg-surface-variant/60 transition-soft">Terjadwal</button>
                <button type="button" onclick="selectDropdown('status','Archive','Archive')" class="w-full text-left px-4 py-2.5 text-sm text-on-surface hover:bg-surface-variant/60 transition-soft">Archive</button>
            </div>
        </div>
    </div>
    
    {{-- Grid Materi --}}
    <div id="materiContainer" class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($materi as $m)
        <div class="materi-item bg-surface rounded-xl border border-outline-variant/30 overflow-hidden group hover:shadow-md hover:border-primary/50 transition-soft cursor-pointer flex flex-col" 
            data-judul="{{ strtolower($m->judul) }}" 
            data-kelas="{{ $m->kelas_id }}" 
            data-status="{{ $m->status ?? 'published' }}"
            onclick="window.location.href='{{ route('guru.materi.tambah') }}?edit=true&judul={{ urlencode($m->judul) }}'">
            
            <div class="h-36 bg-primary-container flex items-center justify-center relative">
                <span class="material-symbols-outlined text-5xl text-on-primary-container opacity-50 group-hover:scale-110 transition-soft">book</span>
                <span class="absolute top-3 right-3 bg-white/90 text-primary text-xs font-bold px-2 py-1 rounded shadow-sm">Materi</span>
                
                {{-- Status Badge --}}
                @php
                    $statusColor = match($m->status ?? 'published') {
                        'published' => 'bg-green-100 text-green-700',
                        'terjadwal' => 'bg-blue-100 text-blue-700',
                        'archived' => 'bg-amber-100 text-amber-700',
                        default => 'bg-gray-100 text-gray-700',
                    };
                @endphp
                <span class="absolute top-3 left-3 {{ $statusColor }} text-[10px] font-bold px-2 py-1 rounded shadow-sm flex items-center gap-1">
                    {{ $m->status ?? 'published' }}
                </span>
            </div>
            
            <div class="p-4 flex flex-col flex-1">
                <div class="flex-1">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-secondary bg-secondary-fixed/30 px-2 py-1 rounded">Pertemuan</span>
                    <h4 class="font-bold text-on-surface mt-3 mb-1 group-hover:text-primary transition-soft leading-tight">{{ $m->judul }}</h4>
                    <p class="text-xs text-on-surface-variant flex items-center gap-1 mt-2">
                        <span class="material-symbols-outlined" style="font-size: 14px">school</span> {{ $m->kelas_id }}
                    </p>
                    <p class="text-xs text-on-surface-variant flex items-center gap-1 mt-1">
                        <span class="material-symbols-outlined" style="font-size: 14px">calendar_today</span> Diunggah: {{ $m->created_at->format('d M Y') }}
                    </p>
                </div>
                
                {{-- Actions --}}
                <div class="flex gap-2 mt-5 pt-4 border-t border-outline-variant/30" onclick="event.stopPropagation()">
                    <a href="{{ route('guru.materi.tambah') }}?edit=true&judul={{ urlencode($m->judul) }}" class="flex-1 text-center py-2 border border-secondary text-secondary text-xs font-bold rounded-lg hover:bg-secondary hover:text-on-secondary transition-soft flex items-center justify-center gap-1">
                        <span class="material-symbols-outlined" style="font-size: 16px">edit</span> Edit
                    </a>
                    <button onclick="confirmDelete('{{ $m->judul }}')" class="flex-1 py-2 border border-error text-error text-xs font-bold rounded-lg hover:bg-error hover:text-white transition-soft flex items-center justify-center gap-1 group/btn">
                        <span class="material-symbols-outlined group-hover/btn:animate-bounce" style="font-size: 16px">delete</span> Hapus
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="md:col-span-2 lg:col-span-3 p-12 text-center text-on-surface-variant">
            <span class="material-symbols-outlined text-5xl mb-4">book</span>
            <p class="font-bold">Belum ada materi</p>
        </div>
        @endforelse
    </div>
    
    {{-- Empty State --}}
    <div id="emptyState" class="hidden p-12 text-center">
        <div class="w-16 h-16 bg-surface-variant rounded-full flex items-center justify-center mx-auto mb-4">
            <span class="material-symbols-outlined text-3xl text-on-surface-variant">search_off</span>
        </div>
        <h3 class="text-lg font-bold text-on-surface" style="font-family: var(--font-serif)">Materi tidak ditemukan</h3>
        <p class="text-on-surface-variant text-sm mt-1">Coba sesuaikan kata kunci atau filter pencarian Anda.</p>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div id="deleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm opacity-0 pointer-events-none transition-soft">
    <div class="bg-surface rounded-2xl shadow-xl w-full max-w-sm p-6 transform scale-95 transition-soft" id="deleteModalContent">
        <div class="w-12 h-12 rounded-full bg-error/10 flex items-center justify-center mb-4 mx-auto">
            <span class="material-symbols-outlined text-error text-2xl">warning</span>
        </div>
        <h3 class="text-xl font-bold text-center text-on-surface mb-2" style="font-family: var(--font-serif)">Hapus Materi?</h3>
        <p class="text-center text-on-surface-variant text-sm mb-6">
            Anda yakin ingin menghapus materi <br/><strong id="deleteTargetName" class="text-on-surface"></strong>?<br/>
            Tindakan ini tidak dapat dibatalkan.
        </p>
        <div class="flex gap-3">
            <button onclick="closeDeleteModal()" class="flex-1 py-2.5 border border-outline-variant text-on-surface-variant font-bold rounded-xl hover:bg-surface-variant transition-soft text-sm">
                Batal
            </button>
            <button onclick="executeDelete()" class="flex-1 py-2.5 bg-error text-white font-bold rounded-xl hover:brightness-110 transition-soft text-sm flex items-center justify-center gap-1">
                <span class="material-symbols-outlined" style="font-size: 18px">delete</span> Ya, Hapus
            </button>
        </div>
    </div>
</div>

<script>
    function filterMateri() {
        const searchInput = document.getElementById('searchInput').value.toLowerCase();
        const filterKelas = document.getElementById('filterKelas').value;
        const filterStatus = document.getElementById('filterStatus').value;
        
        const items = document.querySelectorAll('.materi-item');
        let visibleCount = 0;

        items.forEach(item => {
            const judul = item.getAttribute('data-judul');
            const kelas = item.getAttribute('data-kelas');
            const status = item.getAttribute('data-status');
            
            const matchSearch = judul.includes(searchInput);
            const matchKelas = filterKelas === 'Semua' || kelas.includes(filterKelas);
            const matchStatus = filterStatus === 'Semua' || status === filterStatus;

            if (matchSearch && matchKelas && matchStatus) {
                item.style.display = 'flex';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        // Show/hide empty state
        const emptyState = document.getElementById('emptyState');
        if (visibleCount === 0) {
            emptyState.classList.remove('hidden');
        } else {
            emptyState.classList.add('hidden');
        }
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
        filterMateri();
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

    // Modal Delete Logic
    let currentDeleteTarget = null;
    let currentDeleteItemEvent = null;

    function confirmDelete(judul) {
        currentDeleteTarget = judul;
        currentDeleteItemEvent = event;
        document.getElementById('deleteTargetName').innerText = judul;
        
        const modal = document.getElementById('deleteModal');
        const modalContent = document.getElementById('deleteModalContent');
        
        modal.classList.remove('opacity-0', 'pointer-events-none');
        modalContent.classList.remove('scale-95');
        modalContent.classList.add('scale-100');
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        const modalContent = document.getElementById('deleteModalContent');
        
        modal.classList.add('opacity-0', 'pointer-events-none');
        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-95');
        
        setTimeout(() => {
            currentDeleteTarget = null;
            currentDeleteItemEvent = null;
        }, 300);
    }

    function executeDelete() {
        // Simulasi hapus element dari DOM
        if (currentDeleteItemEvent) {
            const item = currentDeleteItemEvent.target.closest('.materi-item');
            if(item) {
                // animasi hilang
                item.style.transform = 'scale(0.9)';
                item.style.opacity = '0';
                setTimeout(() => {
                    item.remove();
                    filterMateri(); // Re-evaluate empty state
                }, 300);
            }
        }
        closeDeleteModal();
    }
</script>
@endsection
