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

        .dashboard-submission-table .submission-main-cell {
            align-items: flex-start;
            flex-direction: column;
            text-align: left;
        }

        .dashboard-submission-table .submission-action-cell {
            justify-content: flex-start;
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

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-16">
    <div class="bg-surface-container-lowest rounded-xl p-6 shadow-sm border border-surface-variant hover:bg-surface-container-low transition-soft group relative overflow-hidden">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-primary opacity-5 rounded-full group-hover:scale-150 transition-soft"></div>
        <div class="flex justify-between items-start mb-4 relative z-10">
            <div class="p-3 bg-surface-container-high rounded-lg text-primary"><span class="material-symbols-outlined">groups</span></div>
        </div>
        <div class="relative z-10">
            <p class="text-on-surface-variant text-xs font-bold uppercase tracking-wider mb-1">Kelas Aktif</p>
            <h3 class="font-bold text-4xl text-primary" style="font-family: var(--font-serif)">{{ $kelasAktif }}</h3>
            <p class="text-sm text-on-surface-variant mt-2">Semester Ganjil 2023</p>
        </div>
    </div>
    <div class="bg-surface-container-lowest rounded-xl p-6 shadow-sm border border-red-200 hover:bg-red-50/30 transition-soft group relative overflow-hidden">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-red-400 opacity-5 rounded-full group-hover:scale-150 transition-soft"></div>
        <div class="flex justify-between items-start mb-4 relative z-10">
            <div class="p-3 bg-red-50 rounded-lg text-red-500"><span class="material-symbols-outlined">assignment_late</span></div>
            <span class="bg-red-400 text-white text-xs font-bold px-2 py-1 rounded-full animate-pulse">Perlu Perhatian</span>
        </div>
        <div class="relative z-10">
            <p class="text-on-surface-variant text-xs font-bold uppercase tracking-wider mb-1">Belum Dinilai</p>
            <h3 class="font-bold text-4xl text-red-500" style="font-family: var(--font-serif)">{{ $belumDinilaiCount }}</h3>
            <p class="text-sm text-on-surface-variant mt-2">Tugas &amp; Kuis</p>
        </div>
    </div>
    <div class="bg-surface-container-lowest rounded-xl p-6 shadow-sm border border-secondary-container hover:bg-secondary-container/10 transition-soft group relative overflow-hidden">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-secondary-container opacity-10 rounded-full group-hover:scale-150 transition-soft"></div>
        <div class="flex justify-between items-start mb-4 relative z-10">
            <div class="p-3 bg-secondary-fixed text-on-secondary-fixed rounded-lg"><span class="material-symbols-outlined">timer</span></div>
            <span class="flex items-center gap-1 text-secondary text-xs font-bold"><span class="w-2 h-2 rounded-full bg-secondary"></span> Live</span>
        </div>
        <div class="relative z-10">
            <p class="text-on-surface-variant text-xs font-bold uppercase tracking-wider mb-1">Ujian Berlangsung</p>
            <h3 class="font-bold text-4xl text-secondary" style="font-family: var(--font-serif)">{{ $ujianBerlangsung }}</h3>
            <p class="text-sm text-on-surface-variant mt-2">Hari Ini</p>
        </div>
    </div>
    <div class="bg-surface-container-lowest rounded-xl p-6 shadow-sm border border-surface-variant hover:bg-surface-container-low transition-soft group relative overflow-hidden">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-tertiary opacity-5 rounded-full group-hover:scale-150 transition-soft"></div>
        <div class="flex justify-between items-start mb-4 relative z-10">
            <div class="p-3 bg-tertiary-fixed text-on-tertiary-fixed rounded-lg"><span class="material-symbols-outlined">school</span></div>
        </div>
        <div class="relative z-10">
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
        <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-surface-variant overflow-hidden">
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
                    <tr class="hover:bg-surface-container transition-soft">
                        <td data-label="Nama Tugas" class="submission-main-cell p-4"><p class="font-bold text-primary">{{ $s->tugas->judul ?? 'Tugas' }}</p><p class="text-sm text-on-surface-variant">Siswa: {{ $s->siswa->name ?? '-' }} | Disubmit: {{ \Carbon\Carbon::parse($s->dikumpulkan_at)->diffForHumans() }}</p></td>
                        <td data-label="Kelas" class="p-4 text-on-surface">{{ $s->tugas->kelas->nama_kelas ?? '-' }}</td>
                        <td data-label="Menunggu" class="p-4 text-center"><span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-error-container text-error font-bold">1</span></td>
                        <td data-label="Aksi" class="submission-action-cell p-4 text-right"><a href="{{ route('guru.penilaian.tugas', ['id' => $s->id]) }}" class="px-4 py-2 border-2 border-secondary text-secondary text-xs font-bold rounded-lg hover:bg-secondary hover:text-on-secondary transition-soft">Nilai Sekarang</a></td>
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
    </section>
</div>
@endsection
