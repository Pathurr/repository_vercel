@extends('layouts.guru')
@section('title', 'Buat Ujian Baru - SMK Mandalahayu 1')

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
        <a href="{{ route('guru.ujian') }}" class="inline-flex items-center gap-2 text-sm text-secondary hover:text-primary transition-colors font-bold">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span> Kembali ke Daftar Ujian
        </a>
    </div>
    <div class="flex flex-wrap items-end justify-between gap-4 border-b border-outline-variant/30 pb-4">
        <div>
            <h2 class="font-bold text-4xl text-primary" style="font-family: var(--font-serif)">{{ request('edit') ? 'Edit Ujian' : 'Buat Ujian Baru' }}</h2>
            <p class="text-on-surface-variant text-lg">Rancang instrumen penilaian akademik dengan standar kurikulum vokasi terbaru.</p>
        </div>
        <div class="flex items-center gap-2 text-sm font-semibold text-on-surface-variant/60 italic">
            <span class="material-symbols-outlined text-sm">schedule</span>
            Tersimpan otomatis <span id="autoSaveTime">14:05</span>
        </div>
    </div>

    <form class="space-y-10 pb-10" id="ujianForm" method="POST" action="{{ isset($ujian) ? route('guru.ujian.update', $ujian->id) : route('guru.ujian.store') }}" enctype="multipart/form-data">
        @csrf
        @if(isset($ujian))
            @method('PUT')
        @endif
        <section class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
            
            <!-- Left Column: Konfigurasi -->
            <div class="bg-white p-8 rounded-xl shadow-sm border border-outline-variant/20 space-y-6 sticky top-6">
                <div class="flex items-center gap-3 mb-2 border-b border-surface-variant pb-4">
                    <span class="material-symbols-outlined text-primary-container" style="font-variation-settings: 'FILL' 1;">settings</span>
                    <h3 class="font-bold text-2xl text-primary" style="font-family: var(--font-serif)">Konfigurasi Dasar</h3>
                </div>
                
                <div class="space-y-2">
                    <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Judul Ujian</label>
                    <input class="w-full text-2xl font-semibold stationery-input py-2" id="ujianName" name="judul" placeholder="Contoh: Ujian Tengah Semester (UTS) Ganjil" type="text" value="{{ old('judul', $ujian->judul ?? request('judul')) }}"/>
                    <p class="text-xs text-error font-bold hidden" id="err-ujianName"></p>
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
                                        <input type="checkbox" name="kelas_id[]" value="{{ $kelas->id }}" data-name="{{ $kelas->nama_kelas }}" class="w-5 h-5 text-secondary border-outline rounded class-checkbox" onchange="updateSelectedClasses()" {{ (isset($ujian) && $ujian->kelas_id == $kelas->id) ? 'checked' : '' }}>
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
                        <p class="text-xs text-error font-bold hidden" id="err-ujianKelas"></p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2 relative">
                        <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Durasi (Menit)</label>
                        <div class="relative">
                            <input class="w-full p-3 bg-surface-container-low border border-outline rounded-lg focus:ring-2 focus:ring-secondary-container pr-12" id="ujianDuration" name="durasi" type="number" min="0" value="{{ old('durasi', $ujian->durasi_menit ?? request('durasi', 90)) }}"/>
                            <span class="absolute right-4 top-3 text-on-surface-variant/60 text-xs font-bold uppercase tracking-wider">Mnt</span>
                        </div>
                        <p class="text-xs text-error font-bold hidden" id="err-ujianDurasi"></p>
                    </div>
                    <div class="space-y-2 relative">
                        <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Batas Percobaan</label>
                        <input id="ujianBatas" name="batas" class="w-full p-3 bg-surface-container-low border border-outline rounded-lg focus:ring-2 focus:ring-secondary-container" type="number" min="0" value="{{ old('batas', $ujian->batas_percobaan ?? 1) }}"/>
                        <p class="text-xs text-error font-bold hidden" id="err-ujianBatas"></p>
                    </div>
                </div>

                <div class="space-y-4">
                    <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Status Ujian</label>
                    <div class="flex flex-wrap gap-4">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input {{ in_array(old('status', $ujian->status ?? ''), ['aktif', 'published']) ? 'checked' : '' }} class="w-5 h-5 text-secondary border-outline focus:ring-secondary-container" name="status" type="radio" value="published" onchange="toggleScheduleInput()"/>
                            <span class="text-sm group-hover:text-primary transition-colors">Published</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input {{ in_array(old('status', $ujian->status ?? ''), ['selesai', 'closed']) ? 'checked' : '' }} class="w-5 h-5 text-secondary border-outline focus:ring-secondary-container" name="status" type="radio" value="closed" onchange="toggleScheduleInput()"/>
                            <span class="text-sm group-hover:text-primary transition-colors">Closed</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input {{ old('status', $ujian->status ?? '') === 'archived' ? 'checked' : '' }} class="w-5 h-5 text-secondary border-outline focus:ring-secondary-container" name="status" type="radio" value="archived" onchange="toggleScheduleInput()"/>
                            <span class="text-sm group-hover:text-primary transition-colors">Archived</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input {{ old('status', $ujian->status ?? '') === 'terjadwal' ? 'checked' : '' }} class="w-5 h-5 text-secondary border-outline focus:ring-secondary-container" name="status" type="radio" value="terjadwal" onchange="toggleScheduleInput()"/>
                            <span class="text-sm group-hover:text-primary transition-colors">Terjadwal</span>
                        </label>
                    </div>
                    <p class="text-xs text-error font-bold hidden mt-1" id="err-ujianStatus"></p>
                    <div id="scheduleContainer" class="hidden space-y-4 mt-4 bg-surface-container-low p-4 rounded-lg border border-outline/30">
                        <div class="space-y-2" id="waktuMulaiContainer">
                            <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Waktu Mulai</label>
                            <input class="w-full p-3 bg-white border border-outline rounded-lg focus:ring-2 focus:ring-secondary-container" type="datetime-local" name="waktu_mulai" id="ujianWaktuMulai" value="{{ old('waktu_mulai', isset($ujian) ? date('Y-m-d\TH:i', strtotime($ujian->waktu_mulai)) : '') }}"/>
                            <p class="text-xs text-on-surface-variant/60 italic">Ujian akan otomatis diterbitkan pada waktu yang ditentukan.</p>
                            <p class="text-xs text-error font-bold hidden" id="err-ujianWaktuMulai"></p>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Waktu Berakhir</label>
                            <input class="w-full p-3 bg-white border border-outline rounded-lg focus:ring-2 focus:ring-secondary-container" type="datetime-local" name="waktu_berakhir" id="ujianWaktuBerakhir" value="{{ old('waktu_berakhir', isset($ujian) ? date('Y-m-d\TH:i', strtotime($ujian->waktu_berakhir)) : '') }}"/>
                            <p class="text-xs text-on-surface-variant/60 italic">Ujian akan otomatis ditutup pada waktu yang ditentukan.</p>
                            <p class="text-xs text-error font-bold hidden" id="err-ujianWaktuBerakhir"></p>
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Deskripsi Ujian</label>
                    <textarea name="deskripsi" class="w-full p-4 bg-surface-container-low border border-outline rounded-lg focus:ring-2 focus:ring-secondary-container resize-none" placeholder="Berikan instruksi atau tata tertib selama ujian berlangsung..." rows="4">{{ old('deskripsi', $ujian->deskripsi ?? '') }}</textarea>
                </div>

                <div class="space-y-3 pt-2">
                    <div class="flex items-center justify-between p-3 bg-surface-container-low rounded-lg border border-outline-variant/50 hover:border-primary transition-colors cursor-pointer group" onclick="document.getElementById('toggle1').click()">
                        <div>
                            <p class="font-bold text-primary text-sm group-hover:text-secondary-container transition-colors">Acak Soal (Randomization)</p>
                            <p class="text-xs text-on-surface-variant">Urutan berbeda untuk setiap siswa.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer" onclick="event.stopPropagation()">
                            <input id="toggle1" name="acak_soal" value="1" {{ old('acak_soal', $ujian->acak_soal ?? false) ? 'checked' : '' }} class="sr-only peer" type="checkbox"/>
                            <div class="w-9 h-5 bg-outline-variant peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-secondary"></div>
                        </label>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-surface-container-low rounded-lg border border-outline-variant/50 hover:border-primary transition-colors cursor-pointer group" onclick="document.getElementById('toggle2').click()">
                        <div>
                            <p class="font-bold text-primary text-sm group-hover:text-secondary-container transition-colors">Auto-Submit</p>
                            <p class="text-xs text-on-surface-variant">Otomatis kumpul saat waktu habis.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer" onclick="event.stopPropagation()">
                            <input checked id="toggle2" name="auto_submit" value="1" {{ old('auto_submit', $ujian->auto_submit ?? true) ? 'checked' : '' }} class="sr-only peer" type="checkbox"/>
                            <div class="w-9 h-5 bg-outline-variant peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-secondary"></div>
                        </label>
                    </div>
                </div>

                <div class="pt-6 border-t border-outline-variant/30 flex flex-col-reverse md:flex-row gap-3 justify-end mt-4">
                    <button type="button" class="ui-btn ui-btn-secondary px-6 py-2 text-sm w-full md:w-auto" onclick="openActionModal('cancel')">
                        Batal
                    </button>
                    <button type="button" class="ui-btn ui-btn-primary px-6 py-2 text-sm w-full md:w-auto" onclick="openActionModal('save')">
                        <span class="material-symbols-outlined" style="font-size: 18px">save</span> {{ request('edit') ? 'Perbarui Ujian' : 'Simpan Ujian' }}
                    </button>
                </div>
            </div>

            <!-- Right Column: Daftar Pertanyaan -->
            <div class="bg-white p-8 rounded-xl shadow-sm border border-outline-variant/20 space-y-6" id="questionContainer">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-surface-variant pb-4">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary-container" style="font-variation-settings: 'FILL' 1;">quiz</span>
                        <h3 class="font-bold text-2xl text-primary" style="font-family: var(--font-serif)">Daftar Pertanyaan</h3>
                    </div>
                </div>
                <p class="text-xs text-error font-bold hidden" id="err-ujianQuestions"></p>
                
                <div class="space-y-4">
                    <!-- Question Card 1 -->
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
                            </div>
                        </div>
                        <div class="space-y-6">
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Teks Soal</label>
                                <textarea class="w-full p-4 bg-surface-container-low border border-outline rounded-lg focus:ring-2 focus:ring-secondary-container question-text" placeholder="Masukkan pertanyaan ujian di sini..." rows="3">Apa fungsi utama dari protokol DHCP pada jaringan komputer?</textarea>
                                <p class="text-xs text-error font-bold hidden err-text"></p>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Lampiran Gambar (Opsional)</label>
                                <input type="file" name="gambar_soal_1" accept="image/*" class="w-full p-3 bg-surface-container-low border border-outline rounded-lg question-image">
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
                                        <input class="flex-1 stationery-input py-1 question-option-text" value="Mentransmisi data via IP" type="text"/>
                                    </div>
                                    <div class="flex items-center gap-4 option-item-1" data-opt="B">
                                        <input class="w-5 h-5 text-secondary border-outline opt-input-1 question-correct" name="q1-correct" type="radio" value="B" checked/>
                                        <span class="font-bold text-on-surface-variant opt-label-1">B.</span>
                                        <input class="flex-1 stationery-input py-1 question-option-text" value="Memberikan IP address otomatis" type="text"/>
                                    </div>
                                    <div class="flex items-center gap-4 option-item-1" data-opt="C">
                                        <input class="w-5 h-5 text-secondary border-outline opt-input-1 question-correct" name="q1-correct" type="radio" value="C"/>
                                        <span class="font-bold text-on-surface-variant opt-label-1">C.</span>
                                        <input class="flex-1 stationery-input py-1 question-option-text" value="Mengenkripsi lalu lintas data" type="text"/>
                                    </div>
                                    <div class="flex items-center gap-4 option-item-1" data-opt="D">
                                        <input class="w-5 h-5 text-secondary border-outline opt-input-1 question-correct" name="q1-correct" type="radio" value="D"/>
                                        <span class="font-bold text-on-surface-variant opt-label-1">D.</span>
                                        <input class="flex-1 stationery-input py-1 question-option-text" value="Mengelola nama domain" type="text"/>
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
                </div>
                    
                <button class="w-full py-5 border-2 border-dashed border-outline-variant hover:border-secondary-container hover:bg-secondary-container/5 transition-all rounded-xl flex flex-col items-center justify-center gap-2 group" onclick="addQuestion()" type="button">
                        <span class="material-symbols-outlined text-4xl text-outline-variant group-hover:text-secondary-container transition-colors">add_circle</span>
                        <span class="font-bold text-on-surface-variant group-hover:text-primary transition-colors">Tambah Soal Baru</span>
                    </button>
                </div>
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
        <h3 class="text-xl font-bold text-[#50290b] mb-2" style="font-family: var(--font-serif)" id="actionModalTitle">Simpan Ujian?</h3>
        <p class="text-xs text-[#51443c] mb-6" id="actionModalDesc">Apakah Anda yakin data ujian sudah benar dan siap disimpan?</p>
        <div class="flex gap-2 justify-center" id="actionModalButtons">
            <button type="button" onclick="closeActionModal()" class="ui-btn ui-btn-secondary px-4 py-2 text-xs">Periksa Lagi</button>
            <button type="button" id="confirmActionBtn" class="ui-btn ui-btn-primary px-4 py-2 text-xs">Ya, Simpan</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
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
        const container = document.querySelector('#questionContainer .space-y-4');
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
                <div class="col-span-1 md:col-span-2 pg-multi-option" id="multi-${questionCount}">
                    <label class="flex items-center gap-3 cursor-pointer group w-max">
                        <input class="w-5 h-5 text-secondary border-outline focus:ring-secondary-container" type="checkbox" onchange="toggleMultiAnswer(this, ${questionCount})"/>
                        <span class="text-sm font-bold text-on-surface-variant group-hover:text-primary transition-colors">Jawaban benar lebih dari satu</span>
                    </label>
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Bobot Nilai</label>
                    <input type="number" min="0" value="10" class="w-full p-3 bg-surface-container-low border border-outline rounded-lg question-weight">
                </div>
            </div>

                <div class="space-y-6">
                <div class="space-y-2">
                    <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Teks Soal</label>
                    <textarea rows="3" placeholder="Masukkan pertanyaan ujian di sini..." class="w-full p-4 bg-surface-container-low border border-outline rounded-lg focus:ring-2 focus:ring-secondary-container question-text"></textarea>
                    <p class="text-xs text-error font-bold hidden err-text"></p>
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Lampiran Gambar (Opsional)</label>
                    <input type="file" name="gambar_soal_${questionCount}" accept="image/*" class="w-full p-3 bg-surface-container-low border border-outline rounded-lg question-image">
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
        void modal.offsetWidth;
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
            title.textContent = 'Simpan Ujian?';
            desc.className = 'text-xs text-[#51443c] mb-6';
            desc.textContent = 'Apakah Anda yakin data ujian sudah benar dan siap disimpan?';
            
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
        const checked = document.querySelectorAll('.class-checkbox:checked');
        const textSpan = document.getElementById('selectedClassesText');
        if (checked.length === 0) {
            textSpan.textContent = 'Pilih Kelas...';
            textSpan.classList.add('text-on-surface-variant');
            textSpan.classList.remove('font-semibold');
        } else {
            const values = Array.from(checked).map(cb => cb.getAttribute('data-name'));
            textSpan.textContent = values.join(', ');
            textSpan.classList.remove('text-on-surface-variant');
            textSpan.classList.add('font-semibold');
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        updateSelectedClasses();
        toggleScheduleInput();

        @if(isset($ujian) && $ujian->soal && $ujian->soal->count() > 0)
            // Pre-populate questions for Edit mode
            const soalData = @json($ujian->soal);
            
            // Remove the default empty card
            const defaultCard = document.querySelector('.question-card');
            if (defaultCard) defaultCard.remove();
            
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
            });
        @endif
    });

    document.addEventListener('click', function(event) {
        const container = document.getElementById('classDropdownContainer');
        const dropdown = document.getElementById('classesDropdown');
        if (container && !container.contains(event.target) && dropdown) {
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

        const name = document.getElementById('ujianName').value.trim();
        if (!name) showError('err-ujianName', 'Judul Ujian harus diisi.');

        const classes = document.querySelectorAll('.class-checkbox:checked');
        if (classes.length === 0) showError('err-ujianKelas', 'Pilih minimal satu Kelas.');

        const durasi = document.getElementById('ujianDuration').value;
        if (!durasi || durasi <= 0) showError('err-ujianDurasi', 'Durasi tidak valid.');

        const batas = document.getElementById('ujianBatas').value;
        if (!batas || batas <= 0) showError('err-ujianBatas', 'Batas percobaan tidak valid.');

        const checkedStatus = document.querySelector('input[name="status"]:checked');
        if (!checkedStatus) showError('err-ujianStatus', 'Pilih Status Ujian.');

        const scheduledRadio = document.querySelector('input[name="status"][value="terjadwal"]');
        const publishedRadio = document.querySelector('input[name="status"][value="published"]');
        const waktuMulai = document.getElementById('ujianWaktuMulai').value;
        const waktuBerakhir = document.getElementById('ujianWaktuBerakhir').value;

        if (scheduledRadio && scheduledRadio.checked) {
            if (!waktuMulai) {
                showError('err-ujianWaktuMulai', 'Waktu mulai harus diisi jika ujian terjadwal.');
            } else {
                const scheduleDate = new Date(waktuMulai);
                const now = new Date();
                const diffMinutes = (scheduleDate - now) / 1000 / 60;
                if (diffMinutes < 5) {
                    showError('err-ujianWaktuMulai', 'Waktu mulai minimal 5 menit dari waktu saat ini.');
                }
            }
            if (!waktuBerakhir) {
                showError('err-ujianWaktuBerakhir', 'Waktu berakhir harus diisi.');
            }
            if (waktuMulai && waktuBerakhir) {
                const start = new Date(waktuMulai);
                const end = new Date(waktuBerakhir);
                if (end <= start) {
                    showError('err-ujianWaktuBerakhir', 'Waktu berakhir tidak boleh sebelum atau sama dengan waktu mulai.');
                }
            }
        }
        
        if (publishedRadio && publishedRadio.checked) {
            if (!waktuBerakhir) {
                showError('err-ujianWaktuBerakhir', 'Waktu berakhir harus diisi untuk ujian yang diterbitkan.');
            } else {
                const end = new Date(waktuBerakhir);
                const now = new Date();
                if (end <= now) {
                    showError('err-ujianWaktuBerakhir', 'Waktu berakhir tidak boleh sebelum atau sama dengan waktu saat ini.');
                }
            }
        }

        const questions = document.querySelectorAll('.question-card');
        if (questions.length === 0) showError('err-ujianQuestions', 'Minimal harus ada 1 soal.');

        questions.forEach((q, index) => {
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
            firstErrorElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        return isValid;
    }

    function confirmAction() {
        if (currentAction === 'cancel') {
            closeActionModal();
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
                window.location.href = '/guru/ujian';
            }, 1500);
        } else if (currentAction === 'save') {
            if (validateForm()) {
                closeActionModal();
                const toastContainer = document.getElementById('toastContainer');
                const toast = document.createElement('div');
                toast.className = 'fixed top-5 left-1/2 -translate-x-1/2 z-[70] flex items-center gap-3 bg-green-100 border border-green-300 text-green-800 px-6 py-3 rounded-lg shadow-lg opacity-0 invisible transition-all duration-300 transform -translate-y-4';
                toast.innerHTML = `
                    <span class="material-symbols-outlined">check_circle</span>
                    <span class="font-bold text-sm whitespace-nowrap">${document.querySelector('h2').textContent.includes('Edit') ? 'Ujian berhasil diperbarui!' : 'Ujian berhasil disimpan!'}</span>
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
                    document.getElementById('ujianForm').appendChild(qInput);
                    
                    document.getElementById('ujianForm').submit();
                }, 1500);
            } else {
                closeActionModal();
            }
        }
    }

    // Simulating auto-save time
    const autoSaveTime = document.getElementById('autoSaveTime');
    if(autoSaveTime) {
        setInterval(() => {
            const now = new Date();
            const time = now.getHours() + ":" + String(now.getMinutes()).padStart(2, '0');
            autoSaveTime.innerText = time;
        }, 60000);
    }
</script>
@endpush
