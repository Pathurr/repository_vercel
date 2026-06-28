@extends('layouts.guru')
@section('title', 'Penilaian Ujian - SMK Mandalahayu 1')

@section('content')
<style>
    .smooth-wave-bg {
        background-image: radial-gradient(circle at 2px 2px, rgba(107, 63, 31, 0.04) 1px, transparent 0);
        background-size: 24px 24px;
    }
    .shadow-ambient { box-shadow: 0 4px 20px -2px rgba(107, 63, 31, 0.06); }

    /* Student Navigator Thumbs */
    .student-thumb {
        cursor: pointer;
        transition: all .25s;
        width: 32px; height: 32px;
        border-radius: 9999px;
        border-width: 2px;
        border-style: solid;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 10px;
        flex-shrink: 0;
    }
    .student-thumb.active {
        border-color: #835500 !important;
        background-color: #ffdcc6 !important;
        color: #50290b !important;
        opacity: 1 !important;
        filter: none !important;
        box-shadow: 0 0 0 3px rgba(131,85,0,.2);
    }
    .student-thumb.inactive {
        border-color: #d6c3b8;
        background-color: #f2ede7;
        color: #84746b;
        opacity: .55;
        filter: grayscale(.8);
    }
    .student-thumb.inactive:hover {
        opacity: .85;
        filter: none;
    }

</style>

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

<div class="smooth-wave-bg -m-6 p-4" style="min-height: calc(100vh - 64px);">
<div class="max-w-full mx-auto h-full flex flex-col">

    <!-- Page Title Badge -->
    <div class="flex items-center gap-2 mb-2">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-secondary-container text-on-secondary-container rounded-full text-xs font-bold uppercase tracking-wider">
            <span class="material-symbols-outlined text-sm">contract_edit</span> Penilaian Ujian
        </span>
        <span class="text-on-surface-variant text-xs">Ujian Tengah Semester Ganjil</span>
    </div>

    <div class="flex flex-col lg:flex-row gap-6 flex-1">
        <!-- Left Column: 70% -->
        <div class="w-full lg:basis-[68%] lg:max-w-[68%] min-w-0 flex flex-col">

            <!-- Exam Header Card -->
            <div class="bg-surface-container-lowest p-3 rounded-xl shadow-ambient border border-outline-variant/30 mb-3">
                <div class="flex flex-col md:flex-row justify-between items-start gap-2 mb-2">
                    <div>
                        <h1 class="font-bold text-lg text-primary mb-0" style="font-family: var(--font-serif)">{{ $submission['siswa']->name }}</h1>
                        <p class="text-xs text-on-surface-variant">{{ $submission['ujian']->kelas->nama_kelas }} · {{ $submission['ujian']->kelas->mata_pelajaran }}</p>
                    </div>
                </div>
            </div>

            <!-- Questions — scrollable -->
            <div class="overflow-y-auto space-y-3 pr-1" style="max-height: calc(100vh - 220px)">
                @foreach($submission['answers'] as $index => $ans)
                <div class="bg-surface-container-lowest p-4 rounded-xl shadow-ambient border border-outline-variant/30 relative">
                    @if($ans->soal->tipe !== 'essay')
                        <div class="absolute top-4 right-4 flex items-center gap-2">
                            @if($ans->benar)
                            <span class="bg-green-100 text-green-700 px-2.5 py-1 rounded-full text-xs font-bold flex items-center gap-1 border border-green-200">
                                <span class="material-symbols-outlined text-sm">check_circle</span> Benar
                            </span>
                            @else
                            <span class="bg-error-container text-on-error-container px-2.5 py-1 rounded-full text-xs font-bold flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">cancel</span> Salah
                            </span>
                            @endif
                        </div>
                    @else
                        <div class="absolute top-4 right-4 flex items-center gap-2">
                            <span class="bg-secondary-container/20 text-on-secondary-container px-2.5 py-1 rounded-full text-xs font-bold flex items-center gap-1 border border-secondary-container/40">
                                <span class="material-symbols-outlined text-sm">edit</span> Essay (Nilai Manual)
                            </span>
                        </div>
                    @endif
                    
                    <h4 class="text-xs font-bold text-secondary uppercase tracking-wider mb-2">Pertanyaan {{ $index + 1 }} — {{ $ans->soal->tipe === 'essay' ? 'Essay' : 'Pilihan Ganda' }}</h4>
                    <p class="text-base font-semibold text-on-surface mb-4 pr-28">{!! $ans->soal->pertanyaan !!}</p>
                    
                    @if($ans->soal->tipe !== 'essay')
                        <div class="space-y-2">
                            @foreach($ans->soal->pilihan ?? [] as $optIdx => $optText)
                                @php
                                    $isSelected = ((string)$ans->jawaban === (string)$optIdx);
                                    $isCorrectOpt = ((string)$ans->soal->jawaban_benar === (string)$optIdx);
                                    
                                    $bgClass = 'bg-surface-container border-outline-variant/20';
                                    $icon = '';
                                    if ($isSelected && $isCorrectOpt) {
                                        $bgClass = 'bg-green-50 border-green-500 text-green-900 border-2';
                                        $icon = '<span class="material-symbols-outlined text-green-600 text-lg ml-auto">check</span>';
                                    } elseif ($isSelected && !$isCorrectOpt) {
                                        $bgClass = 'bg-error-container/20 border-error text-on-error-container border-2';
                                        $icon = '<span class="material-symbols-outlined text-error text-lg ml-auto">close</span>';
                                    } elseif (!$isSelected && $isCorrectOpt) {
                                        $bgClass = 'bg-green-50/50 border-green-400/50 text-green-800 border-2';
                                        $icon = '<span class="text-xs ml-auto font-bold">(Kunci Jawaban)</span>';
                                    }
                                @endphp
                                <div class="flex items-center p-3 rounded-lg border {{ $bgClass }}">
                                    <span class="text-sm flex-1">{{ is_string($optText) ? $optText : '' }}</span>
                                    {!! $icon !!}
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-surface-bright p-4 rounded-lg border border-outline-variant/30 mb-4 italic text-on-surface text-sm leading-relaxed">
                            "{{ $ans->jawaban ?? 'Siswa tidak menjawab.' }}"
                        </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        <!-- Right: Grading Sidebar 30% -->
        <aside class="w-full lg:basis-[30%] lg:max-w-[30%] flex-shrink-0 lg:sticky lg:top-4 lg:h-[calc(100vh-88px)]">
            <form id="gradingForm" method="POST" action="{{ route('guru.penilaian.ujian.store', ['ujian_id' => $submission['ujian']->id, 'siswa_id' => $submission['siswa']->id]) }}" class="bg-surface-container-highest p-4 rounded-xl shadow-ambient border border-outline-variant/30 h-full flex flex-col">
                @csrf
                <h3 class="font-bold text-[15px] text-primary mb-3 flex-shrink-0" style="font-family: var(--font-serif)">Ringkasan Nilai</h3>

                @php
                    $mcqTotal = $submission['answers']->where('soal.tipe', '!=', 'essay')->count();
                    $mcqScore = $submission['answers']->where('soal.tipe', '!=', 'essay')->where('benar', true)->count();
                    $baseScore = $mcqTotal > 0 ? ($mcqScore / $mcqTotal) * 100 : 0;
                    
                    // Fetch existing score if any
                    $existingNilai = \App\Models\Nilai::where('siswa_id', $submission['siswa']->id)
                        ->where('nilaiable_type', \App\Models\Ujian::class)
                        ->where('nilaiable_id', $submission['ujian']->id)
                        ->first();
                @endphp

                <!-- Score Breakdown -->
                <div class="space-y-1.5 mb-5 flex-shrink-0">
                    <div class="flex justify-between text-[11px]">
                        <span class="text-on-surface-variant">Skor Auto (PG)</span>
                        <span class="font-bold text-primary">{{ $mcqScore }}/{{ $mcqTotal }} ({{ round($baseScore) }} Poin)</span>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="text-[11px] font-bold uppercase tracking-wider text-primary mb-1 block">Nilai Keseluruhan (0-100)</label>
                    <div class="relative">
                        <input name="nilai" value="{{ old('nilai', $existingNilai ? $existingNilai->nilai : round($baseScore)) }}" class="w-full text-3xl font-bold p-3 bg-white border-b-2 border-primary focus:ring-0 focus:border-secondary transition-all rounded-t-xl text-center" max="100" min="0" placeholder="0" type="number" required/>
                    </div>
                    <p class="text-[10px] text-on-surface-variant mt-1 text-center">Sesuaikan nilai di atas jika ada soal essay.</p>
                </div>

                <div class="space-y-2 flex-1 flex flex-col min-h-0 justify-end">
                    <!-- Actions -->
                    <div class="pt-3 border-t border-outline-variant/30 flex-shrink-0">
                        <button type="button" id="btn-trigger-simpan" class="w-full px-6 py-2.5 bg-[#feae2c] text-[#6b4500] text-sm font-bold rounded-lg flex items-center justify-center gap-2 hover:brightness-110 transition-soft">
                            <span class="material-symbols-outlined" style="font-size: 18px">save</span> Simpan Nilai
                        </button>
                    </div>
                </div>
            </form>
        </aside>
    </div>
</div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // ── Student Navigator ──────────────────────────────────────
    const thumbs = document.querySelectorAll('.student-thumb');
    const prevBtn = document.getElementById('prevStudent');
    const nextBtn = document.getElementById('nextStudent');
    const navCounter = document.getElementById('navCounter');
    let currentIndex = 0;

    function activateStudent(idx) {
        thumbs.forEach((t, i) => {
            t.classList.toggle('active', i === idx);
            t.classList.toggle('inactive', i !== idx);
        });
        navCounter.textContent = `Siswa ${idx + 1} dari ${thumbs.length}`;
        prevBtn.style.opacity = idx === 0 ? '.35' : '1';
        nextBtn.style.opacity = idx === thumbs.length - 1 ? '.35' : '1';
        currentIndex = idx;
    }

    const urlParams = new URLSearchParams(window.location.search);
    const sIndex = urlParams.get('s');
    if (sIndex !== null && !isNaN(sIndex) && sIndex >= 0 && sIndex < thumbs.length) {
        currentIndex = parseInt(sIndex);
    }

    thumbs.forEach((t, i) => t.addEventListener('click', () => activateStudent(i)));
    prevBtn.addEventListener('click', () => { if (currentIndex > 0) activateStudent(currentIndex - 1); });
    nextBtn.addEventListener('click', () => { if (currentIndex < thumbs.length - 1) activateStudent(currentIndex + 1); });
    activateStudent(currentIndex);

    // ── Dynamic Score Calculation ──────────────────────────────
    const AUTO_CORRECT = 55;
    const AUTO_MAX = 70;
    const ESSAY_MAX_TOTAL = 30;

    function recalcScore() {
        let essayGained = 0;
        document.querySelectorAll('.essay-score').forEach(inp => {
            const v = parseInt(inp.value) || 0;
            const max = parseInt(inp.dataset.max) || 30;
            essayGained += Math.min(Math.max(v, 0), max);
        });
        const total = AUTO_CORRECT + essayGained;
        const totalMax = AUTO_MAX + ESSAY_MAX_TOTAL;
        const pct = Math.round((total / totalMax) * 100);
        document.getElementById('totalScore').textContent = pct;
        document.getElementById('totalPercent').textContent = pct + '%';
        document.getElementById('manualScore').textContent = essayGained + '/' + ESSAY_MAX_TOTAL;
        const circle = document.querySelector('.score-circle');
        circle.classList.add('scale-105');
        setTimeout(() => circle.classList.remove('scale-105'), 200);
    }
    document.querySelectorAll('.essay-score').forEach(inp => inp.addEventListener('input', recalcScore));

    // ── Modals & Toasts ─────────────────────────────────────
    const monitorUrl = "{{ route('guru.monitor') }}";
    
    const modalSimpan = document.getElementById('modal-confirm-simpan');
    const toastSuccess = document.getElementById('toast-success');
    const btnTriggerSimpan = document.getElementById('btn-trigger-simpan');
    const btnConfirmSimpan = document.getElementById('btn-confirm-simpan');
    const btnCancelSimpan = document.getElementById('btn-cancel-simpan');

    // Trigger Modals
    btnTriggerSimpan.addEventListener('click', () => {
        modalSimpan.classList.remove('hidden');
        modalSimpan.classList.add('flex');
    });

    // Cancel Modals
    btnCancelSimpan.addEventListener('click', () => {
        modalSimpan.classList.add('hidden');
        modalSimpan.classList.remove('flex');
    });

    // Confirm Simpan
    btnConfirmSimpan.addEventListener('click', () => {
        modalSimpan.classList.add('hidden');
        modalSimpan.classList.remove('flex');
        
        document.getElementById('gradingForm').submit();
    });
});
</script>
@endpush
@endsection
