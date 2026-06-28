@extends('layouts.guru')
@section('title', 'Penilaian Siswa - SMK Mandalahayu 1')

@section('content')
<style>
    .shadow-ambient {
        box-shadow: 0 4px 20px -2px rgba(107, 63, 31, 0.06);
    }
    .card-border {
        border: 1px solid #efe6de;
    }
</style>

<!-- Confirmation Modal -->
<div id="confirmModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl p-6 max-w-sm w-full mx-4 border border-outline-variant">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-full bg-secondary-container flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-on-secondary-container">help</span>
            </div>
            <div>
                <h3 class="font-bold text-lg text-primary" style="font-family: var(--font-serif)">Konfirmasi Simpan</h3>
            </div>
        </div>
        <p class="text-sm text-on-surface-variant mb-5">Apakah Anda yakin ingin menyimpan nilai ini?</p>
        <div class="flex gap-3">
            <button id="modalCancel" class="flex-1 py-2 border-2 border-outline-variant text-on-surface-variant font-bold rounded-xl hover:bg-surface-container transition-all text-sm">Batal</button>
            <button id="modalConfirm" class="flex-1 py-2 bg-secondary-container text-on-secondary-container font-bold rounded-xl hover:opacity-90 transition-all text-sm">Ya, Simpan</button>
        </div>
    </div>
</div>

<div class="min-h-[calc(100vh-100px)] lg:h-[calc(100vh-100px)] flex flex-col">
    <!-- Header Section -->
    <div class="mb-4 flex-shrink-0">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="font-bold text-2xl text-primary" style="font-family: var(--font-serif)">Penilaian Siswa</h1>
                <div class="flex items-center gap-3 mt-1 flex-wrap">
                    <span class="text-base font-semibold text-on-surface" id="studentName">{{ $submission->siswa->name }}</span>
                    @if($submission->dikumpulkan_at && $submission->tugas->deadline && $submission->dikumpulkan_at->gt($submission->tugas->deadline))
                    <span class="px-2 py-0.5 bg-error-container text-on-error-container text-[11px] font-bold rounded-full flex items-center gap-1 uppercase tracking-wider">
                        <span class="material-symbols-outlined text-[13px]">schedule</span> Terlambat
                    </span>
                    @endif
                    <span class="text-xs text-on-surface-variant font-medium">| {{ $submission->tugas->kelas->nama_kelas }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Asymmetric Split Grid -->
    <div class="flex-1 min-h-0 flex flex-col lg:flex-row gap-6 items-stretch">
        <!-- Left: Submission Content -->
        <div class="lg:w-[65%] flex flex-col min-h-0">
            <!-- Submission Info Card -->
            <div class="bg-surface-container-lowest rounded-2xl p-4 card-border shadow-ambient flex-1 flex flex-col min-h-0">
                <div class="flex justify-between items-start mb-4 border-b border-outline-variant/30 pb-3 flex-wrap gap-3 flex-shrink-0">
                    <div>
                        <h3 class="font-bold text-lg text-primary mb-1" style="font-family: var(--font-serif)">{{ $submission->tugas->judul }}</h3>
                        <p class="text-xs text-on-surface-variant">Mata Pelajaran: {{ $submission->tugas->kelas->mata_pelajaran }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-bold uppercase text-on-surface-variant mb-1">Waktu Submit</p>
                        <p class="text-xs font-medium text-on-surface">{{ $submission->dikumpulkan_at ? $submission->dikumpulkan_at->format('d M Y, H:i') . ' WIB' : 'Belum dikumpulkan' }}</p>
                    </div>
                </div>

                <!-- File Display -->
                <div class="relative group flex-1 w-full bg-surface-container rounded-xl overflow-hidden shadow-inner border border-outline-variant/30 min-h-0">
                    <div class="w-full h-full flex flex-col items-center justify-center gap-4 text-on-surface-variant p-4 text-center">
                        @if($submission->file_path && $submission->file_path !== '-')
                            @php
                                $ext = pathinfo($submission->file_path, PATHINFO_EXTENSION);
                                $icon = in_array(strtolower($ext), ['png','jpg','jpeg','gif']) ? 'image' : (strtolower($ext) == 'pdf' ? 'picture_as_pdf' : 'description');
                            @endphp
                            <span class="material-symbols-outlined text-5xl text-primary/30">{{ $icon }}</span>
                            <p class="text-sm font-medium">{{ basename($submission->file_path) }}</p>
                            <a href="{{ \Illuminate\Support\Facades\Storage::disk(env('FILESYSTEM_DISK', 'public'))->url($submission->file_path) }}" target="_blank" class="bg-white text-primary px-4 py-1.5 rounded-full font-bold shadow text-xs border border-outline-variant flex items-center gap-1.5 hover:bg-surface-container-lowest transition-colors">
                                <span class="material-symbols-outlined text-[13px]">open_in_new</span> Buka File
                            </a>
                        @else
                            <span class="material-symbols-outlined text-5xl text-primary/30">do_not_disturb</span>
                            <p class="text-sm font-medium">Tidak ada file yang diunggah.</p>
                        @endif
                    </div>
                </div>

                @if($submission->file_path && $submission->file_path !== '-')
                <div class="mt-3 flex items-center justify-between p-3 bg-surface-container rounded-lg border border-outline-variant/30 flex-shrink-0">
                    <div class="flex items-center gap-3 overflow-hidden">
                        <div class="w-8 h-8 bg-primary/10 rounded flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-primary text-xl">{{ $icon ?? 'description' }}</span>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-on-surface truncate">{{ basename($submission->file_path) }}</p>
                            <p class="text-[10px] text-on-surface-variant uppercase">{{ $ext ?? 'file' }} Document</p>
                        </div>
                    </div>
                    <a href="{{ \Illuminate\Support\Facades\Storage::disk(env('FILESYSTEM_DISK', 'public'))->url($submission->file_path) }}" download class="text-primary hover:underline font-bold text-xs flex items-center gap-1 flex-shrink-0">
                        <span class="material-symbols-outlined text-sm">download</span> Unduh
                    </a>
                </div>
                @endif
            </div>
        </div>

        <!-- Right: Grading Panel -->
        <div class="lg:w-[35%] flex flex-col min-h-0">
            <div class="bg-surface-container-high rounded-2xl p-4 shadow-ambient border-2 border-primary-container/10 flex-1 flex flex-col min-h-0">
                <h3 class="font-bold text-lg text-primary mb-3 flex-shrink-0" style="font-family: var(--font-serif)">Penilaian</h3>
                
                <form id="gradingForm" action="{{ route('guru.penilaian.tugas.store', $submission->id) }}" method="POST" class="flex-1 flex flex-col min-h-0 space-y-4">
                    @csrf
                    <!-- Grade Input -->
                    <div class="flex-shrink-0">
                        <label class="text-[11px] font-bold uppercase tracking-wider text-primary mb-1 block">Nilai (0-100)</label>
                        <div class="relative">
                            <input name="nilai" value="{{ old('nilai', $submission->nilai) }}" class="w-full text-3xl font-bold p-3 bg-white border-b-2 border-primary focus:ring-0 focus:border-secondary transition-all rounded-t-xl" id="gradeInput" max="100" min="0" placeholder="0" type="number" required/>
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-lg font-bold text-on-surface-variant/40">/ 100</span>
                        </div>
                        <p id="gradeError" class="text-error text-xs font-bold mt-1 hidden">Nilai harus diisi sebelum disimpan!</p>
                    </div>

                    <!-- Feedback Area -->
                    <div class="flex-1 flex flex-col min-h-0">
                        <label class="text-[11px] font-bold uppercase tracking-wider text-primary mb-1 block">Feedback Guru</label>
                        <textarea name="feedback" class="flex-1 w-full bg-white border border-outline-variant rounded-xl p-3 text-xs focus:ring-2 focus:ring-secondary/30 transition-all focus:outline-none resize-none" placeholder="Berikan komentar konstruktif untuk siswa...">{{ old('feedback', $submission->feedback) }}</textarea>
                    </div>
                    
                    <!-- Late Penalty -->
                    @if($submission->dikumpulkan_at && $submission->tugas->deadline && $submission->dikumpulkan_at->gt($submission->tugas->deadline))
                    <div class="p-3 bg-red-50 border border-red-100 rounded-xl flex items-center justify-between flex-shrink-0">
                        <span class="text-[11px] font-bold text-red-700 uppercase tracking-wider">Pinalti Terlambat</span>
                        <span class="text-xs font-bold text-red-700">Tenggat terlewati</span>
                    </div>
                    @endif

                    <!-- Actions -->
                    <div class="pt-3 border-t border-outline-variant/30 flex-shrink-0">
                        <button type="button" id="saveBtn" class="w-full px-6 py-2.5 bg-[#feae2c] text-[#6b4500] text-sm font-bold rounded-lg flex items-center justify-center gap-2 hover:brightness-110 transition-soft">
                            <span class="material-symbols-outlined" style="font-size: 18px">save</span> Simpan Nilai
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Toast Success -->
<div id="toast-success" class="fixed top-5 left-1/2 -translate-x-1/2 z-[70] flex items-center gap-3 bg-green-100 border border-green-300 text-green-800 px-6 py-3 rounded-lg shadow-lg opacity-0 invisible transition-all duration-300 transform -translate-y-4">
    <span class="material-symbols-outlined">check_circle</span>
    <span class="font-bold text-sm">Nilai berhasil disimpan!</span>
</div>

<!-- Modal Konfirmasi Simpan -->
<div id="modal-confirm-simpan" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/50 hidden backdrop-blur-sm transition-opacity">
    <div class="bg-[#fef9f3] rounded-xl shadow-2xl p-6 w-full max-w-sm border border-[#d6c3b8] text-center">
        <span class="material-symbols-outlined text-[#feae2c] text-5xl mb-4">help</span>
        <h3 class="text-xl font-bold text-[#50290b] mb-2" style="font-family: var(--font-serif)">Simpan Nilai?</h3>
        <p class="text-xs text-[#51443c] mb-6">Apakah Anda yakin nilai yang diberikan sudah benar dan siap disimpan?</p>
        <div class="flex gap-2 justify-center">
            <button type="button" id="btn-cancel-simpan" class="px-4 py-2 rounded-lg font-bold text-xs text-[#51443c] border border-[#d6c3b8] hover:bg-[#f8f3ed] transition-colors">Periksa Lagi</button>
            <button type="button" id="btn-confirm-simpan" class="px-4 py-2 rounded-lg font-bold text-xs bg-[#feae2c] text-[#6b4500] hover:brightness-110 transition-all">Ya, Simpan</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const saveBtn = document.getElementById('saveBtn');
    const gradeInput = document.getElementById('gradeInput');
    const gradeError = document.getElementById('gradeError');
    const confirmModal = document.getElementById('modal-confirm-simpan');
    const modalCancel = document.getElementById('btn-cancel-simpan');
    const modalConfirm = document.getElementById('btn-confirm-simpan');
    const toastSuccess = document.getElementById('toast-success');

    gradeInput.addEventListener('input', (e) => {
        let val = parseInt(e.target.value);
        if (val > 100) e.target.value = 100;
        if (val < 0) e.target.value = 0;
        if (val >= 75) {
            gradeInput.style.color = '#15803d';
        } else if (val > 0) {
            gradeInput.style.color = '#b91c1c';
        } else {
            gradeInput.style.color = '';
        }
        
        if (e.target.value !== '') {
            gradeError.classList.add('hidden');
            gradeInput.classList.remove('border-red-500');
        }
    });

    saveBtn.addEventListener('click', () => {
        if (!gradeInput.value || gradeInput.value === '') {
            gradeError.classList.remove('hidden');
            gradeInput.classList.add('border-red-500');
            return;
        }
        confirmModal.classList.remove('hidden');
        confirmModal.classList.add('flex');
    });

    modalCancel.addEventListener('click', () => {
        confirmModal.classList.add('hidden');
        confirmModal.classList.remove('flex');
    });

    modalConfirm.addEventListener('click', () => {
        confirmModal.classList.add('hidden');
        confirmModal.classList.remove('flex');
        
        // Actually submit the form
        document.getElementById('gradingForm').submit();
    });
</script>
@endpush
@endsection
