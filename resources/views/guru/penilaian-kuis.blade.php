@extends('layouts.guru')
@section('title', 'Penilaian Kuis - SMK Mandalahayu 1')

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



<div class="smooth-wave-bg -m-6 p-4" style="min-height: calc(100vh - 64px);">
<div class="max-w-full mx-auto h-full flex flex-col">

    <!-- Page Title Badge -->
    <div class="flex items-center gap-2 mb-2">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-secondary-container text-on-secondary-container rounded-full text-xs font-bold uppercase tracking-wider">
            <span class="material-symbols-outlined text-sm">quiz</span> Penilaian Kuis
        </span>
        <span class="text-on-surface-variant text-xs">Kuis Bab 2 Routing — Jaringan Dasar</span>
    </div>

    <!-- Header Card (same width as questions area) -->
    <div class="flex flex-col lg:flex-row gap-6 flex-1">
        <!-- Left Column: 70% -->
        <div class="w-full lg:basis-[68%] lg:max-w-[68%] min-w-0 flex flex-col">

            <!-- Quiz Header Card -->
            <div class="bg-surface-container-lowest p-3 rounded-xl shadow-ambient border border-outline-variant/30 mb-3">
                <div class="flex flex-col md:flex-row justify-between items-start gap-2 mb-2">
                    <div>
                        <h1 class="font-bold text-lg text-primary mb-0" style="font-family: var(--font-serif)">{{ $submission['siswa']->name }}</h1>
                        <p class="text-xs text-on-surface-variant">{{ $submission['kuis']->kelas->nama_kelas }} · {{ $submission['kuis']->kelas->mata_pelajaran }}</p>
                    </div>
                </div>
            </div>

            <!-- Questions — scrollable area -->
            <div class="overflow-y-auto space-y-3 pr-1" style="max-height: calc(100vh - 220px)">
                @foreach($submission['answers'] as $index => $ans)
                <div class="bg-surface-container-lowest p-4 rounded-xl shadow-ambient border border-outline-variant/30 relative">
                    @if($ans->soal->tipe !== 'essay')
                        <div class="absolute top-4 right-4 flex items-center gap-2">
                            @if($ans->skor == 1)
                            <span class="bg-green-100 text-green-700 px-2.5 py-1 rounded-full text-xs font-bold flex items-center gap-1 border border-green-200">
                                <span class="material-symbols-outlined text-sm">check_circle</span> Benar
                            </span>
                            @elseif($ans->skor > 0)
                            <span class="bg-secondary-container/20 text-on-secondary-container px-2.5 py-1 rounded-full text-xs font-bold flex items-center gap-1 border border-secondary-container/40">
                                <span class="material-symbols-outlined text-sm">check_circle</span> Sebagian Benar
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
                    
                    <h4 class="text-xs font-bold text-secondary uppercase tracking-wider mb-2 flex items-center flex-wrap gap-2">
                        Pertanyaan {{ $index + 1 }} — {{ $ans->soal->tipe === 'essay' ? 'Essay' : 'Pilihan Ganda' }}
                        <span class="text-[10px] text-on-surface-variant normal-case font-bold bg-surface-variant/30 px-2 py-0.5 rounded border border-outline-variant/30">Bobot: {{ $ans->soal->bobot ?? 1 }} Poin</span>
                    </h4>
                    <p class="text-base font-semibold text-on-surface mb-4 pr-28">{!! $ans->soal->pertanyaan !!}</p>
                    
                    @if($ans->soal->tipe !== 'essay')
                        <div class="space-y-2">
                            @foreach($ans->soal->pilihan ?? [] as $optIdx => $optText)
                                @php
                                    $studentAnswers = is_array(json_decode($ans->jawaban, true)) ? json_decode($ans->jawaban, true) : [$ans->jawaban];
                                    $correctAnswers = is_array(json_decode($ans->soal->jawaban_benar, true)) ? json_decode($ans->soal->jawaban_benar, true) : [$ans->soal->jawaban_benar];
                                    
                                    $studentAnswers = array_map('strval', $studentAnswers);
                                    $correctAnswers = array_map('strval', $correctAnswers);
                                    
                                    $isSelected = in_array((string)$optIdx, $studentAnswers, true);
                                    $isCorrectOpt = in_array((string)$optIdx, $correctAnswers, true);
                                    
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
                        <div class="bg-surface-bright p-4 rounded-lg border border-outline-variant/30 mb-4 text-on-surface text-sm leading-relaxed">
                            {{ $ans->jawaban ?? 'Siswa tidak menjawab.' }}
                        </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        <!-- Right: Grading Sidebar 30% -->
        <aside class="w-full lg:basis-[30%] lg:max-w-[30%] flex-shrink-0 lg:sticky lg:top-4 lg:h-[calc(100vh-88px)]">
            <form id="gradingForm" method="POST" action="{{ route('guru.penilaian.kuis.store', ['kuis_id' => $submission['kuis']->id, 'siswa_id' => $submission['siswa']->id]) }}" class="bg-surface-container-highest p-4 rounded-xl shadow-ambient border border-outline-variant/30 h-full flex flex-col">
                @csrf
                <h3 class="font-bold text-[15px] text-primary mb-3 flex-shrink-0" style="font-family: var(--font-serif)">Ringkasan Nilai</h3>

                @php
                    $mcqTotal = $submission['answers']->where('soal.tipe', '!=', 'essay')->count();
                    $mcqScore = $submission['answers']->where('soal.tipe', '!=', 'essay')->sum('skor');
                    $baseScore = $mcqTotal > 0 ? ($mcqScore / $mcqTotal) * 100 : 0;
                    
                    // Fetch existing score if any
                    $existingNilai = \App\Models\Nilai::where('siswa_id', $submission['siswa']->id)
                        ->where('nilaiable_type', \App\Models\Kuis::class)
                        ->where('nilaiable_id', $submission['kuis']->id)
                        ->first();
                @endphp

                <!-- Score Breakdown -->
                <div class="space-y-1.5 mb-5 flex-shrink-0">
                    <div class="flex justify-between text-[11px]">
                        <span class="text-on-surface-variant">Skor Auto (PG)</span>
                        <span class="font-bold text-primary">{{ is_numeric($mcqScore) && floor($mcqScore) == $mcqScore ? number_format($mcqScore, 0) : number_format($mcqScore, 2) }}/{{ $mcqTotal }} ({{ round($baseScore) }} Poin)</span>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="text-[11px] font-bold uppercase tracking-wider text-primary mb-1 block">Nilai Keseluruhan (0-100)</label>
                    <div class="relative">
                        <input id="gradeInput" name="nilai" value="{{ old('nilai', $existingNilai ? $existingNilai->nilai : round($baseScore)) }}" class="w-full text-3xl font-bold p-3 bg-white border-b-2 border-primary focus:ring-0 focus:border-secondary transition-all rounded-t-xl text-center" max="100" min="0" placeholder="0" type="number" required/>
                    </div>
                    <p class="text-[10px] text-on-surface-variant mt-1 text-center">Sesuaikan nilai di atas jika ada soal essay.</p>
                </div>

                <div class="space-y-2 flex-1 flex flex-col min-h-0 justify-end">
                    <!-- Actions -->
                    <div class="pt-3 border-t border-outline-variant/30 flex-shrink-0">
                        <button type="button" id="btn-trigger-simpan" class="ui-btn ui-btn-primary w-full px-6 py-2.5 text-sm">
                            <span class="material-symbols-outlined" style="font-size: 18px">save</span> Simpan Nilai
                        </button>
                    </div>
                </div>
                
                <!-- Modal Konfirmasi Simpan -->
                <div id="modal-confirm-simpan" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/50 hidden backdrop-blur-sm transition-opacity">
                    <div class="ui-modal-card">
                        <span class="material-symbols-outlined text-[#feae2c] text-5xl mb-4">help</span>
                        <h3 class="text-xl font-bold text-[#50290b] mb-2" style="font-family: var(--font-serif)">Simpan Nilai?</h3>
                        <p class="text-xs text-[#51443c] mb-6">Apakah Anda yakin nilai yang diberikan sudah benar dan siap disimpan?</p>
                        <div class="flex gap-2 justify-center">
                            <button type="button" id="btn-cancel-simpan" class="ui-btn ui-btn-secondary px-4 py-2 text-xs">Periksa Lagi</button>
                            <button type="submit" id="btn-confirm-simpan" class="ui-btn ui-btn-primary px-4 py-2 text-xs">Ya, Simpan</button>
                        </div>
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
    const gradeInput = document.getElementById('gradeInput');
    if (gradeInput) {
        gradeInput.addEventListener('keydown', (e) => {
            if (e.key === '-' || e.key === 'e') e.preventDefault();
        });
        gradeInput.addEventListener('input', (e) => {
            if (e.target.value === '') return;
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
        });
    }

    // ── Modals & Toasts ─────────────────────────────────────
    const monitorUrl = "{{ route('guru.monitor') }}";
    
    const modalSimpan = document.getElementById('modal-confirm-simpan');
    const toastSuccess = document.getElementById('toast-success');
    const btnTriggerSimpan = document.getElementById('btn-trigger-simpan');
    const btnConfirmSimpan = document.getElementById('btn-confirm-simpan');
    const btnCancelSimpan = document.getElementById('btn-cancel-simpan');

    // Trigger Modals
    btnTriggerSimpan.addEventListener('click', () => {
        if (gradeInput && gradeInput.value === '') {
            gradeInput.focus();
            return;
        }
        modalSimpan.classList.remove('hidden');
        modalSimpan.classList.add('flex');
    });

    // Cancel Modals
    btnCancelSimpan.addEventListener('click', () => {
        modalSimpan.classList.add('hidden');
        modalSimpan.classList.remove('flex');
    });

    // Confirm Simpan
    if(btnConfirmSimpan) {
        btnConfirmSimpan.addEventListener('click', () => {
            modalSimpan.classList.add('hidden');
            modalSimpan.classList.remove('flex');
            // Form is submitted natively by type="submit"
        });
    }
});
</script>
@endpush
@endsection
