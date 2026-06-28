@extends('layouts.siswa')
@section('title', 'Nilai & Feedback - SMK Mandalahayu 1')
@section('page-title', 'Nilai dan Feedback')

@section('content')
<style>
    .animate-fade-in {
        animation: fadeIn 0.3s ease-in-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: var(--color-outline-variant); border-radius: 10px; }
    @media (max-width: 767px) {
        .student-grade-table thead {
            display: none;
        }

        .student-grade-table,
        .student-grade-table tbody,
        .student-grade-table tr,
        .student-grade-table td {
            display: block;
            width: 100%;
        }

        .student-grade-table tbody {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            padding: 0.75rem;
        }

        .student-grade-table tr {
            border: 1px solid rgba(132, 116, 107, 0.28);
            border-radius: 0.75rem;
            overflow: hidden;
            background: var(--color-surface);
        }

        .student-grade-table td {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 0.75rem 1rem;
            text-align: right;
        }

        .student-grade-table td::before {
            content: attr(data-label);
            color: var(--color-on-surface-variant);
            font-size: 0.68rem;
            font-weight: 700;
            text-align: left;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .student-grade-table .primary-cell {
            align-items: flex-start;
            flex-direction: column;
            text-align: left;
        }

        .student-grade-table .action-cell {
            justify-content: flex-start;
        }

        .student-grade-table .feedback-row {
            margin-top: -0.75rem;
            border-top: 0;
        }

        .student-grade-table .feedback-cell {
            display: block;
            text-align: left;
        }

        .student-grade-table .feedback-cell::before {
            display: none;
        }
    }
</style>

<div class="space-y-6">
    <!-- Summary Stats Section -->
    <section class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- IPK Card -->
        <div class="bg-primary text-on-primary p-5 rounded-xl shadow-sm relative overflow-hidden group hover:-translate-y-0.5 transition-soft">
            <div class="relative z-10">
                <p class="text-[11px] uppercase tracking-widest text-on-primary/60 mb-1 font-bold">Rata-rata Keseluruhan</p>
                <h3 class="text-4xl font-bold leading-none mb-2" style="font-family: var(--font-serif)">{{ number_format($rataRata, 1) }}</h3>
                <p class="text-[11px] text-secondary font-bold flex items-center gap-1">
                </p>
            </div>
            <div class="absolute right-0 bottom-0 opacity-10 transform translate-x-1/4 translate-y-1/4 group-hover:scale-110 transition-transform duration-700">
                <span class="material-symbols-outlined text-[100px]">school</span>
            </div>
        </div>

        <!-- Progress Ring Cards -->
        <div class="bg-primary-container text-on-primary p-5 rounded-xl shadow-sm relative overflow-hidden group hover:-translate-y-0.5 transition-soft">
            <div class="relative z-10 h-full flex flex-col justify-between">
                <div>
                    <p class="text-[11px] uppercase tracking-widest text-on-primary/60 mb-1 font-bold">Tugas Terkumpul</p>
                    <h3 class="text-4xl font-bold leading-none mb-2" style="font-family: var(--font-serif)">{{ $tugasSelesai }}/{{ $totalTugas }}</h3>
                    <p class="text-[11px] text-secondary font-bold flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">check_circle</span>
                        Diselesaikan
                    </p>
                </div>
                <div class="w-full bg-on-primary/10 h-1.5 rounded-full mt-4">
                    <div class="bg-secondary h-1.5 rounded-full" style="width: {{ $totalTugas > 0 ? ($tugasSelesai / $totalTugas * 100) : 0 }}%"></div>
                </div>
            </div>
            <div class="absolute right-0 bottom-0 opacity-10 transform translate-x-1/4 translate-y-1/4 group-hover:scale-110 transition-transform duration-700">
                <span class="material-symbols-outlined text-[100px]">assignment_turned_in</span>
            </div>
        </div>
    </section>

    <!-- Graded Items Table Section -->
    <section class="space-y-4">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-[20px]">history_edu</span>
                <h3 class="text-lg font-bold text-primary" style="font-family: var(--font-serif)">Daftar Nilai & Evaluasi</h3>
            </div>
            <div class="flex flex-wrap gap-4 w-full md:w-auto">
                {{-- Custom Select Kategori --}}
                <div class="relative w-full md:w-48">
                    <input type="hidden" id="filter-category" value="semua">
                    <button type="button" onclick="toggleDropdownSiswa('kategori')" class="w-full bg-surface border border-outline-variant/50 rounded-xl px-4 py-2 text-sm text-left text-on-surface hover:bg-surface-variant/50 transition-soft focus:outline-none focus:border-secondary flex items-center justify-between">
                        <span id="filterKategoriLabel" class="block truncate mr-6">Semua Kategori</span>
                        <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none transition-soft" style="font-size: 20px">expand_more</span>
                    </button>
                    <div id="dropdownKategori" class="hidden absolute right-0 z-20 mt-2 w-full md:min-w-[170px] bg-surface rounded-xl border border-outline-variant/30 shadow-xl overflow-hidden">
                        <button type="button" onclick="selectDropdownSiswa('kategori','semua','Semua Kategori')" class="w-full text-left px-4 py-2 text-sm text-on-surface hover:bg-surface-variant/60 transition-soft">Semua Kategori</button>
                        <button type="button" onclick="selectDropdownSiswa('kategori','ujian','Ujian')" class="w-full text-left px-4 py-2 text-sm text-on-surface hover:bg-surface-variant/60 transition-soft">Ujian</button>
                        <button type="button" onclick="selectDropdownSiswa('kategori','tugas','Tugas')" class="w-full text-left px-4 py-2 text-sm text-on-surface hover:bg-surface-variant/60 transition-soft">Tugas</button>
                        <button type="button" onclick="selectDropdownSiswa('kategori','kuis','Kuis')" class="w-full text-left px-4 py-2 text-sm text-on-surface hover:bg-surface-variant/60 transition-soft">Kuis</button>
                    </div>
                </div>
                {{-- Custom Select Urutkan --}}
                <div class="relative w-full md:w-48">
                    <input type="hidden" id="sort-score" value="terbaru">
                    <button type="button" onclick="toggleDropdownSiswa('sort')" class="w-full bg-surface border border-outline-variant/50 rounded-xl px-4 py-2 text-sm text-left text-on-surface hover:bg-surface-variant/50 transition-soft focus:outline-none focus:border-secondary flex items-center justify-between">
                        <span id="sortScoreLabel" class="block truncate mr-6">Terbaru</span>
                        <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none transition-soft" style="font-size: 20px">expand_more</span>
                    </button>
                    <div id="dropdownSort" class="hidden absolute right-0 z-20 mt-2 w-full md:min-w-[170px] bg-surface rounded-xl border border-outline-variant/30 shadow-xl overflow-hidden">
                        <button type="button" onclick="selectDropdownSiswa('sort','terbaru','Terbaru')" class="w-full text-left px-4 py-2 text-sm text-on-surface hover:bg-surface-variant/60 transition-soft">Terbaru</button>
                        <button type="button" onclick="selectDropdownSiswa('sort','tertinggi','Nilai Tertinggi')" class="w-full text-left px-4 py-2 text-sm text-on-surface hover:bg-surface-variant/60 transition-soft">Nilai Tertinggi</button>
                        <button type="button" onclick="selectDropdownSiswa('sort','terendah','Nilai Terendah')" class="w-full text-left px-4 py-2 text-sm text-on-surface hover:bg-surface-variant/60 transition-soft">Nilai Terendah</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Container -->
        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-sm overflow-hidden flex flex-col">
            <div class="hidden md:block">
                <table class="responsive-card-table student-grade-table w-full table-fixed text-left border-collapse">
                    <thead class="bg-surface-container text-on-surface-variant uppercase text-[10px] font-bold tracking-widest sticky top-0 z-10">
                        <tr>
                            <th class="px-5 py-3 w-[40%]">Nama Tugas / Ujian</th>
                            <th class="px-4 py-3 w-[15%]">Kategori</th>
                            <th class="px-4 py-3 w-[15%]">Tanggal Dinilai</th>
                            <th class="px-4 py-3 text-center w-[15%]">Skor</th>
                            <th class="px-5 py-3 text-right w-[15%]">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
            
            <!-- Limit to max 350px so about 7 rows are visible -->
            <div class="overflow-y-auto custom-scrollbar max-h-[350px]">
                <table class="responsive-card-table student-grade-table w-full table-fixed text-left border-collapse">
                    <tbody id="table-body" class="divide-y divide-outline-variant/20">
                        <!-- Rendered by JS -->
                    </tbody>
                </table>
            </div>
            
            <!-- Footer pagination info -->
            <div class="px-5 py-3 bg-surface-container flex justify-between items-center text-[11px] font-bold text-on-surface-variant border-t border-outline-variant/30">
                <p id="table-info">Menampilkan 10 entri</p>
                <div class="flex gap-1">
                    <button class="w-7 h-7 flex items-center justify-center rounded bg-surface-container-lowest border border-outline-variant/30 text-primary hover:bg-secondary hover:text-white transition-all shadow-sm">1</button>
                    <button class="w-7 h-7 flex items-center justify-center rounded border border-outline-variant/30 text-primary hover:bg-secondary hover:text-white transition-all">2</button>
                    <button class="w-7 h-7 flex items-center justify-center rounded border border-outline-variant/30 text-primary hover:bg-secondary hover:text-white transition-all">3</button>
                    <button class="w-7 h-7 flex items-center justify-center rounded border border-outline-variant/30 text-primary hover:bg-secondary hover:text-white transition-all">
                        <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                    </button>
                </div>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
    const dataNilai = @json($dataNilai);

    function getCategoryBadge(cat) {
        if (cat === 'ujian') return '<span class="px-2 py-0.5 bg-primary-fixed text-on-primary-fixed text-[9px] font-bold rounded-full uppercase tracking-wider">Ujian</span>';
        if (cat === 'tugas') return '<span class="px-2 py-0.5 bg-tertiary-fixed text-on-tertiary-fixed text-[9px] font-bold rounded-full uppercase tracking-wider">Tugas</span>';
        if (cat === 'kuis') return '<span class="px-2 py-0.5 bg-secondary-fixed text-on-secondary-fixed text-[9px] font-bold rounded-full uppercase tracking-wider">Kuis</span>';
        return '';
    }

    function renderTable(data) {
        const tbody = document.getElementById('table-body');
        tbody.innerHTML = '';
        
        if (data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="5" class="px-5 py-8 text-center text-on-surface-variant font-bold text-xs">Tidak ada data untuk filter ini.</td></tr>`;
            document.getElementById('table-info').innerText = 'Menampilkan 0 entri';
            return;
        }

        data.forEach(item => {
            const tr = document.createElement('tr');
            tr.className = 'hover:bg-surface-container-low transition-colors group';
            tr.innerHTML = `
                <td data-label="Nama" class="primary-cell px-5 py-3 w-[40%]">
                    <div class="font-bold text-primary group-hover:text-secondary transition-colors text-[12px] truncate max-w-[280px]">${item.name}</div>
                    <div class="text-[10px] text-on-surface-variant/80 mt-0.5 truncate max-w-[280px]">${item.desc}</div>
                </td>
                <td data-label="Kategori" class="px-4 py-3 w-[15%]">
                    ${getCategoryBadge(item.category)}
                </td>
                <td data-label="Tanggal" class="px-4 py-3 text-on-surface-variant text-[11px] font-medium w-[15%]">${item.dateStr}</td>
                <td data-label="Skor" class="px-4 py-3 w-[15%]">
                    <div class="flex flex-col items-center">
                        <span class="text-lg font-bold text-primary leading-none" style="font-family: var(--font-serif)">${item.score}</span>
                        <span class="text-[9px] text-on-surface-variant/60 font-bold">/ 100</span>
                    </div>
                </td>
                <td data-label="Aksi" class="action-cell px-5 py-3 text-right w-[15%]">
                    <button class="bg-secondary text-on-secondary px-3 py-1.5 rounded-lg font-bold text-[10px] hover:brightness-110 transform active:scale-95 transition-all" onclick="toggleFeedback(${item.id})">Feedback</button>
                </td>
            `;

            const trFb = document.createElement('tr');
            trFb.id = `fb${item.id}`;
            trFb.className = 'hidden bg-surface-container-low border-t-0 feedback-row';
            trFb.innerHTML = `
                <td class="feedback-cell px-5 py-3" colspan="5">
                    <div class="flex gap-3 items-start bg-surface-container-lowest p-3 rounded-xl border border-outline-variant/30 shadow-sm ml-4">
                        <span class="material-symbols-outlined text-secondary text-[20px]">chat_bubble</span>
                        <div class="space-y-1.5">
                            <p class="font-bold text-primary text-[11px]">Komentar Guru (${item.teacher}):</p>
                            <p class="italic text-on-surface-variant text-[11px] leading-relaxed">"${item.feedback}"</p>
                            <p class="text-[9px] font-bold text-error flex items-center gap-1 mt-2 pt-2 border-t border-surface-variant/50">
                                <span class="material-symbols-outlined text-[12px]">lock</span>
                                Nilai ini dikunci permanen.
                            </p>
                        </div>
                    </div>
                </td>
            `;

            tbody.appendChild(tr);
            tbody.appendChild(trFb);
        });

        document.getElementById('table-info').innerText = `Menampilkan ${data.length} entri`;
    }

    function applyFilters() {
        const cat = document.getElementById('filter-category').value;
        const sort = document.getElementById('sort-score').value;

        let filtered = dataNilai.slice();

        if (cat !== 'semua') {
            filtered = filtered.filter(d => d.category === cat);
        }

        if (sort === 'terbaru') {
            filtered.sort((a, b) => b.timestamp - a.timestamp);
        } else if (sort === 'tertinggi') {
            filtered.sort((a, b) => b.score - a.score);
        } else if (sort === 'terendah') {
            filtered.sort((a, b) => a.score - b.score);
        }

        renderTable(filtered);
    }

    function toggleFeedback(id) {
        const element = document.getElementById(`fb${id}`);
        if (element.classList.contains('hidden')) {
            element.classList.remove('hidden');
            element.classList.add('animate-fade-in');
        } else {
            element.classList.add('hidden');
            element.classList.remove('animate-fade-in');
        }
    }

    // Init table
    applyFilters();

    function toggleDropdownSiswa(type) {
        const targetId = type === 'kategori' ? 'dropdownKategori' : 'dropdownSort';
        const target = document.getElementById(targetId);
        const otherId = type === 'kategori' ? 'dropdownSort' : 'dropdownKategori';
        const other = document.getElementById(otherId);
        if (other && !other.classList.contains('hidden')) {
            other.classList.add('hidden');
        }
        target.classList.toggle('hidden');
    }

    function selectDropdownSiswa(type, value, label) {
        if (type === 'kategori') {
            document.getElementById('filter-category').value = value;
            document.getElementById('filterKategoriLabel').textContent = label;
            document.getElementById('dropdownKategori').classList.add('hidden');
        } else {
            document.getElementById('sort-score').value = value;
            document.getElementById('sortScoreLabel').textContent = label;
            document.getElementById('dropdownSort').classList.add('hidden');
        }
        applyFilters();
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(event) {
        const dropdownKategori = document.getElementById('dropdownKategori');
        const dropdownSort = document.getElementById('dropdownSort');
        const btnKategori = document.getElementById('filterKategoriLabel').parentElement;
        const btnSort = document.getElementById('sortScoreLabel').parentElement;

        if (!btnKategori.contains(event.target) && !dropdownKategori.contains(event.target)) {
            dropdownKategori.classList.add('hidden');
        }
        if (!btnSort.contains(event.target) && !dropdownSort.contains(event.target)) {
            dropdownSort.classList.add('hidden');
        }
    });
</script>
@endpush
@endsection
