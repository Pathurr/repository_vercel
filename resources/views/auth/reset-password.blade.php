@extends('layouts.auth')

@section('title', 'SMK Mandalahayu 1 Bekasi - Reset Password')

@section('content')
<div class="w-full min-h-screen md:h-screen flex flex-col md:flex-row overflow-hidden">
    @include('auth.partials.left-panel', [
        'heading'    => 'Buat Sandi Baru',
        'subheading' => 'Silakan masukkan sandi baru untuk akun Anda. Pastikan untuk menggunakan sandi yang kuat dan mudah diingat.',
    ])

    {{-- Right Side: Reset Password Form --}}
    <div class="w-full md:w-3/5 bg-surface-container-lowest flex items-center justify-center md:justify-start min-h-screen md:h-screen overflow-y-auto p-6 md:p-8 md:pl-10 lg:pl-14">
        <div class="w-full max-w-md">
            {{-- Header --}}
            <div class="mb-8 text-center md:text-left">
                <h1 class="font-bold text-4xl text-primary mb-2" style="font-family: var(--font-serif)">Sandi Baru</h1>
                <p class="text-on-surface-variant">Masukkan sandi baru Anda di bawah ini</p>
            </div>

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
            <form class="space-y-4" method="POST" action="{{ route('password.update') }}">
                @csrf
                
                <input type="hidden" name="token" value="{{ $token }}">

                @if (session('reset_email'))
                    <input type="hidden" name="email" value="{{ session('reset_email') }}">
                    <div class="mb-4 p-3 bg-secondary-fixed/20 rounded-lg text-sm text-on-surface-variant">
                        Mereset password untuk: <strong>{{ session('reset_email') }}</strong>
                    </div>
                @else
                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-semibold text-primary mb-2" for="email">Email</label>
                        <div class="relative">
                            <input class="w-full bg-surface-container-low border-0 border-b-2 border-primary/20 text-on-surface focus:ring-0 focus:border-secondary-container transition-soft py-2.5 px-3.5 text-sm"
                                   id="email" name="email" placeholder="Masukkan email Anda"
                                   type="email" value="{{ old('email') }}" autocomplete="email" required/>
                        </div>
                    </div>
                @endif

                {{-- Password --}}
                <div>
                    <label class="block text-sm font-semibold text-primary mb-2" for="password">Password Baru</label>
                    <div class="relative">
                        <input class="w-full bg-surface-container-low border-0 border-b-2 border-primary/20 text-on-surface focus:ring-0 focus:border-secondary-container transition-soft py-2.5 px-3.5 pr-12 text-sm"
                               id="password" name="password" placeholder="Masukkan sandi baru"
                               type="password" required autocomplete="new-password"/>
                        <button class="absolute inset-y-0 right-0 px-3 flex items-center text-on-surface-variant hover:text-primary transition-soft"
                                type="button" onclick="togglePassword('password', 'eye-icon-1')">
                            <span class="material-symbols-outlined text-[20px]" id="eye-icon-1">visibility</span>
                        </button>
                    </div>
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label class="block text-sm font-semibold text-primary mb-2" for="password_confirmation">Konfirmasi Password Baru</label>
                    <div class="relative">
                        <input class="w-full bg-surface-container-low border-0 border-b-2 border-primary/20 text-on-surface focus:ring-0 focus:border-secondary-container transition-soft py-2.5 px-3.5 pr-12 text-sm"
                               id="password_confirmation" name="password_confirmation" placeholder="Ketik ulang sandi baru"
                               type="password" required autocomplete="new-password"/>
                        <button class="absolute inset-y-0 right-0 px-3 flex items-center text-on-surface-variant hover:text-primary transition-soft"
                                type="button" onclick="togglePassword('password_confirmation', 'eye-icon-2')">
                            <span class="material-symbols-outlined text-[20px]" id="eye-icon-2">visibility</span>
                        </button>
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="pt-4">
                    <button class="w-full bg-secondary-container text-on-secondary-container hover:bg-secondary hover:text-white font-bold py-3 px-4 rounded-lg transition-soft text-sm flex items-center justify-center gap-2"
                            type="submit">
                        Reset Password <span class="material-symbols-outlined text-[18px]">lock_reset</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.textContent = 'visibility_off';
        } else {
            input.type = 'password';
            icon.textContent = 'visibility';
        }
    }
</script>
@endpush
