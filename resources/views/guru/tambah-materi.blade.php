@extends('layouts.guru')
@section('title', request('edit') ? 'Edit Materi - SMK Mandalahayu 1' : 'Tambah Materi Baru - SMK Mandalahayu 1')
@section('content')
@php
    $isEdit = request('edit') == 'true';
    $judul = request('judul', '');
@endphp
<div class="mb-4">
    <a href="{{ route('guru.materi') }}" class="inline-flex items-center gap-2 text-sm text-secondary hover:text-primary transition-colors font-bold mb-4">
        <span class="material-symbols-outlined text-[18px]">arrow_back</span> Kembali ke Daftar Materi
    </a>
    <h2 class="font-bold text-3xl text-primary serif" style="font-family: var(--font-serif)">{{ $isEdit ? 'Edit Materi' : 'Tambah Materi Baru' }}</h2>
    <p class="text-sm text-on-surface-variant mt-1">{{ $isEdit ? 'Perbarui informasi materi yang sudah diunggah.' : 'Buat dan publikasikan materi pembelajaran digital untuk siswa Anda.' }}</p>
</div>
<div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-start">
    <div class="lg:col-span-8 space-y-4">
        <div class="bg-surface-container-lowest rounded-2xl p-5 shadow-ambient border border-outline-variant/30">
            <form id="form-materi" method="POST" action="{{ route('guru.materi.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-primary mb-1" for="judul">Judul Materi</label>
                        <input class="w-full bg-surface-container-low border-0 border-b-2 border-primary focus:border-secondary focus:ring-0 text-base transition-soft px-3 py-2"
                               id="judul" name="judul" placeholder="Contoh: Pengantar Jaringan Komputer Dasar" type="text" value="{{ $judul }}" required/>
                        <p id="judul-error" class="hidden text-[11px] text-red-500 font-bold mt-1">Judul materi wajib diisi.</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-primary mb-1" for="deskripsi">Deskripsi Materi</label>
                        <textarea class="w-full bg-surface-container-low border-0 border-b-2 border-primary focus:border-secondary focus:ring-0 transition-soft px-3 py-2 resize-none"
                                  id="deskripsi" name="deskripsi" rows="2" placeholder="Jelaskan secara singkat kompetensi dasar...">{{ $isEdit ? 'Deskripsi dummy untuk materi ' . $judul . ' (Dalam mode edit, deskripsi akan diisi secara otomatis).' : '' }}</textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-primary mb-2">Dokumen Materi</label>
                        <div class="border-2 border-dashed border-outline-variant rounded-xl p-4 text-center bg-surface-container-low/50 hover:bg-surface-container-low transition-soft cursor-pointer group" id="drop-area">
                            <input accept=".pdf,.ppt,.pptx,image/*" class="hidden" id="file-upload" name="file" type="file"/>
                            <div id="drop-prompt">
                                <span class="material-symbols-outlined text-3xl text-primary/40 group-hover:scale-110 group-hover:text-primary transition-soft mb-1" style="display:block;">cloud_upload</span>
                                <p class="text-sm text-on-surface-variant"><span class="text-primary font-bold">Klik untuk unggah</span> atau seret file ke sini</p>
                                <p class="text-xs text-on-surface-variant/60 mt-1 uppercase tracking-widest">PDF, PPTX, JPG (Max. 20MB)</p>
                            </div>
                            <div id="file-preview" class="hidden w-full">
                                <div id="preview-container" class="mt-2 w-full"></div>
                                <div class="mt-2 text-center">
                                    <p id="preview-name" class="text-sm font-bold text-primary truncate px-4">filename.pdf</p>
                                    <p id="preview-size" class="text-xs text-on-surface-variant/80 mt-1">2.5 MB</p>
                                    <p class="text-[10px] text-primary/60 mt-2 hover:underline">Klik area ini untuk mengganti file</p>
                                </div>
                            </div>
                        </div>
                        <p id="file-error" class="hidden text-[11px] text-red-500 font-bold mt-1">Dokumen materi wajib diunggah.</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-primary mb-2">Kelas Tujuan</label>
                            <div class="space-y-2 bg-surface-container-low p-3 rounded-xl max-h-32 overflow-y-auto custom-scrollbar" id="kelas-container">
                                @forelse($kelases as $kelas)
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input class="kelas-checkbox w-4 h-4 rounded border-outline-variant text-secondary focus:ring-secondary" name="kelas_id[]" type="checkbox" value="{{ $kelas->id }}"/>
                                    <span class="text-sm text-on-surface-variant group-hover:text-primary">{{ $kelas->nama_kelas }}</span>
                                </label>
                                @empty
                                <p class="text-xs text-red-500 italic">Anda belum memiliki kelas. Buat kelas terlebih dahulu.</p>
                                @endforelse
                            </div>
                            <p id="kelas-error" class="hidden text-[11px] text-red-500 font-bold mt-1">Pilih minimal satu kelas tujuan.</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-primary mb-2" for="urutan">Urutan Materi (Pertemuan)</label>
                            <input class="w-full bg-surface-container-low border-0 border-b-2 border-primary focus:border-secondary focus:ring-0 transition-soft px-3 py-2"
                                   id="urutan" name="urutan" min="1" placeholder="1" type="number"/>
                            <p class="text-[10px] text-on-surface-variant/60 mt-1 italic">Urutan materi membantu siswa mengikuti alur kurikulum.</p>
                            <p id="urutan-error" class="hidden text-[11px] text-red-500 font-bold mt-1">Urutan materi wajib diisi.</p>
                        </div>
                    </div>
                </div>
        </div>
    </div>
    <div class="lg:col-span-4 space-y-4">
        <div class="bg-surface-container-lowest rounded-2xl p-5 shadow-ambient border border-outline-variant/30">
            <h3 class="text-xs font-bold uppercase tracking-wider text-primary mb-3" style="font-family: var(--font-serif)">Status Publikasi</h3>
            <div class="space-y-2">
                @foreach([['terjadwal','Terjadwal','Materi dijadwalkan untuk dipublikasikan'],['published','Publikasikan','Siswa dapat langsung mengakses'],['archived','Arsip','Materi lama tidak untuk siswa']] as [$val,$label,$desc])
                <label class="relative flex items-center p-3 rounded-xl border-2 border-outline-variant/30 hover:bg-surface-container transition-soft cursor-pointer group has-[:checked]:border-secondary has-[:checked]:bg-secondary/5">
                    <input {{ $val === 'published' ? 'checked' : '' }} class="hidden peer status-radio" name="status" type="radio" value="{{ $val }}"/>
                    <div class="flex-1">
                        <p class="text-sm font-bold text-on-surface group-hover:text-secondary transition-soft">{{ $label }}</p>
                        <p class="text-[10px] text-on-surface-variant/70">{{ $desc }}</p>
                    </div>
                    <span class="material-symbols-outlined text-secondary opacity-0 peer-checked:opacity-100">check_circle</span>
                </label>
                @endforeach
            </div>
            <!-- Hidden info jadwal -->
            <input type="hidden" name="scheduled_at" id="scheduled_at_input" />
            <div id="scheduled-display" class="hidden text-[11px] text-[#835500] font-bold bg-[#835500]/10 px-2 py-1 rounded-md inline-block w-full text-center mt-2"></div>
        </div>
        <div class="flex flex-col gap-2">
            <button type="button" id="btn-trigger-simpan" class="ui-btn ui-btn-primary px-6 py-2 text-sm">
                <span class="material-symbols-outlined" style="font-size: 18px">save</span> {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Materi' }}
            </button>
            <button type="button" id="btn-trigger-batal" class="ui-btn ui-btn-secondary px-6 py-2 text-sm">
                Batal
            </button>
        </div>
            </form>
    </div>
</div>

<!-- Modal Konfirmasi Simpan -->
<div id="modal-confirm-simpan" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/50 hidden backdrop-blur-sm transition-opacity">
    <div class="ui-modal-card">
        <span class="material-symbols-outlined text-[#feae2c] text-5xl mb-4">help</span>
        <h3 class="text-xl font-bold text-[#50290b] mb-2" style="font-family: var(--font-serif)">Simpan Materi?</h3>
        <p class="text-xs text-[#51443c] mb-6">Apakah Anda yakin data materi sudah benar dan siap disimpan?</p>
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
    <span class="font-bold text-sm">Materi berhasil disimpan!</span>
</div>

<!-- Toast Batal (Popup Merah) -->
<div id="toast-batal" class="fixed top-5 left-1/2 -translate-x-1/2 z-[70] flex items-center gap-3 bg-red-100 border border-red-300 text-red-800 px-6 py-3 rounded-lg shadow-lg opacity-0 invisible transition-all duration-300 transform -translate-y-4">
    <span class="material-symbols-outlined">cancel</span>
    <span class="font-bold text-sm">Pembuatan dibatalkan!</span>
</div>

<!-- Toast Error (Popup Merah) -->
<div id="toast-error" class="fixed top-5 left-1/2 -translate-x-1/2 z-[80] flex items-center gap-3 bg-red-100 border border-red-300 text-red-800 px-6 py-3 rounded-lg shadow-lg opacity-0 invisible transition-all duration-300 transform -translate-y-4">
    <span class="material-symbols-outlined">error</span>
    <span class="font-bold text-sm" id="toast-error-msg">Mohon lengkapi semua data wajib terlebih dahulu!</span>
</div>

<!-- Modal Popup Terjadwal -->
<div id="modal-terjadwal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 hidden backdrop-blur-sm transition-opacity">
    <div class="ui-modal-card text-left">
        <h3 class="text-lg font-bold text-[#50290b] mb-2 flex items-center gap-2" style="font-family: var(--font-serif)">
            <span class="material-symbols-outlined">schedule</span> Jadwal Materi
        </h3>
        <p class="text-[11px] text-[#51443c] mb-5">Atur kapan materi ini akan aktif. Pilih waktu minimal 5 menit dari sekarang.</p>
        
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

@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropArea = document.getElementById('drop-area');
    const fileInput = document.getElementById('file-upload');
    const fileError = document.getElementById('file-error');
    
    dropArea.addEventListener('click', () => fileInput.click());
    ['dragenter','dragover','dragleave','drop'].forEach(e => dropArea.addEventListener(e, ev => { ev.preventDefault(); ev.stopPropagation(); }));
    ['dragenter','dragover'].forEach(e => dropArea.addEventListener(e, () => dropArea.classList.add('border-secondary','bg-secondary/5')));
    ['dragleave','drop'].forEach(e => dropArea.addEventListener(e, () => dropArea.classList.remove('border-secondary','bg-secondary/5')));
    
    dropArea.addEventListener('drop', e => { 
        const files = e.dataTransfer.files; 
        if(files.length) {
            if (files[0].size > 4 * 1024 * 1024) {
                fileInput.value = '';
                document.getElementById('file-preview').classList.add('hidden');
                document.getElementById('drop-prompt').classList.remove('hidden');
                const toastError = document.getElementById('toast-error');
                if (toastError) {
                    document.getElementById('toast-error-msg').innerText = 'Ukuran file tidak boleh lebih dari 4MB!';
                    toastError.classList.remove('invisible', 'opacity-0', '-translate-y-4');
                    toastError.classList.add('opacity-100', 'translate-y-0');
                    setTimeout(() => {
                        toastError.classList.remove('opacity-100', 'translate-y-0');
                        toastError.classList.add('invisible', 'opacity-0', '-translate-y-4');
                    }, 3000);
                }
                return;
            }
            fileInput.files = files;
            fileError.classList.add('hidden');
            showPreview(files[0]);
        }
    });

    fileInput.addEventListener('change', () => {
        if(fileInput.files.length > 0) {
            if (fileInput.files[0].size > 4 * 1024 * 1024) {
                fileInput.value = '';
                document.getElementById('file-preview').classList.add('hidden');
                document.getElementById('drop-prompt').classList.remove('hidden');
                const toastError = document.getElementById('toast-error');
                if (toastError) {
                    document.getElementById('toast-error-msg').innerText = 'Ukuran file tidak boleh lebih dari 4MB!';
                    toastError.classList.remove('invisible', 'opacity-0', '-translate-y-4');
                    toastError.classList.add('opacity-100', 'translate-y-0');
                    setTimeout(() => {
                        toastError.classList.remove('opacity-100', 'translate-y-0');
                        toastError.classList.add('invisible', 'opacity-0', '-translate-y-4');
                    }, 3000);
                }
                return;
            }
            fileError.classList.add('hidden');
            showPreview(fileInput.files[0]);
        }
    });

    function showPreview(file) {
        document.getElementById('drop-prompt').classList.add('hidden');
        document.getElementById('file-preview').classList.remove('hidden');
        
        document.getElementById('preview-name').innerText = file.name;
        document.getElementById('preview-size').innerText = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
        
        const previewContainer = document.getElementById('preview-container');
        const fileType = file.type;
        const fileUrl = URL.createObjectURL(file);
        
        let htmlContent = '';
        if (fileType.startsWith('image/')) {
            htmlContent = `<img src="${fileUrl}" alt="Preview" class="w-full max-h-64 object-contain rounded-lg border border-outline-variant/30 shadow-sm mx-auto">`;
        } else if (fileType === 'application/pdf') {
            htmlContent = `<iframe src="${fileUrl}#toolbar=0" class="w-full h-80 rounded-lg border border-outline-variant/30 shadow-sm"></iframe>`;
        } else {
            let icon = 'description';
            if (file.name.toLowerCase().endsWith('.ppt') || file.name.toLowerCase().endsWith('.pptx')) icon = 'slideshow';
            else if (file.name.toLowerCase().endsWith('.xls') || file.name.toLowerCase().endsWith('.xlsx')) icon = 'table_view';
            else if (file.name.toLowerCase().endsWith('.zip') || file.name.toLowerCase().endsWith('.rar')) icon = 'folder_zip';
            
            htmlContent = `
            <div class="flex flex-col items-center justify-center p-6 bg-surface rounded-lg border border-outline-variant shadow-sm w-full mx-auto">
                <div class="p-3 bg-primary/10 text-primary rounded-full mb-3 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[36px]">${icon}</span>
                </div>
                <p class="font-bold text-sm text-on-surface truncate max-w-[80%]">${file.name}</p>
                <p class="text-[11px] text-on-surface-variant">Dokumen terlampir</p>
            </div>`;
        }
        
        previewContainer.innerHTML = htmlContent;
    }

    // Validasi input
    const judulInput = document.getElementById('judul');
    const judulError = document.getElementById('judul-error');
    const kelasCheckboxes = document.querySelectorAll('.kelas-checkbox');
    const kelasError = document.getElementById('kelas-error');
    
    judulInput.addEventListener('input', () => {
        if (judulInput.value.trim()) {
            judulError.classList.add('hidden');
            judulInput.classList.remove('border-red-500');
            judulInput.classList.add('border-primary');
        }
    });
    
    kelasCheckboxes.forEach(cb => {
        cb.addEventListener('change', () => {
            const anyChecked = Array.from(kelasCheckboxes).some(c => c.checked);
            if (anyChecked) {
                kelasError.classList.add('hidden');
            }
        });
    });

    function validateForm() {
        let isValid = true;
        let errorMsg = "Mohon lengkapi semua data wajib terlebih dahulu!";
        
        if (!judulInput.value.trim()) {
            judulError.classList.remove('hidden');
            judulInput.classList.add('border-red-500');
            judulInput.classList.remove('border-primary');
            isValid = false;
        }
        
        if (fileInput.files.length > 0) {
            if (fileInput.files[0].size > 4 * 1024 * 1024) {
                fileError.innerText = "Ukuran file tidak boleh lebih dari 4MB.";
                fileError.classList.remove('hidden');
                isValid = false;
                errorMsg = "Ukuran file tidak boleh lebih dari 4MB!";
            }
        }

        const anyChecked = Array.from(kelasCheckboxes).some(c => c.checked);
        if (!anyChecked) {
            kelasError.classList.remove('hidden');
            isValid = false;
        }

        if (!isValid) {
            const toastError = document.getElementById('toast-error');
            document.getElementById('toast-error-msg').innerText = errorMsg;
            toastError.classList.remove('invisible', 'opacity-0', '-translate-y-4');
            toastError.classList.add('opacity-100', 'translate-y-0');
            setTimeout(() => {
                toastError.classList.remove('opacity-100', 'translate-y-0');
                toastError.classList.add('invisible', 'opacity-0', '-translate-y-4');
            }, 3000);
        }

        return isValid;
    }

    // --- STATUS PUBLIKASI & JADWAL ---
    const statusRadios = document.querySelectorAll('.status-radio');
    const modalTerjadwal = document.getElementById('modal-terjadwal');
    const datetimeInput = document.getElementById('modal-datetime-input');
    const btnCancelTerjadwal = document.getElementById('btn-cancel-terjadwal');
    const btnSaveTerjadwal = document.getElementById('btn-save-terjadwal');
    const hiddenScheduledInput = document.getElementById('scheduled_at_input');
    const scheduledDisplay = document.getElementById('scheduled-display');
    const errorMessage = document.getElementById('modal-error');
    
    let previousStatus = 'published';

    function getMinDateTime() {
        const now = new Date();
        now.setMinutes(now.getMinutes() + 5);
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        return now.toISOString().slice(0, 16);
    }

    function showModalTerjadwal() {
        const minDateTime = getMinDateTime();
        datetimeInput.min = minDateTime;
        if (!datetimeInput.value || datetimeInput.value < minDateTime) {
            datetimeInput.value = minDateTime;
        }
        modalTerjadwal.classList.remove('hidden');
    }

    function hideModalTerjadwal() {
        modalTerjadwal.classList.add('hidden');
        errorMessage.classList.add('hidden');
    }

    statusRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'terjadwal') {
                showModalTerjadwal();
            } else {
                previousStatus = this.value;
                hiddenScheduledInput.value = '';
                scheduledDisplay.classList.add('hidden');
            }
        });
    });

    btnCancelTerjadwal.addEventListener('click', function() {
        hideModalTerjadwal();
        const prevRadio = document.querySelector(`.status-radio[value="${previousStatus}"]`);
        if (prevRadio) prevRadio.checked = true;
    });

    btnSaveTerjadwal.addEventListener('click', function() {
        const selectedTime = new Date(datetimeInput.value).getTime();
        const minTime = new Date(getMinDateTime()).getTime();
        
        if (selectedTime < minTime) {
            errorMessage.classList.remove('hidden');
            return;
        }
        
        errorMessage.classList.add('hidden');
        hiddenScheduledInput.value = datetimeInput.value;
        previousStatus = 'terjadwal';
        hideModalTerjadwal();
        
        const dateObj = new Date(datetimeInput.value);
        scheduledDisplay.textContent = 'Jadwal Materi: ' + dateObj.toLocaleString('id-ID', {
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

    btnTriggerSimpan.addEventListener('click', () => {
        if (!validateForm()) return;
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
            window.location.href = "{{ route('guru.materi') }}";
        }, 1500);
    });

    btnConfirmSimpan.addEventListener('click', () => {
        console.log('confirm simpan clicked');
        modalConfirmSimpan.classList.add('hidden');
        console.log('akan submit form');
        setTimeout(() => {
            console.log('submit!');
            document.getElementById('form-materi').submit();
        }, 1500);
    });
});
</script>
@endpush
