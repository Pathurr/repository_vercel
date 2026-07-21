@extends('layouts.siswa')

@section('title', 'Dashboard Siswa - SMK Mandalahayu 1')
@section('page-title', 'Dashboard')

@section('content')
{{-- Greeting Section --}}
<section class="bg-surface-container rounded-xl p-5 border border-outline-variant/30 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
    <div>
        <h2 class="font-bold text-xl text-primary mb-1" style="font-family: var(--font-serif)">Selamat datang kembali, {{ $user->name ?? 'Siswa' }}!</h2>
        <div class="flex items-center gap-2">
            <span class="bg-primary-fixed text-primary px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider">{{ $semester }}</span>
            <span class="text-on-surface-variant text-xs">•</span>
            <span class="text-on-surface-variant text-xs">{{ $user->kelas->first()->nama_kelas ?? 'Belum ada kelas' }}</span>
        </div>
    </div>
</section>

{{-- Quick Stats --}}
<section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-surface-container-lowest rounded-xl p-4 border border-outline-variant/30 shadow-sm hover:shadow-md transition-soft flex items-center gap-4">
        <div class="p-2 bg-primary-fixed rounded-lg text-primary"><span class="material-symbols-outlined text-lg">menu_book</span></div>
        <div>
            <h3 class="text-on-surface-variant text-xs mb-0.5" style="font-family: var(--font-serif)">Mata Pelajaran Aktif</h3>
            <p class="font-bold text-2xl text-primary">{{ $kelasAktifCount }}</p>
        </div>
    </div>
    <div class="bg-surface-container-lowest rounded-xl p-4 border border-outline-variant/30 shadow-sm hover:shadow-md transition-soft relative flex items-center gap-4">
        @if($tugasBelumDikerjakanCount > 0)
        <div class="absolute top-2 right-2"><span class="bg-red-400 text-white px-1.5 py-0.5 rounded text-[8px] font-bold uppercase">Mendesak</span></div>
        @endif
        <div class="p-2 bg-red-50 rounded-lg text-red-500"><span class="material-symbols-outlined text-lg">assignment_late</span></div>
        <div>
            <h3 class="text-on-surface-variant text-xs mb-0.5" style="font-family: var(--font-serif)">Tugas Belum Dikerjakan</h3>
            <p class="font-bold text-2xl text-red-500">{{ $tugasBelumDikerjakanCount }}</p>
        </div>
    </div>
    <div class="bg-surface-container-lowest rounded-xl p-4 border border-outline-variant/30 shadow-sm hover:shadow-md transition-soft flex items-center gap-4">
        <div class="p-2 bg-secondary-container rounded-lg text-on-secondary-container"><span class="material-symbols-outlined text-lg">schedule</span></div>
        <div>
            <h3 class="text-on-surface-variant text-xs mb-0.5" style="font-family: var(--font-serif)">Tugas Mendekati Deadline</h3>
            <p class="font-bold text-2xl text-primary">{{ $tugasMendekatiDeadlineCount }}</p>
        </div>
    </div>
    <div class="bg-surface-container-lowest rounded-xl p-4 border border-outline-variant/30 shadow-sm hover:shadow-md transition-soft flex items-center gap-4">
        <div class="p-2 bg-tertiary-fixed rounded-lg text-on-tertiary-fixed-variant"><span class="material-symbols-outlined text-lg">grade</span></div>
        <div>
            <h3 class="text-on-surface-variant text-xs mb-0.5" style="font-family: var(--font-serif)">Rata-rata Nilai</h3>
            <p class="font-bold text-2xl text-primary">{{ number_format($rataRataNilai, 1) }}</p>
        </div>
    </div>
</section>

{{-- Main Grid --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Left: Tasks & Materials --}}
    <div class="lg:col-span-2 flex flex-col gap-6">
        {{-- Aktivitas Mendekati Deadline --}}
        <section>
            <div class="flex justify-between items-end mb-4">
                <h3 class="font-bold text-lg text-primary" style="font-family: var(--font-serif)">Aktivitas Mendatang</h3>
            </div>
            <div class="space-y-3">
                @forelse($aktivitasMendatang as $a)
                <div class="bg-surface-container-lowest p-3 rounded-lg border border-outline-variant/30 flex flex-col sm:flex-row sm:items-center justify-between gap-3 group hover:border-secondary transition-soft">
                    <div class="flex items-start gap-3">
                        <div class="bg-surface-container-high p-2 rounded-lg text-primary">
                            <span class="material-symbols-outlined text-sm">
                                {{ $a->tipe === 'Ujian' ? 'school' : ($a->tipe === 'Kuis' ? 'quiz' : 'assignment') }}
                            </span>
                        </div>
                        <div>
                            <div class="flex items-center gap-2 mb-0.5">
                                <span class="bg-tertiary-fixed text-on-tertiary-fixed px-1.5 py-0.5 rounded text-[8px] font-bold uppercase tracking-wider">{{ $a->tipe }}</span>
                                <h4 class="font-bold text-sm text-on-surface">{{ $a->judul }}</h4>
                            </div>
                            <p class="text-on-surface-variant text-xs">{{ $a->mata_pelajaran }}</p>
                        </div>
                    </div>
                    <div class="flex sm:flex-col items-center sm:items-end gap-3 sm:gap-1">
                        <div class="{{ $a->deadline && \Carbon\Carbon::parse($a->deadline)->isPast() ? 'text-error bg-error-container/30' : 'text-secondary bg-secondary-container/30' }} font-semibold text-[10px] flex items-center gap-1 px-1.5 py-0.5 rounded">
                            <span class="material-symbols-outlined text-xs">timer</span>
                            <span>{{ $a->deadline ? \Carbon\Carbon::parse($a->deadline)->diffForHumans() : 'Tanpa Tenggat' }}</span>
                        </div>
                        <a href="{{ $a->route }}" class="bg-secondary-container hover:bg-secondary text-on-secondary-container hover:text-white font-semibold px-4 py-1.5 rounded transition-soft text-xs">Kerjakan</a>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 bg-surface-container-lowest rounded-lg border border-outline-variant/30">
                    <span class="material-symbols-outlined text-on-surface-variant text-3xl mb-2 opacity-50">task</span>
                    <p class="text-sm text-on-surface-variant">Belum ada aktivitas yang mendekati deadline.</p>
                </div>
                @endforelse
        </section>

        {{-- Materi Terbaru --}}
        <section class="mt-4">
            <div class="flex justify-between items-end mb-4">
                <h3 class="font-bold text-lg text-primary" style="font-family: var(--font-serif)">Materi Terbaru</h3>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @forelse($materiBaru as $m)
                <a href="{{ route('siswa.lihat-materi', $m->id) }}" class="bg-surface-container-lowest rounded-lg border border-outline-variant/30 overflow-hidden group hover:shadow-md transition-soft flex flex-col">
                    <div class="h-24 relative {{ $m->link_video ? 'bg-secondary-container' : 'bg-primary-container' }}">
                        <div class="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition-soft"></div>
                        <span class="absolute top-2 left-2 {{ $m->link_video ? 'bg-secondary/80 text-on-secondary' : 'bg-primary/80 text-on-primary' }} text-[8px] px-1.5 py-0.5 rounded uppercase tracking-widest font-bold">
                            {{ $m->link_video ? 'Video' : 'PDF' }}
                        </span>
                    </div>
                    <div class="p-3">
                        <h4 class="font-bold text-sm text-on-surface mb-0.5 group-hover:text-secondary transition-soft">{{ $m->judul }}</h4>
                        <p class="text-[10px] text-on-surface-variant">{{ $m->kelas->mata_pelajaran ?? 'Umum' }}</p>
                    </div>
                </a>
                @empty
                <div class="col-span-1 sm:col-span-2 text-center py-6 bg-surface-container-lowest rounded-lg border border-outline-variant/30">
                    <span class="material-symbols-outlined text-on-surface-variant text-3xl mb-2 opacity-50">folder_open</span>
                    <p class="text-sm text-on-surface-variant">Belum ada materi baru.</p>
                </div>
                @endforelse
            </div>
        </section>
    </div>

    {{-- Right: Grades --}}
    <div class="flex flex-col gap-6">

        {{-- Nilai Terbaru --}}
        <section class="bg-surface-container-lowest rounded-lg p-4 border border-outline-variant/30 shadow-sm mt-4 lg:mt-0">
            <h3 class="font-bold text-lg text-primary mb-3" style="font-family: var(--font-serif)">Nilai Terbaru</h3>
            <div class="flex flex-col gap-2">
                @forelse($nilaiTerbaru as $n)
                @php
                    // Pilih warna secara acak untuk keindahan UI (primary atau secondary)
                    $color = array_rand(['primary' => 1, 'secondary' => 1]);
                @endphp
                <div class="bg-surface-container-low rounded p-3 flex justify-between items-center group hover:bg-{{ $color }}/5 transition-soft border border-transparent hover:border-{{ $color }}/30">
                    <div>
                        <h4 class="font-bold text-xs text-on-surface mb-0.5">{{ $n['mapel'] }}</h4>
                        <p class="text-[9px] text-on-surface-variant uppercase tracking-wider">{{ $n['judul'] }}</p>
                    </div>
                    <div class="bg-{{ $color }}-container text-on-{{ $color }}-container font-bold text-xs px-2.5 py-1 rounded shadow-sm">
                        {{ $n['nilai'] }}
                    </div>
                </div>
                @empty
                <div class="text-center py-4 bg-surface-container-lowest rounded-lg border border-outline-variant/30">
                    <p class="text-xs text-on-surface-variant">Belum ada nilai terbaru.</p>
                </div>
                @endforelse
            </div>
            <a href="{{ route('siswa.nilai') }}" class="block w-full mt-4 py-1.5 text-center text-primary text-xs font-semibold hover:bg-primary/10 rounded-lg transition-soft border border-outline-variant/30">Lihat Seluruh Nilai</a>
        </section>
    </div>
</div>
@endsection
