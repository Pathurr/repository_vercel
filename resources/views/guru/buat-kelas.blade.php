@extends('layouts.guru')
@section('title', 'Buat Kelas Baru - SMK Mandalahayu 1')

@section('content')
<div class="max-w-[1000px] w-full">


    {{-- Page Header --}}
    <div class="mb-4">
        <h1 class="font-bold text-2xl text-[#50290b] mb-1" style="font-family: var(--font-serif)">Buat Kelas Baru</h1>
        <p class="text-xs text-[#51443c] max-w-2xl">Mulai perjalanan akademik baru. Isi formulir di bawah ini untuk mendaftarkan kelas resmi di sistem Portal Guru.</p>
    </div>

    {{-- Form Card --}}
    <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-2xl p-4 md:p-6 shadow-sm">
        <form id="form-buat-kelas" action="{{ route('guru.kelas.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                {{-- Nama Kelas --}}
                <div>
                    <label class="block text-xs font-bold text-[#51443c] mb-1">Nama Kelas</label>
                    <input type="text" id="nama-kelas" name="nama_kelas" placeholder="Contoh: Pengembangan Web" class="w-full bg-[#f8f3ed] border-none rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#835500] text-sm text-[#50290b] placeholder-[#84746b]" required>
                    <p id="nama-kelas-error" class="hidden text-[11px] text-red-500 font-bold mt-1">Nama kelas wajib diisi.</p>
                </div>
                {{-- Pemilihan Kelas --}}
                <div>
                    <label class="block text-xs font-bold text-[#51443c] mb-1">Pilih Tingkat / Mata Pelajaran</label>
                    <div class="relative">
                        <select id="pilih-kelas" name="mata_pelajaran" class="w-full bg-[#f8f3ed] border-none rounded-lg pl-3 pr-8 py-2 focus:ring-2 focus:ring-[#835500] text-sm text-[#50290b] appearance-none cursor-pointer" required>
                            <option value="">Pilih satu kelas...</option>
                            <option value="X TKJ 1">X TKJ 1</option>
                            <option value="X TKJ 2">X TKJ 2</option>
                            <option value="XI TKJ 1">XI TKJ 1</option>
                            <option value="XI TKJ 2">XI TKJ 2</option>
                            <option value="XII TKJ 1">XII TKJ 1</option>
                            <option value="XII TKJ 2">XII TKJ 2</option>
                        </select>
                        <span class="material-symbols-outlined absolute right-2 top-1/2 -translate-y-1/2 text-[#84746b] pointer-events-none" style="font-size: 18px">expand_more</span>
                    </div>
                    <p id="pilih-kelas-error" class="hidden text-[11px] text-red-500 font-bold mt-1">Pilih satu kelas.</p>
                </div>
            </div>

            {{-- Deskripsi Kelas --}}
            <div class="mb-4">
                <label class="block text-xs font-bold text-[#51443c] mb-1">Deskripsi Kelas</label>
                <textarea name="deskripsi" rows="2" placeholder="Jelaskan tujuan dan cakupan materi kelas ini..." class="w-full bg-[#f8f3ed] border-none rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#835500] text-sm text-[#50290b] placeholder-[#84746b] resize-none"></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                {{-- Guru Pengampu --}}
                <div>
                    <label class="block text-xs font-bold text-[#51443c] mb-1">Guru Pengampu</label>
                    <input type="text" value="{{ Auth::user()->name ?? 'Budi Santoso' }}" class="w-full bg-[#f8f3ed] border-none rounded-lg px-3 py-2 text-sm text-[#50290b] cursor-not-allowed opacity-80" readonly>
                </div>
                {{-- Class Key --}}
                <div>
                    <label class="block text-xs font-bold text-[#51443c] mb-1">Class Key</label>
                    <div class="flex gap-2">
                        <div class="relative flex-1">
                            <input type="text" id="classKeyInput" name="kode_kelas" placeholder="MNDLH-XXXX" class="w-full bg-[#f8f3ed] border-none rounded-lg pl-3 pr-14 py-2 focus:ring-2 focus:ring-[#835500] text-sm font-mono tracking-wider text-[#50290b] placeholder-[#84746b]/50" required>
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-bold text-[#84746b] uppercase tracking-widest">Sistem</span>
                        </div>
                        <button type="button" onclick="generateClassKey()" class="px-3 py-2 border border-[#835500] text-[#835500] font-bold text-xs rounded-lg flex items-center justify-center gap-1 hover:bg-[#835500]/5 transition-soft flex-shrink-0">
                            <span class="material-symbols-outlined" style="font-size: 16px">refresh</span> Generate
                        </button>
                    </div>
                </div>
            </div>

            {{-- Status Kelas --}}
            <div class="mb-4">
                <label class="block text-xs font-bold text-[#51443c] mb-2">Status Kelas</label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-2 md:gap-4">
                    {{-- Terjadwal --}}
                    <label class="cursor-pointer relative">
                        <input type="radio" name="status" value="terjadwal" id="status-terjadwal-main" class="peer sr-only">
                        <div class="w-full h-full bg-[#f8f3ed] border-2 border-transparent rounded-xl flex items-center justify-center py-2 gap-1 text-[#51443c] peer-checked:bg-[#fef9f3] peer-checked:border-[#835500] peer-checked:text-[#50290b] transition-soft hover:bg-[#e6e2dc]">
                            <span class="material-symbols-outlined" style="font-size: 18px">schedule</span>
                            <span class="text-xs font-semibold">Terjadwal</span>
                        </div>
                    </label>
                    {{-- Active (Default) --}}
                    <label class="cursor-pointer relative">
                        <input type="radio" name="status" value="active" class="peer sr-only" checked>
                        <div class="w-full h-full bg-[#f8f3ed] border-2 border-transparent rounded-xl flex items-center justify-center py-2 gap-1 text-[#51443c] peer-checked:bg-[#fef9f3] peer-checked:border-[#835500] peer-checked:text-[#50290b] transition-soft hover:bg-[#e6e2dc]">
                            <span class="material-symbols-outlined" style="font-size: 18px">check_circle</span>
                            <span class="text-xs font-semibold">Active</span>
                        </div>
                    </label>
                    {{-- Closed --}}
                    <label class="cursor-pointer relative">
                        <input type="radio" name="status" value="closed" id="status-closed" class="peer sr-only">
                        <div class="w-full h-full bg-[#f8f3ed] border-2 border-transparent rounded-xl flex items-center justify-center py-2 gap-1 text-[#51443c] peer-checked:bg-[#fef9f3] peer-checked:border-[#835500] peer-checked:text-[#50290b] transition-soft hover:bg-[#e6e2dc]">
                            <span class="material-symbols-outlined" style="font-size: 18px">lock</span>
                            <span class="text-xs font-semibold">Closed</span>
                        </div>
                    </label>

                </div>
                <!-- Hidden info jadwal -->
                <input type="hidden" name="scheduled_at" id="scheduled_at_input" />
                <div id="scheduled-display" class="hidden text-[11px] text-[#835500] font-bold bg-[#835500]/10 px-2 py-1 rounded-md inline-block w-full text-center mt-2"></div>
            </div>

            {{-- Footer Buttons --}}
            <div class="border-t border-outline-variant/30 pt-4 flex flex-col-reverse sm:flex-row justify-end gap-3">
                <button type="button" id="btn-trigger-batal" class="ui-btn ui-btn-secondary px-6 py-2 text-sm">
                    Batal
                </button>
                <button type="button" id="btn-trigger-simpan" class="ui-btn ui-btn-primary px-6 py-2 text-sm">
                    <span class="material-symbols-outlined" style="font-size: 18px">save</span> Simpan Kelas
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Konfirmasi Simpan -->
<div id="modal-confirm-simpan" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/50 hidden backdrop-blur-sm transition-opacity">
    <div class="ui-modal-card">
        <span class="material-symbols-outlined text-[#feae2c] text-5xl mb-4">help</span>
        <h3 class="text-xl font-bold text-[#50290b] mb-2" style="font-family: var(--font-serif)">Simpan Kelas?</h3>
        <p class="text-xs text-[#51443c] mb-6">Apakah Anda yakin data kelas sudah benar dan siap disimpan?</p>
        <div class="flex gap-2 justify-center">
            <button type="button" id="btn-cancel-simpan" class="ui-btn ui-btn-secondary px-4 py-2 text-xs">Periksa Lagi</button>
            <button type="button" id="btn-confirm-simpan" class="ui-btn ui-btn-primary px-4 py-2 text-xs">Ya, Simpan</button>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Batal -->
<div id="modal-confirm-batal" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/50 hidden backdrop-blur-sm transition-opacity">
    <div class="bg-red-50 rounded-xl shadow-2xl p-6 w-full max-w-sm border border-red-200 text-center">
        <span class="material-symbols-outlined text-red-500 text-5xl mb-4">warning</span>
        <h3 class="text-xl font-bold text-red-700 mb-2" style="font-family: var(--font-serif)">Batalkan Pembuatan?</h3>
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
    <span class="font-bold text-sm">Kelas berhasil dibuat!</span>
</div>

<!-- Toast Batal (Popup Merah) -->
<div id="toast-batal" class="fixed top-5 left-1/2 -translate-x-1/2 z-[70] flex items-center gap-3 bg-red-100 border border-red-300 text-red-800 px-6 py-3 rounded-lg shadow-lg opacity-0 invisible transition-all duration-300 transform -translate-y-4">
    <span class="material-symbols-outlined">cancel</span>
    <span class="font-bold text-sm">Kelas berhasil dibatalkan!</span>
</div>

<!-- Modal Popup Terjadwal -->
<div id="modal-terjadwal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 hidden backdrop-blur-sm transition-opacity">
    <div class="ui-modal-card text-left">
        <h3 class="text-lg font-bold text-[#50290b] mb-2 flex items-center gap-2" style="font-family: var(--font-serif)">
            <span class="material-symbols-outlined">schedule</span> Jadwal Kelas
        </h3>
        <p class="text-[11px] text-[#51443c] mb-5">Atur kapan kelas ini akan aktif. Pilih waktu minimal 5 menit dari sekarang.</p>
        
        <div class="mb-5">
            <label class="block text-xs font-bold text-[#51443c] mb-2">Pilih Tanggal & Waktu</label>
            <input id="modal-datetime-input" type="datetime-local" class="w-full bg-[#f8f3ed] border-none border-b-2 border-[#835500] focus:ring-0 focus:border-[#feae2c] transition-all py-2 px-3 text-sm text-[#50290b]" />
            <p id="modal-error" class="hidden text-red-500 text-[11px] mt-2 font-bold max-w-full">Waktu yang dipilih kurang dari 5 menit dari sekarang!</p>
        </div>
        
        <div class="flex gap-2 justify-end">
            <button type="button" id="btn-cancel-terjadwal" class="ui-btn ui-btn-secondary px-4 py-2 text-xs">Batal</button>
            <button type="button" id="btn-save-terjadwal" class="ui-btn ui-btn-primary px-4 py-2 text-xs">Simpan Jadwal</button>
        </div>
    </div>
</div>

<script>
    function generateClassKey() {
        const classSelect = document.getElementById('pilih-kelas');
        let prefix = 'MNDLH';
        
        if (classSelect && classSelect.value) {
            // Hilangkan spasi dari nama kelas, contoh: "X TKJ 1" -> "XTKJ1"
            prefix = classSelect.value.replace(/\s+/g, '').toUpperCase();
        }

        const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        let randomPart = '';
        for (let i = 0; i < 4; i++) {
            randomPart += characters.charAt(Math.floor(Math.random() * characters.length));
        }
        document.getElementById('classKeyInput').value = prefix + '-' + randomPart;
    }

    document.addEventListener('DOMContentLoaded', function() {
        // --- STATUS PUBLIKASI ---
        const radioTerjadwalMain = document.getElementById('status-terjadwal-main');
        const radioActive = document.querySelector('input[name="status"][value="active"]');
        const radioClosed = document.getElementById('status-closed');
        const modal = document.getElementById('modal-terjadwal');
        const datetimeInput = document.getElementById('modal-datetime-input');
        const btnCancel = document.getElementById('btn-cancel-terjadwal');
        const btnSave = document.getElementById('btn-save-terjadwal');
        const hiddenScheduledInput = document.getElementById('scheduled_at_input');
        const scheduledDisplay = document.getElementById('scheduled-display');
        const errorMessage = document.getElementById('modal-error');
        
        let previousStatus = 'active';

        function getMinDateTime() {
            const now = new Date();
            now.setMinutes(now.getMinutes() + 5);
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
            return now.toISOString().slice(0, 16);
        }

        function showModal() {
            const minDateTime = getMinDateTime();
            datetimeInput.min = minDateTime;
            if (!datetimeInput.value || datetimeInput.value < minDateTime) {
                datetimeInput.value = minDateTime;
            }
            modal.classList.remove('hidden');
        }

        function hideModal() {
            modal.classList.add('hidden');
            errorMessage.classList.add('hidden');
        }

        [radioActive, radioClosed].forEach(radio => {
            radio?.addEventListener('change', function() {
                if (this.checked) {
                    previousStatus = this.value;
                    hiddenScheduledInput.value = '';
                    scheduledDisplay.classList.add('hidden');
                }
            });
        });

        radioTerjadwalMain.addEventListener('change', function() {
            if (this.checked) {
                showModal();
            }
        });

        btnCancel.addEventListener('click', function() {
            hideModal();
            if (previousStatus === 'terjadwal') radioTerjadwalMain.checked = true;
            else if (previousStatus === 'active') radioActive.checked = true;
            else if (radioClosed) radioClosed.checked = true;
        });

        btnSave.addEventListener('click', function() {
            const selectedTime = new Date(datetimeInput.value).getTime();
            const minTime = new Date(getMinDateTime()).getTime();
            
            if (selectedTime < minTime) {
                errorMessage.classList.remove('hidden');
                return;
            }
            
            errorMessage.classList.add('hidden');
            hiddenScheduledInput.value = datetimeInput.value;
            previousStatus = 'terjadwal';
            hideModal();
            
            const dateObj = new Date(datetimeInput.value);
            scheduledDisplay.textContent = 'Jadwal Kelas: ' + dateObj.toLocaleString('id-ID', {
                day: 'numeric', month: 'short', year: 'numeric', 
                hour: '2-digit', minute: '2-digit'
            });
            scheduledDisplay.classList.remove('hidden');
        });

        // --- KONFIRMASI SIMPAN & BATAL ---
        const btnTriggerSimpan = document.getElementById('btn-trigger-simpan');
        const btnTriggerBatal = document.getElementById('btn-trigger-batal');
        const modalConfirmSimpan = document.getElementById('modal-confirm-simpan');
        const modalConfirmBatal = document.getElementById('modal-confirm-batal');
        const btnCancelSimpan = document.getElementById('btn-cancel-simpan');
        const btnConfirmSimpan = document.getElementById('btn-confirm-simpan');
        const btnCancelBatal = document.getElementById('btn-cancel-batal');
        const btnConfirmBatal = document.getElementById('btn-confirm-batal');
        const toastSuccess = document.getElementById('toast-success');
        const toastBatal = document.getElementById('toast-batal');

        const namaKelasInput = document.getElementById('nama-kelas');
        const pilihKelasInput = document.getElementById('pilih-kelas');
        const namaKelasError = document.getElementById('nama-kelas-error');
        const pilihKelasError = document.getElementById('pilih-kelas-error');

        function validateForm() {
            let isValid = true;
            
            if (!namaKelasInput.value.trim()) {
                namaKelasError.classList.remove('hidden');
                namaKelasInput.classList.add('border-red-500', 'border-2', 'border-solid');
                isValid = false;
            } else {
                namaKelasError.classList.add('hidden');
                namaKelasInput.classList.remove('border-red-500', 'border-2', 'border-solid');
            }

            if (!pilihKelasInput.value) {
                pilihKelasError.classList.remove('hidden');
                isValid = false;
            } else {
                pilihKelasError.classList.add('hidden');
            }

            return isValid;
        }

        namaKelasInput.addEventListener('input', () => {
            if (namaKelasInput.value.trim()) {
                namaKelasError.classList.add('hidden');
                namaKelasInput.classList.remove('border-red-500', 'border-2', 'border-solid');
            }
        });
        
        pilihKelasInput.addEventListener('change', () => {
            if (pilihKelasInput.value) {
                pilihKelasError.classList.add('hidden');
                // Auto generate class key based on selected class
                generateClassKey();
            }
        });

        btnTriggerSimpan.addEventListener('click', () => {
            modalConfirmSimpan.classList.remove('hidden');
        });
        
        btnTriggerBatal.addEventListener('click', () => {
            modalConfirmBatal.classList.remove('hidden');
        });

        btnCancelSimpan.addEventListener('click', () => {
            modalConfirmSimpan.classList.add('hidden');
        });

        btnCancelBatal.addEventListener('click', () => {
            modalConfirmBatal.classList.add('hidden');
        });

        btnConfirmBatal.addEventListener('click', () => {
            modalConfirmBatal.classList.add('hidden');
            
            toastBatal.classList.remove('invisible', 'opacity-0', '-translate-y-4');
            toastBatal.classList.add('opacity-100', 'translate-y-0');
            
            setTimeout(() => {
                window.location.href = "{{ route('guru.kelas') }}";
            }, 1500);
        });

        btnConfirmSimpan.addEventListener('click', () => {
            if (!validateForm()) {
                modalConfirmSimpan.classList.add('hidden');
                return;
            }
            modalConfirmSimpan.classList.add('hidden');
            
            // Allow form submission to proceed
            document.getElementById('form-buat-kelas').submit();
        });
    });
</script>
@endsection
