@extends('layouts.guru')

@section('title', isset($kuis) ? 'Edit Kuis - SMK Mandalahayu 1' : 'Buat Kuis Baru - SMK Mandalahayu 1')
@section('page-title', 'Kuis')

@section('content')
<style>
    .stationery-input {
        border: none;
        border-bottom: 2px solid #84746b;
        background: transparent;
        transition: border-color 0.3s ease;
    }
    .stationery-input:focus {
        outline: none;
        border-bottom-color: #feae2c;
        ring: 0;
    }
</style>

<!-- Toast Container -->
<div id="toastContainer" class="fixed top-4 left-1/2 -translate-x-1/2 z-[60] flex flex-col gap-2 transition-all pointer-events-none"></div>

<div class="max-w-[1200px] mx-auto space-y-8">
    <div class="mb-2">
        <a href="{{ route('guru.kuis') }}" class="inline-flex items-center gap-2 text-sm text-secondary hover:text-primary transition-colors font-bold">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span> Kembali ke Daftar Kuis
        </a>
    </div>
    <div class="flex flex-wrap items-end justify-between gap-4 border-b border-outline-variant/30 pb-4">
        <div>
            <h1 class="text-3xl font-bold text-primary mb-2" style="font-family: var(--font-serif)">{{ isset($kuis) ? 'Edit Kuis' : 'Buat Kuis Baru' }}</h1>
            <p class="text-on-surface-variant text-sm">{{ isset($kuis) ? 'Perbarui kuis yang sudah ada.' : 'Rancang asesmen berkualitas untuk perkembangan akademik siswa.' }}</p>
        </div>
        <div class="flex items-center gap-2 text-sm font-semibold text-on-surface-variant/60 italic">
            <span class="material-symbols-outlined text-sm">schedule</span>
            Tersimpan otomatis 14:05
        </div>
    </div>
    
    <form class="space-y-10 pb-10" id="quizForm" method="POST" action="{{ isset($kuis) ? route('guru.kuis.update', $kuis->id) : route('guru.kuis.store') }}" enctype="multipart/form-data">
        @csrf
        @if(isset($kuis))
            @method('PUT')
        @endif
        <section class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
            <div class="bg-white p-8 rounded-xl shadow-sm border border-outline-variant/20 space-y-6 sticky top-6">
                <h3 class="font-bold text-2xl text-primary" style="font-family: var(--font-serif)">Informasi Kuis</h3>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Nama Kuis</label>
                    <input class="w-full text-2xl font-semibold stationery-input py-2" id="quizName" name="judul" placeholder="Contoh: Kuis Akhir Bab 3 - Jaringan Komputer" type="text" value="{{ old('judul', $kuis->judul ?? request('judul')) }}"/>
                    <p class="text-xs text-error font-bold hidden" id="err-quizName"></p>
                </div>
                <div class="z-10">
                    <div class="space-y-2 relative" id="classDropdownContainer">
                        <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Pilih Kelas</label>
                        <button type="button" class="w-full p-3 bg-surface-container-low border border-outline rounded-lg focus:ring-2 focus:ring-secondary-container flex justify-between items-center" onclick="toggleClassesDropdown()">
                            <span id="selectedClassesText" class="text-on-surface-variant">Pilih Kelas...</span>
                            <span class="material-symbols-outlined">expand_more</span>
                        </button>
                        <div id="classesDropdown" class="absolute z-50 w-full mt-1 bg-white border border-outline-variant/50 rounded-lg shadow-lg hidden top-full left-0">
                            <ul class="p-2 space-y-1 max-h-48 overflow-y-auto">
                                @forelse($kelases as $kelas)
                                <li>
                                    <label class="flex items-center gap-3 p-2 hover:bg-surface-container-low rounded cursor-pointer group">
                                        <input type="checkbox" name="kelas_id[]" value="{{ $kelas->id }}" data-name="{{ $kelas->nama_kelas }}" class="w-5 h-5 text-secondary border-outline rounded class-checkbox" onchange="updateSelectedClasses()" {{ (isset($kuis) && $kuis->kelas_id == $kelas->id) ? 'checked' : '' }}>
                                        <span class="text-sm group-hover:text-primary">{{ $kelas->nama_kelas }} ({{ $kelas->mata_pelajaran }})</span>
                                    </label>
                                </li>
                                @empty
                                <li>
                                    <p class="text-xs text-red-500 italic p-2">Anda belum memiliki kelas.</p>
                                </li>
                                @endforelse
                            </ul>
                        </div>
                        <p class="text-xs text-error font-bold hidden" id="err-quizClasses"></p>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Durasi Kuis (Menit)</label>
                    <div class="relative flex items-center">
                        <input class="w-full p-3 bg-surface-container-low border border-outline rounded-lg focus:ring-2 focus:ring-secondary-container pr-12" id="quizDuration" name="durasi" type="number" min="0" value="{{ old('durasi', $kuis->durasi_menit ?? request('durasi', 60)) }}"/>
                        <span class="absolute right-4 text-on-surface-variant/60 text-xs font-bold uppercase tracking-wider">Min</span>
                    </div>
                    <p class="text-xs text-error font-bold hidden" id="err-quizDuration"></p>
                </div>
                <div class="space-y-4">
                    <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Status Kuis</label>
                    <div class="flex flex-wrap gap-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="status" value="published" class="text-primary focus:ring-primary h-4 w-4" {{ old('status', $kuis->status ?? '') === 'published' || !isset($kuis) ? 'checked' : '' }}>
                            <span class="text-sm font-semibold">Published</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="status" value="closed" class="text-primary focus:ring-primary h-4 w-4" {{ old('status', $kuis->status ?? '') === 'closed' ? 'checked' : '' }}>
                            <span class="text-sm font-semibold">Closed</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="status" value="archived" class="text-primary focus:ring-primary h-4 w-4" {{ old('status', $kuis->status ?? '') === 'archived' ? 'checked' : '' }}>
                            <span class="text-sm font-semibold">Archived</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="status" value="terjadwal" class="text-primary focus:ring-primary h-4 w-4" {{ old('status', $kuis->status ?? '') === 'terjadwal' ? 'checked' : '' }} onchange="toggleScheduleInput()">
                            <span class="text-sm font-semibold">Terjadwal</span>
                        </label>
                    </div>
                    <p class="text-xs text-error font-bold hidden mt-1" id="err-quizStatus"></p>
                    <div id="scheduleContainer" class="hidden space-y-4 mt-4 bg-surface-container-low p-4 rounded-lg border border-outline/30">
                        <div class="space-y-2" id="waktuMulaiContainer">
                            <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Waktu Mulai</label>
                            <input class="w-full p-3 bg-white border border-outline rounded-lg focus:ring-2 focus:ring-secondary-container" type="datetime-local" name="waktu_mulai" id="quizScheduleDate" value="{{ old('waktu_mulai', isset($kuis) && $kuis->mulai_at ? date('Y-m-d\TH:i', strtotime($kuis->mulai_at)) : '') }}"/>
                            <p class="text-xs text-on-surface-variant/60 italic">Kuis akan otomatis diterbitkan pada waktu yang ditentukan.</p>
                            <p class="text-xs text-error font-bold hidden" id="err-quizSchedule"></p>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Waktu Berakhir</label>
                            <input class="w-full p-3 bg-white border border-outline rounded-lg focus:ring-2 focus:ring-secondary-container" type="datetime-local" name="waktu_berakhir" id="quizEndDate" value="{{ old('waktu_berakhir', isset($kuis) && $kuis->selesai_at ? date('Y-m-d\TH:i', strtotime($kuis->selesai_at)) : '') }}"/>
                            <p class="text-xs text-on-surface-variant/60 italic">Kuis akan otomatis ditutup pada waktu yang ditentukan.</p>
                            <p class="text-xs text-error font-bold hidden" id="err-quizEndDate"></p>
                        </div>
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Deskripsi Kuis</label>
                    <textarea name="deskripsi" class="w-full p-4 bg-surface-container-low border border-outline rounded-lg focus:ring-2 focus:ring-secondary-container resize-none" placeholder="Berikan instruksi atau deskripsi singkat mengenai cakupan materi kuis ini..." rows="4">{{ old('deskripsi', $kuis->deskripsi ?? '') }}</textarea>
                </div>
                
                <div class="pt-6 border-t border-outline-variant/30 flex flex-col-reverse md:flex-row gap-3 justify-end mt-4">
                    <button type="button" class="ui-btn ui-btn-secondary px-6 py-2 text-sm w-full md:w-auto" onclick="openActionModal('cancel')">
                        Batal
                    </button>
                    <button type="button" class="ui-btn ui-btn-primary px-6 py-2 text-sm w-full md:w-auto" onclick="openActionModal('save')">
                        <span class="material-symbols-outlined" style="font-size: 18px">save</span> {{ request('edit') ? 'Perbarui Kuis' : 'Simpan Kuis' }}
                    </button>
                </div>
            </div>

            <div class="bg-white p-8 rounded-xl shadow-sm border border-outline-variant/20 space-y-6" id="questionContainer">
                <h3 class="font-bold text-2xl text-primary" style="font-family: var(--font-serif)">Pembuat Soal</h3>
                <p class="text-on-surface-variant text-base">Tambahkan butir soal satu per satu. Anda bisa memilih antara Pilihan Ganda untuk penilaian otomatis atau Esai.</p>
                <p class="text-xs text-error font-bold hidden" id="err-quizQuestions"></p>

                <div class="question-card bg-white p-6 rounded-xl shadow-sm border border-outline-variant/20 relative group">
                    <div class="absolute -left-3 top-6 bg-primary text-on-primary w-8 h-8 rounded-full flex items-center justify-center font-bold question-number-label">1</div>
                    <button class="absolute top-4 right-4 text-on-surface-variant/40 hover:text-error transition-colors" type="button" onclick="openDeleteModal(this)">
                        <span class="material-symbols-outlined">delete</span>
                    </button>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Tipe Soal</label>
                            <select class="w-full p-3 bg-surface-container-low border border-outline rounded-lg question-type" onchange="toggleQuestionType(this, 1)">
                                <option value="pg">Pilihan Ganda</option>
                                <option value="essay">Esai</option>
                            </select>
                        </div>
                        <div class="col-span-1 md:col-span-2 pg-multi-option" id="multi-1">
                            <label class="flex items-center gap-3 cursor-pointer group w-max">
                                <input class="w-5 h-5 text-secondary border-outline focus:ring-secondary-container" type="checkbox" onchange="toggleMultiAnswer(this, 1)"/>
                                <span class="text-sm font-bold text-on-surface-variant group-hover:text-primary transition-colors">Jawaban benar lebih dari satu</span>
                            </label>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Bobot Nilai</label>
                            <input class="w-full p-3 bg-surface-container-low border border-outline rounded-lg question-weight" type="number" min="0" value="10"/>
                            <p class="text-xs text-error font-bold hidden err-weight"></p>
                        </div>
                    </div>
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Teks Soal</label>
                            <textarea class="w-full p-4 bg-surface-container-low border border-outline rounded-lg focus:ring-2 focus:ring-secondary-container question-text" placeholder="Masukkan pertanyaan kuis di sini..." rows="3"></textarea>
                            <p class="text-xs text-error font-bold hidden err-text"></p>
                        </div>
                        <div class="space-y-2">
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Lampiran File (Opsional) - Gambar/PDF/Word/PPT</label>
                                <div class="relative mt-2">
                                    <input type="file" name="gambar_soal_1" accept=".pdf,.doc,.docx,.ppt,.pptx,image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="previewFile(this)">
                                    <div class="w-full p-6 border-2 border-dashed border-outline-variant/60 rounded-xl bg-surface-container-lowest hover:bg-surface-container-low transition-colors flex flex-col items-center justify-center text-center gap-2">
                                        <span class="material-symbols-outlined text-3xl text-outline">cloud_upload</span>
                                        <div class="space-y-1">
                                            <p class="text-sm font-bold text-on-surface">Pilih File Lampiran (Opsional)</p>
                                            <p class="text-xs text-on-surface-variant">Biarkan kosong jika tidak diperlukan</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3 hidden image-preview-container">
                                    <!-- Preview HTML akan dirender di sini -->
                                </div>
                            </div>
                        </div>
                <div class="pg-options space-y-4" id="options-1">
                            <div class="flex justify-between items-center mb-2">
                                <p class="text-xs text-on-surface-variant italic">Tentukan opsi jawaban dan pilih jawaban yang benar:</p>
                                <div class="flex gap-2">
                                    <button type="button" class="text-secondary hover:text-primary transition-colors flex items-center gap-1" onclick="addOption(1)">
                                        <span class="material-symbols-outlined text-[16px]">add</span> <span class="text-xs font-bold">Tambah</span>
                                    </button>
                                    <button type="button" class="text-red-500 hover:text-red-700 transition-colors flex items-center gap-1" onclick="removeOption(1)">
                                        <span class="material-symbols-outlined text-[16px]">remove</span> <span class="text-xs font-bold">Kurangi</span>
                                    </button>
                                </div>
                            </div>
                            <p class="text-xs text-error font-bold hidden err-options"></p>
                            <div id="options-list-1" class="space-y-4">
                                <div class="flex items-center gap-4 option-item-1" data-opt="A">
                                    <input class="w-5 h-5 text-secondary border-outline opt-input-1 question-correct" name="q1-correct" type="radio" value="A"/>
                                    <span class="font-bold text-on-surface-variant opt-label-1">A.</span>
                                    <input class="flex-1 stationery-input py-1 question-option-text" placeholder="Opsi A" type="text"/>
                                </div>
                                <div class="flex items-center gap-4 option-item-1" data-opt="B">
                                    <input class="w-5 h-5 text-secondary border-outline opt-input-1 question-correct" name="q1-correct" type="radio" value="B"/>
                                    <span class="font-bold text-on-surface-variant opt-label-1">B.</span>
                                    <input class="flex-1 stationery-input py-1 question-option-text" placeholder="Opsi B" type="text"/>
                                </div>
                                <div class="flex items-center gap-4 option-item-1" data-opt="C">
                                    <input class="w-5 h-5 text-secondary border-outline opt-input-1 question-correct" name="q1-correct" type="radio" value="C"/>
                                    <span class="font-bold text-on-surface-variant opt-label-1">C.</span>
                                    <input class="flex-1 stationery-input py-1 question-option-text" placeholder="Opsi C" type="text"/>
                                </div>
                                <div class="flex items-center gap-4 option-item-1" data-opt="D">
                                    <input class="w-5 h-5 text-secondary border-outline opt-input-1 question-correct" name="q1-correct" type="radio" value="D"/>
                                    <span class="font-bold text-on-surface-variant opt-label-1">D.</span>
                                    <input class="flex-1 stationery-input py-1 question-option-text" placeholder="Opsi D" type="text"/>
                                </div>
                                <div class="flex items-center gap-4 option-item-1" data-opt="E">
                                    <input class="w-5 h-5 text-secondary border-outline opt-input-1 question-correct" name="q1-correct" type="radio" value="E"/>
                                    <span class="font-bold text-on-surface-variant opt-label-1">E.</span>
                                    <input class="flex-1 stationery-input py-1 question-option-text" placeholder="Opsi E" type="text"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="w-full py-5 border-2 border-dashed border-outline-variant hover:border-secondary-container hover:bg-secondary-container/5 transition-all rounded-xl flex flex-col items-center justify-center gap-2 group" onclick="addQuestion()" type="button">
                    <span class="material-symbols-outlined text-4xl text-outline-variant group-hover:text-secondary-container transition-colors">add_circle</span>
                    <span class="font-bold text-on-surface-variant group-hover:text-primary transition-colors">Tambah Soal Baru</span>
                </button>
            </div>
        </section>
    </form>
</div>

<!-- Modal Confirm Delete Question -->
<div id="deleteQuestionModal" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/50 hidden backdrop-blur-sm transition-opacity opacity-0">
    <div class="bg-red-50 rounded-xl shadow-2xl p-6 w-full max-w-sm border border-red-200 text-center transform scale-95 transition-soft">
        <span class="material-symbols-outlined text-red-500 text-5xl mb-4">warning</span>
        <h3 class="text-xl font-bold text-red-700 mb-2" style="font-family: var(--font-serif)">Hapus Soal?</h3>
        <p class="text-xs text-red-600/80 mb-6">Apakah Anda yakin ingin menghapus soal ini? Tindakan ini tidak dapat dibatalkan.</p>
        <div class="flex gap-2 justify-center">
            <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 rounded-lg font-bold text-xs text-red-700 border border-red-200 hover:bg-red-100 transition-colors">Batal</button>
            <button type="button" id="confirmDeleteBtn" class="px-4 py-2 rounded-lg font-bold text-xs bg-red-500 text-white hover:bg-red-600 transition-all shadow-md hover:shadow-lg">Ya, Hapus</button>
        </div>
    </div>
</div>

<!-- Modal Confirm Action -->
<div id="actionModal" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/50 hidden backdrop-blur-sm transition-opacity opacity-0">
    <div class="ui-modal-card transform scale-95 transition-soft">
        <span class="material-symbols-outlined text-[#feae2c] text-5xl mb-4" id="actionModalIcon">help</span>
        <h3 class="text-xl font-bold text-[#50290b] mb-2" style="font-family: var(--font-serif)" id="actionModalTitle">Simpan Kuis?</h3>
        <p class="text-xs text-[#51443c] mb-6" id="actionModalDesc">Apakah Anda yakin data kuis sudah benar dan siap disimpan?</p>
        <div class="flex gap-2 justify-center" id="actionModalButtons">
            <button type="button" onclick="closeActionModal()" class="ui-btn ui-btn-secondary px-4 py-2 text-xs">Periksa Lagi</button>
            <button type="button" id="confirmActionBtn" class="ui-btn ui-btn-primary px-4 py-2 text-xs">Ya, Simpan</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function previewFile(input) {
        const previewContainer = input.parentElement.nextElementSibling;
        
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const fileType = file.type;
            const fileName = file.name;
            const fileUrl = URL.createObjectURL(file);
            
            let htmlContent = '';
            if (fileType.startsWith('image/')) {
                htmlContent = `
                <div class="relative group mt-3">
                    <img src="${fileUrl}" alt="Preview" class="w-full max-h-[300px] object-cover rounded-xl border border-outline-variant/50 shadow-sm">
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity rounded-xl flex items-center justify-center gap-4">
                        <a href="${fileUrl}" target="_blank" class="ui-btn bg-white/20 hover:bg-white/30 text-white backdrop-blur-sm border-white/30 flex items-center gap-2">
                            <span class="material-symbols-outlined text-lg">fullscreen</span>
                            <span>Lihat Penuh</span>
                        </a>
                    </div>
                </div>`;
            } else if (fileType === 'application/pdf') {
                htmlContent = `
                <div class="mt-3 relative group">
                    <iframe src="${fileUrl}#toolbar=0" class="w-full h-[400px] rounded-xl border border-outline-variant/50 shadow-sm bg-white"></iframe>
                </div>`;
            } else {
                let icon = 'description';
                if (fileName.endsWith('.ppt') || fileName.endsWith('.pptx')) icon = 'slideshow';
                else if (fileName.endsWith('.xls') || fileName.endsWith('.xlsx')) icon = 'table_view';
                else if (fileName.endsWith('.zip') || fileName.endsWith('.rar')) icon = 'folder_zip';
                
                htmlContent = `
                <div class="mt-3 flex items-center justify-between p-4 bg-surface rounded-xl border border-outline-variant shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-4 min-w-0">
                        <div class="w-12 h-12 bg-primary/10 text-primary rounded-xl flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-3xl">${icon}</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="font-bold text-sm text-on-surface truncate">${fileName}</p>
                            <p class="text-[10px] text-on-surface-variant mt-0.5">Dokumen terlampir</p>
                        </div>
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

    let questionCount = 1;

    function toggleQuestionType(selectElement, id) {
        const optionsDiv = document.getElementById(`options-${id}`);
        const noteDiv = document.getElementById(`note-${id}`);
        const multiDiv = document.getElementById(`multi-${id}`);

        if (selectElement.value === 'essay') {
            optionsDiv.classList.add('hidden');
            if (noteDiv) noteDiv.classList.remove('hidden');
            if (multiDiv) multiDiv.classList.add('hidden');
        } else {
            optionsDiv.classList.remove('hidden');
            if (noteDiv) noteDiv.classList.add('hidden');
            if (multiDiv) multiDiv.classList.remove('hidden');
        }
    }

    function toggleMultiAnswer(checkboxElement, id) {
        const inputs = document.querySelectorAll(`.opt-input-${id}`);
        inputs.forEach(input => {
            if (checkboxElement.checked) {
                input.type = 'checkbox';
                input.name = `q${id}-correct[]`;
            } else {
                input.type = 'radio';
                input.name = `q${id}-correct`;
                input.checked = false;
            }
        });
    }

    function addQuestion() {
        questionCount++;
        const container = document.getElementById('questionContainer');
        const btn = container.querySelector('button[onclick="addQuestion()"]');
        const visualNumber = document.querySelectorAll('.question-card').length + 1;

        const newCard = document.createElement('div');
        newCard.className = 'question-card bg-white p-6 rounded-xl shadow-sm border border-outline-variant/20 relative group animate-in fade-in slide-in-from-bottom-4 transition-soft';
        newCard.innerHTML = `
            <div class="absolute -left-3 top-6 bg-primary text-on-primary w-8 h-8 rounded-full flex items-center justify-center font-bold question-number-label">${visualNumber}</div>
            <button type="button" class="absolute top-4 right-4 text-on-surface-variant/40 hover:text-error transition-colors" onclick="openDeleteModal(this)">
                <span class="material-symbols-outlined">delete</span>
            </button>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="space-y-2">
                    <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Tipe Soal</label>
                    <select class="w-full p-3 bg-surface-container-low border border-outline rounded-lg question-type" onchange="toggleQuestionType(this, ${questionCount})">
                        <option value="pg">Pilihan Ganda</option>
                        <option value="essay">Esai</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Bobot Nilai</label>
                    <input type="number" min="0" value="10" class="w-full p-3 bg-surface-container-low border border-outline rounded-lg question-weight">
                    <p class="text-xs text-error font-bold hidden err-weight"></p>
                </div>
                <div class="col-span-1 md:col-span-2 pg-multi-option" id="multi-${questionCount}">
                    <label class="flex items-center gap-3 cursor-pointer group w-max">
                        <input class="w-5 h-5 text-secondary border-outline focus:ring-secondary-container" type="checkbox" onchange="toggleMultiAnswer(this, ${questionCount})"/>
                        <span class="text-sm font-bold text-on-surface-variant group-hover:text-primary transition-colors">Jawaban benar lebih dari satu</span>
                    </label>
                </div>
            </div>

            <div class="space-y-6">
                <div class="space-y-2">
                    <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Teks Soal</label>
                    <textarea rows="3" placeholder="Masukkan pertanyaan kuis di sini..." class="w-full p-4 bg-surface-container-low border border-outline rounded-lg focus:ring-2 focus:ring-secondary-container question-text"></textarea>
                    <p class="text-xs text-error font-bold hidden err-text"></p>
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Lampiran File (Opsional) - Gambar/PDF/Word/PPT</label>
                    <div class="relative mt-2">
                        <input type="file" name="gambar_soal_${questionCount}" accept=".pdf,.doc,.docx,.ppt,.pptx,image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="previewFile(this)">
                        <div class="w-full p-6 border-2 border-dashed border-outline-variant/60 rounded-xl bg-surface-container-lowest hover:bg-surface-container-low transition-colors flex flex-col items-center justify-center text-center gap-2 dropzone-box">
                            <span class="material-symbols-outlined text-3xl text-outline dropzone-icon">cloud_upload</span>
                            <div class="space-y-1 dropzone-text">
                                <p class="text-sm font-bold text-on-surface dropzone-title">Pilih File Lampiran (Opsional)</p>
                                <p class="text-xs text-on-surface-variant dropzone-desc">Biarkan kosong jika tidak diperlukan</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 hidden image-preview-container">
                        <!-- Preview HTML akan dirender di sini -->
                    </div>
                </div>

                <div class="pg-options space-y-4" id="options-${questionCount}">
                    <div class="flex justify-between items-center mb-2">
                        <p class="text-xs text-on-surface-variant italic">Tentukan opsi jawaban dan pilih jawaban yang benar:</p>
                        <div class="flex gap-2">
                            <button type="button" class="text-secondary hover:text-primary transition-colors flex items-center gap-1" onclick="addOption(${questionCount})">
                                <span class="material-symbols-outlined text-[16px]">add</span> <span class="text-xs font-bold">Tambah</span>
                            </button>
                            <button type="button" class="text-red-500 hover:text-red-700 transition-colors flex items-center gap-1" onclick="removeOption(${questionCount})">
                                <span class="material-symbols-outlined text-[16px]">remove</span> <span class="text-xs font-bold">Kurangi</span>
                            </button>
                        </div>
                    </div>
                    <p class="text-xs text-error font-bold hidden err-options"></p>
                    <div id="options-list-${questionCount}" class="space-y-4">
                        ${['A', 'B', 'C', 'D', 'E'].map(letter => `
                            <div class="flex items-center gap-4 option-item-${questionCount}" data-opt="${letter}">
                                <input type="radio" name="q${questionCount}-correct" class="w-5 h-5 text-secondary border-outline opt-input-${questionCount} question-correct" value="${letter}">
                                <span class="font-bold text-on-surface-variant opt-label-${questionCount}">${letter}.</span>
                                <input type="text" placeholder="Opsi ${letter}" class="flex-1 stationery-input py-1 question-option-text">
                            </div>
                        `).join('')}
                    </div>
                </div>
            </div>
        `;

        container.insertBefore(newCard, btn);
        newCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    // Dynamic options logic
    function addOption(questionId) {
        const list = document.getElementById(`options-list-${questionId}`);
        const items = list.querySelectorAll(`.option-item-${questionId}`);
        if (items.length >= 10) return; // limit to J
        
        const alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        const nextLetter = alphabet[items.length];
        
        const isMulti = document.querySelector(`#multi-${questionId} input`).checked;
        const inputType = isMulti ? 'checkbox' : 'radio';
        const inputName = isMulti ? `q${questionId}-correct[]` : `q${questionId}-correct`;
        
        const div = document.createElement('div');
        div.className = `flex items-center gap-4 option-item-${questionId}`;
        div.dataset.opt = nextLetter;
        
        div.innerHTML = `
            <input class="w-5 h-5 text-secondary border-outline opt-input-${questionId} question-correct" name="${inputName}" type="${inputType}" value="${nextLetter}"/>
            <span class="font-bold text-on-surface-variant opt-label-${questionId}">${nextLetter}.</span>
            <input class="flex-1 stationery-input py-1 question-option-text" placeholder="Opsi ${nextLetter}" type="text"/>
        `;
        list.appendChild(div);
    }

    function removeOption(questionId) {
        const list = document.getElementById(`options-list-${questionId}`);
        const items = list.querySelectorAll(`.option-item-${questionId}`);
        if (items.length <= 2) return; // minimum 2 options
        
        list.removeChild(items[items.length - 1]);
    }

    // Modal Delete Logic
    let questionToDelete = null;

    function openDeleteModal(btn) {
        questionToDelete = btn.closest('.question-card');
        const modal = document.getElementById('deleteQuestionModal');
        modal.classList.remove('hidden');
        void modal.offsetWidth; // trigger reflow
        modal.classList.remove('opacity-0');
        modal.querySelector('div').classList.remove('scale-95');
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteQuestionModal');
        modal.classList.add('opacity-0');
        modal.querySelector('div').classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            questionToDelete = null;
        }, 300);
    }

    function updateQuestionNumbers() {
        const cards = document.querySelectorAll('.question-card');
        cards.forEach((card, index) => {
            const label = card.querySelector('.question-number-label');
            if (label) {
                label.textContent = index + 1;
            }
        });
    }

    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        if (questionToDelete) {
            questionToDelete.remove();
            updateQuestionNumbers();
        }
        closeDeleteModal();
    });

    // Modal Action Logic
    let currentAction = null;

    function openActionModal(action) {
        currentAction = action;
        const modal = document.getElementById('actionModal');
        const title = document.getElementById('actionModalTitle');
        const desc = document.getElementById('actionModalDesc');
        const confirmBtn = document.getElementById('confirmActionBtn');
        const icon = document.getElementById('actionModalIcon');
        const buttonsContainer = document.getElementById('actionModalButtons');
        const modalCard = modal.querySelector('div');

        if (action === 'cancel') {
            modalCard.className = 'bg-red-50 rounded-xl shadow-2xl p-6 w-full max-w-sm border border-red-200 text-center transform scale-95 transition-soft';
            icon.className = 'material-symbols-outlined text-red-500 text-5xl mb-4';
            icon.textContent = 'warning';
            title.className = 'text-xl font-bold text-red-700 mb-2';
            title.textContent = 'Batalkan Pembuatan?';
            desc.className = 'text-xs text-red-600/80 mb-6';
            desc.textContent = 'Semua data yang telah Anda isikan akan hilang. Apakah Anda yakin?';
            
            buttonsContainer.innerHTML = `
                <button type="button" onclick="closeActionModal()" class="px-4 py-2 rounded-lg font-bold text-xs text-red-700 border border-red-200 hover:bg-red-100 transition-colors">Kembali</button>
                <button type="button" id="confirmActionBtn" class="px-4 py-2 rounded-lg font-bold text-xs bg-red-500 text-white hover:bg-red-600 transition-all shadow-md hover:shadow-lg">Ya, Batalkan</button>
            `;
            
            document.getElementById('confirmActionBtn').addEventListener('click', confirmAction);
        } else if (action === 'save') {
            modalCard.className = 'ui-modal-card transform scale-95 transition-soft';
            icon.className = 'material-symbols-outlined text-[#feae2c] text-5xl mb-4';
            icon.textContent = 'help';
            title.className = 'text-xl font-bold text-[#50290b] mb-2';
            title.textContent = 'Simpan Kuis?';
            desc.className = 'text-xs text-[#51443c] mb-6';
            desc.textContent = 'Apakah Anda yakin data kuis sudah benar dan siap disimpan?';
            
            buttonsContainer.innerHTML = `
                <button type="button" onclick="closeActionModal()" class="ui-btn ui-btn-secondary px-4 py-2 text-xs">Periksa Lagi</button>
                <button type="button" id="confirmActionBtn" class="ui-btn ui-btn-primary px-4 py-2 text-xs">Ya, Simpan</button>
            `;
            
            document.getElementById('confirmActionBtn').addEventListener('click', confirmAction);
        }

        modal.classList.remove('hidden');
        void modal.offsetWidth;
        modal.classList.remove('opacity-0');
        modalCard.classList.remove('scale-95');
    }

    function closeActionModal() {
        const modal = document.getElementById('actionModal');
        modal.classList.add('opacity-0');
        modal.querySelector('div').classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            currentAction = null;
        }, 300);
    }

    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `px-6 py-3 rounded-full shadow-lg font-bold text-white transform transition-all duration-300 -translate-y-4 opacity-0 flex items-center gap-2 ${type === 'success' ? 'bg-green-500' : 'bg-error text-on-error'}`;
        toast.innerHTML = `
            <span class="material-symbols-outlined">${type === 'success' ? 'check_circle' : 'info'}</span>
            ${message}
        `;
        document.getElementById('toastContainer').appendChild(toast);
        
        setTimeout(() => toast.classList.remove('-translate-y-4', 'opacity-0'), 10);

        setTimeout(() => {
            toast.classList.add('-translate-y-4', 'opacity-0');
            setTimeout(() => toast.remove(), 300);
        }, 2000);
    }

    function clearErrors() {
        document.querySelectorAll('.text-error:not(#toastContainer .text-error)').forEach(el => {
            if(el.tagName === 'P') {
                el.classList.add('hidden');
                el.innerText = '';
            }
        });
    }

    function toggleScheduleInput() {
        const scheduledRadio = document.querySelector('input[name="status"][value="terjadwal"]');
        const publishedRadio = document.querySelector('input[name="status"][value="published"]');
        const scheduleContainer = document.getElementById('scheduleContainer');
        const waktuMulaiContainer = document.getElementById('waktuMulaiContainer');
        
        if (scheduledRadio.checked || publishedRadio.checked) {
            scheduleContainer.classList.remove('hidden');
            if (publishedRadio.checked) {
                waktuMulaiContainer.classList.add('hidden'); // waktu mulai is now, so hide it
            } else {
                waktuMulaiContainer.classList.remove('hidden');
            }
        } else {
            scheduleContainer.classList.add('hidden');
        }
    }

    function toggleClassesDropdown() {
        const dropdown = document.getElementById('classesDropdown');
        dropdown.classList.toggle('hidden');
    }

    function updateSelectedClasses() {
        const checkedBoxes = Array.from(document.querySelectorAll('.class-checkbox')).filter(cb => cb.checked);
        const textSpan = document.getElementById('selectedClassesText');
        const allBoxes = document.querySelectorAll('.class-checkbox');

        if (allBoxes.length === 0) {
            textSpan.textContent = "Anda belum memiliki kelas.";
            textSpan.classList.remove('text-primary', 'font-bold', 'text-on-surface-variant');
            textSpan.classList.add('text-red-500');
            return;
        }

        if (checkedBoxes.length === 0) {
            textSpan.textContent = 'Pilih Kelas...';
            textSpan.classList.add('text-on-surface-variant');
            textSpan.classList.remove('font-semibold', 'text-red-500');
        } else {
            const values = checkedBoxes.map(cb => cb.getAttribute('data-name'));
            textSpan.textContent = values.join(', ');
            textSpan.classList.remove('text-on-surface-variant', 'text-red-500');
            textSpan.classList.add('font-semibold');
        }
    }

    document.addEventListener('click', function(event) {
        const container = document.getElementById('classDropdownContainer');
        const dropdown = document.getElementById('classesDropdown');
        if (container && !container.contains(event.target)) {
            dropdown.classList.add('hidden');
        }
    });

    function validateForm() {
        clearErrors();
        let isValid = true;
        let firstErrorElement = null;

        function showError(idOrElement, message) {
            isValid = false;
            let el = typeof idOrElement === 'string' ? document.getElementById(idOrElement) : idOrElement;
            if (el) {
                el.innerText = message;
                el.classList.remove('hidden');
                if (!firstErrorElement) firstErrorElement = el;
            }
        }

        const name = document.getElementById('quizName').value.trim();
        if (!name) showError('err-quizName', 'Nama Kuis harus diisi.');

        const classes = document.querySelectorAll('.class-checkbox:checked');
        if (classes.length === 0) showError('err-quizClasses', 'Pilih minimal satu Kelas.');
        
        const duration = document.getElementById('quizDuration').value;
        if (!duration || duration <= 0) showError('err-quizDuration', 'Durasi Kuis tidak valid.');

        const checkedStatus = document.querySelector('input[name="status"]:checked');
        if (!checkedStatus) showError('err-quizStatus', 'Pilih Status Kuis.');

        const scheduledRadio = document.querySelector('input[name="status"][value="terjadwal"]');
        const publishedRadio = document.querySelector('input[name="status"][value="published"]');
        const scheduleInput = document.getElementById('quizScheduleDate').value;
        const endDateInput = document.getElementById('quizEndDate').value;
        
        if (scheduledRadio.checked) {
            if (!scheduleInput) {
                showError('err-quizSchedule', 'Waktu mulai harus diisi jika kuis terjadwal.');
            } else {
                const scheduleDate = new Date(scheduleInput);
                const now = new Date();
                const diffMinutes = (scheduleDate - now) / 1000 / 60;
                if (diffMinutes < 5) {
                    showError('err-quizSchedule', 'Waktu mulai minimal 5 menit dari waktu saat ini.');
                }
            }
            if (!endDateInput) {
                showError('err-quizEndDate', 'Waktu berakhir harus diisi.');
            }
            if (scheduleInput && endDateInput) {
                const scheduleDate = new Date(scheduleInput);
                const endDate = new Date(endDateInput);
                if (endDate <= scheduleDate) {
                    showError('err-quizEndDate', 'Waktu berakhir tidak boleh sebelum atau sama dengan waktu mulai.');
                }
            }
        }
        if (publishedRadio.checked) {
            if (!endDateInput) {
                showError('err-quizEndDate', 'Waktu berakhir harus diisi untuk kuis yang diterbitkan.');
            } else {
                const endDate = new Date(endDateInput);
                const now = new Date();
                if (endDate <= now) {
                    showError('err-quizEndDate', 'Waktu berakhir tidak boleh sebelum atau sama dengan waktu saat ini.');
                }
            }
        }

        const questions = document.querySelectorAll('.question-card');
        if (questions.length === 0) showError('err-quizQuestions', 'Minimal harus ada 1 soal.');

        questions.forEach((q, index) => {
            const weight = q.querySelector('.question-weight').value;
            if (!weight || weight <= 0) showError(q.querySelector('.err-weight'), 'Bobot nilai belum diisi.');
            
            const text = q.querySelector('.question-text').value.trim();
            if (!text) showError(q.querySelector('.err-text'), 'Teks soal belum diisi.');

            const type = q.querySelector('.question-type').value;
            if (type === 'pg') {
                const opts = q.querySelectorAll('.question-option-text');
                let optsValid = true;
                opts.forEach(opt => {
                    if (!opt.value.trim()) optsValid = false;
                });
                
                const checked = q.querySelectorAll('.question-correct:checked');
                
                let optErr = [];
                if (!optsValid) optErr.push('Semua opsi jawaban harus diisi.');
                if (checked.length === 0) optErr.push('Pilih minimal satu jawaban benar.');
                
                if (optErr.length > 0) {
                    showError(q.querySelector('.err-options'), optErr.join(' '));
                }
            }
        });

        if (!isValid && firstErrorElement) {
            alert('Data Belum Lengkap!\n\nMohon periksa kembali form dan isi semua data wajib yang ditandai dengan teks merah.');
            firstErrorElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        return isValid;
    }

    function confirmAction() {
        if (currentAction === 'cancel') {
            closeActionModal();
            // Show toast batal merah
            const toastContainer = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = 'fixed top-5 left-1/2 -translate-x-1/2 z-[70] flex items-center gap-3 bg-red-100 border border-red-300 text-red-800 px-6 py-3 rounded-lg shadow-lg opacity-0 invisible transition-all duration-300 transform -translate-y-4';
            toast.innerHTML = `
                <span class="material-symbols-outlined">cancel</span>
                <span class="font-bold text-sm whitespace-nowrap">Pembuatan dibatalkan!</span>
            `;
            toastContainer.appendChild(toast);
            setTimeout(() => {
                toast.classList.remove('invisible', 'opacity-0', '-translate-y-4');
                toast.classList.add('opacity-100', 'translate-y-0');
            }, 10);
            
            setTimeout(() => {
                window.location.href = '/guru/kuis';
            }, 1500);
        } else if (currentAction === 'save') {
            if (validateForm()) {
                closeActionModal();
                const toastContainer = document.getElementById('toastContainer');
                const toast = document.createElement('div');
                toast.className = 'fixed top-5 left-1/2 -translate-x-1/2 z-[70] flex items-center gap-3 bg-green-100 border border-green-300 text-green-800 px-6 py-3 rounded-lg shadow-lg opacity-0 invisible transition-all duration-300 transform -translate-y-4';
                toast.innerHTML = `
                    <span class="material-symbols-outlined">check_circle</span>
                    <span class="font-bold text-sm whitespace-nowrap">${document.querySelector('h2').textContent.includes('Edit') ? 'Kuis berhasil diperbarui!' : 'Kuis berhasil disimpan!'}</span>
                `;
                toastContainer.appendChild(toast);
                setTimeout(() => {
                    toast.classList.remove('invisible', 'opacity-0', '-translate-y-4');
                    toast.classList.add('opacity-100', 'translate-y-0');
                }, 10);
                setTimeout(() => {
                    const qCards = document.querySelectorAll('.question-card');
                    const qData = [];
                    qCards.forEach((q, idx) => {
                        const text = q.querySelector('.question-text').value.trim();
                        const opts = Array.from(q.querySelectorAll('.question-option-text')).map(o => o.value.trim());
                        let type = q.querySelector('.question-type').value;
                        const multiCheck = q.querySelector('.pg-multi-option input[type="checkbox"]');
                        if (type === 'pg' && multiCheck && multiCheck.checked) {
                            type = 'multiple_select';
                        }
                        
                        let jawabanBenar;
                        if (type === 'multiple_select') {
                            const checkedEls = q.querySelectorAll('.question-correct:checked');
                            jawabanBenar = Array.from(checkedEls).map(el => Math.max(0, el.value.charCodeAt(0) - 65));
                            if (jawabanBenar.length === 0) jawabanBenar = [0]; // fallback
                        } else {
                            const checkedEl = q.querySelector('.question-correct:checked');
                            const correctVal = checkedEl ? checkedEl.value : 'A';
                            jawabanBenar = Math.max(0, correctVal.charCodeAt(0) - 65);
                        }
                        
                        const weight = parseFloat(q.querySelector('.question-weight').value) || 1;
                        
                        qData.push({
                            pertanyaan: text,
                            tipe: type,
                            pilihan: opts,
                            jawaban_benar: jawabanBenar,
                            bobot: weight,
                            urutan: idx + 1
                        });
                    });
                    
                    const qInput = document.createElement('input');
                    qInput.type = 'hidden';
                    qInput.name = 'questions_data';
                    qInput.value = JSON.stringify(qData);
                    document.getElementById('quizForm').appendChild(qInput);
                    
                    document.getElementById('quizForm').submit();
                }, 1500);
            } else {
                closeActionModal();
            }
        }
    }

    updateSelectedClasses();
    toggleScheduleInput();

        @if(isset($kuis) && $kuis->soal && $kuis->soal->count() > 0)
            // Pre-populate questions for Edit mode
            const soalData = @json($kuis->soal);
            
            // Remove the default empty card
            const defaultCard = document.querySelector('.question-card');
            if (defaultCard) defaultCard.remove();
            
            questionCount = 0; // Reset questionCount to match urutan
            
            soalData.forEach((soal, index) => {
                addQuestion();
                const cards = document.querySelectorAll('.question-card');
                const newCard = cards[cards.length - 1];
                
                newCard.querySelector('.question-type').value = soal.tipe;
                toggleQuestionType(newCard.querySelector('.question-type'), questionCount);
                
                newCard.querySelector('.question-weight').value = soal.bobot;
                newCard.querySelector('.question-text').value = soal.pertanyaan;
                
                if ((soal.tipe === 'pg' || soal.tipe === 'multiple_select') && soal.pilihan) {
                    const opts = newCard.querySelectorAll('.question-option-text');
                    const choices = soal.pilihan;
                    opts.forEach((opt, idx) => {
                        if (choices[idx]) opt.value = choices[idx];
                    });
                    
                    if (soal.tipe === 'multiple_select') {
                        // Switch type in dropdown to 'pg' (because multiple_select is just pg + checkbox)
                        newCard.querySelector('.question-type').value = 'pg';
                        const multiCheck = newCard.querySelector('.pg-multi-option input[type="checkbox"]');
                        if (multiCheck) {
                            multiCheck.checked = true;
                            toggleMultiAnswer(multiCheck, questionCount);
                        }
                        
                        let answers = soal.jawaban_benar;
                        if (!Array.isArray(answers)) {
                            // Try parsing if stringified JSON
                            try { answers = JSON.parse(answers); } catch(e) { answers = [answers]; }
                        }
                        if (!Array.isArray(answers)) answers = [answers];
                        
                        const correctMapRev = { 0: 'A', 1: 'B', 2: 'C', 3: 'D', 4: 'E', '0': 'A', '1': 'B', '2': 'C', '3': 'D', '4': 'E' };
                        answers.forEach(ans => {
                            let correctChar = correctMapRev[ans] || ans;
                            if (['A', 'B', 'C', 'D', 'E'].includes(correctChar)) {
                                const check = newCard.querySelector(`.question-correct[value="${correctChar}"]`);
                                if (check) check.checked = true;
                            }
                        });
                    } else {
                        const correctMapRev = { 0: 'A', 1: 'B', 2: 'C', 3: 'D', 4: 'E', '0': 'A', '1': 'B', '2': 'C', '3': 'D', '4': 'E' };
                        let correctChar = correctMapRev[soal.jawaban_benar] || soal.jawaban_benar;
                        if (!['A', 'B', 'C', 'D', 'E'].includes(correctChar)) correctChar = 'A';
                        
                        const radio = newCard.querySelector(`.question-correct[value="${correctChar}"]`);
                        if (radio) radio.checked = true;
                    }
                }
                // Prepopulate file
                if (soal.file_path) {
                    const previewContainer = newCard.querySelector('.image-preview-container');
                    const baseUrl = "{{ rtrim(\Illuminate\Support\Facades\Storage::disk(env('FILESYSTEM_DISK', 'public'))->url(''), '/') }}";
                    const existingFileUrl = soal.file_url || `${baseUrl}/${soal.file_path}`;
                    const originalFileName = soal.original_file_name || `Lampiran Soal ${index + 1}.${soal.file_path.split('.').pop()}`;
                    
                    // Update dropzone UI
                    const dropzoneTitle = newCard.querySelector('.dropzone-title');
                    const dropzoneDesc = newCard.querySelector('.dropzone-desc');
                    const dropzoneIcon = newCard.querySelector('.dropzone-icon');
                    if(dropzoneTitle) dropzoneTitle.textContent = originalFileName;
                    if(dropzoneDesc) dropzoneDesc.textContent = "File sebelumnya terlampir. Pilih file baru untuk mengganti.";
                    if(dropzoneIcon) {
                        dropzoneIcon.textContent = "check_circle";
                        dropzoneIcon.classList.replace("text-outline", "text-primary");
                    }

                    
                    let htmlContent = '';
                    const ext = soal.file_path.split('.').pop().toLowerCase();
                    if (['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'].includes(ext)) {
                        htmlContent = `
                        <div class="relative group mt-3">
                            <img src="${existingFileUrl}" alt="Preview" class="w-full max-h-[300px] object-cover rounded-xl border border-outline-variant/50 shadow-sm">
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity rounded-xl flex items-center justify-center gap-4">
                                <a href="${existingFileUrl}" target="_blank" class="ui-btn bg-white/20 hover:bg-white/30 text-white backdrop-blur-sm border-white/30 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-lg">fullscreen</span>
                                    <span>Lihat Penuh</span>
                                </a>
                                <a href="${existingFileUrl}" download="${originalFileName}" class="ui-btn bg-primary text-white border-primary hover:bg-primary/90 flex items-center gap-2 shadow-lg">
                                    <span class="material-symbols-outlined text-lg">download</span>
                                    <span>Unduh</span>
                                </a>
                            </div>
                        </div>`;
                    } else if (ext === 'pdf') {
                        htmlContent = `
                        <div class="mt-3 relative group">
                            <iframe src="${existingFileUrl}#toolbar=0" class="w-full h-[400px] rounded-xl border border-outline-variant/50 shadow-sm bg-white"></iframe>
                            <div class="absolute top-4 right-6 flex gap-2">
                                <a href="${existingFileUrl}" download="${originalFileName}" class="ui-btn bg-primary text-white shadow-lg hover:bg-primary/90 hover:-translate-y-0.5 transition-all flex items-center gap-2 border border-primary/20 backdrop-blur-md">
                                    <span class="material-symbols-outlined text-lg">download</span>
                                    <span>Unduh PDF</span>
                                </a>
                            </div>
                        </div>`;
                    } else {
                        let icon = 'description';
                        if (['ppt', 'pptx'].includes(ext)) icon = 'slideshow';
                        else if (['xls', 'xlsx'].includes(ext)) icon = 'table_view';
                        else if (['zip', 'rar'].includes(ext)) icon = 'folder_zip';
                        
                        htmlContent = `
                        <div class="mt-3 flex items-center justify-between p-4 bg-surface rounded-xl border border-outline-variant shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-4 min-w-0">
                                <div class="w-12 h-12 bg-primary/10 text-primary rounded-xl flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-3xl">${icon}</span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="font-bold text-sm text-on-surface truncate">${originalFileName}</p>
                                    <p class="text-[10px] text-on-surface-variant mt-0.5">Dokumen terlampir</p>
                                </div>
                            </div>
                            <a href="${existingFileUrl}" download="${originalFileName}" class="ui-btn bg-primary/10 text-primary hover:bg-primary hover:text-white transition-colors flex items-center gap-2 shrink-0 ml-4 rounded-lg px-4 py-2">
                                <span class="material-symbols-outlined text-lg">download</span>
                                <span class="text-xs font-bold">Unduh</span>
                            </a>
                        </div>`;
                    }
                    previewContainer.innerHTML = htmlContent;
                    previewContainer.classList.remove('hidden');
                }
            });
        @endif

    document.addEventListener('DOMContentLoaded', toggleScheduleInput);
</script>
@endpush
