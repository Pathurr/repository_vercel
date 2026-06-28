@extends('layouts.admin')
@section('title', 'Admin Dashboard - SMK Mandalahayu 1 Bekasi')

@section('content')
<div class="max-w-[1200px] mx-auto flex flex-col gap-10">
    <!-- Header -->
    <div>
        <h1 class="font-h1 text-h1 text-primary mb-2">Pusat Kendali Eksekutif</h1>
        <p class="font-body-lg text-body-lg text-on-surface-variant">Pantau ringkasan statistik, kelola pendaftaran baru, dan awasi integritas sistem akademi secara real-time.</p>
    </div>
    
    <!-- KPI Cards Row -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm shadow-primary/5 flex flex-col justify-between group hover:bg-surface-container-low transition-colors">
            <div class="flex justify-between items-start mb-4">
                <span class="font-label-sm text-label-sm text-on-surface-variant">Total Pengguna Aktif</span>
                <div class="w-10 h-10 rounded-full bg-primary-container/10 flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined">groups</span>
                </div>
            </div>
            <div>
                <span class="font-h2 text-h2 text-primary">{{ number_format($totalAktif) }}</span>
                <p class="font-body-md text-[13px] text-on-surface-variant mt-1">Siswa & Guru Terdaftar</p>
            </div>
        </div>
        
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm shadow-primary/5 flex flex-col justify-between group hover:bg-surface-container-low transition-colors relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-error/5 rounded-bl-full -z-0"></div>
            <div class="flex justify-between items-start mb-4 relative z-10">
                <span class="font-label-sm text-label-sm text-on-surface-variant">Antrean Verifikasi</span>
                <div class="w-10 h-10 rounded-full bg-error-container text-on-error-container flex items-center justify-center group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined">how_to_reg</span>
                </div>
            </div>
            <div class="flex items-center gap-3 relative z-10">
                <span class="font-h2 text-h2 text-primary">{{ number_format($totalPending) }}</span>
                @if($totalPending > 0)
                <span class="bg-error text-on-error px-2 py-0.5 rounded-full font-label-sm text-[11px] animate-pulse">Perlu Persetujuan</span>
                @else
                <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded-full font-label-sm text-[11px]">Semua Tuntas</span>
                @endif
            </div>
        </div>
        
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm shadow-primary/5 flex flex-col justify-between group hover:bg-surface-container-low transition-colors">
            <div class="flex justify-between items-start mb-4">
                <span class="font-label-sm text-label-sm text-on-surface-variant">Akun Ditangguhkan</span>
                <div class="w-10 h-10 rounded-full bg-surface-variant text-on-surface-variant flex items-center justify-center group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined">block</span>
                </div>
            </div>
            <div>
                <span class="font-h2 text-h2 text-primary">{{ number_format($totalSuspended) }}</span>
                <p class="font-body-md text-[13px] text-on-surface-variant mt-1">Status Bermasalah</p>
            </div>
        </div>
        
    </div>
    
    <!-- Main Layout -->
    <div class="flex flex-col gap-8">
        <!-- Table -->
        <div class="flex flex-col gap-6 w-full">
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm shadow-primary/5 overflow-hidden">
                <div class="p-6 border-b border-outline-variant flex justify-between items-center bg-surface-container-low/50">
                    <h3 class="font-h3 text-[20px] text-primary">Akun Menunggu Tindakan</h3>
                    <button class="text-secondary font-label-sm text-label-sm hover:underline">Lihat Semua</button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-surface-container-lowest border-b border-outline-variant font-label-sm text-label-sm text-on-surface-variant">
                                <th class="py-4 px-6 font-semibold">Nama Pengguna</th>
                                <th class="py-4 px-6 font-semibold">Identitas (NIS/NRG)</th>
                                <th class="py-4 px-6 font-semibold">Email Utama</th>
                                <th class="py-4 px-6 font-semibold">Peran</th>
                                <th class="py-4 px-6 font-semibold">Waktu Pendaftaran</th>
                                <th class="py-4 px-6 font-semibold text-right">Otorisasi</th>
                            </tr>
                        </thead>
                        <tbody class="font-body-md text-[15px]">
                            @forelse($pendingUsers as $pu)
                            <tr class="border-b border-outline-variant hover:bg-surface-container-low transition-colors">
                                <td class="py-4 px-6 font-medium text-primary">{{ $pu->name }}</td>
                                <td class="py-4 px-6 text-on-surface-variant">{{ $pu->role == 'guru' ? $pu->nrg : $pu->nis }}</td>
                                <td class="py-4 px-6 text-on-surface-variant">{{ $pu->email }}</td>
                                <td class="py-4 px-6">
                                    <span class="px-2.5 py-1 rounded-md {{ $pu->role == 'guru' ? 'bg-primary-container text-on-primary-container' : 'bg-surface-variant text-on-surface-variant' }} text-[12px] font-label-sm">{{ ucfirst($pu->role == 'murid' ? 'Siswa' : $pu->role) }}</span>
                                </td>
                                <td class="py-4 px-6 text-on-surface-variant">{{ $pu->created_at->format('d M Y, H:i') }}</td>
                                <td class="py-4 px-6 flex justify-end gap-2">
                                    <button onclick="showModalAktifkan({{ $pu->id }})" class="px-4 py-1.5 bg-[#feae2c] text-[#6b4500] hover:brightness-110 font-bold text-xs rounded-md shadow-sm transition-soft flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">check</span> Izinkan</button>
                                    <button onclick="showModalTolak({{ $pu->id }})" class="px-4 py-1.5 border border-[#d6c3b8] text-[#51443c] hover:bg-[#f8f3ed] font-bold text-xs rounded-md transition-soft flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">close</span> Tolak</button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-8 px-6 text-center text-on-surface-variant">Tidak ada akun yang menunggu persetujuan saat ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Aktifkan -->
<div id="modal-aktifkan" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/50 hidden backdrop-blur-sm transition-opacity">
    <div class="bg-[#fef9f3] rounded-xl shadow-2xl p-6 w-full max-w-sm border border-[#d6c3b8] text-center">
        <span class="material-symbols-outlined text-[#feae2c] text-5xl mb-4">how_to_reg</span>
        <h3 class="text-xl font-bold text-[#50290b] mb-2" style="font-family: var(--font-serif)">Otorisasi Akses?</h3>
        <p class="text-xs text-[#51443c] mb-6">Pengguna akan mendapatkan hak akses penuh dan dapat menggunakan sistem.</p>
        <form method="POST" action="{{ route('admin.akun.status') }}">
            @csrf
            <input type="hidden" name="user_id" id="aktifkan-user-id" value="">
            <input type="hidden" name="action" value="aktifkan">
            <div class="flex gap-2 justify-center">
                <button type="button" onclick="closeModalAktifkan()" class="px-4 py-2 rounded-lg font-bold text-xs text-[#51443c] border border-[#d6c3b8] hover:bg-[#f8f3ed] transition-colors">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-lg font-bold text-xs bg-[#feae2c] text-[#6b4500] hover:brightness-110 transition-all">Izinkan Akses</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Tolak -->
<div id="modal-tolak" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/50 hidden backdrop-blur-sm transition-opacity">
    <div class="bg-red-50 rounded-xl shadow-2xl p-6 w-full max-w-sm border border-red-200 text-center">
        <span class="material-symbols-outlined text-red-500 text-5xl mb-4">person_off</span>
        <h3 class="text-xl font-bold text-red-700 mb-2" style="font-family: var(--font-serif)">Tolak Pendaftaran?</h3>
        <p class="text-xs text-red-600/80 mb-6">Apakah Anda yakin ingin menolak dan menyingkirkan akun ini dari antrean verifikasi?</p>
        <form method="POST" action="{{ route('admin.akun.status') }}">
            @csrf
            <input type="hidden" name="user_id" id="tolak-user-id" value="">
            <input type="hidden" name="action" value="tolak">
            <div class="flex gap-2 justify-center">
                <button type="button" onclick="closeModalTolak()" class="px-4 py-2 rounded-lg font-bold text-xs text-red-700 border border-red-200 hover:bg-red-100 transition-colors">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-lg font-bold text-xs bg-red-500 text-white hover:bg-red-600 transition-all shadow-md hover:shadow-lg">Ya, Tolak</button>
            </div>
        </form>
    </div>
</div>

<!-- Toasts -->
<div id="toast-action" class="fixed top-5 left-1/2 -translate-x-1/2 z-[70] flex items-center gap-3 bg-green-100 border border-green-300 text-green-800 px-6 py-3 rounded-lg shadow-lg transition-all duration-300 transform {{ session('success') ? 'opacity-100 visible translate-y-0' : 'opacity-0 invisible -translate-y-4' }}">
    <span class="material-symbols-outlined">check_circle</span>
    <span class="font-bold text-sm">{{ session('success') ?? '' }}</span>
</div>

@push('scripts')
<script>
    const modalAktifkan = document.getElementById('modal-aktifkan');
    const modalTolak = document.getElementById('modal-tolak');
    const toastAction = document.getElementById('toast-action');

    function showModalAktifkan(id) { 
        document.getElementById('aktifkan-user-id').value = id;
        modalAktifkan.classList.remove('hidden'); 
    }
    function closeModalAktifkan() { modalAktifkan.classList.add('hidden'); }
    
    function showModalTolak(id) { 
        document.getElementById('tolak-user-id').value = id;
        modalTolak.classList.remove('hidden'); 
    }
    function closeModalTolak() { modalTolak.classList.add('hidden'); }

    if (toastAction.classList.contains('opacity-100')) {
        setTimeout(() => {
            toastAction.classList.remove('opacity-100', 'visible', 'translate-y-0');
            toastAction.classList.add('opacity-0', 'invisible', '-translate-y-4');
        }, 3000);
    }
</script>
@endpush
@endsection
