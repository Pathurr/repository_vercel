{{--
    Partial: Auth Left Panel
    Digunakan di: login, register, forgot-password, verify-email
    Props (via @include): $heading, $subheading
--}}
<div class="hidden md:flex md:w-2/5 flex-col relative overflow-hidden min-h-screen"
     style="background: linear-gradient(160deg, #3b1a06 0%, #50290b 40%, #6b3f1f 100%);">

    {{-- Decorative circles --}}
    <div class="absolute -top-24 -right-24 w-72 h-72 rounded-full opacity-10"
         style="background: #feae2c;"></div>
    <div class="absolute bottom-0 -left-16 w-56 h-56 rounded-full opacity-10"
         style="background: #feae2c;"></div>

    {{-- Logo (absolute top-left) --}}
    <div class="absolute top-0 left-0 z-10 p-8">
        <div class="flex items-center gap-3">
            <img src="/logo.png" alt="Logo SMK Mandalahayu" class="h-10 w-auto object-contain">
            <span class="font-bold text-white text-lg" style="font-family: 'Noto Serif', serif;">
                SMK Mandalahayu 1
            </span>
        </div>
    </div>

    {{-- Main Content (Heading & Badges) --}}
    <div class="relative z-10 flex flex-col justify-end h-full px-10 pb-16">

        {{-- Heading & Subheading (Berada tepat di atas badges) --}}
        <div class="mb-10">
            <h2 class="font-bold text-white leading-tight mb-4 text-3xl md:text-4xl"
                style="font-family: 'Noto Serif', serif;">
                {{ $heading ?? 'Mulai Perjalanan Akademik Anda' }}
            </h2>
            <p class="text-white/70 text-sm leading-relaxed pr-4">
                {{ $subheading ?? 'Bergabunglah dengan komunitas pembelajaran kami yang mengedepankan tradisi akademik dan kemajuan modern.' }}
            </p>
        </div>

        {{-- Badges (Di bagian paling bawah) --}}
        <div class="flex items-center gap-4 flex-wrap">
            <div class="flex items-center gap-2 text-white/70 text-xs font-semibold">
                <span class="material-symbols-outlined text-base" style="color:#feae2c;">verified</span>
                Akreditasi A
            </div>
            <div class="w-1.5 h-1.5 rounded-full bg-white/30"></div>
            <div class="flex items-center gap-2 text-white/70 text-xs font-semibold">
                <span class="material-symbols-outlined text-base" style="color:#feae2c;">star</span>
                Fasilitas Lengkap
            </div>
            <div class="w-1.5 h-1.5 rounded-full bg-white/30"></div>
            <div class="flex items-center gap-2 text-white/70 text-xs font-semibold">
                <span class="material-symbols-outlined text-base" style="color:#feae2c;">groups</span>
                1000+ Siswa
            </div>
        </div>
    </div>
</div>
