@extends('layouts.siswa')
@section('title', 'Daftar Kuis - SMK Mandalahayu 1')
@section('page-title', 'Daftar Kuis')
@section('content')
<div class="mb-6">
    <h1 class="font-bold text-2xl text-primary mb-1" style="font-family: var(--font-serif)">Semua Kuis</h1>
    <p class="text-sm text-on-surface-variant">Daftar kuis dari seluruh mata pelajaran Anda.</p>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse($kuis as $k)
    <div class="bg-surface-container-lowest p-5 rounded-xl border border-outline-variant/30 hover:shadow-md transition-soft flex flex-col">
        <div class="flex items-start justify-between mb-3">
            <span class="bg-primary-container text-on-primary-container text-[10px] px-2 py-0.5 rounded font-bold uppercase tracking-wider">{{ $k->kelas->mata_pelajaran ?? 'Umum' }}</span>
        </div>
        <h3 class="font-bold text-primary mb-2">{{ $k->judul }}</h3>
        <p class="text-xs text-on-surface-variant line-clamp-2 mb-4">{{ $k->deskripsi }}</p>
        <div class="mt-auto flex items-center justify-between pt-3 border-t border-surface-variant">
            <span class="text-[10px] font-bold text-on-surface-variant flex items-center gap-1">
                <span class="material-symbols-outlined text-[14px]">timer</span> 
                {{ $k->durasi ? $k->durasi . ' Menit' : 'Tanpa Waktu' }}
            </span>
            <a href="{{ route('siswa.pengerjaan-kuis', $k->id) }}" class="text-xs font-bold text-primary hover:text-secondary flex items-center gap-1">
                Buka <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
            </a>
        </div>
    </div>
    @empty
    <div class="col-span-1 md:col-span-3 text-center py-10 bg-surface-container-lowest rounded-xl border border-outline-variant/30">
        <span class="material-symbols-outlined text-4xl text-on-surface-variant opacity-50 mb-3">quiz</span>
        <h3 class="font-bold text-lg text-primary">Tidak ada kuis</h3>
    </div>
    @endforelse
</div>
@endsection
