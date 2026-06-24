@extends('layouts.guru')
@section('title', 'Notifikasi - SMK Mandalahayu 1')

@section('content')
<div class="space-y-4">
    <!-- Page Header -->
    <div class="border-b border-outline-variant/50 pb-3 mb-4">
        <h2 class="text-xl text-primary font-bold mb-1" style="font-family: var(--font-serif)">Notifikasi</h2>
        <p class="text-xs text-on-surface-variant">Pembaruan terbaru mengenai aktivitas akademik Anda.</p>
    </div>

    <!-- Notification List -->
    <div class="space-y-2" id="notification-container">
        @forelse($notifications as $notification)
            @php
                $icon = match($notification->tipe ?? 'info') {
                    'warning' => 'warning',
                    'tugas' => 'description',
                    'info' => 'info',
                    'success' => 'check_circle',
                    default => 'notifications',
                };
                $isRead = (bool) $notification->dibaca;
            @endphp
            <div class="notification-item group flex items-start gap-3 p-3 rounded-lg {{ $isRead ? 'bg-surface-container border border-outline-variant/30 opacity-80' : 'bg-surface-container-lowest border border-outline-variant/50 shadow-sm hover:shadow-md' }} transition-all relative" data-id="{{ $notification->id }}">
                <div class="{{ $isRead ? 'hidden' : 'absolute left-0 top-0 bottom-0 w-1 bg-secondary rounded-l-lg' }}"></div>
                <div class="flex-shrink-0 w-8 h-8 rounded-full {{ $isRead ? 'bg-surface-variant border border-outline-variant/50 text-on-surface-variant' : 'bg-secondary-container/20 flex items-center justify-center text-on-secondary-container border border-secondary-container' }}">
                    <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">{{ $icon }}</span>
                </div>
                <div class="flex-1 min-w-0 pr-6">
                    <div class="flex justify-between items-start mb-0.5">
                        <h3 class="text-xs {{ $isRead ? 'text-on-surface' : 'text-primary' }} font-bold truncate" style="font-family: var(--font-serif)">{{ $notification->judul }}</h3>
                        @if(!$isRead)
                        <span class="badge-new text-[9px] text-secondary font-bold flex items-center gap-1 uppercase tracking-wider bg-secondary-fixed/20 px-1.5 py-0.5 rounded border border-secondary/20 whitespace-nowrap">
                            <span class="w-1.5 h-1.5 rounded-full bg-secondary inline-block"></span> Belum Dibaca
                        </span>
                        @endif
                    </div>
                    <p class="text-[11px] text-on-surface leading-snug mb-1.5 line-clamp-2">{{ $notification->pesan }}</p>
                    <p class="text-[9px] text-on-surface-variant font-bold flex items-center gap-1">
                        <span class="material-symbols-outlined text-[12px]">schedule</span> {{ $notification->created_at?->diffForHumans() ?? 'Baru saja' }}
                    </p>
                </div>
                <div class="absolute top-2 right-2">
                    <button onclick="toggleMenu({{ $notification->id }})" class="p-1 rounded-full text-on-surface-variant hover:bg-surface-container hover:text-primary transition-colors focus:outline-none">
                        <span class="material-symbols-outlined text-[16px]">more_vert</span>
                    </button>
                    <div id="menu-{{ $notification->id }}" class="hidden absolute right-0 top-full mt-1 w-28 bg-surface-container-lowest border border-outline-variant/30 shadow-lg rounded-lg py-1 z-10">
                        <button onclick="markRead({{ $notification->id }})" class="w-full text-left px-3 py-1.5 text-[10px] font-bold text-on-surface hover:bg-surface-container transition-colors">Tandai Dibaca</button>
                        <button onclick="deleteNotif({{ $notification->id }})" class="w-full text-left px-3 py-1.5 text-[10px] font-bold text-error hover:bg-error/10 transition-colors">Hapus</button>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-6 text-center bg-surface rounded-xl border border-outline-variant/30 text-on-surface-variant">
                <span class="material-symbols-outlined text-4xl mb-3">notifications_off</span>
                <p class="font-bold">Belum ada notifikasi</p>
                <p class="text-xs mt-1">Notifikasi terbaru akan muncul di sini ketika tersedia.</p>
            </div>
        @endforelse
    </div>

    <!-- End of list -->
    <div class="pt-4 text-center">
        <p class="text-xs text-on-surface-variant italic font-bold">Anda telah melihat semua notifikasi.</p>
    </div>
</div>

@push('scripts')
<script>
    function toggleMenu(id) {
        event.stopPropagation();
        
        // Reset z-index of all items
        document.querySelectorAll('.notification-item').forEach(el => {
            el.style.zIndex = '1';
        });

        document.querySelectorAll('[id^="menu-"]').forEach(el => {
            if (el.id !== `menu-${id}`) el.classList.add('hidden');
        });
        
        const menu = document.getElementById(`menu-${id}`);
        menu.classList.toggle('hidden');
        
        if (!menu.classList.contains('hidden')) {
            const item = document.querySelector(`.notification-item[data-id="${id}"]`);
            if (item) item.style.zIndex = '50';
        }
    }

    function markRead(id) {
        const item = document.querySelector(`.notification-item[data-id="${id}"]`);
        if (item) {
            item.classList.remove('bg-surface-container-lowest', 'shadow-sm');
            item.classList.add('bg-surface-container', 'opacity-80');
            const indicator = item.querySelector('.absolute.left-0');
            if (indicator) indicator.classList.add('hidden');
            const badge = item.querySelector('.badge-new');
            if (badge) badge.classList.add('hidden');
        }
        document.getElementById(`menu-${id}`).classList.add('hidden');
    }

    function deleteNotif(id) {
        const item = document.querySelector(`.notification-item[data-id="${id}"]`);
        if (item) {
            item.remove();
        }
    }

    document.addEventListener('click', function(event) {
        const isClickInside = event.target.closest('.absolute.top-2.right-2');
        if (!isClickInside) {
            document.querySelectorAll('[id^="menu-"]').forEach(el => {
                el.classList.add('hidden');
            });
        }
    });
</script>
@endpush
@endsection
