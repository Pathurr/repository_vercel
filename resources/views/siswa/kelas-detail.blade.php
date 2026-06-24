@extends('layouts.siswa')
@section('title', 'Pemrograman Web - Portal Siswa SMK Mandalahayu 1')
@section('page-title', 'Mata Pelajaran')

@section('content')
<style>
.active-tab-indicator {
    height: 4px;
    width: 100%;
    background-color: #feae2c;
    position: absolute;
    bottom: 0;
    left: 0;
    border-radius: 4px 4px 0 0;
}
.batik-overlay {
    background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuD3cnn9KQW8AvbB_SLOab0Wa4tzABThbgbiGAIRbz8vYqoI05HFRDZcgv3USFXNjOC7MU6D2YRIZn-JncIoOIolm-pCA_EEkzr8ZlOQUXBLDZvApGbskAnB-51YHb2jU1deDpp2hfIBddb7IqKqL1Rm1RBiDVq4joArfF9wNQiKnpy63G9jpF8j4Gbp1TQzvLSgUVK7P85iTVLEDeXgb2DKdcX1Xc_pPwcygxAFCngWgjSEact4KY-1In3I0DuO2_axHqHSqh5-dUxB');
    opacity: 0.05;
}
</style>

<!-- Hero Section -->
<section class="relative bg-primary overflow-hidden py-12 px-8 -mx-4 md:-mx-6 -mt-8 mb-10 md:rounded-b-3xl shadow-md">
    <div class="absolute inset-0 batik-overlay"></div>
    <div class="relative z-10 max-w-container-max mx-auto flex flex-col md:flex-row items-end justify-between gap-8">
        <div class="flex-1">
            <div class="flex items-center gap-3 mb-4">
                <span class="px-3 py-1 bg-secondary-container text-on-secondary-container font-bold text-xs rounded-full">{{ $kelas->nama_kelas }}</span>
            </div>
            <h2 class="font-bold text-2xl text-on-primary mb-4" style="font-family: var(--font-serif)">{{ $kelas->mata_pelajaran }}</h2>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-secondary-container bg-surface-variant flex items-center justify-center">
                    <span class="material-symbols-outlined text-on-surface-variant text-[16px]">person</span>
                </div>
                <div>
                    <p class="font-bold text-[10px] text-secondary-container">Guru Pengampu</p>
                    <p class="font-bold text-sm text-on-primary">{{ $kelas->guru->name ?? 'Guru' }}</p>
                </div>
            </div>
        </div>
        <div class="hidden lg:block w-64 bg-on-primary/5 p-4 rounded-xl border border-on-primary/10 backdrop-blur-sm">
            <p class="text-on-primary/80 italic text-xs mb-3">"{{ $kelas->deskripsi ?? 'Mempelajari dasar-dasar mata pelajaran ini.' }}"</p>
            <div class="flex justify-between items-center text-on-primary">
                <div class="text-center">
                    <p class="font-bold text-lg">{{ $kelas->materi->count() }}</p>
                    <p class="text-[10px] opacity-60 font-bold">Modul</p>
                </div>
                <div class="text-center">
                    <p class="font-bold text-lg">{{ $kelas->tugas->count() }}</p>
                    <p class="text-[10px] opacity-60 font-bold">Tugas Aktif</p>
                </div>
                <div class="text-center">
                    <p class="font-bold text-lg">100%</p>
                    <p class="text-[10px] opacity-60 font-bold">Progres</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Content Tabs & Body -->
<section class="max-w-[1000px] mx-auto px-2 md:px-4">
    <!-- Tabs Navigation -->
    <div class="flex border-b border-outline-variant mb-6 relative overflow-x-auto">
        <button class="px-4 md:px-6 py-3 text-sm text-secondary font-bold relative transition-all duration-300 whitespace-nowrap" id="tab-materi" onclick="switchTab('materi')">
            Materi
            <div class="active-tab-indicator" id="indicator-materi"></div>
        </button>
        <button class="px-4 md:px-6 py-3 text-sm text-on-surface-variant hover:text-primary transition-all duration-300 relative whitespace-nowrap font-bold" id="tab-tugas" onclick="switchTab('tugas')">
            Tugas
            <div class="active-tab-indicator hidden" id="indicator-tugas"></div>
        </button>
        <button class="px-4 md:px-6 py-3 text-sm text-on-surface-variant hover:text-primary transition-all duration-300 relative whitespace-nowrap font-bold" id="tab-pengumuman" onclick="switchTab('pengumuman')">
            Pengumuman
            <div class="active-tab-indicator hidden" id="indicator-pengumuman"></div>
        </button>
    </div>

    <!-- Content Area -->
    <div class="w-full">
        <!-- Tab: Materi (Content) -->
        <div class="space-y-4 {{ $tab == 'materi' ? '' : 'hidden' }}" id="content-materi">
            <h3 class="font-bold text-lg text-primary mb-4" style="font-family: var(--font-serif)">Daftar Modul Pembelajaran</h3>
            
            @forelse($kelas->materi as $m)
            <a href="{{ route('siswa.lihat-materi', $m->id) }}" class="bg-surface-container-lowest p-4 rounded-xl shadow-sm border border-outline-variant/30 flex items-center gap-4 hover:shadow-md transition-all group block">
                <div class="w-10 h-10 rounded-lg bg-secondary/10 flex items-center justify-center text-secondary group-hover:scale-110 transition-transform flex-shrink-0">
                    <span class="material-symbols-outlined text-xl">{{ $m->link_video ? 'play_circle' : 'description' }}</span>
                </div>
                <div class="flex-1">
                    <h4 class="font-bold text-sm text-on-surface">{{ $m->judul }}</h4>
                    <p class="text-on-surface-variant text-[11px] flex flex-wrap items-center gap-2 mt-1 font-bold">
                        <span class="material-symbols-outlined text-[12px]">calendar_month</span> Diunggah: {{ \Carbon\Carbon::parse($m->created_at)->format('d M Y') }}
                    </p>
                </div>
                <button type="button" class="p-2 rounded-full bg-surface-container hover:bg-secondary-container transition-colors flex-shrink-0">
                    <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                </button>
            </a>
            @empty
            <div class="text-center py-8 bg-surface-container-lowest rounded-xl border border-outline-variant/30">
                <span class="material-symbols-outlined text-on-surface-variant text-4xl mb-2 opacity-50">folder_open</span>
                <p class="text-on-surface-variant text-sm">Belum ada materi untuk kelas ini.</p>
            </div>
            @endforelse
        </div>

        <!-- Tab: Tugas -->
        <div class="{{ $tab == 'tugas' ? '' : 'hidden' }} space-y-4" id="content-tugas">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4 border-b border-surface-container pb-4">
                <h3 class="font-bold text-lg text-primary" style="font-family: var(--font-serif)">Daftar Tugas</h3>
            </div>
            
            @forelse($kelas->tugas as $t)
            <div class="tugas-card bg-surface-container-lowest p-6 rounded-xl shadow-sm border border-outline-variant/30 hover:border-secondary transition-all">
                <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-4">
                    <div>
                        <div class="flex items-center gap-3 mb-3">
                            @if($t->pengumpulan->isEmpty())
                                <span class="px-2 py-0.5 bg-error/10 text-error font-bold text-[10px] rounded-full uppercase tracking-wider">BELUM DIKUMPULKAN</span>
                            @else
                                <span class="px-2 py-0.5 bg-green-100 text-green-800 font-bold text-[10px] rounded-full uppercase tracking-wider">SUDAH DIKUMPULKAN</span>
                            @endif
                        </div>
                        <h4 class="font-bold text-xl text-primary" style="font-family: var(--font-serif)">{{ $t->judul }}</h4>
                        <p class="text-sm text-on-surface-variant mt-2 leading-relaxed">{{ Str::limit($t->deskripsi, 150) }}</p>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row items-center justify-between pt-4 border-t border-outline-variant/20 gap-4 mt-2">
                    <div class="flex items-center gap-4 text-xs font-bold text-on-surface-variant w-full sm:w-auto">
                        <span class="flex items-center gap-1.5"><span class="material-symbols-outlined text-[16px]">calendar_today</span> Tenggat: {{ $t->deadline ? \Carbon\Carbon::parse($t->deadline)->format('d M Y, H:i') : 'Tanpa Tenggat' }}</span>
                    </div>
                    @if($t->pengumpulan->isEmpty())
                    <a href="{{ route('siswa.pengerjaan-tugas', $t->id) }}" class="w-full sm:w-auto bg-primary text-on-primary px-6 py-2 rounded-lg font-bold text-xs hover:bg-primary-container transition-colors text-center shadow-sm">Kerjakan Tugas</a>
                    @else
                    <a href="{{ route('siswa.pengerjaan-tugas', $t->id) }}" class="w-full sm:w-auto border-2 border-outline-variant text-secondary px-6 py-2 rounded-lg font-bold text-xs hover:bg-surface-container-low transition-colors text-center">Lihat Hasil</a>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center py-8 bg-surface-container-lowest rounded-xl border border-outline-variant/30">
                <span class="material-symbols-outlined text-on-surface-variant text-4xl mb-2 opacity-50">task</span>
                <p class="text-on-surface-variant text-sm">Belum ada tugas untuk kelas ini.</p>
            </div>
            @endforelse
        </div>

        <!-- Tab: Pengumuman (Hidden by default) -->
        <div class="hidden space-y-4" id="content-pengumuman">
            <h3 class="font-bold text-lg text-primary mb-4" style="font-family: var(--font-serif)">Pengumuman Terkini</h3>
            
            <div class="group flex items-start gap-3 p-3 rounded-lg bg-surface-container-lowest border border-outline-variant/50 shadow-sm hover:shadow-md transition-all relative" id="pengumuman-1">
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-secondary rounded-l-lg"></div>
                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-secondary-container/20 flex items-center justify-center text-on-secondary-container border border-secondary-container">
                    <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">campaign</span>
                </div>
                <div class="flex-1 min-w-0 pr-6">
                    <div class="flex justify-between items-start mb-0.5">
                        <h3 class="text-xs text-primary font-bold truncate" style="font-family: var(--font-serif)">Bpk. Budi Santoso</h3>
                        <span class="badge-new text-[9px] text-secondary font-bold flex items-center gap-1 uppercase tracking-wider bg-secondary-fixed/20 px-1.5 py-0.5 rounded border border-secondary/20 whitespace-nowrap">
                            <span class="w-1.5 h-1.5 rounded-full bg-secondary inline-block"></span> Baru
                        </span>
                    </div>
                    <p class="text-[11px] text-on-surface leading-snug mb-1.5">Halo semuanya, untuk pertemuan minggu depan kita akan mengadakan sesi live coding di Laboratorium Komputer 2. Harap pastikan laptop masing-masing sudah terpasang <strong>Visual Studio Code</strong> dan ekstensi <strong>Live Server</strong>. Jangan lupa kerjakan tugas portofolio tepat waktu!</p>
                    <p class="text-[9px] text-on-surface-variant font-bold flex items-center gap-1">
                        <span class="material-symbols-outlined text-[12px]">schedule</span> 2 hari yang lalu
                    </p>
                </div>
                
                <div class="absolute top-2 right-2">
                    <button onclick="toggleMenuPengumuman(1)" class="p-1 rounded-full text-on-surface-variant hover:bg-surface-container hover:text-primary transition-colors focus:outline-none">
                        <span class="material-symbols-outlined text-[16px]">more_vert</span>
                    </button>
                    <div id="menu-pengumuman-1" class="hidden absolute right-0 top-full mt-1 w-28 bg-surface-container-lowest border border-outline-variant/30 shadow-lg rounded-lg py-1 z-10">
                        <button onclick="markReadPengumuman(1)" class="w-full text-left px-3 py-1.5 text-[10px] font-bold text-on-surface hover:bg-surface-container transition-colors">Tandai Dibaca</button>
                    </div>
                </div>
            </div>
            
            <div class="group flex items-start gap-3 p-3 rounded-lg bg-surface-container border border-outline-variant/30 opacity-80 hover:opacity-100 transition-all relative" id="pengumuman-2">
                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-surface-variant flex items-center justify-center text-on-surface-variant border border-outline-variant/50">
                    <span class="material-symbols-outlined text-[16px]">campaign</span>
                </div>
                <div class="flex-1 min-w-0 pr-6">
                    <div class="flex justify-between items-start mb-0.5">
                        <h3 class="text-xs text-on-surface font-bold truncate" style="font-family: var(--font-serif)">Bpk. Budi Santoso</h3>
                    </div>
                    <p class="text-[11px] text-on-surface-variant leading-snug mb-1.5">Daftar nilai Kuis 1 sudah saya unggah. Bagi siswa yang nilainya di bawah KKM (75), silakan hubungi saya untuk jadwal remedial di hari Jumat pukul 14.00.</p>
                    <p class="text-[9px] text-on-surface-variant font-bold flex items-center gap-1">
                        <span class="material-symbols-outlined text-[12px]">schedule</span> 1 minggu yang lalu
                    </p>
                </div>
                
                <div class="absolute top-2 right-2">
                    <button onclick="toggleMenuPengumuman(2)" class="p-1 rounded-full text-on-surface-variant hover:bg-surface-container hover:text-primary transition-colors focus:outline-none">
                        <span class="material-symbols-outlined text-[16px]">more_vert</span>
                    </button>
                    <div id="menu-pengumuman-2" class="hidden absolute right-0 top-full mt-1 w-28 bg-surface-container-lowest border border-outline-variant/30 shadow-lg rounded-lg py-1 z-10">
                        <button onclick="markReadPengumuman(2)" class="w-full text-left px-3 py-1.5 text-[10px] font-bold text-on-surface hover:bg-surface-container transition-colors">Tandai Dibaca</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    function switchTab(tabName) {
        const tabs = ['materi', 'tugas', 'pengumuman'];
        
        tabs.forEach(t => {
            const content = document.getElementById(`content-${t}`);
            const btn = document.getElementById(`tab-${t}`);
            const indicator = document.getElementById(`indicator-${t}`);
            
            if (t === tabName) {
                content.classList.remove('hidden');
                btn.classList.add('text-secondary', 'font-bold');
                btn.classList.remove('text-on-surface-variant');
                indicator.classList.remove('hidden');
            } else {
                content.classList.add('hidden');
                btn.classList.remove('text-secondary', 'font-bold');
                btn.classList.add('text-on-surface-variant');
                indicator.classList.add('hidden');
            }
        });
    }
</script>
<script>
    function filterTugas() {
        const filterVal = document.getElementById('filter-tugas').value;
        const cards = document.querySelectorAll('.tugas-card');
        
        cards.forEach(card => {
            if(filterVal === 'semua' || card.getAttribute('data-status') === filterVal) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    function toggleDropdownUrutkan() {
        document.getElementById('dropdownUrutkan').classList.toggle('hidden');
    }

    function selectDropdownUrutkan(value, label) {
        document.getElementById('filter-tugas').value = value;
        document.getElementById('filterTugasLabel').textContent = label;
        document.getElementById('dropdownUrutkan').classList.add('hidden');
        filterTugas();
    }

    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('dropdownUrutkan');
        const btn = document.getElementById('filterTugasLabel');
        if (dropdown && btn && !btn.parentElement.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.classList.add('hidden');
        }
        
        // Tutup menu pengumuman jika diklik di luar
        for (let i = 1; i <= 2; i++) {
            const menu = document.getElementById('menu-pengumuman-' + i);
            if (menu && !menu.contains(event.target) && !event.target.closest(`button[onclick="toggleMenuPengumuman(${i})"]`)) {
                menu.classList.add('hidden');
            }
        }
    });

    function toggleMenuPengumuman(id) {
        const menu = document.getElementById('menu-pengumuman-' + id);
        if (menu.classList.contains('hidden')) {
            // Tutup semua menu lain dulu
            for (let i = 1; i <= 2; i++) {
                const otherMenu = document.getElementById('menu-pengumuman-' + i);
                if (otherMenu) otherMenu.classList.add('hidden');
            }
            menu.classList.remove('hidden');
        } else {
            menu.classList.add('hidden');
        }
    }

    function markReadPengumuman(id) {
        // Implementasi nyata: panggil API untuk tandai dibaca
        document.getElementById('menu-pengumuman-' + id).classList.add('hidden');
        const card = document.getElementById('pengumuman-' + id);
        
        // Hapus styling "baru"
        const badge = card.querySelector('.badge-new');
        if (badge) badge.remove();
        
        const accent = card.querySelector('.w-1.bg-secondary');
        if (accent) accent.remove();
        
        card.classList.remove('bg-surface-container-lowest', 'shadow-sm');
        card.classList.add('bg-surface-container', 'opacity-80');
        
        const iconContainer = card.querySelector('.flex-shrink-0');
        if (iconContainer) {
            iconContainer.className = 'flex-shrink-0 w-8 h-8 rounded-full bg-surface-variant flex items-center justify-center text-on-surface-variant border border-outline-variant/50';
            iconContainer.innerHTML = '<span class="material-symbols-outlined text-[16px]">campaign</span>';
        }
    }
</script>
@endpush
