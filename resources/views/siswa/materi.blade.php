@extends('layouts.siswa')
@section('title', 'Daftar Materi - SMK Mandalahayu 1')
@section('page-title', 'Daftar Materi')
@section('content')
<div class="mb-6">
    <h1 class="font-bold text-2xl text-primary mb-1" style="font-family: var(--font-serif)">Semua Materi</h1>
    <p class="text-sm text-on-surface-variant">Daftar materi dari seluruh mata pelajaran Anda.</p>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse($materi as $m)
    <div class="bg-surface-container-lowest p-5 rounded-xl border border-outline-variant/30 hover:shadow-md transition-soft flex flex-col">
        <div class="flex items-start justify-between mb-3">
            <span class="bg-primary-container text-on-primary-container text-[10px] px-2 py-0.5 rounded font-bold uppercase tracking-wider">{{ $m->kelas->mata_pelajaran ?? 'Umum' }}</span>
            <span class="{{ $m->link_video ? 'text-secondary bg-secondary/10' : 'text-primary bg-primary/10' }} px-2 py-0.5 rounded text-[10px] font-bold">{{ $m->link_video ? 'Video' : 'Dokumen' }}</span>
        </div>
        <h3 class="font-bold text-primary mb-2">{{ $m->judul }}</h3>
        <p class="text-xs text-on-surface-variant line-clamp-2 mb-4">{{ $m->deskripsi }}</p>
        <div class="mt-auto flex items-center justify-between pt-3 border-t border-surface-variant">
            <span class="text-[10px] font-bold text-on-surface-variant flex items-center gap-1">
                <span class="material-symbols-outlined text-[14px]">calendar_today</span> 
                {{ \Carbon\Carbon::parse($m->created_at)->format('d M Y') }}
            </span>
            <a href="{{ route('siswa.lihat-materi', $m->id) }}" class="text-xs font-bold text-primary hover:text-secondary flex items-center gap-1">
                Lihat <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
            </a>
        </div>
    </div>
    @empty
    <div class="col-span-1 md:col-span-3 text-center py-10 bg-surface-container-lowest rounded-xl border border-outline-variant/30">
        <span class="material-symbols-outlined text-4xl text-on-surface-variant opacity-50 mb-3">folder_open</span>
        <h3 class="font-bold text-lg text-primary">Tidak ada materi</h3>
    </div>
    @endforelse
</div>
@endsection
