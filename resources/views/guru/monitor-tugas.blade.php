@extends('layouts.guru')
@section('title', 'Monitoring Pengumpulan Tugas - SMK Mandalahayu 1')

@section('content')
<style>
    .smooth-shadow {
        box-shadow: 0 2px 10px -2px rgba(107, 63, 31, 0.08);
    }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    .btn-nilai {
        display: inline-block;
        padding: 4px 10px;
        background: #feae2c;
        color: #6b4500;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
        transition: filter .15s;
    }
    .btn-nilai:hover { filter: brightness(0.88); }
    .btn-lihat {
        display: inline-block;
        padding: 4px 10px;
        border: 1.5px solid #84746b;
        color: #84746b;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
        transition: background .15s;
    }
    .btn-lihat:hover { background: #f2ede7; }
    @media (max-width: 767px) {
        .monitor-table thead {
            display: none;
        }

        .monitor-table,
        .monitor-table tbody,
        .monitor-table tr,
        .monitor-table td {
            display: block;
            width: 100%;
        }

        .monitor-table tbody {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            padding: 0.75rem;
        }

        .monitor-table tr {
            border: 1px solid rgba(132, 116, 107, 0.28);
            border-radius: 0.75rem;
            overflow: hidden;
            background: var(--color-surface);
        }

        .monitor-table td {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            padding: 0.75rem 1rem;
            text-align: right;
        }

        .monitor-table td::before {
            content: attr(data-label);
            color: var(--color-on-surface-variant);
            font-size: 0.68rem;
            font-weight: 700;
            text-align: left;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .monitor-table .main-cell {
            flex-direction: column;
            text-align: left;
        }

        .monitor-table .action-cell {
            justify-content: flex-start;
        }
    }
</style>

<!-- Header Compact -->
<div class="mb-4 mt-2 flex flex-col sm:flex-row justify-between items-start sm:items-end gap-3">
    <div>
        <h2 class="font-bold text-2xl text-primary" style="font-family: var(--font-serif)">Monitoring Pengumpulan Tugas</h2>
        <p class="text-sm text-on-surface-variant mt-1">Pantau status pengumpulan tugas dari seluruh siswa.</p>
    </div>
</div>

<!-- Filters -->
<div class="flex flex-col gap-3 mb-4">
    <!-- Search and Dropdowns -->
    <div class="bg-surface rounded-xl border border-outline-variant/30 shadow-sm overflow-visible relative z-20">
        <div class="p-4 bg-surface-container-low flex flex-col md:flex-row gap-4 items-center">
            {{-- Search Input --}}
            <div class="relative flex-1 w-full group">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-secondary pointer-events-none transition-soft" style="font-size: 20px">search</span>
                <input id="searchName" class="w-full bg-surface border-2 border-outline-variant/50 rounded-xl pl-10 pr-4 py-2.5 text-sm text-on-surface focus:outline-none focus:border-secondary focus:ring-0 transition-soft hover:border-secondary/50 placeholder-on-surface-variant/50" placeholder="Cari nama siswa..." type="text"/>
            </div>
            
            {{-- Tipe Tugas Dropdown --}}
            <div class="relative w-full md:w-auto md:min-w-[170px] shrink-0">
                <input type="hidden" id="filterType" value="all">
                <button type="button" onclick="toggleDropdown('type')" class="w-full bg-surface border-2 border-outline-variant/50 rounded-xl pl-10 pr-10 py-2.5 text-sm font-medium text-on-surface text-left focus:outline-none focus:border-secondary focus:ring-0 transition-soft hover:border-secondary/50">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none transition-soft" style="font-size: 20px">category</span>
                    <span id="filterTypeLabel">Semua Tipe</span>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none transition-soft" style="font-size: 20px">expand_more</span>
                </button>
                <div id="dropdownType" class="hidden absolute z-20 mt-2 w-full md:min-w-[170px] bg-surface rounded-xl border border-outline-variant/30 shadow-xl overflow-hidden">
                    <button type="button" onclick="selectDropdown('type','all','Semua Tipe')" class="w-full text-left px-4 py-2.5 text-sm text-on-surface hover:bg-surface-variant/60 transition-soft">Semua Tipe</button>
                    <button type="button" onclick="selectDropdown('type','tugas','Tugas')" class="w-full text-left px-4 py-2.5 text-sm text-on-surface hover:bg-surface-variant/60 transition-soft">Tugas</button>
                    <button type="button" onclick="selectDropdown('type','kuis','Kuis')" class="w-full text-left px-4 py-2.5 text-sm text-on-surface hover:bg-surface-variant/60 transition-soft">Kuis</button>
                    <button type="button" onclick="selectDropdown('type','ujian','Ujian')" class="w-full text-left px-4 py-2.5 text-sm text-on-surface hover:bg-surface-variant/60 transition-soft">Ujian</button>
                </div>
            </div>

            {{-- Status Penilaian Dropdown --}}
            <div class="relative w-full md:w-auto md:min-w-[200px] shrink-0">
                <input type="hidden" id="filterGrade" value="all">
                <button type="button" onclick="toggleDropdown('grade')" class="w-full bg-surface border-2 border-outline-variant/50 rounded-xl pl-10 pr-10 py-2.5 text-sm font-medium text-on-surface text-left focus:outline-none focus:border-secondary focus:ring-0 transition-soft hover:border-secondary/50">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none transition-soft" style="font-size: 20px">fact_check</span>
                    <span id="filterGradeLabel">Semua Penilaian</span>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none transition-soft" style="font-size: 20px">expand_more</span>
                </button>
                <div id="dropdownGrade" class="hidden absolute z-20 mt-2 w-full md:min-w-[200px] bg-surface rounded-xl border border-outline-variant/30 shadow-xl overflow-hidden">
                    <button type="button" onclick="selectDropdown('grade','all','Semua Penilaian')" class="w-full text-left px-4 py-2.5 text-sm text-on-surface hover:bg-surface-variant/60 transition-soft">Semua Penilaian</button>
                    <button type="button" onclick="selectDropdown('grade','belum','Belum Dinilai')" class="w-full text-left px-4 py-2.5 text-sm text-on-surface hover:bg-surface-variant/60 transition-soft">Belum Dinilai</button>
                    <button type="button" onclick="selectDropdown('grade','sudah','Sudah Dinilai')" class="w-full text-left px-4 py-2.5 text-sm text-on-surface hover:bg-surface-variant/60 transition-soft">Sudah Dinilai</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filter Tabs (Status Pengumpulan) -->
    <div class="flex flex-wrap items-center gap-2 pb-1">
        <button data-status="all" class="filter-tab px-4 py-1.5 rounded-full bg-primary text-on-primary font-bold text-xs shadow-md transition-all whitespace-nowrap">Semua</button>
        <button data-status="terkumpul" class="filter-tab px-4 py-1.5 rounded-full bg-surface-container-high text-on-surface-variant font-semibold text-xs hover:bg-surface-variant transition-all whitespace-nowrap">Terkumpul</button>
        <button data-status="terlambat" class="filter-tab px-4 py-1.5 rounded-full bg-surface-container-high text-on-surface-variant font-semibold text-xs hover:bg-surface-variant transition-all whitespace-nowrap">Terlambat</button>
    </div>
</div>

<!-- Data Table Container -->
<div class="bg-white rounded-xl smooth-shadow border border-outline-variant/30 overflow-hidden mb-6">
    <table class="responsive-card-table monitor-table w-full text-left border-collapse table-fixed">
        <thead>
            <tr class="bg-surface-container-low border-b border-outline-variant">
                <th class="px-3 py-2.5 text-[10px] font-bold text-primary uppercase tracking-wider w-[20%]">Nama Siswa</th>
                <th class="px-3 py-2.5 text-[10px] font-bold text-primary uppercase tracking-wider w-[18%]">Kelas & Mapel</th>
                <th class="px-3 py-2.5 text-[10px] font-bold text-primary uppercase tracking-wider w-[20%]">Judul & Tipe</th>
                <th class="px-3 py-2.5 text-[10px] font-bold text-primary uppercase tracking-wider text-center w-[16%]">Status</th>
                <th class="px-3 py-2.5 text-[10px] font-bold text-primary uppercase tracking-wider text-center w-[14%]">Pengumpulan & Nilai</th>
                <th class="px-3 py-2.5 text-[10px] font-bold text-primary uppercase tracking-wider text-right w-[12%]">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-outline-variant/30" id="studentTableBody">
            @forelse($submissions as $submission)
                @php
                    $status = $submission->status;
                    $gradeStatus = $submission->nilai !== null ? 'sudah' : 'belum';
                    $isSuccess = in_array($status, ['terkumpul', 'tepat_waktu']);
                    $badgeClass = $isSuccess ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700';
                    $dotClass = $isSuccess ? 'bg-green-700' : 'bg-red-700';
                    $statusText = ucwords(str_replace('_', ' ', $status));
                    $scoreText = $submission->nilai !== null ? $submission->nilai : '-';
                @endphp
                <tr class="hover:bg-surface-container-lowest transition-colors" data-name="{{ strtolower($submission->siswa->name) }}" data-type="{{ $submission->type }}" data-status="{{ $status }}" data-grade="{{ $gradeStatus }}">
                    <td data-label="Nama Siswa" class="main-cell px-3 py-2.5">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 flex-shrink-0 bg-primary-fixed flex items-center justify-center rounded-full text-primary font-bold text-[10px]">{{ strtoupper(substr($submission->siswa->name, 0, 1)) }}</div>
                            <span class="font-semibold text-xs leading-tight">{{ $submission->siswa->name }}</span>
                        </div>
                    </td>
                    <td data-label="Kelas & Mapel" class="main-cell px-3 py-2.5">
                        <p class="text-xs font-semibold text-on-surface leading-tight">{{ $submission->kelas?->nama_kelas ?? 'Kelas tidak tersedia' }} <span class="text-[10px] text-on-surface-variant font-normal">· {{ $submission->kelas?->mata_pelajaran ?? 'Mata Pelajaran belum ditentukan' }}</span></p>
                        <p class="text-[10px] text-on-surface-variant mt-0.5">{{ $submission->judul }}</p>
                    </td>
                    <td data-label="Judul & Tipe" class="main-cell px-3 py-2.5">
                        <p class="text-xs font-semibold text-on-surface leading-tight truncate">{{ $submission->judul }}</p>
                        <span class="text-[10px] px-1.5 py-0.5 bg-surface-variant rounded text-on-surface-variant">{{ ucfirst($submission->type) }}</span>
                    </td>
                    <td data-label="Status" class="px-3 py-2.5 text-center">
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold inline-flex items-center gap-1 {{ $badgeClass }}">
                            <span class="w-1.5 h-1.5 {{ $dotClass }} rounded-full flex-shrink-0"></span>{{ $statusText }}
                        </span>
                    </td>
                    <td data-label="Pengumpulan & Nilai" class="main-cell px-3 py-2.5 text-center">
                        <p class="text-[10px] text-on-surface-variant">{{ $submission->dikumpulkan_at?->format('d M Y') ?? '-' }}</p>
                        <p class="text-xs font-bold text-on-surface-variant">{{ $scoreText }}</p>
                    </td>
                    <td data-label="Aksi" class="action-cell px-3 py-2.5 text-right">
                        <a href="{{ $submission->link }}" class="btn-nilai">{{ $submission->nilai !== null ? 'Edit Nilai' : 'Nilai' }}</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-3 py-8 text-center text-on-surface-variant">Belum ada pengumpulan tugas untuk ditampilkan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <!-- Pagination -->
    <div class="px-4 py-3 bg-surface-container-low border-t border-outline-variant flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
        <p class="text-on-surface-variant text-xs font-bold">Menampilkan 1-3 dari 32 siswa</p>
        <div class="flex items-center gap-1">
            <button class="w-7 h-7 flex items-center justify-center rounded border border-outline hover:bg-surface-variant transition-colors disabled:opacity-50" disabled>
                <span class="material-symbols-outlined" style="font-size:16px">chevron_left</span>
            </button>
            <button class="w-7 h-7 flex items-center justify-center rounded bg-primary text-on-primary text-xs font-bold shadow-sm">1</button>
            <button class="w-7 h-7 flex items-center justify-center rounded border border-outline hover:bg-surface-variant transition-colors text-xs font-bold text-on-surface-variant">2</button>
            <button class="w-7 h-7 flex items-center justify-center rounded border border-outline hover:bg-surface-variant transition-colors text-xs font-bold text-on-surface-variant">3</button>
            <button class="w-7 h-7 flex items-center justify-center rounded border border-outline hover:bg-surface-variant transition-colors">
                <span class="material-symbols-outlined" style="font-size:16px">chevron_right</span>
            </button>
        </div>
    </div>
</div>


@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const filterTabs = document.querySelectorAll('.filter-tab');
        const searchInput = document.getElementById('searchName');
        const filterType = document.getElementById('filterType');
        const filterGrade = document.getElementById('filterGrade');
        const rows = document.querySelectorAll('#studentTableBody tr');
        let currentStatus = 'all';

        // Filter tab switching logic
        filterTabs.forEach(btn => {
            btn.addEventListener('click', () => {
                // visual update
                filterTabs.forEach(b => {
                    b.classList.remove('bg-primary', 'text-on-primary', 'shadow-md');
                    b.classList.add('bg-surface-container-high', 'text-on-surface-variant');
                });
                btn.classList.add('bg-primary', 'text-on-primary', 'shadow-md');
                btn.classList.remove('bg-surface-container-high', 'text-on-surface-variant');
                
                // state update
                currentStatus = btn.dataset.status;
                applyFilters();
            });
        });

        // Event listeners for other filters
        searchInput.addEventListener('input', applyFilters);

        // Make functions globally available
        window.toggleDropdown = function(type) {
            const targetId = type === 'type' ? 'dropdownType' : 'dropdownGrade';
            const target = document.getElementById(targetId);
            const otherId = type === 'type' ? 'dropdownGrade' : 'dropdownType';
            const other = document.getElementById(otherId);
            if (other && !other.classList.contains('hidden')) {
                other.classList.add('hidden');
            }
            target.classList.toggle('hidden');
        };

        window.selectDropdown = function(type, value, label) {
            if (type === 'type') {
                filterType.value = value;
                document.getElementById('filterTypeLabel').textContent = label;
                document.getElementById('dropdownType').classList.add('hidden');
            } else {
                filterGrade.value = value;
                document.getElementById('filterGradeLabel').textContent = label;
                document.getElementById('dropdownGrade').classList.add('hidden');
            }
            applyFilters();
        };

        document.addEventListener('click', function(event) {
            const typeWrapper = document.getElementById('dropdownType');
            const gradeWrapper = document.getElementById('dropdownGrade');
            if (!event.target.closest('[onclick="toggleDropdown(\'type\')"]') && typeWrapper && !typeWrapper.classList.contains('hidden')) {
                typeWrapper.classList.add('hidden');
            }
            if (!event.target.closest('[onclick="toggleDropdown(\'grade\')"]') && gradeWrapper && !gradeWrapper.classList.contains('hidden')) {
                gradeWrapper.classList.add('hidden');
            }
        });

        function applyFilters() {
            const searchVal = searchInput.value.toLowerCase();
            const typeVal = filterType.value;
            const gradeVal = filterGrade.value;

            rows.forEach(row => {
                const rowName = row.dataset.name.toLowerCase();
                const rowType = row.dataset.type;
                const rowStatus = row.dataset.status;
                const rowGrade = row.dataset.grade;

                const matchSearch = rowName.includes(searchVal);
                const matchType = (typeVal === 'all' || rowType === typeVal);
                const matchGrade = (gradeVal === 'all' || rowGrade === gradeVal);
                const matchStatus = (currentStatus === 'all' || rowStatus === currentStatus);

                if (matchSearch && matchType && matchGrade && matchStatus) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    });
</script>
@endpush
@endsection
