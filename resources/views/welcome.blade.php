<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>SMK Mandalahayu 1 Bekasi</title>
<meta name="description" content="SMK Mandalahayu 1 Bekasi - Membentuk generasi unggul, profesional, dan berkarakter dengan fasilitas modern dan kurikulum yang relevan dengan industri."/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600&family=Noto+Serif:wght@600;700&display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script id="tailwind-config">
    tailwind.config = {
        darkMode: "class",
        theme: {
            extend: {
                colors: {
                    "on-primary-fixed": "#311300",
                    "inverse-surface": "#32302c",
                    "on-tertiary": "#ffffff",
                    "error": "#ba1a1a",
                    "surface-container-lowest": "#ffffff",
                    "secondary-container": "#feae2c",
                    "background": "#fef9f3",
                    "tertiary-container": "#64421d",
                    "secondary": "#835500",
                    "surface": "#fef9f3",
                    "surface-dim": "#ded9d4",
                    "outline-variant": "#d6c3b8",
                    "surface-bright": "#fef9f3",
                    "error-container": "#ffdad6",
                    "tertiary-fixed": "#ffdcbd",
                    "surface-variant": "#e6e2dc",
                    "primary": "#50290b",
                    "surface-container-low": "#f8f3ed",
                    "on-secondary-fixed": "#291800",
                    "inverse-primary": "#f8b990",
                    "surface-tint": "#835331",
                    "surface-container": "#f2ede7",
                    "secondary-fixed": "#ffddb4",
                    "inverse-on-surface": "#f5f0ea",
                    "on-error": "#ffffff",
                    "on-surface": "#1d1b18",
                    "on-secondary-container": "#6b4500",
                    "tertiary": "#4a2c09",
                    "surface-container-highest": "#e6e2dc",
                    "surface-container-high": "#ece7e2",
                    "on-surface-variant": "#51443c",
                    "on-primary": "#ffffff",
                    "outline": "#84746b",
                    "primary-fixed-dim": "#f8b990",
                    "on-tertiary-container": "#dfb082",
                    "on-primary-container": "#eaac84",
                    "on-error-container": "#93000a",
                    "primary-fixed": "#ffdcc6",
                    "on-secondary": "#ffffff",
                    "primary-container": "#6b3f1f"
                },
                borderRadius: {
                    DEFAULT: "0.25rem",
                    lg: "0.5rem",
                    xl: "0.75rem",
                    full: "9999px"
                },
                spacing: {
                    "container-max": "1200px",
                    "section-gap": "80px",
                    "gutter": "24px",
                    "margin-mobile": "16px",
                    "base": "8px"
                },
                fontFamily: {
                    "sans": ["Manrope", "ui-sans-serif", "system-ui"],
                    "serif": ["Noto Serif", "serif"],
                    "h3": ["Noto Serif"],
                    "body-md": ["Manrope"],
                    "body-lg": ["Manrope"],
                    "h2": ["Noto Serif"],
                    "h1": ["Noto Serif"],
                    "label-sm": ["Manrope"]
                },
                fontSize: {
                    "h3": ["24px", { lineHeight: "1.4", fontWeight: "600" }],
                    "body-md": ["16px", { lineHeight: "1.6", fontWeight: "400" }],
                    "body-lg": ["18px", { lineHeight: "1.6", fontWeight: "400" }],
                    "h2": ["36px", { lineHeight: "1.3", fontWeight: "600" }],
                    "h1": ["48px", { lineHeight: "1.2", fontWeight: "700" }],
                    "label-sm": ["14px", { lineHeight: "1.2", letterSpacing: "0.05em", fontWeight: "600" }]
                }
            }
        }
    }
</script>
@include('layouts.partials.ui-system')
<style>
    .material-symbols-outlined {
        font-family: 'Material Symbols Outlined';
        font-weight: normal;
        font-style: normal;
        font-size: 24px;
        line-height: 1;
        letter-spacing: normal;
        text-transform: none;
        display: inline-block;
        white-space: nowrap;
        word-wrap: normal;
        direction: ltr;
        -webkit-font-feature-settings: 'liga';
        -webkit-font-smoothing: antialiased;
    }
    html { scroll-behavior: smooth; }
</style>
    <link rel="shortcut icon" href="/logo.png?v=3" type="image/x-icon">
    <link rel="icon" href="/logo.png?v=3" type="image/png">
</head>
<body class="bg-background text-on-background font-body-md text-body-md antialiased">

<!-- TopNavBar -->
<header class="bg-[#6B3F1F] fixed top-0 w-full z-50 shadow-lg">
    <div class="max-w-[1200px] mx-auto flex justify-between items-center px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex items-center gap-3">
            <img src="/logo.png" alt="Logo SMK Mandalahayu" class="h-12 sm:h-14 w-auto object-contain">
            <div class="text-lg sm:text-xl font-black text-white font-['Noto_Serif'] leading-tight">SMK Mandalahayu 1</div>
        </div>
        <nav class="hidden md:flex gap-6 items-center" id="main-nav">
            <a class="nav-link text-[#F5A623] border-b-2 border-[#F5A623] pb-1 font-['Noto_Serif'] font-bold text-sm tracking-wide transition-colors duration-300" href="#tentang">Tentang Kami</a>
            <a class="nav-link text-white/90 border-b-2 border-transparent pb-1 hover:text-[#F5A623] transition-colors duration-300 font-['Noto_Serif'] font-bold text-sm tracking-wide" href="#program">Program Keahlian</a>
            <a class="nav-link text-white/90 border-b-2 border-transparent pb-1 hover:text-[#F5A623] transition-colors duration-300 font-['Noto_Serif'] font-bold text-sm tracking-wide" href="#kegiatan">Kegiatan</a>
            <a class="nav-link text-white/90 border-b-2 border-transparent pb-1 hover:text-[#F5A623] transition-colors duration-300 font-['Noto_Serif'] font-bold text-sm tracking-wide" href="#kontak">Kontak</a>
        </nav>
        <div class="hidden md:flex items-center gap-4">
            <a href="{{ route('login') }}" class="text-[#F5A623] border-2 border-[#F5A623] px-4 py-2 rounded font-['Noto_Serif'] font-bold text-sm tracking-wide hover:bg-[#F5A623]/10 transition-colors">Masuk E-Learning</a>
            <a href="https://docs.google.com/forms/d/e/1FAIpQLSfmAHsIilHrKhDNi3vsA1ljb5L6AOV2li_SM7bsm01tFMolQA/viewform?usp=publish-editor" target="_blank" class="bg-[#F5A623] text-[#311300] px-4 py-2 rounded font-['Noto_Serif'] font-bold text-sm tracking-wide hover:bg-[#F5A623]/90 transition-colors">Daftar PPDB</a>
        </div>
        <button type="button" onclick="toggleWelcomeMenu()" class="md:hidden w-10 h-10 inline-flex items-center justify-center rounded-lg border border-[#F5A623]/50 text-[#F5A623] hover:bg-[#F5A623]/10 transition-colors" aria-label="Buka menu">
            <span class="material-symbols-outlined" id="welcome-menu-icon">menu</span>
        </button>
    </div>
    <div id="welcome-mobile-menu" class="hidden md:hidden border-t border-[#F5A623]/20 bg-[#6B3F1F] px-4 pb-4">
        <nav class="max-w-[1200px] mx-auto grid gap-2 pt-3">
            <a onclick="closeWelcomeMenu()" class="text-white/90 rounded-lg px-3 py-2 font-['Noto_Serif'] font-bold text-sm hover:bg-white/10 transition-colors" href="#tentang">Tentang Kami</a>
            <a onclick="closeWelcomeMenu()" class="text-white/90 rounded-lg px-3 py-2 font-['Noto_Serif'] font-bold text-sm hover:bg-white/10 transition-colors" href="#program">Program Keahlian</a>
            <a onclick="closeWelcomeMenu()" class="text-white/90 rounded-lg px-3 py-2 font-['Noto_Serif'] font-bold text-sm hover:bg-white/10 transition-colors" href="#kegiatan">Kegiatan</a>
            <a onclick="closeWelcomeMenu()" class="text-white/90 rounded-lg px-3 py-2 font-['Noto_Serif'] font-bold text-sm hover:bg-white/10 transition-colors" href="#kontak">Kontak</a>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-2">
                <a onclick="closeWelcomeMenu()" href="{{ route('login') }}" class="text-center text-[#F5A623] border border-[#F5A623] px-4 py-2 rounded font-['Noto_Serif'] font-bold text-sm hover:bg-[#F5A623]/10 transition-colors">Masuk E-Learning</a>
                <a onclick="closeWelcomeMenu()" href="https://docs.google.com/forms/d/e/1FAIpQLSfmAHsIilHrKhDNi3vsA1ljb5L6AOV2li_SM7bsm01tFMolQA/viewform?usp=publish-editor" target="_blank" class="text-center bg-[#F5A623] text-[#311300] px-4 py-2 rounded font-['Noto_Serif'] font-bold text-sm hover:bg-[#F5A623]/90 transition-colors">Daftar PPDB</a>
            </div>
        </nav>
    </div>
</header>

<!-- Hero Section -->
<section class="mt-[72px] relative bg-primary-container text-on-primary py-24 md:py-32 overflow-hidden flex flex-col items-center justify-center text-center px-gutter">
    <div class="absolute inset-0 z-0">
        <div class="w-full h-full bg-gradient-to-br from-primary-container to-tertiary-container opacity-90"></div>
    </div>
    <div class="relative z-10 max-w-4xl mx-auto space-y-8">
        <h1 class="font-h1 text-h1 text-on-primary">Selamat Datang di<br/>SMK Mandalahayu 1 Bekasi</h1>
        <p class="font-body-lg text-body-lg text-secondary-fixed max-w-2xl mx-auto">Membentuk generasi unggul, profesional, dan berkarakter dengan fasilitas modern dan kurikulum yang relevan dengan industri.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <a href="https://docs.google.com/forms/d/e/1FAIpQLSfmAHsIilHrKhDNi3vsA1ljb5L6AOV2li_SM7bsm01tFMolQA/viewform?usp=publish-editor" target="_blank" class="bg-secondary-container text-on-primary-fixed font-label-sm text-label-sm px-8 py-3 rounded-full hover:bg-secondary-fixed transition-colors shadow-sm">Daftar Sekarang</a>
            <a href="{{ route('login') }}" class="flex items-center gap-2 border-2 border-secondary-container text-secondary-container font-label-sm text-label-sm px-8 py-3 rounded-full hover:bg-secondary-container/10 transition-colors shadow-sm">
                <span class="material-symbols-outlined" style="font-size:18px">laptop_mac</span>
                Masuk E-Learning
            </a>
        </div>
    </div>
</section>

<!-- Announcement Bar -->
<div class="bg-secondary-container text-on-primary-fixed py-3 px-6 flex flex-col sm:flex-row justify-between items-center gap-4">
    <div class="font-label-sm text-label-sm font-bold">🎓 Tahun Ajaran Baru 2025/2026 — Pendaftaran PPDB Dibuka!</div>
    <a href="{{ route('login') }}" class="font-label-sm text-label-sm underline hover:opacity-80 transition-opacity">Masuk E-Learning →</a>
</div>

<!-- Main Content -->
<main class="max-w-[1200px] mx-auto px-6 py-20 space-y-20">

    <!-- About Section -->
    <section id="tentang" class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
        <div class="space-y-6">
            <h2 class="font-h2 text-h2 text-primary">Tradisi Akademik &amp; Kemajuan Modern</h2>
            <p class="font-body-md text-body-md text-on-surface-variant">SMK Mandalahayu 1 Bekasi berdedikasi untuk memberikan pendidikan vokasi berkualitas tinggi. Kami menggabungkan nilai-nilai lokal dengan standar industri global untuk mempersiapkan siswa menghadapi tantangan dunia kerja.</p>
            <div class="space-y-4">
                <h3 class="font-h3 text-h3 text-secondary">Visi &amp; Misi</h3>
                <ul class="space-y-2 list-disc list-inside font-body-md text-body-md text-on-surface-variant">
                    <li>Menjadi lembaga pendidikan unggulan di Bekasi.</li>
                    <li>Mengembangkan keterampilan praktis dan teori.</li>
                    <li>Membina karakter budi pekerti luhur.</li>
                </ul>
            </div>
        </div>
        <div class="rounded-xl overflow-hidden shadow-lg h-[400px] relative bg-surface-variant">
            <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuCN19bXWgx-aJyvo7KjXanBMgJT6BHb9f4Y_YPwQx_tiJ0RLmu9jBNz0vSGprFI-4sdUzTG0ZLFw75baENdlmDsxioSJb6_A9Q2VcdIVqH9eS2lTnSS1X7P6WajEvvze3yB7zOHXJ0izaLnFx-zWIEW3FFftUiDwkERNee74ly2U4UDx3jkCBltggGXOgmmKSrTbowSYuRY4s_csnH2OQ7d5iaYKeIRudiICWBvdrOSGGEZFbJV1hoor1DE1Y42nCKoAOGqIAujK_ZQ')"></div>
        </div>
    </section>

    <!-- Programs Section -->
    <section id="program" class="bg-surface-container-low p-8 md:p-12 rounded-xl border border-outline-variant/30 shadow-sm relative overflow-hidden">
        <div class="relative z-10">
            <div class="text-center mb-12">
                <h2 class="font-h2 text-h2 text-primary">Program Keahlian</h2>
                <p class="font-body-md text-body-md text-on-surface-variant mt-2">Pilihan jurusan yang dirancang untuk kebutuhan industri masa depan.</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Card 1 -->
                <div class="bg-surface p-6 rounded-lg border border-[#EFE6DE] shadow-sm hover:shadow-md hover:bg-surface-container transition-all flex flex-col items-center text-center group cursor-pointer">
                    <div class="w-16 h-16 rounded-full bg-secondary-container/20 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-secondary text-3xl">computer</span>
                    </div>
                    <h3 class="font-h3 text-h3 text-primary mb-2 text-lg">Teknik Komputer &amp; Jaringan</h3>
                    <p class="font-body-md text-body-md text-on-surface-variant text-sm">Infrastruktur jaringan dan perakitan komputer.</p>
                </div>
                <!-- Card 2 -->
                <div class="bg-surface p-6 rounded-lg border border-[#EFE6DE] shadow-sm hover:shadow-md hover:bg-surface-container transition-all flex flex-col items-center text-center group cursor-pointer">
                    <div class="w-16 h-16 rounded-full bg-secondary-container/20 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-secondary text-3xl">movie</span>
                    </div>
                    <h3 class="font-h3 text-h3 text-primary mb-2 text-lg">Multimedia</h3>
                    <p class="font-body-md text-body-md text-on-surface-variant text-sm">Desain grafis, animasi, dan produksi video.</p>
                </div>
                <!-- Card 3 -->
                <div class="bg-surface p-6 rounded-lg border border-[#EFE6DE] shadow-sm hover:shadow-md hover:bg-surface-container transition-all flex flex-col items-center text-center group cursor-pointer">
                    <div class="w-16 h-16 rounded-full bg-secondary-container/20 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-secondary text-3xl">calculate</span>
                    </div>
                    <h3 class="font-h3 text-h3 text-primary mb-2 text-lg">Akuntansi</h3>
                    <p class="font-body-md text-body-md text-on-surface-variant text-sm">Manajemen keuangan dan akuntansi bisnis.</p>
                </div>
                <!-- Card 4 -->
                <div class="bg-surface p-6 rounded-lg border border-[#EFE6DE] shadow-sm hover:shadow-md hover:bg-surface-container transition-all flex flex-col items-center text-center group cursor-pointer">
                    <div class="w-16 h-16 rounded-full bg-secondary-container/20 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-secondary text-3xl">store</span>
                    </div>
                    <h3 class="font-h3 text-h3 text-primary mb-2 text-lg">Bisnis Daring &amp; Pemasaran</h3>
                    <p class="font-body-md text-body-md text-on-surface-variant text-sm">Strategi pemasaran digital dan e-commerce.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Kegiatan Section -->
    <section id="kegiatan" class="space-y-8">
        <div class="text-center">
            <h2 class="font-h2 text-h2 text-primary">Kegiatan Sekolah</h2>
            <p class="font-body-md text-body-md text-on-surface-variant mt-2">Berbagai kegiatan untuk mengembangkan potensi siswa.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="bg-surface-container-low rounded-xl p-6 border border-outline-variant/30 shadow-sm hover:shadow-md transition-all">
                <span class="material-symbols-outlined text-secondary text-4xl mb-3 block">emoji_events</span>
                <h3 class="font-h3 text-h3 text-primary text-lg mb-2">Lomba & Kompetisi</h3>
                <p class="text-on-surface-variant text-sm">Siswa aktif mengikuti lomba tingkat kota, provinsi, hingga nasional di berbagai bidang keahlian.</p>
            </div>
            <div class="bg-surface-container-low rounded-xl p-6 border border-outline-variant/30 shadow-sm hover:shadow-md transition-all">
                <span class="material-symbols-outlined text-secondary text-4xl mb-3 block">business_center</span>
                <h3 class="font-h3 text-h3 text-primary text-lg mb-2">Prakerin / PKL</h3>
                <p class="text-on-surface-variant text-sm">Program magang di perusahaan mitra untuk pengalaman kerja nyata sesuai jurusan masing-masing.</p>
            </div>
            <div class="bg-surface-container-low rounded-xl p-6 border border-outline-variant/30 shadow-sm hover:shadow-md transition-all">
                <span class="material-symbols-outlined text-secondary text-4xl mb-3 block">groups</span>
                <h3 class="font-h3 text-h3 text-primary text-lg mb-2">Ekstrakurikuler</h3>
                <p class="text-on-surface-variant text-sm">OSIS, Pramuka, olahraga, seni, dan berbagai kegiatan pengembangan diri lainnya.</p>
            </div>
        </div>
    </section>

    <!-- PPDB Section -->
    <section id="ppdb" class="bg-[#6B3F1F] rounded-2xl p-10 md:p-16 text-center text-white shadow-xl">
        <h2 class="font-['Noto_Serif'] text-3xl md:text-4xl font-bold mb-4">Daftar PPDB 2025/2026</h2>
        <p class="text-white/80 max-w-xl mx-auto mb-8 font-['Manrope']">Bergabunglah bersama ribuan alumni sukses SMK Mandalahayu 1 Bekasi. Pendaftaran peserta didik baru kini dibuka!</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="https://docs.google.com/forms/d/e/1FAIpQLSfmAHsIilHrKhDNi3vsA1ljb5L6AOV2li_SM7bsm01tFMolQA/viewform?usp=publish-editor" target="_blank" class="bg-[#F5A623] text-[#311300] px-8 py-3 rounded-full font-bold font-['Noto_Serif'] hover:bg-[#F5A623]/90 transition-colors shadow">Daftar Sekarang</a>
            <a href="https://wa.me/6281234567890" target="_blank" class="border-2 border-white text-white px-8 py-3 rounded-full font-bold font-['Noto_Serif'] hover:bg-white/10 transition-colors">Hubungi Kami</a>
        </div>
    </section>

</main>

<script>
    function toggleWelcomeMenu() {
        const menu = document.getElementById('welcome-mobile-menu');
        const icon = document.getElementById('welcome-menu-icon');
        const isHidden = menu.classList.toggle('hidden');
        icon.textContent = isHidden ? 'menu' : 'close';
    }

    function closeWelcomeMenu() {
        const menu = document.getElementById('welcome-mobile-menu');
        const icon = document.getElementById('welcome-menu-icon');
        menu.classList.add('hidden');
        icon.textContent = 'menu';
    }
</script>

<!-- Footer -->
<footer id="kontak" class="bg-[#6B3F1F] w-full py-12 px-8 border-t border-[#F5A623]/20">
    <div class="max-w-[1200px] mx-auto flex flex-col md:flex-row justify-between items-start gap-10">
        <div class="space-y-4 max-w-xs">
            <div class="text-lg font-bold text-white font-['Noto_Serif']">SMK Mandalahayu 1</div>
            <p class="font-['Manrope'] text-sm leading-relaxed text-white/70">Membangun karakter, mengasah keterampilan, meraih masa depan cerah bersama SMK Mandalahayu 1 Bekasi.</p>
            <div class="text-white/50 font-['Manrope'] text-xs mt-4">© {{ date('Y') }} SMK Mandalahayu 1 Bekasi. All Rights Reserved.</div>
        </div>
        <div class="flex flex-col md:flex-row gap-12">
            <div class="flex flex-col gap-2">
                <h4 class="text-[#F5A623] font-semibold font-['Noto_Serif'] text-sm mb-2">Kontak Person</h4>
                <a href="https://wa.me/6281234567890" target="_blank" class="text-white/70 hover:text-white font-['Manrope'] text-sm flex items-center gap-2 transition-colors">
                    <span class="material-symbols-outlined text-[18px]">chat</span> Bapak Budi (Admin): 0812-3456-7890
                </a>
                <a href="https://wa.me/6289876543210" target="_blank" class="text-white/70 hover:text-white font-['Manrope'] text-sm flex items-center gap-2 transition-colors">
                    <span class="material-symbols-outlined text-[18px]">chat</span> Ibu Siti (Humas): 0898-7654-3210
                </a>
            </div>
            <div class="mt-0">
                <a href="{{ route('login') }}" class="text-[#F5A623] border border-[#F5A623] px-4 py-2 rounded text-sm hover:bg-[#F5A623]/10 transition-colors inline-block font-['Noto_Serif']">Masuk E-Learning</a>
            </div>
        </div>
    </div>
</footer>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const navLinks = document.querySelectorAll('#main-nav .nav-link');
        
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                // Remove active classes from all links
                navLinks.forEach(l => {
                    l.classList.remove('text-[#F5A623]', 'border-[#F5A623]');
                    l.classList.add('text-white/90', 'border-transparent');
                });
                
                // Add active classes to the clicked link
                this.classList.remove('text-white/90', 'border-transparent');
                this.classList.add('text-[#F5A623]', 'border-[#F5A623]');
            });
        });
    });
</script>
</body>
</html>
