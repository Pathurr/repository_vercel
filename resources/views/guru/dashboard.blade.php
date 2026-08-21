@extends('layouts.guru')

@section('title', 'Dashboard Guru - SMK Mandalahayu 1')

@section('content')
<style>
    @media (max-width: 767px) {
        .dashboard-submission-table thead {
            display: none;
        }

        .dashboard-submission-table,
        .dashboard-submission-table tbody,
        .dashboard-submission-table tr,
        .dashboard-submission-table td {
            display: block;
            width: 100%;
        }

        .dashboard-submission-table tbody {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            padding: 0.75rem;
        }

        .dashboard-submission-table tr {
            border: 1px solid rgba(132, 116, 107, 0.28);
            border-radius: 0.75rem;
            overflow: hidden;
            background: var(--color-surface);
        }

        .dashboard-submission-table td {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 0.75rem 1rem;
            text-align: right;
        }

        .dashboard-submission-table td::before {
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

<div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 mb-8">
    <div>
        <h2 class="font-bold text-4xl text-primary mb-2" style="font-family: var(--font-serif)">Selamat Datang, {{ Auth::user()->name ?? 'Bapak Budi' }}</h2>
        <p class="text-on-surface-variant text-lg">Ringkasan aktivitas mengajar Anda hari ini.</p>
    </div>
    <p class="text-sm font-semibold text-on-surface-variant flex items-center gap-2 bg-surface-container py-2 px-4 rounded-full">
        <span class="material-symbols-outlined text-secondary">calendar_today</span>
        {{ now()->translatedFormat('l, d F Y') }}
    </p>
</div>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-8 lg:mb-16">
    {{-- Card 1: Kelas Aktif --}}
    <div class="bg-surface-container-lowest rounded-xl p-4 lg:p-6 shadow-sm border border-outline-variant/30 lg:border-surface-variant hover:shadow-md lg:hover:bg-surface-container-low transition-soft group relative overflow-hidden">
        <div class="hidden lg:block absolute -right-4 -top-4 w-24 h-24 bg-primary opacity-5 rounded-full group-hover:scale-150 transition-soft"></div>
        {{-- Mobile/Tablet: horizontal layout --}}
        <div class="flex items-center gap-4 lg:hidden">
            <div class="p-2 bg-primary-fixed rounded-lg text-primary flex-shrink-0"><span class="material-symbols-outlined text-lg">groups</span></div>
            <div>
                <h3 class="text-on-surface-variant text-xs mb-0.5" style="font-family: var(--font-serif)">Kelas Aktif</h3>
                <p class="font-bold text-2xl text-primary">{{ $kelasAktif }}</p>
            </div>
        </div>
        {{-- Desktop: vertical layout --}}
        <div class="hidden lg:block relative z-10">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-surface-container-high rounded-lg text-primary"><span class="material-symbols-outlined">groups</span></div>
            </div>
            <p class="text-on-surface-variant text-xs font-bold uppercase tracking-wider mb-1">Kelas Aktif</p>
            <h3 class="font-bold text-4xl text-primary" style="font-family: var(--font-serif)">{{ $kelasAktif }}</h3>
            <p class="text-sm text-on-surface-variant mt-2">Semester Ganjil 2026</p>
        </div>
    </div>

    {{-- Card 2: Belum Dinilai --}}
    <div class="bg-surface-container-lowest rounded-xl p-4 lg:p-6 shadow-sm border border-outline-variant/30 lg:border-red-200 hover:shadow-md lg:hover:bg-red-50/30 transition-soft group relative overflow-hidden">
        <div class="hidden lg:block absolute -right-4 -top-4 w-24 h-24 bg-red-400 opacity-5 rounded-full group-hover:scale-150 transition-soft"></div>
        @if($belumDinilaiCount > 0)
        <div class="absolute top-2 right-2 lg:hidden"><span class="bg-red-400 text-white px-1.5 py-0.5 rounded text-[8px] font-bold uppercase">Mendesak</span></div>
        @endif
        {{-- Mobile/Tablet: horizontal layout --}}
        <div class="flex items-center gap-4 lg:hidden">
            <div class="p-2 bg-red-50 rounded-lg text-red-500 flex-shrink-0"><span class="material-symbols-outlined text-lg">assignment_late</span></div>
            <div>
                <h3 class="text-on-surface-variant text-xs mb-0.5" style="font-family: var(--font-serif)">Belum Dinilai</h3>
                <p class="font-bold text-2xl text-red-500">{{ $belumDinilaiCount }}</p>
            </div>
        </div>
        {{-- Desktop: vertical layout --}}
        <div class="hidden lg:block relative z-10">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-red-50 rounded-lg text-red-500"><span class="material-symbols-outlined">assignment_late</span></div>
                <span class="bg-red-400 text-white text-xs font-bold px-2 py-1 rounded-full animate-pulse">Perlu Perhatian</span>
            </div>
            <p class="text-on-surface-variant text-xs font-bold uppercase tracking-wider mb-1">Belum Dinilai</p>
            <h3 class="font-bold text-4xl text-red-500" style="font-family: var(--font-serif)">{{ $belumDinilaiCount }}</h3>
            <p class="text-sm text-on-surface-variant mt-2">Tugas &amp; Kuis</p>
        </div>
    </div>

    {{-- Card 3: Ujian Berlangsung --}}
    <div class="bg-surface-container-lowest rounded-xl p-4 lg:p-6 shadow-sm border border-outline-variant/30 lg:border-secondary-container hover:shadow-md lg:hover:bg-secondary-container/10 transition-soft group relative overflow-hidden">
        <div class="hidden lg:block absolute -right-4 -top-4 w-24 h-24 bg-secondary-container opacity-10 rounded-full group-hover:scale-150 transition-soft"></div>
        {{-- Mobile/Tablet: horizontal layout --}}
        <div class="flex items-center gap-4 lg:hidden">
            <div class="p-2 bg-secondary-container rounded-lg text-on-secondary-container flex-shrink-0"><span class="material-symbols-outlined text-lg">timer</span></div>
            <div>
                <h3 class="text-on-surface-variant text-xs mb-0.5" style="font-family: var(--font-serif)">Ujian Berlangsung</h3>
                <p class="font-bold text-2xl text-primary">{{ $ujianBerlangsung }}</p>
            </div>
        </div>
        {{-- Desktop: vertical layout --}}
        <div class="hidden lg:block relative z-10">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-secondary-fixed text-on-secondary-fixed rounded-lg"><span class="material-symbols-outlined">timer</span></div>
                <span class="flex items-center gap-1 text-secondary text-xs font-bold"><span class="w-2 h-2 rounded-full bg-secondary"></span> Live</span>
            </div>
            <p class="text-on-surface-variant text-xs font-bold uppercase tracking-wider mb-1">Ujian Berlangsung</p>
            <h3 class="font-bold text-4xl text-secondary" style="font-family: var(--font-serif)">{{ $ujianBerlangsung }}</h3>
            <p class="text-sm text-on-surface-variant mt-2">Hari Ini</p>
        </div>
    </div>

    {{-- Card 4: Siswa Total --}}
    <div class="bg-surface-container-lowest rounded-xl p-4 lg:p-6 shadow-sm border border-outline-variant/30 lg:border-surface-variant hover:shadow-md lg:hover:bg-surface-container-low transition-soft group relative overflow-hidden">
        <div class="hidden lg:block absolute -right-4 -top-4 w-24 h-24 bg-tertiary opacity-5 rounded-full group-hover:scale-150 transition-soft"></div>
        {{-- Mobile/Tablet: horizontal layout --}}
        <div class="flex items-center gap-4 lg:hidden">
            <div class="p-2 bg-tertiary-fixed rounded-lg text-on-tertiary-fixed-variant flex-shrink-0"><span class="material-symbols-outlined text-lg">school</span></div>
            <div>
                <h3 class="text-on-surface-variant text-xs mb-0.5" style="font-family: var(--font-serif)">Siswa Total</h3>
                <p class="font-bold text-2xl text-primary">{{ $siswaTotal }}</p>
            </div>
        </div>
        {{-- Desktop: vertical layout --}}
        <div class="hidden lg:block relative z-10">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-tertiary-fixed text-on-tertiary-fixed rounded-lg"><span class="material-symbols-outlined">school</span></div>
            </div>
            <p class="text-on-surface-variant text-xs font-bold uppercase tracking-wider mb-1">Siswa Total</p>
            <h3 class="font-bold text-4xl text-primary" style="font-family: var(--font-serif)">{{ $siswaTotal }}</h3>
            <p class="text-sm text-on-surface-variant mt-2">Siswa Aktif</p>
        </div>
    </div>
</div>

<div class="space-y-8">
    <section>
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6">
            <h3 class="font-bold text-2xl text-primary flex items-center gap-2" style="font-family: var(--font-serif)">
                <span class="material-symbols-outlined text-secondary">pending_actions</span>
                Submission Menunggu Penilaian
            </h3>
            <a class="text-sm font-semibold text-secondary hover:underline" href="{{ route('guru.tugas') }}">Lihat Semua</a>
        </div>
        <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-surface-variant overflow-hidden flex flex-col">
            <div class="w-full">
                <table class="responsive-card-table dashboard-submission-table w-full table-fixed text-left">
                    <thead class="bg-surface-container-low border-b border-surface-variant">
                        <tr>
                            <th class="p-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Nama Tugas</th>
                            <th class="p-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Kelas</th>
                            <th class="p-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider text-center">Menunggu</th>
                            <th class="p-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-variant">
                        @forelse($submissionsList as $s)
                        <tr class="submission-row hover:bg-surface-container transition-soft">
                            <td data-label="Nama Tugas" class="p-4">
                                <div class="text-right md:text-left">
                                    <p class="font-bold text-primary">{{ $s->tugas->judul ?? 'Tugas' }}</p>
                                    <p class="text-sm text-on-surface-variant">Siswa: {{ $s->siswa->name ?? '-' }} | Disubmit: {{ \Carbon\Carbon::parse($s->dikumpulkan_at)->diffForHumans() }}</p>
                                </div>
                            </td>
                            <td data-label="Kelas" class="p-4 text-on-surface">{{ $s->tugas->kelas->nama_kelas ?? '-' }}</td>
                            <td data-label="Menunggu" class="p-4 text-center"><span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-error-container text-error font-bold">1</span></td>
                            <td data-label="Aksi" class="p-4 text-right">
                                <a href="{{ route('guru.penilaian.tugas', ['id' => $s->id]) }}" class="px-4 py-2 border-2 border-secondary text-secondary text-xs font-bold rounded-lg hover:bg-secondary hover:text-on-secondary transition-soft">Nilai Sekarang</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-on-surface-variant">
                                <span class="material-symbols-outlined text-4xl opacity-50 mb-2">done_all</span>
                                <p class="text-sm">Semua tugas telah dinilai!</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Footer -->
            <div id="pagination-container" class="bg-surface-container-low border-t border-surface-variant p-4 flex flex-col sm:flex-row items-center justify-between gap-3 rounded-b-xl">
                <!-- Will be populated by JS -->
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
    let currentPage = 1;
    const rowsPerPage = 10;

    function paginateSubmissions() {
        const rows = document.querySelectorAll('.submission-row');
        const totalRows = rows.length;
        const totalPages = Math.ceil(totalRows / rowsPerPage) || 1;

        if (currentPage > totalPages) currentPage = totalPages;

        const start = (currentPage - 1) * rowsPerPage;
        const end = start + rowsPerPage;

        rows.forEach((row, index) => {
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
        paginateSubmissions();
    }

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

    document.addEventListener('DOMContentLoaded', () => {
        paginateSubmissions();
    });
</script>
@endpush

@endsection

