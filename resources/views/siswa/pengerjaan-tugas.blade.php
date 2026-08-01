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
                @php
                    $ext = strtolower(pathinfo($tugas->file_path, PATHINFO_EXTENSION));
                    $fileUrl = \Illuminate\Support\Facades\Storage::disk(env('FILESYSTEM_DISK', 'public'))->url($tugas->file_path);
                    $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                    $isPdf = $ext === 'pdf';
                    $icon = 'description';
                    if (in_array($ext, ['ppt', 'pptx'])) $icon = 'slideshow';
                    elseif (in_array($ext, ['xls', 'xlsx'])) $icon = 'table_view';
                    elseif (in_array($ext, ['zip', 'rar'])) $icon = 'folder_zip';
                @endphp
                
                @php
                    if (!isset($icon) || $icon === 'description') {
                        if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                            $icon = 'image';
                        }
                    }
                @endphp
                <div class="bg-surface rounded-lg border border-outline-variant shadow-sm w-full xl:w-[95%]">
                    <div class="flex items-center justify-between p-3 bg-surface-container-low rounded-t-lg">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="p-2 bg-primary/10 text-primary rounded-md flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-[20px]">{{ $icon }}</span>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="font-bold text-sm text-on-surface truncate">{{ $tugas->original_file_name ?? 'Lampiran Tugas.' . $ext }}</p>
                                <p class="text-[10px] text-on-surface-variant">Dokumen terlampir</p>
                            </div>
                        </div>
                        <a href="{{ $fileUrl }}" download class="shrink-0 flex items-center gap-1.5 px-3 py-1.5 bg-primary/10 text-primary hover:bg-primary hover:text-white rounded-lg transition-colors text-xs font-bold" target="_blank" title="Download File">
                            <span class="material-symbols-outlined text-[16px]">download</span>
                            <span class="hidden sm:inline">Unduh</span>
                        </a>
                    </div>
                    @if($isImage)
                        <img src="{{ $fileUrl }}" class="w-full h-auto max-h-96 object-contain rounded-b-lg border-t border-outline-variant/30" alt="Lampiran Tugas">
                    @elseif($isPdf)
                        <iframe src="{{ $fileUrl }}#toolbar=0" class="w-full h-96 rounded-b-lg border-t border-outline-variant/30"></iframe>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </section>

    <!-- RIGHT PANEL: Submission Area -->
    <section class="w-full md:w-1/2 flex flex-col min-h-[420px] md:h-full gap-4">
        <div class="bg-surface-container-lowest rounded-xl p-4 shadow-sm border border-outline-variant/30 flex justify-between items-center shrink-0">
            @php $submission = $tugas->pengumpulan->first(); @endphp
            <div>
                <p class="font-bold text-[10px] text-on-surface-variant">Status Pengumpulan</p>
                <div class="flex items-center gap-1.5 mt-1">
                    @if($submission)
                        @if($submission->nilai !== null)
                            <span class="material-symbols-outlined text-[16px] text-green-600">verified</span>
                            <p class="font-bold text-xs text-green-600">Sudah Dinilai</p>
                        @else
                            <span class="material-symbols-outlined text-[16px] text-secondary">task_alt</span>
                            <p class="font-bold text-xs text-secondary">Sudah diserahkan</p>
                        @endif
                    @else
                        <span class="material-symbols-outlined text-[16px] text-outline">pending_actions</span>
                        <p class="font-bold text-xs text-on-surface">Belum diserahkan</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-surface-container-lowest rounded-xl p-5 shadow-sm border border-outline-variant/30 flex-1 flex flex-col overflow-hidden">
            <h3 class="font-bold text-sm text-primary mb-3" style="font-family: var(--font-serif)">Area Pengumpulan</h3>
            
            @if(!$submission)
            <form id="form-pengumpulan" action="{{ route('siswa.kumpul-tugas', $tugas->id) }}" method="POST" enctype="multipart/form-data" class="flex-1 flex flex-col min-h-0">
                @csrf
                <div class="flex-1 overflow-y-auto custom-scrollbar flex flex-col pr-1 gap-3">
                    <div class="shrink-0">
                        <label class="block font-bold text-[10px] text-on-surface mb-1.5">Catatan Tambahan (Opsional)</label>
                        <div class="border border-outline-variant rounded-lg overflow-hidden focus-within:border-secondary transition-all">
                            <textarea name="catatan" class="w-full p-2 bg-transparent border-none focus:ring-0 text-[11px] text-on-surface resize-none placeholder-on-surface-variant/50" placeholder="Tuliskan pesan untuk guru..." rows="2"></textarea>
                        </div>
                    </div>
                    
                    @php
                        $formats = $tugas->format_pengumpulan;
                        if(is_string($formats)) $formats = json_decode($formats, true);
                        if(!is_array($formats)) $formats = ['pdf', 'gambar', 'dokumen']; // fallback

                        $acceptArr = [];
                        $labelArr = [];
                        if (in_array('pdf', $formats)) { $acceptArr[] = '.pdf'; $labelArr[] = 'PDF'; }
                        if (in_array('gambar', $formats)) { array_push($acceptArr, '.jpg', '.jpeg', '.png'); $labelArr[] = 'JPG/PNG'; }
                        if (in_array('dokumen', $formats)) { array_push($acceptArr, '.doc', '.docx'); $labelArr[] = 'DOCX'; }

                        $acceptStr = !empty($acceptArr) ? implode(',', $acceptArr) : '.pdf,.zip,.rar,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png';
                        $labelStr = !empty($labelArr) ? implode('/', $labelArr) : 'PDF/DOCX/ZIP/RAR/JPG/PNG';
                        
                        $isLinkAllowed = in_array('link', $formats);
                        $isFileAllowed = count(array_diff($formats, ['link'])) > 0 || empty($formats);
                    @endphp
                    
                    @if($isLinkAllowed)
                    <div class="flex-1 flex flex-col mb-4">
                        <label class="block font-bold text-[10px] text-on-surface mb-1.5">Tautan / Link Tugas {!! !$isFileAllowed ? '<span class="text-error">*</span>' : '' !!}</label>
                        <input type="url" name="link" id="link-upload" class="w-full bg-white border border-outline-variant rounded-xl p-3 text-xs focus:ring-2 focus:ring-secondary/30 transition-all focus:outline-none" placeholder="https://..." {{ !$isFileAllowed ? 'required' : '' }}>
                        <p id="link-error" class="text-[10px] font-bold text-error mt-2 hidden">Tautan valid wajib diisi!</p>
                    </div>
                    @endif

                    @if($isFileAllowed)
                    <div class="flex-1 flex flex-col min-h-[120px]">
                        <label class="block font-bold text-[10px] text-on-surface mb-1.5">Unggah Berkas {!! !$isLinkAllowed ? '<span class="text-error">*</span>' : '' !!}</label>
                        <input type="file" id="file-upload" name="file" accept="{{ $acceptStr }}" class="hidden" onchange="handleFileUpload(event)">
                        <div id="upload-zone" onclick="document.getElementById('file-upload').click()" class="border-2 border-dashed border-outline-variant hover:border-secondary bg-surface-container-low hover:bg-surface-container rounded-xl flex-1 flex flex-col items-center justify-center text-center cursor-pointer transition-colors group p-4">
                            <div class="w-10 h-10 bg-surface-container-highest rounded-full flex items-center justify-center mb-2 group-hover:bg-primary-fixed-dim/20 transition-colors">
                                <span class="material-symbols-outlined text-xl text-primary group-hover:text-secondary">cloud_upload</span>
                            </div>
                            <p id="upload-text" class="text-xs text-on-surface font-bold">Tarik & lepas file</p>
                            <p id="upload-subtext" class="text-[10px] text-on-surface-variant">atau klik untuk mencari dari perangkat</p>
                            <p class="text-[9px] text-on-surface-variant mt-2 font-bold">Maks. 50MB ({{ $labelStr }})</p>
                        </div>
                        <p id="file-error" class="text-[10px] font-bold text-error mt-2 hidden">Berkas wajib diunggah!</p>
                    </div>
                    @endif
                </div>

                <div class="mt-4 pt-3 border-t border-surface-container flex flex-col gap-3 shrink-0">
                    <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 w-full">
                        <button type="button" id="btn-trigger-batal" class="ui-btn ui-btn-secondary px-6 py-2 text-sm">
                            Batalkan
                        </button>
                        <button type="button" id="btn-trigger-simpan" class="ui-btn ui-btn-primary px-6 py-2 text-sm">
                            <span class="material-symbols-outlined" style="font-size: 18px">send</span> Kumpulkan
                        </button>
                    </div>
                </div>
            </form>
            @else
            <div class="flex-1 overflow-y-auto custom-scrollbar flex flex-col pr-1 gap-4">
                @if($submission->file_path && $submission->file_path !== '-')
                <div class="bg-surface-container-low border border-outline-variant rounded-xl p-4">
                    <p class="font-bold text-[10px] text-on-surface-variant mb-2 uppercase tracking-wider">File Diserahkan</p>
                    
                    @php
                        $ext = strtolower(pathinfo($submission->file_path, PATHINFO_EXTENSION));
                        $fileUrl = \Illuminate\Support\Facades\Storage::disk(env('FILESYSTEM_DISK', 'public'))->url($submission->file_path);
                    @endphp
                    
                    @if(in_array($ext, ['png','jpg','jpeg','gif','webp']))
                        <div class="mb-3">
                            <img src="{{ $fileUrl }}" alt="Preview" class="max-w-full max-h-64 object-contain rounded-lg border border-outline-variant/30">
                        </div>
                    @elseif($ext == 'pdf')
                        <div class="mb-3">
                            <iframe src="{{ $fileUrl }}" class="w-full h-64 border-0 rounded-lg shadow-sm"></iframe>
                        </div>
                    @endif
                    
                    <a href="{{ $fileUrl }}" target="_blank" class="flex items-center gap-3 p-3 bg-surface rounded-lg border border-outline-variant hover:border-secondary hover:shadow-md transition-all group">
                        <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-primary group-hover:text-secondary">{{ in_array($ext, ['png','jpg','jpeg','gif','webp']) ? 'image' : ($ext == 'pdf' ? 'picture_as_pdf' : 'description') }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-xs text-on-surface truncate group-hover:text-secondary transition-colors">{{ basename($submission->file_path) }}</p>
                            <p class="text-[10px] text-on-surface-variant mt-0.5">{{ $submission->dikumpulkan_at ? $submission->dikumpulkan_at->format('d M Y, H:i') : '' }} WIB</p>
                        </div>
                        <span class="material-symbols-outlined text-on-surface-variant group-hover:text-secondary">open_in_new</span>
                    </a>
                </div>
                @endif
                
                @if($submission->link)
                <div class="bg-surface-container-low border border-outline-variant rounded-xl p-4">
                    <p class="font-bold text-[10px] text-on-surface-variant mb-2 uppercase tracking-wider">Tautan / Link Diserahkan</p>
                    <a href="{{ $submission->link }}" target="_blank" class="flex items-center gap-3 p-3 bg-surface rounded-lg border border-outline-variant hover:border-secondary hover:shadow-md transition-all group">
                        <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-primary group-hover:text-secondary">link</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-xs text-on-surface truncate group-hover:text-secondary transition-colors">{{ $submission->link }}</p>
                            <p class="text-[10px] text-on-surface-variant mt-0.5">{{ $submission->dikumpulkan_at ? $submission->dikumpulkan_at->format('d M Y, H:i') : '' }} WIB</p>
                        </div>
                        <span class="material-symbols-outlined text-on-surface-variant group-hover:text-secondary">open_in_new</span>
                    </a>
                </div>
                @endif
                
                @if($submission->catatan)
                <div class="bg-surface-container-low border border-outline-variant rounded-xl p-4">
                    <p class="font-bold text-[10px] text-on-surface-variant mb-2 uppercase tracking-wider">Catatan Anda</p>
                    <p class="text-xs text-on-surface leading-relaxed">{{ $submission->catatan }}</p>
                </div>
                @endif

                @if($submission->nilai !== null)
                <div class="bg-primary/5 border border-primary/20 rounded-xl p-4">
                    <div class="flex items-center justify-between mb-3 border-b border-primary/10 pb-3">
                        <p class="font-bold text-[10px] text-primary uppercase tracking-wider">Nilai dari Guru</p>
                        <span class="px-2 py-1 bg-primary text-on-primary font-bold text-xs rounded-md shadow-sm">{{ $submission->nilai }} / 100</span>
                    </div>
                    @if($submission->feedback)
                    <p class="font-bold text-[10px] text-primary mb-1 uppercase tracking-wider">Umpan Balik</p>
                    <p class="text-xs text-on-surface leading-relaxed">{{ $submission->feedback }}</p>
                    @endif
                </div>
                @else
                <div class="bg-surface-container-low border border-outline-variant border-dashed rounded-xl p-4 text-center">
                    <span class="material-symbols-outlined text-outline text-3xl mb-1">hourglass_empty</span>
                    <p class="font-bold text-xs text-on-surface-variant">Menunggu Penilaian Guru</p>
                </div>
                @endif
            </div>
            @endif
        </div>
    </section>
</div>

<!-- Modal Konfirmasi Kumpulkan -->
<div id="modal-confirm-simpan" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/50 hidden backdrop-blur-sm transition-opacity">
    <div class="ui-modal-card">
        <span class="material-symbols-outlined text-[#feae2c] text-5xl mb-4">help</span>
        <h3 class="text-xl font-bold text-[#50290b] mb-2" style="font-family: var(--font-serif)">Kumpulkan Tugas?</h3>
        <p class="text-xs text-[#51443c] mb-6">Pastikan berkas sudah lengkap karena tidak bisa diubah lagi.</p>
        <div class="flex gap-2 justify-center">
            <button type="button" id="btn-cancel-simpan" class="ui-btn ui-btn-secondary px-4 py-2 text-xs">Periksa Lagi</button>
            <button type="button" id="btn-confirm-simpan" class="ui-btn ui-btn-primary px-4 py-2 text-xs">Ya, Kumpulkan</button>
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
            const linkInput = document.getElementById('link-upload');
            let isValid = true;
            
            const isFileAllowed = fileInput !== null;
            const isLinkAllowed = linkInput !== null;
            
            const hasFile = isFileAllowed && fileInput.files && fileInput.files.length > 0;
            const hasLink = isLinkAllowed && linkInput.value.trim() !== '';

            if (isFileAllowed && !isLinkAllowed && !hasFile) {
                document.getElementById('file-error').classList.remove('hidden');
                isValid = false;
            } else if (isLinkAllowed && !isFileAllowed && !hasLink) {
                document.getElementById('link-error').classList.remove('hidden');
                isValid = false;
            } else if (isFileAllowed && isLinkAllowed && !hasFile && !hasLink) {
                document.getElementById('file-error').classList.remove('hidden');
                document.getElementById('link-error').classList.remove('hidden');
                isValid = false;
            }
            
            if (!isValid) return;
            
            if (isFileAllowed) document.getElementById('file-error').classList.add('hidden');
            if (isLinkAllowed) document.getElementById('link-error').classList.add('hidden');
            
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
