<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>@yield('title', 'Portal Siswa - SMK Mandalahayu 1 Bekasi')</title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600&family=Noto+Serif:wght@600;700&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        "background": "#fef9f3", "surface": "#fef9f3",
                        "surface-container": "#f2ede7", "surface-container-low": "#f8f3ed",
                        "surface-container-high": "#ece7e2", "surface-container-lowest": "#ffffff",
                        "surface-variant": "#e6e2dc", "surface-dim": "#ded9d4",
                        "on-surface": "#1d1b18", "on-surface-variant": "#51443c",
                        "primary": "#50290b", "primary-container": "#6b3f1f", "primary-fixed": "#ffdcc6",
                        "on-primary": "#ffffff", "on-primary-container": "#eaac84",
                        "secondary": "#835500", "secondary-container": "#feae2c",
                        "secondary-fixed": "#ffddb4", "on-secondary": "#ffffff",
                        "on-secondary-container": "#6b4500", "on-secondary-fixed": "#291800",
                        "tertiary": "#4a2c09", "tertiary-container": "#64421d",
                        "outline": "#84746b", "outline-variant": "#d6c3b8",
                        "error": "#ba1a1a",
                    },
                    fontFamily: { sans: ["Manrope", "ui-sans-serif", "system-ui"] }
                }
            }
        }
    </script>
    <style>
        .material-symbols-outlined { font-family: 'Material Symbols Outlined'; font-weight: normal; font-style: normal; font-size: 24px; line-height: 1; letter-spacing: normal; text-transform: none; display: inline-block; white-space: nowrap; direction: ltr; -webkit-font-smoothing: antialiased; }
        .transition-soft { transition: all 0.2s ease; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #d6c3b8; border-radius: 2px; }

        @keyframes dropdownFade {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-dropdown { animation: dropdownFade 0.2s ease-out forwards; }

        /* Sidebar transition */
        #siswa-sidebar { transition: width 0.25s cubic-bezier(.4,0,.2,1); }
        #siswa-content { transition: margin-left 0.25s cubic-bezier(.4,0,.2,1); }
        .sidebar-label { transition: opacity 0.15s ease, width 0.25s ease; }

        /* Collapsed state */
        body.sidebar-collapsed #siswa-sidebar { width: 72px; }
        body.sidebar-collapsed #siswa-content { margin-left: 72px; }
        body.sidebar-collapsed .sidebar-label { opacity: 0; width: 0; overflow: hidden; white-space: nowrap; }
        body.sidebar-collapsed .sidebar-logo-text { opacity: 0; width: 0; overflow: hidden; }
        body.sidebar-collapsed .sidebar-logout { display: none; }
        body.sidebar-collapsed #siswa-sidebar nav a { justify-content: center; padding-left: 0; padding-right: 0; margin-left: 8px; margin-right: 8px; }
        body.sidebar-collapsed .sidebar-logo { justify-content: center; padding: 16px 8px; }

        /* Toggle button */
        #sidebar-toggle-btn {
            position: fixed;
            top: 50%;
            transform: translateY(-50%);
            left: calc(256px - 14px);
            z-index: 60;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #835500;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0,0,0,0.18);
            border: 2px solid #fef9f3;
            transition: left 0.25s cubic-bezier(.4,0,.2,1), background 0.15s;
        }
        #sidebar-toggle-btn:hover { background: #50290b; }
        #sidebar-toggle-btn .toggle-icon {
            font-size: 18px;
            transition: transform 0.25s ease;
        }
        body.sidebar-collapsed #sidebar-toggle-btn { left: calc(72px - 14px); }
        body.sidebar-collapsed #sidebar-toggle-btn .toggle-icon { transform: rotate(180deg); }

        .sidebar-mobile-backdrop { display: none; }
        @media (max-width: 767px) {
            #siswa-sidebar {
                display: flex;
                width: 280px;
                transform: translateX(-100%);
                transition: transform 0.25s ease;
                z-index: 70;
            }
            body.sidebar-mobile-open #siswa-sidebar { transform: translateX(0); }
            body.sidebar-mobile-open .sidebar-mobile-backdrop { display: block; }
            #siswa-content { margin-left: 0 !important; }
            #sidebar-toggle-btn { display: none; }
        }
    </style>
</head>
<body class="bg-surface text-on-surface font-sans antialiased min-h-screen">

    {{-- Sidebar Siswa --}}
    <aside id="siswa-sidebar" class="flex bg-primary text-on-primary fixed left-0 top-0 h-full w-64 flex-col z-50 custom-scrollbar overflow-y-auto">
        <div class="sidebar-logo p-6 flex items-center gap-3">
            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-on-primary/10 flex items-center justify-center">
                <span class="material-symbols-outlined text-secondary-fixed text-2xl">school</span>
            </div>
            <div class="sidebar-logo-text overflow-hidden">
                <h1 class="font-bold text-xl text-secondary-fixed whitespace-nowrap" style="font-family: var(--font-serif)">SMK Mandalahayu 1</h1>
                <p class="text-on-primary/70 text-xs whitespace-nowrap">Portal Siswa</p>
            </div>
        </div>
        <nav class="flex flex-col gap-1.5 flex-grow mt-4 px-0">
            <a href="{{ route('siswa.dashboard') }}" title="Dashboard"
               class="{{ request()->routeIs('siswa.dashboard') ? 'bg-secondary-fixed/10 text-secondary-fixed font-bold border-r-4 border-secondary' : 'text-on-primary/70 hover:bg-on-primary/10' }} flex items-center gap-3 px-4 py-2.5 rounded transition-soft text-sm">
                <span class="material-symbols-outlined flex-shrink-0 text-[22px]" style="{{ request()->routeIs('siswa.dashboard') ? 'font-variation-settings:\'FILL\' 1;' : '' }}">dashboard</span>
                <span class="sidebar-label">Dashboard</span>
            </a>
            <a href="{{ route('siswa.mapel') }}" title="Mata Pelajaran"
               class="{{ request()->routeIs('siswa.mapel*') ? 'bg-secondary-fixed/10 text-secondary-fixed font-bold border-r-4 border-secondary' : 'text-on-primary/70 hover:bg-on-primary/10' }} flex items-center gap-3 px-4 py-2.5 rounded transition-soft text-sm">
                <span class="material-symbols-outlined flex-shrink-0 text-[22px]" style="{{ request()->routeIs('siswa.mapel*') ? 'font-variation-settings:\'FILL\' 1;' : '' }}">menu_book</span>
                <span class="sidebar-label">Mata Pelajaran</span>
            </a>

            <a href="{{ route('siswa.nilai') }}" title="Nilai"
               class="{{ request()->routeIs('siswa.nilai*') ? 'bg-secondary-fixed/10 text-secondary-fixed font-bold border-r-4 border-secondary' : 'text-on-primary/70 hover:bg-on-primary/10' }} flex items-center gap-3 px-4 py-2.5 rounded transition-soft text-sm">
                <span class="material-symbols-outlined flex-shrink-0 text-[22px]" style="{{ request()->routeIs('siswa.nilai*') ? 'font-variation-settings:\'FILL\' 1;' : '' }}">assessment</span>
                <span class="sidebar-label">Nilai</span>
            </a>
        </nav>
        <div class="sidebar-logout mt-auto p-4 border-t border-on-primary/10">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full bg-secondary-container hover:bg-secondary text-on-secondary-container hover:text-white font-bold py-3 rounded transition-soft flex items-center justify-center gap-2 text-sm">
                    <span class="material-symbols-outlined text-[20px]">logout</span>
                    Keluar
                </button>
            </form>
        </div>
    </aside>
    <button type="button" class="sidebar-mobile-backdrop fixed inset-0 bg-black/45 z-[60] md:hidden" onclick="closeMobileSidebar()" aria-label="Tutup menu"></button>

    {{-- Floating Sidebar Toggle Button --}}
    <button id="sidebar-toggle-btn" onclick="toggleSidebar()" title="Sembunyikan / Tampilkan Sidebar" class="hidden md:flex">
        <span class="material-symbols-outlined toggle-icon">chevron_left</span>
    </button>

    {{-- Main Content Wrapper --}}
    <div id="siswa-content" class="flex-1 flex flex-col md:ml-64 overflow-hidden min-h-screen">

        {{-- Top Navbar --}}
        <header class="bg-primary text-on-primary sticky top-0 w-full z-40 border-b border-primary-container flex justify-between items-center px-6 py-2">
            {{-- Mobile menu button --}}
            <button type="button" onclick="openMobileSidebar()" class="md:hidden text-on-primary p-2 -ml-2">
                <span class="material-symbols-outlined">menu</span>
            </button>
            {{-- Page Title --}}
            <div class="hidden md:block font-bold text-xl text-on-primary" style="font-family: var(--font-serif)">
                @yield('page-title', 'Dashboard')
            </div>
            <div class="md:hidden font-bold text-lg text-on-primary">SMK MH 1</div>

            {{-- Right: User --}}
            <div class="flex items-center">
                {{-- User Dropdown --}}
                <div class="relative" id="user-menu-wrapper">
                    <button id="user-menu-btn" onclick="toggleUserMenu()" class="flex items-center gap-3 text-left cursor-pointer transition-soft hover:bg-primary-container/50 rounded-lg py-1 px-2">
                        <div class="text-right hidden lg:block">
                            <p class="text-xs font-bold text-on-primary">{{ Auth::user()->name ?? 'Budi Pratama' }}</p>
                            <p class="text-[10px] text-on-primary/70 uppercase">Siswa</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-primary-fixed flex items-center justify-center text-primary font-bold">
                            {{ strtoupper(substr(Auth::user()->name ?? 'S', 0, 1)) }}
                        </div>
                    </button>

                    {{-- Dropdown Menu --}}
                    <div id="user-dropdown"
                        class="hidden absolute left-0 right-0 top-full mt-0 bg-primary z-50 border-b border-primary-container rounded-b-lg animate-dropdown">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center justify-center gap-2 py-2 text-sm text-on-primary hover:bg-on-primary/10 transition-soft font-bold">
                                <span class="material-symbols-outlined" style="font-size:18px">logout</span>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        {{-- Page Content --}}
        <main class="flex-1 px-4 md:px-6 py-8 max-w-[1200px] mx-auto w-full">
            @yield('content')
        </main>

        {{-- Footer --}}
        <footer class="bg-primary py-3 border-t border-primary-container text-center text-on-primary/60 text-xs uppercase tracking-wider mt-auto w-full">
            <p>© {{ date('Y') }} SMK Mandalahayu 1. Seluruh Hak Cipta Dilindungi.</p>
        </footer>
    </div>

    @stack('scripts')
    <script>
        // ── Sidebar Toggle ──────────────────────────────
        function toggleSidebar() {
            if (window.innerWidth < 768) {
                openMobileSidebar();
                return;
            }
            const isCollapsed = document.body.classList.toggle('sidebar-collapsed');
            localStorage.setItem('siswa_sidebar_collapsed', isCollapsed ? '1' : '0');
        }
        function openMobileSidebar() {
            document.body.classList.add('sidebar-mobile-open');
        }
        function closeMobileSidebar() {
            document.body.classList.remove('sidebar-mobile-open');
        }
        // Restore state on page load
        if (window.innerWidth >= 768 && localStorage.getItem('siswa_sidebar_collapsed') === '1') {
            document.body.classList.add('sidebar-collapsed');
        }

        // ── User Dropdown ──────────────────────────────
        function toggleUserMenu() {
            const dropdown = document.getElementById('user-dropdown');
            dropdown.classList.toggle('hidden');
        }
        document.addEventListener('click', function(e) {
            const wrapper = document.getElementById('user-menu-wrapper');
            if (wrapper && !wrapper.contains(e.target)) {
                document.getElementById('user-dropdown').classList.add('hidden');
            }
        });
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 768) closeMobileSidebar();
        });
    </script>
</body>
</html>
