@extends('layouts.siswa')
@section('title', 'Materi Pembelajaran - Portal Siswa')
@section('page-title', 'Materi Pembelajaran')

@section('content')
<div class="flex flex-col lg:flex-row gap-6 items-start w-full">
    <!-- Local Course Sidebar -->
    <aside class="w-full lg:w-72 lg:sticky lg:top-20 bg-surface-container-low border border-outline-variant rounded-xl p-5 flex-shrink-0">
        <div class="mb-5">
            <p class="font-bold text-[10px] text-secondary uppercase tracking-widest mb-1">Mata Pelajaran</p>
            <h2 class="font-bold text-lg text-primary leading-tight" style="font-family: var(--font-serif)">{{ $materi->kelas->mata_pelajaran ?? 'Umum' }}</h2>
        </div>
        <div class="space-y-4">
            <div>
                <h4 class="font-bold text-[11px] text-outline mb-3">Daftar Materi</h4>
                <ul class="space-y-2">
                    @foreach($materis as $idx => $m)
                    @php
                        $isActive = $m->id == $materi->id;
                        $bgClass = $isActive ? 'bg-surface-container-lowest shadow-sm border border-secondary' : 'hover:bg-surface-container-high border border-transparent';
                        $iconBg = $isActive ? 'bg-secondary/10 text-secondary' : 'bg-surface-variant text-outline';
                    @endphp
                    <li>
                        <a href="{{ route('siswa.lihat-materi', $m->id) }}" class="group flex items-start gap-3 p-3 rounded-xl transition-all {{ $bgClass }}">
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center {{ $iconBg }}">
                                <span class="material-symbols-outlined text-[20px]">description</span>
                            </div>
                            <div class="flex-1">
                                <p class="font-bold text-xs {{ $isActive ? 'text-primary' : 'text-on-surface' }}">Pertemuan {{ $idx + 1 }}</p>
                                <p class="text-[10px] text-on-surface-variant line-clamp-1 font-bold">{{ $m->judul }}</p>
                            </div>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </aside>

    <!-- Content Viewer -->
    <section class="flex-1 w-full min-w-0">
        <!-- Title & Status -->
        <div class="flex flex-col md:flex-row justify-between items-start gap-4 mb-6">
            <div class="flex-1">
                <h1 class="font-bold text-2xl text-primary mb-2" style="font-family: var(--font-serif)">{{ $materi->judul }}</h1>
                <p class="text-xs text-on-surface-variant leading-relaxed">{{ $materi->deskripsi }}</p>
            </div>
            <div class="flex flex-row md:flex-col items-center md:items-end gap-3 flex-shrink-0 w-full md:w-auto justify-between md:justify-start">
                @if($materi->file_path)
                <a href="{{ asset('storage/' . $materi->file_path) }}" download class="flex items-center gap-1 font-bold text-[11px] text-secondary hover:underline transition-all">
                    <span class="material-symbols-outlined text-[16px]">download</span>
                    Download File
                </a>
                @endif
                
                @if(!$isRead)
                <form action="{{ route('siswa.materi.read', $materi->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-primary text-on-primary px-4 py-2 rounded-lg font-bold text-xs hover:bg-primary-container hover:text-on-primary-container transition-colors shadow-sm flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">check_circle</span>
                        Tandai Selesai Dibaca
                    </button>
                </form>
                @else
                <span class="px-3 py-1.5 bg-green-100 text-green-800 font-bold text-xs rounded-lg flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">done_all</span>
                    Selesai Dibaca
                </span>
                @endif
            </div>
        </div>

        @if($materi->file_path)
        <!-- PDF Viewer -->
        <div class="bg-surface-container-highest rounded-xl shadow-sm border border-outline-variant overflow-hidden flex flex-col mb-6">
            <div class="flex justify-center bg-[#e5e5e5] h-[600px]">
                <iframe src="{{ asset('storage/' . $materi->file_path) }}" class="w-full h-full border-none"></iframe>
            </div>
        </div>
        @else
        <div class="bg-surface-container-low rounded-xl p-8 text-center border border-dashed border-outline-variant mb-6">
            <span class="material-symbols-outlined text-4xl text-outline mb-2">description</span>
            <p class="text-on-surface-variant text-sm font-bold">Materi ini tidak memiliki lampiran dokumen.</p>
            <p class="text-on-surface-variant/70 text-xs mt-1">Silakan baca deskripsi di atas.</p>
        </div>
        @endif

        <!-- Material Navigation Buttons -->
        @php
            $currentIndex = $materis->search(function($item) use ($materi) {
                return $item->id == $materi->id;
            });
            $prevMateri = $currentIndex > 0 ? $materis[$currentIndex - 1] : null;
            $nextMateri = $currentIndex < ($materis->count() - 1) ? $materis[$currentIndex + 1] : null;
        @endphp
        
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-4 border-t border-outline-variant">
            @if($prevMateri)
            <a href="{{ route('siswa.lihat-materi', $prevMateri->id) }}" class="w-full sm:w-auto group flex items-center gap-3 py-2 px-4 rounded-xl hover:bg-secondary hover:shadow-sm transition-all text-left">
                <div class="w-8 h-8 rounded-full bg-surface-container-highest flex items-center justify-center text-outline group-hover:bg-white/20 group-hover:text-white transition-all">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                </div>
                <div>
                    <p class="font-bold text-[10px] text-outline group-hover:text-on-secondary/80">Materi Sebelumnya</p>
                    <p class="text-xs text-primary font-bold group-hover:text-on-secondary">{{ $prevMateri->judul }}</p>
                </div>
            </a>
            @else
            <div></div> <!-- Spacer -->
            @endif
            
            <div class="flex-1"></div>

            @if($nextMateri)
            <a href="{{ route('siswa.lihat-materi', $nextMateri->id) }}" class="w-full sm:w-auto group flex items-center gap-3 py-2 px-4 rounded-xl hover:bg-secondary hover:shadow-sm transition-all text-right justify-end">
                <div>
                    <p class="font-bold text-[10px] text-outline group-hover:text-on-secondary/80">Materi Selanjutnya</p>
                    <p class="text-xs font-bold text-primary group-hover:text-on-secondary">{{ $nextMateri->judul }}</p>
                </div>
                <div class="w-8 h-8 rounded-full bg-surface-container-highest flex items-center justify-center text-outline group-hover:bg-white/20 group-hover:text-white transition-all">
                    <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                </div>
            </a>
            @endif
        </div>
    </section>
</div>
@endsection
