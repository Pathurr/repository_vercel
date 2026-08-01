@extends('layouts.guru')
@php
$isEdit = request('mode') === 'edit';
$selectedKelas = array_filter(explode('|', request('kelas', '')));
@endphp
@section('title', ($isEdit ? 'Edit Tugas - SMK Mandalahayu 1' : 'Buat Tugas Baru - SMK Mandalahayu 1'))
@section('content')
<div class="bg-surface bg-batik">
    <div class="max-w-[1200px] mx-auto min-h-[calc(100vh-100px)] py-4">
        <header class="mb-6">
            <div class="mb-4">
                <a href="{{ route('guru.tugas') }}" class="inline-flex items-center gap-2 text-sm text-secondary hover:text-primary transition-colors font-bold">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span> Kembali ke Daftar Tugas
                </a>
            </div>
            <h1 class="font-bold text-3xl text-primary mb-1" style="font-family: var(--font-serif)">{{ $isEdit ? 'Edit Tugas' : 'Buat Tugas Baru' }}</h1>
            <p class="text-sm text-on-surface-variant max-w-2xl">
                {{ $isEdit ? 'Perbarui detail tugas yang sudah ada. Pastikan semua informasi sudah benar.' : 'Rancang tugas yang menantang dan mendidik untuk siswa Anda. Isi detail di bawah ini untuk memulai.' }}
            </p>
        </header>

        <form id="form-buat-tugas" method="POST" action="{{ $isEdit && isset($tugas) ? route('guru.tugas.update', $tugas->id) : route('guru.tugas.store') }}" enctype="multipart/form-data" class="grid grid-cols-12 gap-5">
            @csrf
            @if($isEdit && isset($tugas))
                @method('PUT')
            @endif
            <div class="col-span-12 lg:col-span-8 space-y-4">
                <div class="bg-surface-container-lowest p-5 rounded-xl shadow-ambient border border-outline-variant/30">
                    <div class="flex items-center gap-2 mb-4">
                        <h2 class="text-xl font-bold text-primary" style="font-family: var(--font-serif)">Dasar &amp; Konten</h2>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-on-surface mb-1">Judul Tugas</label>
                            <input id="judul-tugas" name="judul" class="w-full bg-surface-container-low border-0 border-b-2 border-primary focus:ring-0 focus:border-secondary-container transition-all py-2 px-3 text-sm" placeholder="Contoh: Analisis Rangkaian Listrik AC/DC" type="text" value="{{ old('judul', $tugas->judul ?? '') }}"/>
                            <p id="judul-tugas-error" class="hidden text-[11px] text-red-500 font-bold mt-1">Judul tugas wajib diisi.</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-on-surface mb-1">Deskripsi &amp; Instruksi</label>
                            <textarea id="deskripsi-tugas" name="deskripsi" class="w-full bg-surface-container-low border-0 border-b-2 border-primary focus:ring-0 focus:border-secondary-container transition-all py-2 px-3 text-sm" placeholder="Tuliskan instruksi lengkap untuk siswa..." rows="3">{{ old('deskripsi', $tugas->deskripsi ?? '') }}</textarea>
                            <p id="deskripsi-tugas-error" class="hidden text-[11px] text-red-500 font-bold mt-1">Deskripsi tugas wajib diisi.</p>
                        </div>
                        <div class="mt-2">
                            <label class="block text-xs font-bold text-on-surface mb-2">Lampiran File (Opsional) - Gambar/PDF/Word/PPT</label>
                            <div class="relative">
                                <input type="file" name="file_path" id="file_path" accept=".pdf,.doc,.docx,.ppt,.pptx,image/*" class="hidden" onchange="previewFile(this)">
                                <label for="file_path" class="cursor-pointer flex flex-col items-center justify-center gap-1 w-full border-2 border-dashed border-outline-variant hover:border-primary bg-surface hover:bg-surface-container transition-all py-4 rounded-xl text-sm text-on-surface-variant group">
                                    <span class="material-symbols-outlined text-[28px] group-hover:text-primary transition-colors">cloud_upload</span>
                                    <span id="file-name-display" class="font-bold text-on-surface group-hover:text-primary transition-colors text-center px-4 truncate w-full">
                                        @if(isset($tugas) && $tugas->file_path)
                                            Ganti File Lampiran (Opsional)
                                        @else
                                            Klik untuk memilih file dari perangkat
                                        @endif
                                    </span>
                                    @if(isset($tugas) && $tugas->file_path)
                                        <span class="text-[10px] text-on-surface-variant">Biarkan kosong jika ingin menggunakan file lama</span>
                                    @endif
                                    <p class="text-[10px] text-red-500 font-bold mt-1">Maksimal ukuran file 4MB</p>
                                </label>
                                <p id="file-error" class="hidden text-red-500 text-[11px] font-bold mt-2">Ukuran file maksimal 4MB.</p>
                            </div>
                            <div class="mt-3 hidden image-preview-container">
                                <!-- Preview HTML akan dirender di sini -->
                            </div>
                        </div>
                        <div class="relative">
                            <label class="block text-xs font-bold text-on-surface mb-1">Pilih Kelas</label>
                            
                            <button type="button" id="btn-dropdown-kelas" class="w-full flex items-center justify-between bg-surface-container-low border-0 border-b-2 border-primary py-2 px-3 text-sm text-left hover:bg-surface-container transition-colors">
                                <span id="dropdown-kelas-text" class="text-on-surface truncate">Pilih kelas...</span>
                                <span class="material-symbols-outlined text-on-surface-variant transition-soft" id="dropdown-kelas-icon">expand_more</span>
                            </button>

                            <div id="dropdown-kelas-menu" class="hidden absolute z-10 w-full mt-1 bg-surface border border-outline-variant/30 rounded-lg shadow-lg">
                                <div class="p-2 max-h-48 overflow-y-auto space-y-1">
                                    @forelse($kelases as $kelas)
                                    <label class="flex items-center gap-2 p-2 hover:bg-surface-container rounded-md cursor-pointer transition-colors">
                                        <input class="kelas-checkbox text-secondary rounded focus:ring-secondary-container" name="kelas_id[]" type="checkbox" value="{{ $kelas->id }}" data-name="{{ $kelas->nama_kelas }}" {{ (isset($tugas) && $tugas->kelas_id == $kelas->id) ? 'checked' : '' }}/>
                                        <span class="text-sm text-on-surface">{{ $kelas->nama_kelas }} ({{ $kelas->mata_pelajaran }})</span>
                                    </label>
                                    @empty
                                    <p class="text-xs text-red-500 italic p-2">Anda belum memiliki kelas.</p>
                                    @endforelse
                                </div>
                            </div>
                            <p id="kelas-error" class="hidden text-[11px] text-red-500 font-bold mt-2">Pilih minimal satu kelas.</p>
                        </div>
                    </div>
                </div>

                <div class="bg-surface-container-lowest p-5 rounded-xl shadow-ambient border border-outline-variant/30">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="material-symbols-outlined text-secondary text-xl" style="font-variation-settings: 'FILL' 1;">event_available</span>
                        <h2 class="text-xl font-bold text-primary" style="font-family: var(--font-serif)">Pengaturan Waktu &amp; Nilai</h2>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block text-xs font-bold text-on-surface mb-1">Batas Akhir (Deadline)</label>
                            <input id="deadline" name="deadline" class="w-full bg-surface-container-low border-0 border-b-2 border-primary focus:ring-0 focus:border-secondary-container py-2 px-3 text-sm" type="datetime-local" value="{{ old('deadline', isset($tugas->deadline) ? $tugas->deadline->format('Y-m-d\TH:i') : '') }}"/>
                            <p id="deadline-error" class="hidden text-[11px] text-red-500 font-bold mt-1">Deadline wajib diisi.</p>
                        </div>
                    </div>
                </div>

                <div class="bg-surface-container-lowest p-5 rounded-xl shadow-ambient border border-outline-variant/30">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="material-symbols-outlined text-secondary text-xl" style="font-variation-settings: 'FILL' 1;">rule</span>
                        <h2 class="text-xl font-bold text-primary" style="font-family: var(--font-serif)">Aturan &amp; Format</h2>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-on-surface mb-2">Tipe Tugas</label>
                            <input type="hidden" name="tipe_tugas" id="tipe-tugas-input" value="individual">
                            <div class="flex gap-3">
                                <button id="btn-tipe-individual" class="px-4 py-1.5 rounded-full border-2 border-secondary bg-secondary/10 text-secondary text-sm font-bold hover:bg-secondary/10 transition-colors" type="button">Individual</button>
                                <button id="btn-tipe-kelompok" class="px-4 py-1.5 rounded-full border-2 border-outline-variant text-on-surface-variant text-sm font-bold hover:border-secondary hover:text-secondary transition-colors inline-block" type="button">Kelompok</button>
                            </div>
                            <p id="tipe-tugas-error" class="hidden text-[11px] text-red-500 font-bold mt-2">Pilih tipe tugas terlebih dahulu.</p>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-surface-container-low rounded-lg">
                            <div>
                                <p class="font-bold text-sm text-on-surface">Terima Pengiriman Terlambat</p>
                                <p class="text-[11px] text-on-surface-variant">Siswa masih bisa mengirim setelah deadline dengan tanda.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input checked class="sr-only peer" type="checkbox"/>
                                <div class="w-9 h-5 bg-outline-variant peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-secondary"></div>
                            </label>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-on-surface mb-2">Format Pengumpulan</label>
                            <div class="flex flex-wrap gap-2">
                                @php
                                    $formats = isset($tugas) && is_array($tugas->format_pengumpulan) ? $tugas->format_pengumpulan : ['pdf', 'gambar'];
                                @endphp
                                <label class="format-pengumpulan-label flex items-center gap-1.5 px-3 py-1.5 bg-surface {{ in_array('pdf', $formats) ? 'border-secondary bg-secondary/5' : 'border-outline-variant' }} border-2 rounded-lg cursor-pointer transition-all">
                                    <input {{ in_array('pdf', $formats) ? 'checked' : '' }} class="text-secondary rounded w-3.5 h-3.5" name="format_pengumpulan[]" value="pdf" type="checkbox"/>
                                    <span class="font-bold {{ in_array('pdf', $formats) ? 'text-secondary' : 'text-on-surface-variant' }} text-xs">PDF</span>
                                </label>
                                <label class="format-pengumpulan-label flex items-center gap-1.5 px-3 py-1.5 bg-surface {{ in_array('link', $formats) ? 'border-secondary bg-secondary/5' : 'border-outline-variant' }} border-2 rounded-lg cursor-pointer transition-all">
                                    <input {{ in_array('link', $formats) ? 'checked' : '' }} class="text-secondary rounded w-3.5 h-3.5" name="format_pengumpulan[]" value="link" type="checkbox"/>
                                    <span class="font-bold {{ in_array('link', $formats) ? 'text-secondary' : 'text-on-surface-variant' }} text-xs">Link/Tautan</span>
                                </label>
                                <label class="format-pengumpulan-label flex items-center gap-1.5 px-3 py-1.5 bg-surface {{ in_array('gambar', $formats) ? 'border-secondary bg-secondary/5' : 'border-outline-variant' }} border-2 rounded-lg cursor-pointer transition-all">
                                    <input {{ in_array('gambar', $formats) ? 'checked' : '' }} class="text-secondary rounded w-3.5 h-3.5" name="format_pengumpulan[]" value="gambar" type="checkbox"/>
                                    <span class="font-bold {{ in_array('gambar', $formats) ? 'text-secondary' : 'text-on-surface-variant' }} text-xs">Gambar</span>
                                </label>
                                <label class="format-pengumpulan-label flex items-center gap-1.5 px-3 py-1.5 bg-surface {{ in_array('dokumen', $formats) ? 'border-secondary bg-secondary/5' : 'border-outline-variant' }} border-2 rounded-lg cursor-pointer transition-all">
                                    <input {{ in_array('dokumen', $formats) ? 'checked' : '' }} class="text-secondary rounded w-3.5 h-3.5" name="format_pengumpulan[]" value="dokumen" type="checkbox"/>
                                    <span class="font-bold {{ in_array('dokumen', $formats) ? 'text-secondary' : 'text-on-surface-variant' }} text-xs">Dokumen (.doc)</span>
                                </label>
                            </div>
                            <p id="format-pengumpulan-error" class="hidden text-[11px] text-red-500 font-bold mt-2">Pilih minimal satu format pengumpulan.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-span-12 lg:col-span-4">
                <div class="sticky top-20 space-y-5">
                    <div class="bg-surface-container-lowest p-5 rounded-xl shadow-ambient border border-outline-variant/30">
                        <h3 class="text-base font-bold text-primary mb-3" style="font-family: var(--font-serif)">Status Publikasi</h3>
                        <div class="space-y-2">
                            <label class="status-publikasi-label flex items-center justify-between p-2.5 border-2 border-secondary bg-secondary/5 rounded-lg cursor-pointer transition-all">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined icon text-secondary text-[20px]">publish</span>
                                    <div>
                                        <p class="title font-bold text-sm text-secondary">Terbitkan (Publish)</p>
                                        <p class="subtitle text-[11px] text-secondary/70">Segera aktif di portal siswa.</p>
                                    </div>
                                </div>
                                <input checked class="text-secondary focus:ring-secondary w-4 h-4" name="status" id="status-publish" value="publish" type="radio"/>
                            </label>
                            <label class="status-publikasi-label flex items-center justify-between p-2.5 border-2 border-transparent hover:border-outline-variant rounded-lg cursor-pointer transition-all">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined icon text-on-surface-variant text-[20px]">schedule</span>
                                    <div>
                                        <p class="title font-bold text-sm text-on-surface">Terjadwal</p>
                                        <p class="subtitle text-[11px] text-on-surface-variant/70">Terbit otomatis sesuai waktu.</p>
                                    </div>
                                </div>
                                <input class="text-secondary focus:ring-secondary w-4 h-4" name="status" id="status-terjadwal" value="terjadwal" type="radio"/>
                            </label>
                            <!-- Hidden info jadwal -->
                            <input type="hidden" name="scheduled_at" id="scheduled_at_input" />
                            <div id="scheduled-display" class="hidden text-[11px] text-secondary font-bold bg-secondary/10 px-2 py-1 rounded-md inline-block w-full text-center mt-1"></div>
                        </div>
                    </div>

                    <div class="bg-primary-container p-4 rounded-xl text-on-primary-container relative overflow-hidden">
                        <span class="material-symbols-outlined absolute -right-3 -bottom-3 text-7xl opacity-10">lightbulb</span>
                        <h4 class="font-bold text-sm mb-1.5 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-lg">tips_and_updates</span>
                            Tips Guru
                        </h4>
                        <p class="text-[11px] italic leading-relaxed">"Sertakan kriteria penilaian (rubrik) di bagian deskripsi agar siswa memahami standar kualitas yang diharapkan."</p>
                    </div>

                    <div class="flex flex-col gap-2 pt-2">
                        <button type="button" id="btn-trigger-simpan" class="ui-btn ui-btn-primary px-6 py-2 text-sm w-full">
                            <span class="material-symbols-outlined" style="font-size: 18px">save</span> {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Tugas' }}
                        </button>
                        <button type="button" id="btn-trigger-batal" class="ui-btn ui-btn-secondary px-6 py-2 text-sm w-full mt-3">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Konfirmasi Simpan -->
<div id="modal-confirm-simpan" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/50 hidden backdrop-blur-sm transition-opacity">
    <div class="ui-modal-card">
        <span class="material-symbols-outlined text-[#feae2c] text-5xl mb-4">help</span>
        <h3 class="text-xl font-bold text-[#50290b] mb-2" style="font-family: var(--font-serif)">Simpan Tugas?</h3>
        <p class="text-xs text-[#51443c] mb-6">Apakah Anda yakin data tugas sudah benar dan siap disimpan?</p>
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
    <span class="font-bold text-sm">Tugas berhasil disimpan!</span>
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
    <div class="bg-surface-container-lowest rounded-xl shadow-2xl p-6 w-full max-w-sm border border-outline-variant/30">
        <h3 class="text-lg font-bold text-primary mb-2 flex items-center gap-2" style="font-family: var(--font-serif)">
            <span class="material-symbols-outlined">schedule</span> Jadwal Publikasi
        </h3>
        <p class="text-[11px] text-on-surface-variant mb-5">Atur kapan tugas ini akan diterbitkan. Pilih waktu minimal 5 menit dari sekarang.</p>
        
        <div class="mb-5">
            <label class="block text-xs font-bold text-on-surface mb-2">Pilih Tanggal & Waktu</label>
            <input id="modal-datetime-input" type="datetime-local" class="w-full bg-surface-container-low border-0 border-b-2 border-primary focus:ring-0 focus:border-secondary-container transition-all py-2 px-3 text-sm" />
            <p id="modal-error" class="hidden text-error text-[11px] mt-2 font-bold max-w-full text-red-500">Waktu yang dipilih kurang dari 5 menit dari sekarang!</p>
        </div>
        
        <div class="flex gap-2 justify-end">
            <button type="button" id="btn-cancel-terjadwal" class="px-4 py-2 rounded-lg font-bold text-xs text-on-surface border border-outline-variant hover:bg-surface-container transition-colors">Batal</button>
            <button type="button" id="btn-save-terjadwal" class="px-4 py-2 rounded-lg font-bold text-xs bg-secondary-container text-on-secondary-container hover:shadow-md transition-all">Simpan Jadwal</button>
        </div>
    </div>
</div>

<script>
    function previewFile(input) {
        const previewContainer = document.querySelector('.image-preview-container');
        const fileNameDisplay = document.getElementById('file-name-display');
        
        if (input.files && input.files[0]) {
            const file = input.files[0];

            if (file.size > 4 * 1024 * 1024) {
                input.value = '';
                previewContainer.innerHTML = '';
                previewContainer.classList.add('hidden');
                if (fileNameDisplay) {
                    fileNameDisplay.textContent = 'Pilih File Lampiran';
                    fileNameDisplay.classList.remove('text-primary');
                }
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

            const fileType = file.type;
            const fileName = file.name;
            const fileUrl = URL.createObjectURL(file);
            
            if (fileNameDisplay) {
                fileNameDisplay.textContent = fileName;
                fileNameDisplay.classList.add('text-primary');
            }
            
            let htmlContent = '';
            if (fileType.startsWith('image/')) {
                htmlContent = `<img src="${fileUrl}" alt="Preview" class="max-h-64 rounded-lg border border-outline-variant/30 shadow-sm mx-auto">`;
            } else if (fileType === 'application/pdf') {
                htmlContent = `<iframe src="${fileUrl}#toolbar=0" class="w-full h-96 rounded-lg border border-outline-variant/30 shadow-sm"></iframe>`;
            } else {
                // Document (Word, PPT, Excel, dll)
                let icon = 'description';
                if (fileName.endsWith('.ppt') || fileName.endsWith('.pptx')) icon = 'slideshow';
                else if (fileName.endsWith('.xls') || fileName.endsWith('.xlsx')) icon = 'table_view';
                else if (fileName.endsWith('.zip') || fileName.endsWith('.rar')) icon = 'folder_zip';
                
                htmlContent = `
                <div class="flex items-center gap-3 p-3 bg-surface rounded-lg border border-outline-variant shadow-sm w-full md:w-1/2">
                    <div class="p-2 bg-primary/10 text-primary rounded-md flex items-center justify-center">
                        <span class="material-symbols-outlined text-[24px]">${icon}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-sm text-on-surface truncate">${fileName}</p>
                        <p class="text-[10px] text-on-surface-variant">Dokumen terlampir</p>
                    </div>
                </div>`;
            }
            previewContainer.innerHTML = htmlContent;
            previewContainer.classList.remove('hidden');
        } else {
            previewContainer.innerHTML = '';
            previewContainer.classList.add('hidden');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        try {
    // --- STATUS PUBLIKASI ---
    const radioPublish = document.getElementById('status-publish');
    const radioTerjadwal = document.getElementById('status-terjadwal');
    const modal = document.getElementById('modal-terjadwal');
    const datetimeInput = document.getElementById('modal-datetime-input');
    const btnCancel = document.getElementById('btn-cancel-terjadwal');
    const btnSave = document.getElementById('btn-save-terjadwal');
    const hiddenScheduledInput = document.getElementById('scheduled_at_input');
    const scheduledDisplay = document.getElementById('scheduled-display');
    const errorMessage = document.getElementById('modal-error');
    const radioPublikasis = document.querySelectorAll('input[name="status"]');
    
    let previousStatus = 'publish';

    // Logika active styling untuk Status Publikasi
    function updateStatusPublikasiStyle() {
        document.querySelectorAll('.status-publikasi-label').forEach(label => {
            const radio = label.querySelector('input[type="radio"]');
            const icon = label.querySelector('.icon');
            const title = label.querySelector('.title');
            const subtitle = label.querySelector('.subtitle');

            if (radio.checked) {
                // Style Active
                // Gunakan class khusus alih-alih me-replace padding dan border secara statis untuk menghindari layout shift
                label.className = "status-publikasi-label flex items-center justify-between p-2.5 border-2 border-secondary bg-secondary/5 rounded-lg cursor-pointer transition-all";
                icon.className = "material-symbols-outlined icon text-secondary text-[20px]";
                title.className = "title font-bold text-sm text-secondary";
                subtitle.className = "subtitle text-[11px] text-secondary/70";
            } else {
                // Style Inactive
                // Pastikan border tetap transparan sebagai fallback atau konsisten berukuran sama agar tidak lompat
                label.className = "status-publikasi-label flex items-center justify-between p-2.5 border-2 border-transparent hover:border-outline-variant rounded-lg cursor-pointer transition-all";
                icon.className = "material-symbols-outlined icon text-on-surface-variant text-[20px]";
                title.className = "title font-bold text-sm text-on-surface";
                subtitle.className = "subtitle text-[11px] text-on-surface-variant/70";
            }
        });
    }

    radioPublikasis.forEach(radio => {
        radio.addEventListener('change', updateStatusPublikasiStyle);
    });

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

    radioPublish.addEventListener('change', function() {
        if (this.checked) {
            previousStatus = this.value;
            hiddenScheduledInput.value = '';
            scheduledDisplay.classList.add('hidden');
        }
    });

    radioTerjadwal.addEventListener('change', function() {
        if (this.checked) {
            showModal();
        }
    });

    btnCancel.addEventListener('click', function() {
        hideModal();
        if (previousStatus === 'publish') radioPublish.checked = true;
        else radioTerjadwal.checked = true;
        updateStatusPublikasiStyle();
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
        scheduledDisplay.textContent = 'Jadwal Publikasi: ' + dateObj.toLocaleString('id-ID', {
            day: 'numeric', month: 'short', year: 'numeric', 
            hour: '2-digit', minute: '2-digit'
        });
        scheduledDisplay.classList.remove('hidden');
        updateStatusPublikasiStyle();
    });

    // --- TIPE TUGAS ---
    const btnIndividual = document.getElementById('btn-tipe-individual');
    const btnKelompok = document.getElementById('btn-tipe-kelompok');
    const tipeTugasInput = document.getElementById('tipe-tugas-input');

    function setActiveTipeTugas(tipe) {
        tipeTugasInput.value = tipe;
        if (tipe === 'individual') {
            btnIndividual.className = "px-4 py-1.5 rounded-full border-2 border-secondary bg-secondary/10 text-secondary text-sm font-bold transition-colors inline-block";
            btnKelompok.className = "px-4 py-1.5 rounded-full border-2 border-outline-variant text-on-surface-variant text-sm font-bold hover:border-secondary hover:text-secondary transition-colors inline-block";
        } else {
            btnKelompok.className = "px-4 py-1.5 rounded-full border-2 border-secondary bg-secondary/10 text-secondary text-sm font-bold transition-colors inline-block";
            btnIndividual.className = "px-4 py-1.5 rounded-full border-2 border-outline-variant text-on-surface-variant text-sm font-bold hover:border-secondary hover:text-secondary transition-colors inline-block";
        }
        hideError(tipeTugasError);
    }

    btnIndividual.addEventListener('click', () => setActiveTipeTugas('individual'));
    btnKelompok.addEventListener('click', () => setActiveTipeTugas('kelompok'));

    // --- FORMAT PENGUMPULAN ---
    const formatCheckboxes = document.querySelectorAll('.format-pengumpulan-label input[type="checkbox"]');
    
    function updateFormatPengumpulanStyle(checkbox) {
        const label = checkbox.closest('label');
        const span = label.querySelector('span');
        
        if (checkbox.checked) {
            label.classList.remove('border-outline-variant');
            label.classList.add('border-secondary', 'bg-secondary/5');
            span.classList.remove('text-on-surface-variant');
            span.classList.add('text-secondary');
        } else {
            label.classList.remove('border-secondary', 'bg-secondary/5');
            label.classList.add('border-outline-variant');
            span.classList.remove('text-secondary');
            span.classList.add('text-on-surface-variant');
        }
    }

    formatCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateFormatPengumpulanStyle(this);
            if (Array.from(formatCheckboxes).some(cb => cb.checked)) {
                hideError(formatPengumpulanError);
            }
        });
    });
// --- DROPDOWN KELAS ---
    const btnDropdownKelas = document.getElementById('btn-dropdown-kelas');
    const dropdownKelasMenu = document.getElementById('dropdown-kelas-menu');
    const dropdownKelasIcon = document.getElementById('dropdown-kelas-icon');
    const dropdownKelasText = document.getElementById('dropdown-kelas-text');
    const kelasCheckboxes = document.querySelectorAll('.kelas-checkbox');

    // Toggle dropdown UI
    function toggleDropdown(e) {
        e.preventDefault();
        const isHidden = dropdownKelasMenu.classList.contains('hidden');
        if (!isHidden) {
            dropdownKelasMenu.classList.add('hidden');
            dropdownKelasIcon.classList.remove('rotate-180');
        } else {
            dropdownKelasMenu.classList.remove('hidden');
            dropdownKelasIcon.classList.add('rotate-180');
        }
    }

    btnDropdownKelas.addEventListener('click', toggleDropdown);

    // Menutup dropdown jika user mengklik area luar
    document.addEventListener('click', (e) => {
        if (!btnDropdownKelas.contains(e.target) && !dropdownKelasMenu.contains(e.target)) {
            dropdownKelasMenu.classList.add('hidden');
            dropdownKelasIcon.classList.remove('rotate-180');
        }
    });

    function updateKelasText() {
        if (kelasCheckboxes.length === 0) {
            dropdownKelasText.textContent = "Anda belum memiliki kelas.";
            dropdownKelasText.classList.remove('text-primary', 'font-bold');
            dropdownKelasText.classList.add('text-red-500');
            return;
        }
        const checkedBoxes = Array.from(kelasCheckboxes).filter(cb => cb.checked);
        if (checkedBoxes.length === 0) {
            dropdownKelasText.textContent = "Pilih kelas...";
            dropdownKelasText.classList.remove('text-primary', 'font-bold');
        } else if (checkedBoxes.length === 1) {
            dropdownKelasText.textContent = checkedBoxes[0].getAttribute('data-name');
            dropdownKelasText.classList.add('text-primary', 'font-bold');
        } else {
            dropdownKelasText.textContent = `${checkedBoxes.length} Kelas Dipilih`;
            dropdownKelasText.classList.add('text-primary', 'font-bold');
        }

        if (checkedBoxes.length > 0) {
            hideError(kelasError);
            btnDropdownKelas.classList.remove('border-red-500');
            btnDropdownKelas.classList.add('border-primary');
        }
    }

    kelasCheckboxes.forEach(cb => {
        cb.addEventListener('change', updateKelasText);
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

    // --- VALIDASI FORM ---
    const judulInput = document.getElementById('judul-tugas');
    const deskripsiInput = document.getElementById('deskripsi-tugas');
    const deadlineInput = document.getElementById('deadline');
    const judulError = document.getElementById('judul-tugas-error');
    const deskripsiError = document.getElementById('deskripsi-tugas-error');
    const deadlineError = document.getElementById('deadline-error');
    const kelasError = document.getElementById('kelas-error');
    const formatPengumpulanError = document.getElementById('format-pengumpulan-error');
    const tipeTugasError = document.getElementById('tipe-tugas-error');
    const tipeTugasInput = document.getElementById('tipe-tugas-input');
    const formatCheckboxes = document.querySelectorAll('input[name="format_pengumpulan[]"]');

    updateKelasText();

    function showError(errorEl, message) {
        errorEl.textContent = message;
        errorEl.classList.remove('hidden');
    }

    function hideError(errorEl) {
        errorEl.classList.add('hidden');
    }

    function setInputError(input, errorEl, message) {
        showError(errorEl, message);
        input.classList.add('border-red-500');
        input.classList.remove('border-primary');
    }

    function clearInputError(input, errorEl) {
        hideError(errorEl);
        input.classList.remove('border-red-500');
        input.classList.add('border-primary');
    }

    function getTodayDateString() {
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    // Pastikan deadline tidak bisa memilih tanggal sebelum hari ini
    deadlineInput.min = getTodayDateString();

    function validateForm() {
        let isValid = true;

        const requiredInputs = [
            { input: judulInput, error: judulError, message: 'Judul tugas wajib diisi.' },
            { input: deskripsiInput, error: deskripsiError, message: 'Deskripsi tugas wajib diisi.' },
            { input: deadlineInput, error: deadlineError, message: 'Deadline wajib diisi.' }
        ];

        requiredInputs.forEach(item => {
            const value = item.input.value.trim();
            if (!value) {
                setInputError(item.input, item.error, item.message);
                isValid = false;
            } else {
                clearInputError(item.input, item.error);
            }
        });

        // Validasi deadline tidak boleh sebelum hari ini
        if (deadlineInput.value) {
            const selectedDate = new Date(deadlineInput.value);
            const today = new Date(getTodayDateString());
            if (selectedDate < today) {
                setInputError(deadlineInput, deadlineError, 'Deadline tidak boleh sebelum hari ini.');
                isValid = false;
            }
        }



        // Validasi kelas
        const kelasChecked = Array.from(kelasCheckboxes).some(cb => cb.checked);
        if (!kelasChecked) {
            showError(kelasError, 'Pilih minimal satu kelas.');
            btnDropdownKelas.classList.add('border-red-500');
            btnDropdownKelas.classList.remove('border-primary');
            isValid = false;
        } else {
            hideError(kelasError);
            btnDropdownKelas.classList.remove('border-red-500');
            btnDropdownKelas.classList.add('border-primary');
        }

        // Validasi tipe tugas
        if (!tipeTugasInput.value) {
            showError(tipeTugasError, 'Pilih tipe tugas terlebih dahulu.');
            isValid = false;
        } else {
            hideError(tipeTugasError);
        }

        // Validasi format pengumpulan
        const formatChecked = Array.from(formatCheckboxes).some(cb => cb.checked);
        if (!formatChecked) {
            showError(formatPengumpulanError, 'Pilih minimal satu format pengumpulan.');
            isValid = false;
        } else {
            hideError(formatPengumpulanError);
        }

        // Validasi ukuran file
        const fileInput = document.getElementById('file_path');
        const fileError = document.getElementById('file-error');
        let errorMsg = 'Mohon lengkapi semua data wajib terlebih dahulu!';
        
        if (fileInput.files.length > 0) {
            if (fileInput.files[0].size > 4 * 1024 * 1024) {
                fileError.classList.remove('hidden');
                isValid = false;
                errorMsg = 'Ukuran file tidak boleh lebih dari 4MB!';
            } else {
                fileError.classList.add('hidden');
            }
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

    [judulInput, deskripsiInput, deadlineInput].forEach(input => {
        input.addEventListener('input', () => {
            if (input.value.trim()) {
                const errorMap = {
                    'judul-tugas': judulError,
                    'deskripsi-tugas': deskripsiError,
                    'deadline': deadlineError
                };
                clearInputError(input, errorMap[input.id]);
            }
        });
    });

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
        window.location.href = "{{ route('guru.tugas') }}";
    });

    btnConfirmSimpan.addEventListener('click', () => {
        console.log('simpan clicked');
        if (!validateForm()) {
            console.log('validasi gagal');
            modalConfirmSimpan.classList.add('hidden');
            return;
        }
        console.log('validasi ok, akan submit');
        modalConfirmSimpan.classList.add('hidden');
        toastSuccess.classList.remove('invisible', 'opacity-0', '-translate-y-4');
        toastSuccess.classList.add('opacity-100', 'translate-y-0');
        setTimeout(() => {
            console.log('submit!');
            document.getElementById('form-buat-tugas').submit();
        }, 1500);
    });

        } catch (error) {
            console.error('JS Error on Load:', error);
            alert('Terjadi kesalahan pada sistem saat memuat halaman: ' + error.message);
        }

        // Cek kalau ada file_path (Edit Mode)
        @if(isset($tugas) && $tugas->file_path)
            const previewContainer = document.querySelector('.image-preview-container');
            const existingFileUrl = "{{ \Illuminate\Support\Facades\Storage::disk(env('FILESYSTEM_DISK', 'public'))->url($tugas->file_path) }}";
            const existingFileName = "{{ basename($tugas->file_path) }}";
            let ext = existingFileName.split('.').pop().toLowerCase();
            
            let previewVisual = '';
            if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext)) {
                previewVisual = `<img src="${existingFileUrl}" alt="Preview" class="w-full h-auto max-h-96 object-contain rounded-b-lg border-t border-outline-variant/30">`;
            } else if (ext === 'pdf') {
                previewVisual = `<iframe src="${existingFileUrl}#toolbar=0" class="w-full h-96 rounded-b-lg border-t border-outline-variant/30"></iframe>`;
            }

            let icon = 'description';
            if (['ppt', 'pptx'].includes(ext)) icon = 'slideshow';
            else if (['xls', 'xlsx'].includes(ext)) icon = 'table_view';
            else if (['zip', 'rar'].includes(ext)) icon = 'folder_zip';
            else if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext)) icon = 'image';
            
            let htmlContent = `
            <div class="bg-surface rounded-lg border border-outline-variant shadow-sm w-full">
                <div class="flex items-center justify-between p-3 bg-surface-container-low rounded-t-lg">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="p-2 bg-primary/10 text-primary rounded-md flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[20px]">${icon}</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="font-bold text-sm text-on-surface truncate">{{ $tugas->original_file_name ?? 'Lampiran Tugas.' . pathinfo($tugas->file_path, PATHINFO_EXTENSION) }}</p>
                            <p class="text-[10px] text-on-surface-variant">Dokumen tersimpan saat ini</p>
                        </div>
                    </div>
                    <a href="${existingFileUrl}" download class="shrink-0 flex items-center gap-1.5 px-3 py-1.5 bg-primary/10 text-primary hover:bg-primary hover:text-white rounded-lg transition-colors text-xs font-bold" target="_blank" title="Download File">
                        <span class="material-symbols-outlined text-[16px]">download</span>
                        <span>Unduh</span>
                    </a>
                </div>
                ${previewVisual}
            </div>`;
            
            previewContainer.innerHTML = htmlContent;
            previewContainer.classList.remove('hidden');
        @endif
});
</script>
@endsection
