@extends('layouts.siswa')
@section('title', 'Pengumpulan Tugas - SMK Mandalahayu 1')
@section('page-title', $tugas->judul)

@section('content')
<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: var(--color-outline-variant); border-radius: 10px; }
</style>

<div class="flex-1 flex flex-col md:flex-row gap-4 w-full min-h-[calc(100vh-120px)] md:h-[calc(100vh-120px)] overflow-visible md:overflow-hidden -mt-2">
    <!-- LEFT PANEL: Task Details -->
    <section class="w-full md:w-1/2 flex flex-col min-h-[420px] md:h-full bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant/30 overflow-hidden">
        <div class="p-5 border-b border-outline-variant/30 shrink-0">
            <div class="inline-flex items-center gap-2 bg-surface-container-low text-primary px-2.5 py-0.5 rounded-full mb-3">
                <span class="material-symbols-outlined text-[14px]">menu_book</span>
                <span class="font-bold text-[10px]">{{ $tugas->kelas->mata_pelajaran ?? 'Mata Pelajaran' }}</span>
            </div>
            <h1 class="font-bold text-lg text-primary mb-4" style="font-family: var(--font-serif)">{{ $tugas->judul }}</h1>
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex items-center gap-3">
                    <div class="p-1.5 bg-error/10 text-error rounded-md">
                        <span class="material-symbols-outlined text-[16px]">schedule</span>
                    </div>
                    <div>
                        <span class="font-bold text-[10px] text-on-surface-variant block">Tenggat Waktu</span>
                        <span class="font-bold text-[11px] text-error block mt-0.5">{{ $tugas->deadline ? \Carbon\Carbon::parse($tugas->deadline)->format('d M Y • H:i') . ' WIB' : 'Tidak ada tenggat' }}</span>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="p-1.5 bg-primary/10 text-primary rounded-md">
                        <span class="material-symbols-outlined text-[16px]">monitoring</span>
                    </div>
                    <div>
                        <span class="font-bold text-[10px] text-on-surface-variant block">Bobot Nilai</span>
                        <span class="font-bold text-[11px] text-on-surface block mt-0.5">20% dari Akhir Semester</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-5 overflow-y-auto flex-1 custom-scrollbar">
            <h3 class="font-bold text-sm text-primary mb-3" style="font-family: var(--font-serif)">Deskripsi Tugas</h3>
            <div class="prose max-w-none text-[11px] text-on-surface-variant space-y-2">
                {!! nl2br(e($tugas->deskripsi)) !!}
            </div>
            
            @if($tugas->file_path)
            <div class="mt-5 pt-4 border-t border-surface-container">
                <p class="font-bold text-[10px] text-on-surface-variant mb-2">Lampiran Guru:</p>
                <a class="flex items-center gap-2 p-2 rounded-lg border border-outline-variant hover:bg-surface-container-low transition-colors group" href="#">
                    <span class="material-symbols-outlined text-primary text-[18px] group-hover:text-secondary">attachment</span>
                    <div class="flex-1 leading-tight">
                        <p class="font-bold text-[11px] text-on-surface group-hover:text-secondary transition-colors">Lampiran Tugas</p>
                    </div>
                    <span class="material-symbols-outlined text-on-surface-variant text-[16px] group-hover:text-secondary">download</span>
                </a>
            </div>
            @endif
        </div>
    </section>

    <!-- RIGHT PANEL: Submission Area -->
    <section class="w-full md:w-1/2 flex flex-col min-h-[420px] md:h-full gap-4">
        <div class="bg-surface-container-lowest rounded-xl p-4 shadow-sm border border-outline-variant/30 flex justify-between items-center shrink-0">
            <div>
                <p class="font-bold text-[10px] text-on-surface-variant">Status Pengumpulan</p>
                <div class="flex items-center gap-1.5 mt-1">
                    <span class="material-symbols-outlined text-[16px] text-outline">pending_actions</span>
                    <p class="font-bold text-xs text-on-surface">Belum diserahkan</p>
                </div>
            </div>
        </div>

        <div class="bg-surface-container-lowest rounded-xl p-5 shadow-sm border border-outline-variant/30 flex-1 flex flex-col overflow-hidden">
            <h3 class="font-bold text-sm text-primary mb-3" style="font-family: var(--font-serif)">Area Pengumpulan</h3>
            
            <form id="form-pengumpulan" action="{{ route('siswa.kumpul-tugas', $tugas->id) }}" method="POST" enctype="multipart/form-data" class="flex-1 flex flex-col min-h-0">
                @csrf
                <div class="flex-1 overflow-y-auto custom-scrollbar flex flex-col pr-1 gap-3">
                    <div class="shrink-0">
                        <label class="block font-bold text-[10px] text-on-surface mb-1.5">Catatan Tambahan (Opsional)</label>
                        <div class="border border-outline-variant rounded-lg overflow-hidden focus-within:border-secondary transition-all">
                            <textarea name="catatan" class="w-full p-2 bg-transparent border-none focus:ring-0 text-[11px] text-on-surface resize-none placeholder-on-surface-variant/50" placeholder="Tuliskan pesan untuk guru..." rows="2"></textarea>
                        </div>
                    </div>
                    
                    <div class="flex-1 flex flex-col min-h-[120px]">
                        <label class="block font-bold text-[10px] text-on-surface mb-1.5">Unggah Berkas <span class="text-error">*</span></label>
                        <input type="file" id="file-upload" name="file" accept=".pdf,.zip,.rar,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png" class="hidden" onchange="handleFileUpload(event)">
                        <div id="upload-zone" onclick="document.getElementById('file-upload').click()" class="border-2 border-dashed border-outline-variant hover:border-secondary bg-surface-container-low hover:bg-surface-container rounded-xl flex-1 flex flex-col items-center justify-center text-center cursor-pointer transition-colors group p-4">
                            <div class="w-10 h-10 bg-surface-container-highest rounded-full flex items-center justify-center mb-2 group-hover:bg-primary-fixed-dim/20 transition-colors">
                                <span class="material-symbols-outlined text-xl text-primary group-hover:text-secondary">cloud_upload</span>
                            </div>
                            <p id="upload-text" class="text-xs text-on-surface font-bold">Tarik & lepas file</p>
                            <p id="upload-subtext" class="text-[10px] text-on-surface-variant">atau klik untuk mencari dari perangkat</p>
                            <p class="text-[9px] text-on-surface-variant mt-2 font-bold">Maks. 50MB (PDF/DOCX/ZIP/RAR/JPG/PNG)</p>
                        </div>
                        <p id="file-error" class="text-[10px] font-bold text-error mt-2 hidden">Berkas wajib diunggah sebelum dikumpulkan!</p>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-surface-container flex flex-col gap-3 shrink-0">
                    <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 w-full">
                        <button type="button" id="btn-trigger-batal" class="px-6 py-2 border border-[#d6c3b8] text-[#51443c] text-sm text-center font-bold rounded-lg hover:bg-[#f8f3ed] transition-soft">
                            Batalkan
                        </button>
                        <button type="button" id="btn-trigger-simpan" class="px-6 py-2 bg-[#feae2c] text-[#6b4500] text-sm font-bold rounded-lg flex items-center justify-center gap-1 hover:brightness-110 transition-soft">
                            <span class="material-symbols-outlined" style="font-size: 18px">send</span> Kumpulkan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>

<!-- Modal Konfirmasi Kumpulkan -->
<div id="modal-confirm-simpan" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/50 hidden backdrop-blur-sm transition-opacity">
    <div class="bg-[#fef9f3] rounded-xl shadow-2xl p-6 w-full max-w-sm border border-[#d6c3b8] text-center">
        <span class="material-symbols-outlined text-[#feae2c] text-5xl mb-4">help</span>
        <h3 class="text-xl font-bold text-[#50290b] mb-2" style="font-family: var(--font-serif)">Kumpulkan Tugas?</h3>
        <p class="text-xs text-[#51443c] mb-6">Pastikan berkas sudah lengkap karena tidak bisa diubah lagi.</p>
        <div class="flex gap-2 justify-center">
            <button type="button" id="btn-cancel-simpan" class="px-4 py-2 rounded-lg font-bold text-xs text-[#51443c] border border-[#d6c3b8] hover:bg-[#f8f3ed] transition-colors">Periksa Lagi</button>
            <button type="button" id="btn-confirm-simpan" class="px-4 py-2 rounded-lg font-bold text-xs bg-[#feae2c] text-[#6b4500] hover:brightness-110 transition-all">Ya, Kumpulkan</button>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Batal -->
<div id="modal-confirm-batal" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/50 hidden backdrop-blur-sm transition-opacity">
    <div class="bg-red-50 rounded-xl shadow-2xl p-6 w-full max-w-sm border border-red-200 text-center">
        <span class="material-symbols-outlined text-red-500 text-5xl mb-4">warning</span>
        <h3 class="text-xl font-bold text-red-700 mb-2" style="font-family: var(--font-serif)">Batalkan Pengumpulan?</h3>
        <p class="text-xs text-red-600/80 mb-6">Semua data yang telah Anda isikan akan hilang. Apakah Anda yakin?</p>
        <div class="flex gap-2 justify-center">
            <button type="button" id="btn-cancel-batal" class="px-4 py-2 rounded-lg font-bold text-xs text-red-700 border border-red-200 hover:bg-red-100 transition-colors">Kembali</button>
            <button type="button" id="btn-confirm-batal" class="px-4 py-2 rounded-lg font-bold text-xs bg-red-500 text-white hover:bg-red-600 transition-all shadow-md hover:shadow-lg">Ya, Batalkan</button>
        </div>
    </div>
</div>

<!-- Toast Success (Popup Hijau) -->
<div id="toast-success" class="fixed top-5 left-1/2 -translate-x-1/2 z-[70] flex items-center gap-3 bg-green-100 border border-green-300 text-green-800 px-6 py-3 rounded-lg shadow-lg opacity-0 invisible transition-all duration-300 transform -translate-y-4">
    <span class="material-symbols-outlined">check_circle</span>
    <span class="font-bold text-sm">Tugas berhasil dikumpulkan!</span>
</div>

<!-- Toast Batal (Popup Merah) -->
<div id="toast-batal" class="fixed top-5 left-1/2 -translate-x-1/2 z-[70] flex items-center gap-3 bg-red-100 border border-red-300 text-red-800 px-6 py-3 rounded-lg shadow-lg opacity-0 invisible transition-all duration-300 transform -translate-y-4">
    <span class="material-symbols-outlined">cancel</span>
    <span class="font-bold text-sm">Pengumpulan dibatalkan!</span>
</div>

@push('scripts')
<script>
    function handleFileUpload(event) {
        const file = event.target.files[0];
        if (file) {
            document.getElementById('upload-text').innerText = file.name;
            document.getElementById('upload-subtext').innerText = "Berkas siap diunggah.";
            document.getElementById('upload-zone').classList.add('border-primary', 'bg-primary/5');
            document.getElementById('file-error').classList.add('hidden');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const btnTriggerSimpan = document.getElementById('btn-trigger-simpan');
        const modalSimpan = document.getElementById('modal-confirm-simpan');
        const btnCancelSimpan = document.getElementById('btn-cancel-simpan');
        const btnConfirmSimpan = document.getElementById('btn-confirm-simpan');
        const toastSuccess = document.getElementById('toast-success');

        const btnTriggerBatal = document.getElementById('btn-trigger-batal');
        const modalBatal = document.getElementById('modal-confirm-batal');
        const btnCancelBatal = document.getElementById('btn-cancel-batal');
        const btnConfirmBatal = document.getElementById('btn-confirm-batal');
        const toastBatal = document.getElementById('toast-batal');

        function showToast(toastEl) {
            toastEl.classList.remove('opacity-0', 'invisible', '-translate-y-4');
            toastEl.classList.add('opacity-100', 'visible', 'translate-y-0');
            setTimeout(() => {
                toastEl.classList.remove('opacity-100', 'visible', 'translate-y-0');
                toastEl.classList.add('opacity-0', 'invisible', '-translate-y-4');
            }, 3000);
        }

        // Simpan Flow
        btnTriggerSimpan.addEventListener('click', () => {
            const fileInput = document.getElementById('file-upload');
            if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
                document.getElementById('file-error').classList.remove('hidden');
                return;
            }
            modalSimpan.classList.remove('hidden');
        });
        btnCancelSimpan.addEventListener('click', () => modalSimpan.classList.add('hidden'));
        btnConfirmSimpan.addEventListener('click', () => {
            modalSimpan.classList.add('hidden');
            document.getElementById('form-pengumpulan').submit();
        });

        // Batal Flow
        btnTriggerBatal.addEventListener('click', () => modalBatal.classList.remove('hidden'));
        btnCancelBatal.addEventListener('click', () => modalBatal.classList.add('hidden'));
        btnConfirmBatal.addEventListener('click', () => {
            modalBatal.classList.add('hidden');
            showToast(toastBatal);
            setTimeout(() => {
                window.location.href = "{{ route('siswa.mapel.detail', $tugas->kelas_id ?? 1) }}?tab=tugas";
            }, 1000);
        });
    });
</script>
@endpush
@endsection
