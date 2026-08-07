@extends('layouts.auth')
@section('title', 'Lupa Password - SMK Mandalahayu 1')
@section('content')
<div class="w-full min-h-screen md:h-screen flex flex-col md:flex-row overflow-hidden">
    @include('auth.partials.left-panel', [
        'heading'    => 'Pulihkan Akses Anda',
        'subheading' => 'Jangan khawatir, masukkan email Anda dan kami akan segera mengirimkan tautan untuk mereset password akun LMS Anda.',
    ])

    {{-- Right Side --}}
    <div class="w-full md:w-3/5 bg-surface-container-lowest flex items-center justify-center md:justify-start min-h-screen md:h-screen overflow-y-auto p-6 md:p-8 md:pl-10 lg:pl-14">
        <div class="w-full max-w-md">
            <div class="mb-6 text-center md:text-left">
                <div class="w-14 h-14 rounded-2xl bg-primary/10 flex items-center justify-center mb-4 mx-auto md:mx-0">
                    <span class="material-symbols-outlined text-3xl text-primary">lock_reset</span>
                </div>
                <h1 class="font-bold text-4xl text-primary mb-2" style="font-family: var(--font-serif)">Lupa Password?</h1>
                <p class="text-on-surface-variant text-sm">Masukkan email Anda dan kami akan mengirimkan link reset password.</p>
            </div>

            @if (session('status'))
                <div class="mb-6 p-4 bg-green-100 border border-green-400 rounded-lg text-sm">{{ session('status') }}</div>
                <div id="resend-container" class="text-center">
                    <p class="text-sm text-on-surface-variant mb-3">Belum menerima email? Periksa folder spam atau</p>
                    <button id="resend-btn" type="button" disabled
                            class="inline-flex items-center gap-2 bg-gray-300 text-gray-500 font-bold py-3 px-6 rounded-lg text-sm cursor-not-allowed">
                        <span id="resend-text">Kirim Ulang (30)</span>
                        <span class="material-symbols-outlined text-[18px]">send</span>
                    </button>
                </div>
            @endif

            <form id="forgot-form" method="POST" action="{{ route('password.email') }}" class="space-y-6 @if (session('status')) hidden @endif">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-primary mb-2" for="email">Email</label>
                    <input class="w-full bg-surface-container-low border-0 border-b-2 border-primary/20 text-on-surface focus:ring-0 focus:border-secondary-container transition-soft py-2.5 px-3.5 text-sm"
                           id="email" name="email" type="email" placeholder="Masukkan email Anda" value="{{ old('email') }}" required/>
                    @error('email')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="pt-2">
                    <button type="submit" class="w-full bg-secondary-container text-on-secondary-container font-bold py-4 px-6 rounded hover:bg-secondary-fixed transition-soft flex justify-center items-center gap-2">
                        Kirim Link Reset
                        <span class="material-symbols-outlined">send</span>
                    </button>
                </div>
            </form>

            <div class="mt-8 text-center md:text-left">
                <a href="{{ route('login') }}" class="inline-flex items-center justify-center md:justify-start gap-1.5 py-1 text-primary font-semibold text-sm leading-normal transition-soft group">
                    <span class="material-symbols-outlined shrink-0 text-[16px] leading-none transition-transform group-hover:-translate-x-0.5">arrow_back</span>
                    <span class="leading-normal group-hover:underline underline-offset-4 decoration-primary">Kembali ke Login</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@if (session('status'))
@push('scripts')
<script>
    (function() {
        let countdown = 30;
        const resendBtn = document.getElementById('resend-btn');
        const resendText = document.getElementById('resend-text');
        const forgotForm = document.getElementById('forgot-form');

        function updateCountdown() {
            countdown--;
            if (countdown > 0) {
                resendText.textContent = 'Kirim Ulang (' + countdown + ')';
                setTimeout(updateCountdown, 1000);
            } else {
                resendBtn.disabled = false;
                resendBtn.classList.remove('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
                resendBtn.classList.add('bg-secondary-container', 'text-on-secondary-container', 'hover:bg-secondary-fixed');
                resendText.textContent = 'Kirim Ulang Link';
            }
        }

        resendBtn.addEventListener('click', function() {
            if (!this.disabled) {
                forgotForm.submit();
            }
        });

        setTimeout(updateCountdown, 1000);
    })();
</script>
@endpush
@endif
