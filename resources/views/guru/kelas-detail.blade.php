@extends('layouts.guru')
@section('title', 'Detail Kelas - X TKJ 1')

@section('content')
<div class="max-w-[1200px] mx-auto w-full">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row gap-6 items-start justify-between mb-8">
        {{-- Left: Class Info --}}
        <div class="flex gap-6 items-center">
            {{-- Icon Box --}}
            <div class="w-24 h-24 rounded-2xl bg-secondary-container flex items-center justify-center flex-shrink-0 text-on-secondary-container">
                <span class="material-symbols-outlined text-5xl">computer</span>
            </div>
            {{-- Text --}}
            <div>
                <h1 class="font-bold text-5xl text-primary mb-1" style="font-family: var(--font-serif)">{{ $kelas->nama_kelas }}</h1>
                <p class="font-bold text-secondary uppercase tracking-wider text-sm mb-4">{{ $kelas->mata_pelajaran }}</p>
                <div class="flex flex-wrap items-center gap-4 md:gap-6 text-on-surface-variant text-sm">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined" style="font-size: 18px">calendar_today</span>
                        <div>
                            <p>Dibuat</p>
                            <p>{{ $kelas->created_at?->format('d M Y') ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="hidden md:block w-px h-8 bg-outline-variant/50"></div>
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined" style="font-size: 18px">meeting_room</span>
                        <div>
                            <p>Kode Kelas</p>
                            <p>{{ $kelas->kode_kelas ?? 'Belum tersedia' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: Enrollment Key Panel --}}
        <div class="bg-surface-container-low border border-outline-variant/30 rounded-2xl p-5 w-full md:w-auto mt-4 md:mt-0">
            <div class="flex items-center gap-2 mb-3 text-on-surface-variant text-xs font-bold">
                <span class="material-symbols-outlined" style="font-size: 16px">key</span>
                ENROLLMENT KEY
                <span id="enrollmentStatus" class="bg-secondary-container/30 text-secondary px-2 py-0.5 rounded text-[10px]">Aktif</span>
            </div>
            <div class="flex flex-wrap sm:flex-nowrap items-center gap-3">
                <div id="enrollmentKeyDisplay" class="bg-surface border border-outline-variant/30 rounded-xl px-4 py-2 font-mono font-bold text-lg text-primary tracking-[0.2em] flex-1 text-center sm:text-left">
                    {{ $kelas->kode_kelas }}
                </div>
                <div class="flex gap-2">
                    <button onclick="copyEnrollmentKey()" class="w-10 h-10 bg-surface border border-outline-variant/30 rounded-xl flex items-center justify-center text-on-surface-variant hover:bg-surface-container transition-soft" title="Salin">
                        <span class="material-symbols-outlined" style="font-size: 20px">content_copy</span>
                    </button>
                </div>
                <button id="toggleEnrollmentBtn" onclick="toggleEnrollmentKey()" class="w-full sm:w-auto h-10 px-4 bg-error-container/20 border border-error/20 text-error rounded-xl font-bold text-sm flex items-center justify-center gap-2 hover:bg-error-container/40 transition-soft">
                    <span id="toggleEnrollmentIcon" class="material-symbols-outlined" style="font-size: 18px">block</span>
                    <span id="toggleEnrollmentText">Nonaktifkan</span>
                </button>
            </div>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="border-b border-outline-variant/30 mb-8">
        <nav class="flex gap-4 md:gap-8 px-2 overflow-x-auto custom-scrollbar" id="class-tabs">
            <button onclick="switchTab('siswa', this)" class="tab-btn border-b-4 border-secondary text-secondary font-bold py-4 flex items-center gap-2 whitespace-nowrap">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">groups</span> Siswa
            </button>
            <button onclick="switchTab('materi', this)" class="tab-btn border-b-4 border-transparent text-on-surface-variant hover:text-secondary font-semibold py-4 flex items-center gap-2 whitespace-nowrap transition-soft">
                <span class="material-symbols-outlined">folder</span> Materi
            </button>
            <button onclick="switchTab('tugas', this)" class="tab-btn border-b-4 border-transparent text-on-surface-variant hover:text-secondary font-semibold py-4 flex items-center gap-2 whitespace-nowrap transition-soft">
                <span class="material-symbols-outlined">assignment</span> Tugas
            </button>
            <button onclick="switchTab('kuis', this)" class="tab-btn border-b-4 border-transparent text-on-surface-variant hover:text-secondary font-semibold py-4 flex items-center gap-2 whitespace-nowrap transition-soft">
                <span class="material-symbols-outlined">quiz</span> Kuis
            </button>
            <button onclick="switchTab('ujian', this)" class="tab-btn border-b-4 border-transparent text-on-surface-variant hover:text-secondary font-semibold py-4 flex items-center gap-2 whitespace-nowrap transition-soft">
                <span class="material-symbols-outlined">edit_calendar</span> Ujian
            </button>
            <button onclick="switchTab('pengumuman', this)" class="tab-btn border-b-4 border-transparent text-on-surface-variant hover:text-secondary font-semibold py-4 flex items-center gap-2 whitespace-nowrap transition-soft">
                <span class="material-symbols-outlined">campaign</span> Pengumuman
            </button>
        </nav>
    </div>

    {{-- Content Area --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        {{-- Left: Tab Content (col-span-3) --}}
        <div class="xl:col-span-3 relative min-h-[500px]">
            
            {{-- TAB: SISWA --}}
            <div id="tab-siswa" class="tab-pane block animate-dropdown">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                        <div>
                        <h2 class="font-bold text-3xl md:text-4xl text-primary flex items-end gap-2" style="font-family: var(--font-serif)">
                            Daftar Siswa <span class="text-on-surface-variant text-lg font-sans pb-1">({{ $siswa->total() }})</span>
                        </h2>
                    </div>
                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        <div class="relative flex-1 sm:w-64">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                            <input type="text" id="searchInput" onkeyup="searchSiswa()" placeholder="Cari nama siswa..." class="w-full pl-10 pr-4 py-2 bg-transparent border border-outline-variant/50 rounded-lg text-sm focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary transition-soft text-on-surface">
                        </div>
                    </div>
                </div>

                <div class="bg-surface border border-outline-variant/30 rounded-xl overflow-hidden shadow-sm overflow-x-auto">
                    <table class="w-full text-left text-sm min-w-[600px]" id="siswaTable">
                    <thead class="bg-surface-container-low border-b border-outline-variant/30 text-xs text-on-surface-variant uppercase font-bold tracking-wider">
                        <tr>
                            <th class="py-4 px-4 w-12 text-center">NO</th>
                            <th class="py-4 px-4">PROFIL SISWA</th>
                            <th class="py-4 px-4">TGL BERGABUNG</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/30">
                        @forelse($siswa as $index => $s)
                        <tr class="hover:bg-surface-container-low/50 transition-soft">
                            <td class="py-3 px-4 text-center text-on-surface-variant">{{ $siswa->firstItem() + $index }}</td>
                            <td class="py-3 px-4 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-surface-variant overflow-hidden flex-shrink-0">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($s->name) }}&background=random" class="w-full h-full object-cover" alt="avatar">
                                </div>
                                <div>
                                    <p class="font-bold text-primary text-base">{{ $s->name }}</p>
                                    <p class="text-xs text-on-surface-variant">{{ $s->email }}</p>
                                </div>
                            </td>
                            <td class="py-3 px-4 text-on-surface-variant">{{ \Carbon\Carbon::parse($s->pivot->joined_at)->format('d M Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="py-8 text-center text-on-surface-variant">Belum ada siswa yang bergabung di kelas ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                @if($siswa->hasPages())
                <div class="px-4 py-3 border-t border-outline-variant/30">
                    {{ $siswa->links() }}
                </div>
                @endif
            </div>
            </div> {{-- End tab-siswa --}}

            {{-- TAB: MATERI --}}
            <div id="tab-materi" class="tab-pane hidden animate-dropdown">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="font-bold text-3xl md:text-4xl text-primary" style="font-family: var(--font-serif)">Materi Kelas</h2>
                    <a href="{{ route('guru.materi.tambah') }}" class="bg-secondary text-on-secondary px-4 py-2 rounded-lg font-bold text-sm flex items-center gap-2 hover:brightness-110 transition-soft">
                        <span class="material-symbols-outlined" style="font-size: 18px">add</span> Tambah Materi
                    </a>
                </div>
                <div class="flex flex-col gap-4">
                    @forelse($kelas->materi as $materi)
                    <div onclick="window.location.href='{{ route('guru.materi') }}'" class="bg-surface border border-outline-variant/30 rounded-xl p-4 flex justify-between items-center hover:bg-surface-container-low transition-soft cursor-pointer shadow-sm">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-lg bg-secondary-container/20 text-secondary flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-outlined">{{ $materi->link_video ? 'play_circle' : 'picture_as_pdf' }}</span>
                            </div>
                            <div>
                                <h3 class="font-bold text-primary text-sm md:text-base" style="font-family: var(--font-serif)">{{ $materi->judul }}</h3>
                                <p class="text-xs text-on-surface-variant">Diunggah {{ $materi->created_at ? $materi->created_at->format('d M Y') : '-' }}</p>
                            </div>
                        </div>
                        <form action="{{ route('guru.materi.destroy', $materi->id) }}" method="POST" class="inline" onclick="event.stopPropagation();" onsubmit="return confirm('Apakah Anda yakin ingin menghapus materi ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-8 h-8 flex items-center justify-center text-on-surface-variant hover:bg-error-container hover:text-error rounded-lg transition-soft" title="Hapus">
                                <span class="material-symbols-outlined">delete</span>
                            </button>
                        </form>
                    </div>
                    @empty
                    <div class="text-center py-8 text-on-surface-variant">
                        <span class="material-symbols-outlined text-4xl mb-2 opacity-50">folder_open</span>
                        <p>Belum ada materi untuk kelas ini.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- TAB: TUGAS --}}
            <div id="tab-tugas" class="tab-pane hidden animate-dropdown">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="font-bold text-3xl md:text-4xl text-primary" style="font-family: var(--font-serif)">Tugas Kelas</h2>
                    <a href="{{ route('guru.tugas.buat') }}" class="bg-secondary text-on-secondary px-4 py-2 rounded-lg font-bold text-sm flex items-center gap-2 hover:brightness-110 transition-soft">
                        <span class="material-symbols-outlined" style="font-size: 18px">add</span> Buat Tugas
                    </a>
                </div>
                <div class="flex flex-col gap-4">
                    @forelse($kelas->tugas as $tugas)
                    <div onclick="window.location.href='{{ route('guru.monitor.tugas') }}'" class="bg-surface border border-outline-variant/30 rounded-xl p-4 hover:bg-surface-container-low transition-soft cursor-pointer shadow-sm">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-lg bg-secondary-container/20 text-secondary flex items-center justify-center flex-shrink-0">
                                    <span class="material-symbols-outlined">assignment</span>
                                </div>
                                <div>
                                    <h3 class="font-bold text-primary text-sm md:text-base" style="font-family: var(--font-serif)">{{ $tugas->judul }}</h3>
                                    <p class="text-xs text-on-surface-variant">Tenggat: {{ $tugas->deadline ? $tugas->deadline->format('d M Y, H:i') : '-' }}</p>
                                </div>
                            </div>
                            <div class="flex gap-2 items-center">
                                <span class="bg-secondary-container/20 text-secondary text-xs font-bold px-2 py-1 rounded">Aktif</span>
                                <form action="{{ route('guru.tugas.destroy', $tugas->id) }}" method="POST" class="inline" onclick="event.stopPropagation();" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tugas ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 flex items-center justify-center text-on-surface-variant hover:bg-error-container hover:text-error rounded-lg transition-soft" title="Hapus">
                                        <span class="material-symbols-outlined">delete</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="flex justify-between items-center text-sm border-t border-outline-variant/30 pt-3">
                            <p class="text-on-surface-variant"><span class="font-bold text-primary">{{ $tugas->pengumpulan_count ?? 0 }}/{{ $kelas->siswa->count() }}</span> Mengumpulkan</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-on-surface-variant">
                        <span class="material-symbols-outlined text-4xl mb-2 opacity-50">assignment</span>
                        <p>Belum ada tugas untuk kelas ini.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- TAB: LAINNYA --}}
            <div id="tab-kuis" class="tab-pane hidden animate-dropdown">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="font-bold text-3xl md:text-4xl text-primary" style="font-family: var(--font-serif)">Kuis Kelas</h2>
                    <a href="{{ route('guru.kuis.buat') }}" class="bg-secondary text-on-secondary px-4 py-2 rounded-lg font-bold text-sm flex items-center gap-2 hover:brightness-110 transition-soft">
                        <span class="material-symbols-outlined" style="font-size: 18px">add</span> Buat Kuis
                    </a>
                </div>
                <div class="flex flex-col gap-4">
                    @forelse($kelas->kuis as $kuis)
                    <div onclick="window.location.href='{{ route('guru.kuis') }}'" class="bg-surface border border-outline-variant/30 rounded-xl p-4 hover:bg-surface-container-low transition-soft cursor-pointer shadow-sm">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-lg bg-primary-container/20 text-primary flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-outlined">quiz</span>
                            </div>
                            <div>
                                <h3 class="font-bold text-primary text-sm md:text-base" style="font-family: var(--font-serif)">{{ $kuis->judul }}</h3>
                                <p class="text-xs text-on-surface-variant">Durasi: {{ $kuis->durasi_menit }} Menit</p>
                            </div>
                        </div>
                        <form action="{{ route('guru.kuis.destroy', $kuis->id) }}" method="POST" class="inline" onclick="event.stopPropagation();" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kuis ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-8 h-8 flex items-center justify-center text-on-surface-variant hover:bg-error-container hover:text-error rounded-lg transition-soft" title="Hapus">
                                <span class="material-symbols-outlined">delete</span>
                            </button>
                        </form>
                    </div>
                    @empty
                    <div class="text-center py-8 text-on-surface-variant">
                        <span class="material-symbols-outlined text-4xl mb-2 opacity-50">quiz</span>
                        <p>Belum ada kuis untuk kelas ini.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <div id="tab-ujian" class="tab-pane hidden animate-dropdown">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="font-bold text-3xl md:text-4xl text-primary" style="font-family: var(--font-serif)">Ujian Kelas</h2>
                    <a href="{{ route('guru.ujian.buat') }}" class="bg-secondary text-on-secondary px-4 py-2 rounded-lg font-bold text-sm flex items-center gap-2 hover:brightness-110 transition-soft">
                        <span class="material-symbols-outlined" style="font-size: 18px">add</span> Buat Ujian
                    </a>
                </div>
                <div class="flex flex-col gap-4">
                    @forelse($kelas->ujian as $ujian)
                    <div onclick="window.location.href='{{ route('guru.ujian') }}'" class="bg-surface border border-outline-variant/30 rounded-xl p-4 hover:bg-surface-container-low transition-soft cursor-pointer shadow-sm">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-lg bg-error-container/20 text-error flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-outlined">edit_calendar</span>
                            </div>
                            <div>
                                <h3 class="font-bold text-primary text-sm md:text-base" style="font-family: var(--font-serif)">{{ $ujian->judul }}</h3>
                                <p class="text-xs text-on-surface-variant">Jadwal: {{ $ujian->mulai_at ? $ujian->mulai_at->format('d M Y, H:i') : '-' }}</p>
                            </div>
                        </div>
                        <form action="{{ route('guru.ujian.destroy', $ujian->id) }}" method="POST" class="inline" onclick="event.stopPropagation();" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ujian ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-8 h-8 flex items-center justify-center text-on-surface-variant hover:bg-error-container hover:text-error rounded-lg transition-soft" title="Hapus">
                                <span class="material-symbols-outlined">delete</span>
                            </button>
                        </form>
                    </div>
                    @empty
                    <div class="text-center py-8 text-on-surface-variant">
                        <span class="material-symbols-outlined text-4xl mb-2 opacity-50">edit_calendar</span>
                        <p>Belum ada ujian untuk kelas ini.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <div id="tab-pengumuman" class="tab-pane hidden animate-dropdown">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="font-bold text-3xl md:text-4xl text-primary" style="font-family: var(--font-serif)">Pengumuman Kelas</h2>
                    <button onclick="openModalBuatPengumuman()" class="bg-secondary text-on-secondary px-4 py-2 rounded-lg font-bold text-sm flex items-center gap-2 hover:brightness-110 transition-soft">
                        <span class="material-symbols-outlined" style="font-size: 18px">add</span> Buat Pengumuman
                    </button>
                </div>
                <div class="flex flex-col gap-4">
                    <div class="bg-surface border border-outline-variant/30 rounded-xl p-4 hover:bg-surface-container-low transition-soft cursor-pointer shadow-sm relative group">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-lg bg-secondary-container/20 text-secondary flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-outlined">campaign</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-bold text-primary text-sm md:text-base" id="pengumuman-title-1" style="font-family: var(--font-serif)">Jadwal Praktikum Tambahan</h3>
                                <p class="text-xs text-on-surface-variant">Diposting: 15 Sep 2023 oleh Bpk. Ahmad</p>
                            </div>
                            <div class="relative">
                                <button onclick="toggleDropdown('dropdown-1')" class="w-8 h-8 flex items-center justify-center text-on-surface-variant hover:bg-surface-variant rounded-lg transition-soft">
                                    <span class="material-symbols-outlined">more_vert</span>
                                </button>
                                <div id="dropdown-1" class="hidden absolute right-0 top-full mt-2 w-36 bg-surface border border-outline-variant/50 rounded-xl shadow-lg z-10 overflow-hidden">
                                    <button onclick="editPengumuman(1)" class="w-full px-4 py-2 text-left text-sm hover:bg-surface-variant text-on-surface flex items-center gap-2">
                                        <span class="material-symbols-outlined" style="font-size: 18px">edit</span> Edit
                                    </button>
                                    <button onclick="deletePengumuman(this)" class="w-full px-4 py-2 text-left text-sm hover:bg-error-container hover:text-error text-error flex items-center gap-2">
                                        <span class="material-symbols-outlined" style="font-size: 18px">delete</span> Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    </div>
</div>

<!-- Modal Buat Pengumuman -->
<div id="modalBuat" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm transition-opacity">
    <div class="bg-surface border border-outline-variant/30 rounded-2xl w-full max-w-lg shadow-xl overflow-hidden transform scale-95 transition-transform" id="modalBuatContent">
        <div class="p-5 border-b border-outline-variant/30 flex justify-between items-center bg-surface-container-low">
            <h3 class="font-bold text-xl text-primary" style="font-family: var(--font-serif)">Buat Pengumuman</h3>
            <button onclick="closeModalBuatPengumuman()" class="text-on-surface-variant hover:text-error transition-soft">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="p-5">
            <div id="pengumumanWarning" class="hidden mb-3 p-3 bg-error-container/20 border border-error/30 text-error text-sm rounded-lg flex items-center gap-2">
                <span class="material-symbols-outlined" style="font-size: 18px">warning</span>
                Pengumuman tidak boleh kosong!
            </div>
            <textarea id="pengumumanInput" class="w-full h-32 p-3 bg-surface-container-lowest border border-outline-variant/50 rounded-xl focus:ring-1 focus:ring-secondary focus:border-secondary text-sm resize-none text-on-surface outline-none" placeholder="Tulis informasi penting untuk siswa kelas ini..."></textarea>
            <div class="flex justify-end gap-3 mt-4">
                <button onclick="closeModalBuatPengumuman()" class="px-4 py-2 rounded-lg font-bold text-sm text-on-surface hover:bg-surface-variant transition-soft border border-outline-variant/50">
                    Batal
                </button>
                <button onclick="postPengumuman()" class="bg-primary text-on-primary px-4 py-2 rounded-lg font-bold text-sm hover:bg-primary/90 transition-soft">
                    Posting
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Pengumuman -->
<div id="modalEdit" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm transition-opacity">
    <div class="bg-surface border border-outline-variant/30 rounded-2xl w-full max-w-lg shadow-xl overflow-hidden transform scale-95 transition-transform" id="modalEditContent">
        <div class="p-5 border-b border-outline-variant/30 flex justify-between items-center bg-surface-container-low">
            <h3 class="font-bold text-xl text-primary" style="font-family: var(--font-serif)">Edit Pengumuman</h3>
            <button onclick="closeModalEdit()" class="text-on-surface-variant hover:text-error transition-soft">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="p-5">
            <textarea id="editPengumumanInput" class="w-full h-32 p-3 bg-surface-container-lowest border border-outline-variant/50 rounded-xl focus:ring-1 focus:ring-secondary focus:border-secondary text-sm resize-none text-on-surface outline-none"></textarea>
            <div class="flex justify-end gap-3 mt-4">
                <button onclick="closeModalEdit()" class="px-4 py-2 rounded-lg font-bold text-sm text-on-surface hover:bg-surface-variant transition-soft border border-outline-variant/50">
                    Batal
                </button>
                <button onclick="saveEditPengumuman()" class="bg-primary text-on-primary px-4 py-2 rounded-lg font-bold text-sm hover:bg-primary/90 transition-soft">
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Hapus Pengumuman -->
<div id="modalDelete" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm transition-opacity">
    <div class="bg-surface border border-outline-variant/30 rounded-2xl w-full max-w-md shadow-xl overflow-hidden transform scale-95 transition-transform" id="modalDeleteContent">
        <div class="p-6 text-center">
            <div class="w-16 h-16 rounded-full bg-error-container/20 text-error flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined" style="font-size: 32px">warning</span>
            </div>
            <h3 class="font-bold text-xl text-primary mb-2" style="font-family: var(--font-serif)">Hapus Pengumuman?</h3>
            <p class="text-on-surface-variant text-sm mb-6">Tindakan ini tidak dapat dibatalkan. Pengumuman akan dihapus secara permanen dari kelas ini.</p>
            <div class="flex justify-center gap-3">
                <button onclick="closeModalDelete()" class="px-4 py-2 rounded-lg font-bold text-sm text-on-surface hover:bg-surface-variant transition-soft border border-outline-variant/50">
                    Batal
                </button>
                <button onclick="confirmDeletePengumuman()" class="bg-error text-white px-4 py-2 rounded-lg font-bold text-sm hover:bg-error/90 transition-soft">
                    Hapus
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Toast Berhasil Disalin -->
<div id="copyToast" class="fixed bottom-6 right-6 translate-y-24 opacity-0 bg-[#c4eed0] border border-[#0f5223]/30 text-[#0f5223] px-5 py-3 rounded-xl shadow-lg flex items-center gap-2 transition-all duration-300 z-[100] pointer-events-none">
    <span class="material-symbols-outlined" style="font-size: 20px">check_circle</span>
    <span class="font-bold text-sm">Berhasil disalin!</span>
</div>

<!-- Toast Success (Popup Hijau) -->
<div id="toast-success" class="fixed top-5 left-1/2 -translate-x-1/2 z-[100] flex items-center gap-3 bg-green-100 border border-green-300 text-green-800 px-6 py-3 rounded-lg shadow-lg opacity-0 invisible transition-all duration-300 transform -translate-y-4">
    <span class="material-symbols-outlined">check_circle</span>
    <span class="font-bold text-sm" id="toast-success-text">Berhasil!</span>
</div>

@push('scripts')
<script>
    let currentEditId = null;
    let currentDeleteElement = null;
    let isEnrollmentActive = true;

    function showCopyToast() {
        const toast = document.getElementById("copyToast");
        toast.classList.remove("translate-y-24", "opacity-0");
        
        setTimeout(() => {
            toast.classList.add("translate-y-24", "opacity-0");
        }, 2500);
    }

    function showSuccessToast(message) {
        const toast = document.getElementById('toast-success');
        const toastText = document.getElementById('toast-success-text');
        
        toastText.innerText = message;
        toast.classList.remove('invisible', 'opacity-0', '-translate-y-4');
        toast.classList.add('opacity-100', 'translate-y-0');
        
        setTimeout(() => {
            toast.classList.add('invisible', 'opacity-0', '-translate-y-4');
            toast.classList.remove('opacity-100', 'translate-y-0');
        }, 2500);
    }

    function copyEnrollmentKey() {
        const keyText = document.getElementById("enrollmentKeyDisplay").innerText.trim();
        
        // Memakai fallback document.execCommand('copy') agar bisa berjalan di http (non-HTTPS) laragon local
        const textArea = document.createElement("textarea");
        textArea.value = keyText;
        textArea.style.position = "fixed";
        textArea.style.left = "-999999px";
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();

        try {
            document.execCommand('copy');
            showCopyToast();
        } catch (err) {
            console.error('Gagal menyalin teks', err);
            alert("Gagal menyalin otomatis, silakan salin manual: " + keyText);
        }

        document.body.removeChild(textArea);
    }

    function regenerateEnrollmentKey() {
        if(!isEnrollmentActive) {
            alert("Aktifkan Enrollment Key terlebih dahulu untuk meregenerate!");
            return;
        }
        
        // Simple random key generator for demo purposes
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        let randomSuffix = '';
        for (let i = 0; i < 2; i++) {
            randomSuffix += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        
        const newKey = `TKJ1-2024-${randomSuffix}`;
        document.getElementById("enrollmentKeyDisplay").innerText = newKey;
    }

    function toggleEnrollmentKey() {
        const statusSpan = document.getElementById("enrollmentStatus");
        const toggleBtn = document.getElementById("toggleEnrollmentBtn");
        const toggleIcon = document.getElementById("toggleEnrollmentIcon");
        const toggleText = document.getElementById("toggleEnrollmentText");
        const keyDisplay = document.getElementById("enrollmentKeyDisplay");

        isEnrollmentActive = !isEnrollmentActive;

        if (isEnrollmentActive) {
            statusSpan.innerText = "Aktif";
            statusSpan.className = "bg-secondary-container/30 text-secondary px-2 py-0.5 rounded text-[10px]";
            
            toggleBtn.className = "w-full sm:w-auto h-10 px-4 bg-error-container/20 border border-error/20 text-error rounded-xl font-bold text-sm flex items-center justify-center gap-2 hover:bg-error-container/40 transition-soft";
            toggleIcon.innerText = "block";
            toggleText.innerText = "Nonaktifkan";
            
            keyDisplay.classList.remove("text-on-surface-variant");
            keyDisplay.classList.add("text-primary");
            keyDisplay.style.opacity = "1";
        } else {
            statusSpan.innerText = "Nonaktif";
            statusSpan.className = "bg-error-container/30 text-error px-2 py-0.5 rounded text-[10px]";
            
            toggleBtn.className = "w-full sm:w-auto h-10 px-4 bg-secondary-container/20 border border-secondary/20 text-secondary rounded-xl font-bold text-sm flex items-center justify-center gap-2 hover:bg-secondary-container/40 transition-soft";
            toggleIcon.innerText = "check_circle";
            toggleText.innerText = "Aktifkan";

            keyDisplay.classList.remove("text-primary");
            keyDisplay.classList.add("text-on-surface-variant");
            keyDisplay.style.opacity = "0.5";
        }
    }

    function openModalBuatPengumuman() {
        const modal = document.getElementById('modalBuat');
        const modalContent = document.getElementById('modalBuatContent');
        const input = document.getElementById('pengumumanInput');
        const warning = document.getElementById('pengumumanWarning');
        
        input.value = '';
        warning.classList.add('hidden');
        input.classList.remove('border-error');
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            modalContent.classList.remove('scale-95');
            modalContent.classList.add('scale-100');
        }, 10);
    }

    function closeModalBuatPengumuman() {
        const modal = document.getElementById('modalBuat');
        const modalContent = document.getElementById('modalBuatContent');
        
        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 200);
    }

    function postPengumuman() {
        const input = document.getElementById("pengumumanInput");
        const warning = document.getElementById("pengumumanWarning");
        const val = input.value.trim();
        
        if (val === "") {
            warning.classList.remove("hidden");
            input.classList.add("border-error");
        } else {
            warning.classList.add("hidden");
            input.classList.remove("border-error");
            
            closeModalBuatPengumuman();
            
            setTimeout(() => {
                showSuccessToast("Pengumuman berhasil diposting!");
                input.value = "";
            }, 300);
        }
    }

    function toggleDropdown(id) {
        const dropdown = document.getElementById(id);
        dropdown.classList.toggle('hidden');
    }

    // Modal Edit Logic
    function editPengumuman(id) {
        currentEditId = id;
        const titleElement = document.getElementById('pengumuman-title-' + id);
        const currentText = titleElement.innerText;
        document.getElementById('editPengumumanInput').value = currentText;
        
        document.getElementById('dropdown-' + id).classList.add('hidden');
        
        const modal = document.getElementById('modalEdit');
        const content = document.getElementById('modalEditContent');
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95');
            content.classList.add('scale-100');
        }, 10);
    }

    function closeModalEdit() {
        const content = document.getElementById('modalEditContent');
        content.classList.remove('scale-100');
        content.classList.add('scale-95');
        setTimeout(() => {
            document.getElementById('modalEdit').classList.add('hidden');
        }, 150);
    }

    function saveEditPengumuman() {
        const newText = document.getElementById('editPengumumanInput').value.trim();
        if(newText !== "" && currentEditId !== null) {
            document.getElementById('pengumuman-title-' + currentEditId).innerText = newText;
        }
        closeModalEdit();
        
        setTimeout(() => {
            showSuccessToast("Pengumuman berhasil diperbarui!");
        }, 300);
    }

    // Modal Delete Logic
    function deletePengumuman(element) {
        currentDeleteElement = element.closest('.group');
        
        const modal = document.getElementById('modalDelete');
        const content = document.getElementById('modalDeleteContent');
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95');
            content.classList.add('scale-100');
        }, 10);
    }

    function closeModalDelete() {
        const content = document.getElementById('modalDeleteContent');
        content.classList.remove('scale-100');
        content.classList.add('scale-95');
        setTimeout(() => {
            document.getElementById('modalDelete').classList.add('hidden');
        }, 150);
    }

    function confirmDeletePengumuman() {
        if(currentDeleteElement) {
            currentDeleteElement.remove();
        }
        closeModalDelete();
    }

    // Close dropdowns if clicked outside
    document.addEventListener('click', function(event) {
        const dropdowns = document.querySelectorAll('[id^="dropdown-"]');
        dropdowns.forEach(dropdown => {
            if (!dropdown.contains(event.target) && !event.target.closest('button[onclick^="toggleDropdown"]')) {
                dropdown.classList.add('hidden');
            }
        });
    });

    function searchSiswa() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("searchInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("siswaTable");
        tr = table.getElementsByTagName("tr");

        // Start from 1 to skip table header
        for (i = 1; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[1]; // Second column for name
            if (td) {
                // Find element that has the student name
                let nameElement = td.querySelector("p.text-primary") || td; 
                txtValue = nameElement.textContent || nameElement.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }

    function switchTab(tabId, btnElement) {
        // Hide all tab panes
        document.querySelectorAll('.tab-pane').forEach(el => {
            el.classList.add('hidden');
            el.classList.remove('block');
        });
        
        // Show selected tab pane
        const selectedTab = document.getElementById('tab-' + tabId);
        if(selectedTab) {
            selectedTab.classList.remove('hidden');
            selectedTab.classList.add('block');
        }

        // Update button styles
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('border-secondary', 'text-secondary', 'font-bold');
            btn.classList.add('border-transparent', 'text-on-surface-variant', 'font-semibold');
        });

        // Highlight active button
        if(btnElement) {
            btnElement.classList.remove('border-transparent', 'text-on-surface-variant', 'font-semibold');
            btnElement.classList.add('border-secondary', 'text-secondary', 'font-bold');
        }
    }

    // Auto-select tab based on URL parameters
    document.addEventListener('DOMContentLoaded', () => {
        const urlParams = new URLSearchParams(window.location.search);
        const activeTab = urlParams.get('tab');
        if (activeTab) {
            const btn = document.querySelector(`[onclick="switchTab('${activeTab}', this)"]`);
            if (btn) switchTab(activeTab, btn);
        }
    });
</script>
@endpush
@endsection
