@extends('layouts.auth')

@section('title', 'SMK Mandalahayu 1 Bekasi - Login LMS')

@section('content')
<div class="w-full min-h-screen md:h-screen flex flex-col md:flex-row overflow-hidden">
    @include('auth.partials.left-panel', [
        'heading'    => 'Selamat Datang Kembali',
        'subheading' => 'Masuk ke portal e-learning SMK Mandalahayu 1 Bekasi dan lanjutkan perjalanan belajar Anda hari ini.',
    ])

    {{-- Right Side: Login Form --}}
    <div class="w-full md:w-3/5 bg-surface-container-lowest flex items-center justify-center md:justify-start min-h-screen md:h-screen overflow-y-auto p-6 md:p-8 md:pl-10 lg:pl-14">
        <div class="w-full max-w-md">
            {{-- Header --}}
            <div class="mb-8 text-center md:text-left">
                <h1 class="font-bold text-4xl text-primary mb-2" style="font-family: var(--font-serif)">LMS Portal</h1>
                <p class="text-on-surface-variant">SMK Mandalahayu 1 Bekasi</p>
            </div>

            {{-- Session Success --}}
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-100 text-green-800 border border-green-300 rounded-lg text-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-[20px]">check_circle</span>
                    {{ session('success') }}
                </div>
            @endif
            
            {{-- Session Errors --}}
            @if ($errors->any())
                <div class="mb-6 p-4 bg-error-container text-on-error-container rounded-lg text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form --}}
            <form class="space-y-4" method="POST" action="{{ route('login') }}">
                @csrf
                {{-- Email --}}
                <div>
                    <label class="block text-sm font-semibold text-primary mb-2" for="email">Email</label>
                    <div class="relative">
                        <input class="w-full bg-surface-container-low border-0 border-b-2 border-primary/20 text-on-surface focus:ring-0 focus:border-secondary-container transition-soft py-2.5 px-3.5 text-sm"
                               id="email" name="email" placeholder="Masukkan email atau username"
                               type="email" value="{{ old('email') }}" autocomplete="email" required/>
                    </div>
                </div>

                {{-- Password --}}
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-sm font-semibold text-primary" for="password">Password</label>
                        <a class="text-sm font-semibold text-secondary-container hover:underline decoration-secondary-container underline-offset-4"
                           href="{{ route('password.request') }}">Lupa Password?</a>
                    </div>
                    <div class="relative">
                        <input class="w-full bg-surface-container-low border-0 border-b-2 border-primary/20 text-on-surface focus:ring-0 focus:border-secondary-container transition-soft py-2.5 px-3.5 pr-12 text-sm"
                               id="password" name="password" placeholder="Masukkan password"
                               type="password" autocomplete="current-password" required minlength="8"/>
                        <button class="absolute inset-y-0 right-0 px-3 flex items-center text-on-surface-variant hover:text-primary transition-soft"
                                type="button" onclick="togglePassword()">
                            <span class="material-symbols-outlined text-[20px]" id="eye-icon">visibility</span>
                        </button>
                    </div>
                </div>

                {{-- Mock CAPTCHA UI (Hidden by default, shown after failed attempts) --}}
                <div id="captcha-container" class="hidden">
                    <label class="block text-sm font-semibold text-primary mb-2">Verifikasi Keamanan</label>
                    <div class="flex items-center gap-4 bg-surface-container-low p-3 rounded-lg border border-primary/20">
                        <div class="bg-surface-container-high px-4 py-2 font-mono text-lg font-bold tracking-widest select-none rounded decoration-line-through text-on-surface">
                            7W3XY
                        </div>
                        <input type="text" id="captcha" name="captcha" placeholder="Masukkan kode di samping" class="w-full bg-transparent border-0 border-b-2 border-primary/20 text-on-surface focus:ring-0 focus:border-secondary-container transition-soft py-1 px-2 text-sm">
                    </div>
                    <p class="text-xs text-error mt-1 hidden" id="captcha-error">Kode verifikasi salah.</p>
                </div>

                {{-- Submit --}}
                <div class="pt-2">
                    <button class="w-full bg-secondary-container text-on-secondary-container font-bold py-4 px-6 rounded hover:bg-secondary-fixed transition-soft flex justify-center items-center gap-2"
                            type="submit">
                        Masuk
                        <span class="material-symbols-outlined">arrow_forward</span>
                    </button>
                </div>
            </form>

            {{-- Footer Links --}}
            <div class="mt-8 text-center md:text-left">
                <p class="text-on-surface-variant">
                    Belum punya akun?
                    <a class="text-primary font-semibold hover:underline decoration-primary underline-offset-4"
                       href="{{ route('register') }}">Daftar</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function togglePassword() {
    const input = document.getElementById('password');
    const icon = document.getElementById('eye-icon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.textContent = 'visibility_off';
    } else {
        input.type = 'password';
        icon.textContent = 'visibility';
    }
}

// Simulasi logika CAPTCHA untuk frontend
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    // Jika ada session error (misalnya dari backend)
    const hasErrors = {{ $errors->any() ? 'true' : 'false' }};
    let failedAttempts = localStorage.getItem('failed_login_attempts') || 0;
    
    if (hasErrors) {
        failedAttempts++;
        localStorage.setItem('failed_login_attempts', failedAttempts);
    }
    
    const captchaContainer = document.getElementById('captcha-container');
    const captchaInput = document.getElementById('captcha');
    
    // Munculkan CAPTCHA jika gagal >= 3 kali
    if (failedAttempts >= 3) {
        captchaContainer.classList.remove('hidden');
        captchaInput.setAttribute('required', 'required');
    }
    
    form.addEventListener('submit', function(e) {
        if (failedAttempts >= 3) {
            // Simulasi validasi captcha hardcoded '7W3XY' untuk demo frontend
            if (captchaInput.value.toUpperCase() !== '7W3XY') {
                e.preventDefault();
                document.getElementById('captcha-error').classList.remove('hidden');
            } else {
                // Reset jika berhasil login (meski form akan tetap submit ke backend)
                localStorage.removeItem('failed_login_attempts');
            }
        }
    });
});
</script>
@endpush
