@extends('layouts.guru')
@section('title', 'Nilai & Rekap - SMK Mandalahayu 1')

@section('content')
<style>
    @media (max-width: 767px) {
        .grade-table-responsive thead {
            display: none;
        }

        .grade-table-responsive,
        .grade-table-responsive tbody,
        .grade-table-responsive tr,
        .grade-table-responsive td {
            display: block;
            width: 100%;
        }

        .grade-table-responsive tbody {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            padding: 0.75rem;
        }

        .grade-table-responsive tr {
            border: 1px solid rgba(132, 116, 107, 0.28);
            border-radius: 0.75rem;
            overflow: hidden;
            background: var(--color-surface);
        }

        .grade-table-responsive td {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 0.7rem 1rem;
            text-align: right;
        }

        .grade-table-responsive td::before {
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

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4 mt-4">
    <div>
        <h2 class="font-bold text-2xl text-primary" style="font-family: var(--font-serif)">Nilai &amp; Rekap</h2>
        <p class="text-on-surface-variant text-sm mt-1">Kelola dan evaluasi hasil belajar siswa.</p>
    </div>
</div>

<!-- Filter Section -->
<section class="bg-white rounded-xl shadow-sm border border-outline-variant/30 mb-4 z-20 relative">
    <div class="p-4 border-b border-surface-variant bg-surface-container-low flex flex-col md:flex-row gap-3 justify-end">
        {{-- Filter Kelas --}}
        <div class="relative w-full md:w-auto md:min-w-[200px] shrink-0">
            <input type="hidden" id="filterKelas" value="">
            <button type="button" onclick="toggleDropdown('kelas')" class="w-full bg-surface border border-outline-variant/50 rounded-xl pl-10 pr-10 py-2 text-sm font-medium text-on-surface text-left focus:outline-none focus:border-secondary transition-soft hover:border-secondary/50">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none transition-soft" style="font-size: 20px">school</span>
                <span id="filterKelasLabel">Semua Kelas</span>
                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none transition-soft" style="font-size: 20px">expand_more</span>
            </button>
            <div id="dropdownKelas" class="hidden absolute z-20 mt-2 w-full md:min-w-[200px] bg-surface rounded-xl border border-outline-variant/30 shadow-xl overflow-hidden">
                <button type="button" onclick="selectDropdown('kelas','','Semua Kelas')" class="w-full text-left px-4 py-2 text-sm text-on-surface hover:bg-surface-variant/60 transition-soft">Semua Kelas</button>
                @foreach($students->pluck('kelas.nama_kelas')->unique() as $kelasName)
                    <button type="button" onclick="selectDropdown('kelas','{{ $kelasName }}','{{ $kelasName }}')" class="w-full text-left px-4 py-2 text-sm text-on-surface hover:bg-surface-variant/60 transition-soft">{{ $kelasName }}</button>
                @endforeach
            </div>
        </div>
        {{-- Filter Mata Pelajaran --}}
        <div class="relative w-full md:w-auto md:min-w-[200px] shrink-0">
            <input type="hidden" id="filterMapel" value="">
            <button type="button" onclick="toggleDropdown('mapel')" class="w-full bg-surface border border-outline-variant/50 rounded-xl pl-10 pr-10 py-2 text-sm font-medium text-on-surface text-left focus:outline-none focus:border-secondary transition-soft hover:border-secondary/50">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none transition-soft" style="font-size: 20px">menu_book</span>
                <span id="filterMapelLabel">Semua Mapel</span>
                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none transition-soft" style="font-size: 20px">expand_more</span>
            </button>
            <div id="dropdownMapel" class="hidden absolute z-20 mt-2 w-full md:min-w-[200px] bg-surface rounded-xl border border-outline-variant/30 shadow-xl overflow-hidden">
                <button type="button" onclick="selectDropdown('mapel','','Semua Mapel')" class="w-full text-left px-4 py-2 text-sm text-on-surface hover:bg-surface-variant/60 transition-soft">Semua Mapel</button>
                @foreach($students->pluck('kelas.mata_pelajaran')->unique() as $mapelName)
                    <button type="button" onclick="selectDropdown('mapel','{{ $mapelName }}','{{ $mapelName }}')" class="w-full text-left px-4 py-2 text-sm text-on-surface hover:bg-surface-variant/60 transition-soft">{{ $mapelName }}</button>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- Summary Bento Grid -->
<section class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
    <div class="bg-white p-4 rounded-xl shadow-sm border border-outline-variant/30 flex flex-col justify-center">
        <p class="text-on-surface-variant text-xs mb-1">Total Siswa</p>
        <div class="flex items-baseline gap-2">
            <h3 class="font-bold text-3xl text-primary" style="font-family: var(--font-serif)">{{ $summary['total'] }}</h3>
        </div>
    </div>
    <div class="bg-white p-4 rounded-xl shadow-sm border border-outline-variant/30 flex flex-col justify-center">
        <p class="text-on-surface-variant text-xs mb-1">Rata-rata Nilai</p>
        <div class="flex items-baseline gap-2">
            <h3 class="font-bold text-3xl text-secondary" style="font-family: var(--font-serif)">{{ $summary['average'] }}</h3>
            <span class="text-xs text-on-surface-variant">/ 100</span>
        </div>
    </div>
    <div class="bg-white p-4 rounded-xl shadow-sm border border-outline-variant/30 flex flex-col justify-center">
        <p class="text-on-surface-variant text-xs mb-1">Nilai Tertinggi</p>
        <div class="flex items-baseline gap-2">
            <h3 class="font-bold text-3xl text-outline" style="font-family: var(--font-serif)">{{ $summary['max'] }}</h3>
            <span class="text-xs text-on-surface-variant">/ 100</span>
        </div>
    </div>
    <div class="bg-primary text-on-primary p-4 rounded-xl shadow-sm flex flex-col justify-center relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-white to-transparent"></div>
        <p class="text-primary-fixed-dim text-xs mb-1 relative z-10">Tingkat Kelulusan</p>
        <div class="flex items-baseline gap-2 relative z-10">
            <h3 class="font-bold text-3xl" style="font-family: var(--font-serif)">{{ $summary['pass'] ? round($summary['pass'] / max(1, $summary['total']) * 100) : 0 }}%</h3>
            <span class="text-xs text-tertiary-fixed-dim">{{ $summary['pass'] }}/{{ $summary['total'] }}</span>
        </div>
    </div>
</section>

<!-- Master Grade Table -->
<section class="bg-white rounded-xl shadow-sm border border-outline-variant/30 overflow-hidden">
    <div class="p-4 border-b border-surface-variant flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 bg-surface-container-low">
        <h3 class="font-bold text-xl text-primary" style="font-family: var(--font-serif)">Rekapitulasi Nilai Akhir</h3>
        <div class="relative w-full sm:w-auto">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-sm">search</span>
            <input id="searchInput" class="pl-9 pr-3 py-1.5 bg-white rounded-full border border-surface-variant focus:ring-2 focus:ring-secondary-container text-sm w-full sm:w-56" placeholder="Cari nama siswa..." type="text">
        </div>
    </div>
    <div class="w-full">
        <table class="responsive-card-table grade-table-responsive w-full table-fixed text-left border-collapse" id="gradesTable">
            <thead>
                <tr class="bg-surface-container text-on-surface font-bold text-xs border-b border-surface-variant">
                    <th class="py-2 px-4 w-12">No</th>
                    <th class="py-2 px-4">Nama Siswa</th>
                    <th class="py-2 px-4">Kelas</th>
                    <th class="py-2 px-4">Mata Pelajaran</th>
                    <th class="py-2 px-4 text-center">Tugas</th>
                    <th class="py-2 px-4 text-center">Kuis</th>
                    <th class="py-2 px-4 text-center">Ujian</th>
                    <th class="py-2 px-4 text-center text-primary">Akhir</th>
                    <th class="py-2 px-4 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="text-xs text-on-background">
                @forelse($students as $i => $item)
                <tr class="grade-row border-b border-surface-variant hover:bg-surface-container-low transition-colors {{ $item['average'] < 75 ? 'bg-red-50/30' : '' }}">
                    <td data-label="No" class="py-2 px-4 text-on-surface-variant">{{ $i + 1 }}</td>
                    <td data-label="Nama" class="py-2 px-4 font-semibold text-primary student-name">{{ $item['siswa']->name }}</td>
                    <td data-label="Kelas" class="py-2 px-4 student-kelas">{{ $item['kelas']->nama_kelas }}</td>
                    <td data-label="Mapel" class="py-2 px-4 student-mapel">{{ $item['kelas']->mata_pelajaran }}</td>
                    <td data-label="Tugas" class="py-2 px-4 text-center">{{ $item['tugas'] }}</td>
                    <td data-label="Kuis" class="py-2 px-4 text-center">{{ $item['kuis'] }}</td>
                    <td data-label="Ujian" class="py-2 px-4 text-center">{{ $item['ujian'] }}</td>
                    <td data-label="Akhir" class="py-2 px-4 text-center font-bold">{{ $item['average'] }}</td>
                    <td data-label="Status" class="py-2 px-4 text-center">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold {{ $item['status'] === 'Lulus' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <span class="material-symbols-outlined text-[12px]">{{ $item['status'] === 'Lulus' ? 'check_circle' : 'cancel' }}</span> {{ $item['status'] }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="p-6 text-center text-on-surface-variant">Belum ada data nilai untuk ditampilkan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div id="pagination-container" class="bg-surface-container-low border-t border-surface-variant p-4 flex flex-col sm:flex-row items-center justify-between gap-3 rounded-b-xl">
        <!-- Will be populated by JS -->
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const filterKelas = document.getElementById('filterKelas');
        const filterMapel = document.getElementById('filterMapel');
        const rows = document.querySelectorAll('.grade-row');

        let currentPage = 1;
        const rowsPerPage = 10;

        function applyFilters() {
            const query = searchInput.value.toLowerCase();
            const kelasVal = filterKelas.value;
            const mapelVal = filterMapel.value;

            let visibleRows = [];

            rows.forEach(row => {
                const name = (row.querySelector('.student-name')?.textContent || '').toLowerCase();
                const kelas = row.querySelector('.student-kelas')?.textContent || '';
                const mapel = row.querySelector('.student-mapel')?.textContent || '';

                const matchName = name.includes(query);
                const matchKelas = kelasVal === '' || kelas === kelasVal;
                const matchMapel = mapelVal === '' || mapel === mapelVal;

                if (matchName && matchKelas && matchMapel) {
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

        window.changePage = function(page) {
            currentPage = page;
            applyFilters();
        };

        function renderPagination(totalRows, totalPages, start, end) {
            const container = document.getElementById('pagination-container');
            if (!container) return;

            const startText = totalRows === 0 ? 0 : start + 1;
            const endText = Math.min(end, totalRows);

            let html = `<span class="text-on-surface-variant text-sm text-center sm:text-left">Menampilkan ${startText}-${endText} dari ${totalRows} data (Maksimal 10 per halaman)</span>`;
            html += `<div class="flex flex-wrap items-center justify-center gap-1">`;

            // Prev
            if (currentPage === 1) {
                html += `<button class="p-1 rounded text-outline hover:bg-surface-container opacity-50 cursor-not-allowed"><span class="material-symbols-outlined" style="font-size:20px">chevron_left</span></button>`;
            } else {
                html += `<button onclick="changePage(${currentPage - 1})" class="p-1 rounded text-on-surface-variant hover:text-primary hover:bg-surface-container"><span class="material-symbols-outlined" style="font-size:20px">chevron_left</span></button>`;
            }

            // Numbers
            for (let i = 1; i <= totalPages; i++) {
                if (i === currentPage) {
                    html += `<button class="w-8 h-8 rounded bg-primary text-on-primary font-bold text-sm flex items-center justify-center">${i}</button>`;
                } else {
                    html += `<button onclick="changePage(${i})" class="w-8 h-8 rounded text-on-surface-variant hover:bg-surface-container font-bold text-sm flex items-center justify-center">${i}</button>`;
                }
            }

            // Next
            if (currentPage === totalPages) {
                html += `<button class="p-1 rounded text-outline hover:bg-surface-container opacity-50 cursor-not-allowed"><span class="material-symbols-outlined" style="font-size:20px">chevron_right</span></button>`;
            } else {
                html += `<button onclick="changePage(${currentPage + 1})" class="p-1 rounded text-on-surface-variant hover:text-primary hover:bg-surface-container"><span class="material-symbols-outlined" style="font-size:20px">chevron_right</span></button>`;
            }

            html += `</div>`;
            container.innerHTML = html;
        }

        searchInput.addEventListener('keyup', () => {
            currentPage = 1;
            applyFilters();
        });

        window.toggleDropdown = function(type) {
            const targetId = type === 'kelas' ? 'dropdownKelas' : 'dropdownMapel';
            const target = document.getElementById(targetId);
            const otherId = type === 'kelas' ? 'dropdownMapel' : 'dropdownKelas';
            const other = document.getElementById(otherId);
            if (other && !other.classList.contains('hidden')) {
                other.classList.add('hidden');
            }
            target.classList.toggle('hidden');
        };

        window.selectDropdown = function(type, value, label) {
            if (type === 'kelas') {
                filterKelas.value = value;
                document.getElementById('filterKelasLabel').textContent = label;
                document.getElementById('dropdownKelas').classList.add('hidden');
            } else {
                filterMapel.value = value;
                document.getElementById('filterMapelLabel').textContent = label;
                document.getElementById('dropdownMapel').classList.add('hidden');
            }
            currentPage = 1;
            applyFilters();
        };

        // Initialize table
        applyFilters();
    });
</script>
@endsection
