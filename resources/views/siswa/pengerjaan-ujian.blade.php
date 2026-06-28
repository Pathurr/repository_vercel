@extends('layouts.siswa')
@section('title', 'Ruang Ujian - SMK Mandalahayu 1')
@section('page-title', $ujian->judul)

@section('content')
<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: var(--color-outline-variant); border-radius: 10px; }
</style>

<div class="flex flex-col h-[calc(100vh-170px)] -mt-2">
    <!-- Custom Exam Header (Now acting as a secondary banner) -->
    <div class="bg-surface-container-lowest shadow-sm border border-outline-variant/30 rounded-xl px-6 py-3 shrink-0 mb-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center">
                <span class="material-symbols-outlined text-on-primary text-[18px]" style="font-variation-settings: 'FILL' 1;">school</span>
            </div>
            <div>
                <h1 class="font-bold text-sm text-primary" style="font-family: var(--font-serif)">{{ $ujian->judul }}</h1>
                <p class="text-[10px] text-on-surface-variant uppercase tracking-wider">{{ $ujian->kelas->mata_pelajaran ?? 'Mata Pelajaran' }}</p>
            </div>
        </div>
        <div class="flex items-center gap-6">
            <div class="flex items-center gap-2 hidden md:flex">
                <span class="text-[10px] text-on-surface-variant uppercase">Progres</span>
                <div class="flex items-baseline gap-1">
                    <span id="progress-current" class="font-bold text-sm text-primary">1</span>
                    <span id="progress-total" class="text-xs text-outline">/ 40</span>
                </div>
            </div>
            <div class="w-[1px] h-6 bg-outline-variant hidden md:block"></div>
            <div class="bg-secondary-container text-on-secondary-container px-4 py-1 rounded-full flex items-center gap-1.5 shadow-sm">
                <span class="material-symbols-outlined text-[16px]">timer</span>
                <span id="exam-timer" class="font-bold text-sm tracking-widest">01:45:22</span>
            </div>
        </div>
    </div>

    <!-- Main Exam Area -->
    <div class="flex-1 overflow-hidden flex gap-4 w-full">
        <!-- Left Column: Question & Options -->
        <section class="flex-1 flex flex-col h-full bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant/30 overflow-hidden">
            <!-- Question Content -->
            <div class="flex-1 overflow-y-auto p-6 custom-scrollbar flex flex-col">
                <div class="mb-4 flex items-center justify-between border-b border-outline-variant/50 pb-3">
                    <span id="question-label" class="bg-primary/10 text-primary font-bold text-[10px] px-3 py-1 rounded-full uppercase tracking-widest">Soal No. 1</span>
                    <button id="btn-ragu" onclick="toggleDoubt()" class="flex items-center gap-1 text-outline hover:text-secondary transition-colors text-xs font-bold px-2 py-1 rounded hover:bg-secondary/10">
                        <span id="icon-ragu" class="material-symbols-outlined text-[16px]">flag</span>
                        Tandai Ragu-ragu
                    </button>
                </div>
                <div class="prose max-w-none mb-6">
                    <p id="question-text" class="text-sm text-on-surface leading-relaxed font-medium">Loading...</p>
                </div>
                <!-- Options -->
                <div id="options-container" class="flex flex-col gap-3 mt-auto">
                    <!-- Rendered by JS -->
                </div>
            </div>
            <!-- Navigation Footer -->
            <div class="p-4 bg-surface-container-low border-t border-outline-variant/30 flex justify-between items-center shrink-0">
                <button id="btn-prev" onclick="changeQuestion(-1)" class="px-5 py-2 border-2 border-outline-variant text-on-surface-variant rounded-lg text-xs font-bold hover:bg-secondary hover:text-on-secondary hover:border-secondary transition-colors flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">chevron_left</span>
                    Sebelumnya
                </button>
                <button id="btn-next" onclick="changeQuestion(1)" class="px-5 py-2 border-2 border-outline-variant text-on-surface-variant rounded-lg text-xs font-bold hover:bg-secondary hover:text-on-secondary hover:border-secondary transition-colors flex items-center gap-1">
                    Selanjutnya
                    <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                </button>
            </div>
        </section>

        <!-- Right Column: Navigation Grid & Submission -->
        <aside class="w-72 flex flex-col gap-4 h-full hidden lg:flex">
            <!-- Grid Panel -->
            <div class="flex-1 flex flex-col bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant/30 overflow-hidden">
                <div class="p-4 border-b border-outline-variant/30 bg-surface-container-low flex justify-between items-center shrink-0">
                    <h2 class="font-bold text-sm text-primary" style="font-family: var(--font-serif)">Navigasi Soal</h2>
                </div>
                <div class="p-4 overflow-y-auto flex-1 custom-scrollbar">
                    <div id="grid-container" class="grid grid-cols-5 gap-2">
                        <!-- JS Grid -->
                    </div>
                </div>
                <!-- Legend -->
                <div class="p-3 bg-surface-container border-t border-outline-variant/30 shrink-0 flex flex-col gap-1.5">
                    <div class="flex items-center gap-2 text-[10px] font-bold text-on-surface-variant">
                        <div class="w-3 h-3 rounded bg-primary"></div> Sudah Dijawab
                    </div>
                    <div class="flex items-center gap-2 text-[10px] font-bold text-on-surface-variant">
                        <div class="w-3 h-3 rounded bg-surface border border-outline-variant"></div> Belum Dijawab
                    </div>
                    <div class="flex items-center gap-2 text-[10px] font-bold text-on-surface-variant">
                        <div class="w-3 h-3 rounded bg-secondary-container border border-secondary"></div> Ragu-ragu
                    </div>
                </div>
            </div>
            <!-- Submit Panel -->
            <div class="bg-surface-container-lowest rounded-xl p-4 shadow-sm border border-outline-variant/30 shrink-0">
                <p class="text-[10px] text-on-surface-variant mb-3 text-center">Pastikan semua terjawab.</p>
                <div class="flex flex-col gap-2">
                    <button type="button" id="btn-trigger-simpan" class="w-full bg-[#feae2c] text-[#6b4500] hover:brightness-110 py-2.5 rounded-lg text-xs font-bold shadow-sm flex items-center justify-center gap-2 transition-soft">
                        <span class="material-symbols-outlined text-[16px]">task_alt</span> Selesai & Kumpulkan
                    </button>
                </div>
            </div>
        </aside>
    </div>
</div>

<!-- Modal Konfirmasi Kumpulkan -->
<div id="modal-confirm-simpan" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/50 hidden backdrop-blur-sm transition-opacity">
    <div class="bg-[#fef9f3] rounded-xl shadow-2xl p-6 w-full max-w-sm border border-[#d6c3b8] text-center">
        <span class="material-symbols-outlined text-[#feae2c] text-5xl mb-4">help</span>
        <h3 class="text-xl font-bold text-[#50290b] mb-2" style="font-family: var(--font-serif)">Kumpulkan Ujian?</h3>
        <p class="text-xs text-[#51443c] mb-6">Apakah Anda yakin ingin menyelesaikan ujian ini? Jawaban tidak dapat diubah setelah dikumpulkan.</p>
        <div class="flex gap-2 justify-center">
            <button type="button" id="btn-cancel-simpan" class="px-4 py-2 rounded-lg font-bold text-xs text-[#51443c] border border-[#d6c3b8] hover:bg-[#f8f3ed] transition-colors">Periksa Lagi</button>
            <button type="button" id="btn-confirm-simpan" class="px-4 py-2 rounded-lg font-bold text-xs bg-[#feae2c] text-[#6b4500] hover:brightness-110 transition-all">Ya, Kumpulkan</button>
        </div>
    </div>
</div>

<!-- Modal Peringatan Belum Selesai -->
<div id="modal-warning" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/50 hidden backdrop-blur-sm transition-opacity">
    <div class="bg-red-50 rounded-xl shadow-2xl p-6 w-full max-w-sm border border-red-200 text-center">
        <span class="material-symbols-outlined text-red-500 text-5xl mb-4">warning</span>
        <h3 class="text-xl font-bold text-red-700 mb-2" style="font-family: var(--font-serif)">Belum Selesai!</h3>
        <p id="warning-text" class="text-xs text-red-600/80 mb-6">Masih ada soal yang belum dijawab.</p>
        <div class="flex justify-center">
            <button type="button" onclick="document.getElementById('modal-warning').classList.add('hidden')" class="px-6 py-2 rounded-lg font-bold text-xs text-red-700 border border-red-200 hover:bg-red-100 transition-colors">Periksa Kembali</button>
        </div>
    </div>
</div>

<!-- Toast Success (Popup Hijau) -->
<div id="toast-success" class="fixed top-5 left-1/2 -translate-x-1/2 z-[70] flex items-center gap-3 bg-green-100 border border-green-300 text-green-800 px-6 py-3 rounded-lg shadow-lg opacity-0 invisible transition-all duration-300 transform -translate-y-4">
    <span class="material-symbols-outlined">check_circle</span>
    <span class="font-bold text-sm">Ujian berhasil dikumpulkan!</span>
</div>

@push('scripts')
<script>
    // State
    const rawQuestions = @json($ujian->soal);
    const totalQuestions = rawQuestions.length;
    const questions = rawQuestions.map((q, i) => ({
        id: i + 1,
        soal_id: q.id,
        text: q.pertanyaan,
        tipe: q.tipe,
        options: q.pilihan,
        answer: null,
        doubt: false
    }));

    let currentIndex = 0; // Mulai dari soal 1

    function initExam() {
        if (totalQuestions === 0) {
            document.getElementById('question-text').innerText = "Belum ada soal untuk ujian ini.";
            document.getElementById('options-container').innerHTML = "";
            document.getElementById('grid-container').innerHTML = "<p class='text-xs text-on-surface-variant col-span-5'>Kosong</p>";
            document.getElementById('btn-prev').style.visibility = 'hidden';
            document.getElementById('btn-next').style.visibility = 'hidden';
            return;
        }
        renderGrid();
        loadQuestion(currentIndex);
    }

    function loadQuestion(index) {
        currentIndex = index;
        const q = questions[index];
        
        document.getElementById('question-label').innerText = `Soal No. ${q.id}`;
        document.getElementById('question-text').innerHTML = q.text; // Change to innerHTML to support rich text
        document.getElementById('progress-current').innerText = q.id;
        document.getElementById('progress-total').innerText = totalQuestions;

        // Render Options
        const container = document.getElementById('options-container');
        container.innerHTML = '';
        
        if (q.tipe === 'essay') {
            container.innerHTML = `
                <div class="flex-1 flex flex-col h-full">
                    <label class="text-xs font-bold text-on-surface-variant mb-2 block">Jawaban Essay:</label>
                    <textarea id="essay-answer" oninput="saveEssayAnswer()" class="w-full flex-1 min-h-[200px] p-4 bg-surface border border-outline-variant rounded-xl focus:border-primary focus:ring-1 focus:ring-primary text-sm resize-none custom-scrollbar" placeholder="Ketik jawaban Anda di sini...">${q.answer || ''}</textarea>
                </div>
            `;
        } else {
            const labels = ['A', 'B', 'C', 'D', 'E'];
            const opts = q.options || [];
            opts.forEach((opt, idx) => {
                const isSelected = q.answer == idx;
                const bgClass = isSelected ? 'border-primary bg-primary/5 border-2' : 'border-outline-variant hover:border-primary hover:bg-surface-container-low border';
                const textClass = isSelected ? 'text-primary font-bold' : 'text-on-surface-variant';
                
                container.innerHTML += `
                    <label class="group relative flex items-center gap-3 p-3 rounded-lg cursor-pointer transition-all ${bgClass}">
                        <input ${isSelected ? 'checked' : ''} onchange="selectAnswer(${idx})" class="w-4 h-4 text-primary border-outline-variant focus:ring-primary" name="answer" type="radio" value="${idx}"/>
                        <div class="flex-1 flex gap-2 items-center text-xs">
                            <span class="font-bold text-sm ${isSelected ? 'text-primary' : 'text-on-surface'}">${labels[idx] || ''}.</span>
                            <span class="${textClass}">${typeof opt === 'string' ? opt.replace(/</g, '&lt;').replace(/>/g, '&gt;') : opt}</span>
                        </div>
                    </label>
                `;
            });
        }

        // Doubt Button
        const btnRagu = document.getElementById('btn-ragu');
        const iconRagu = document.getElementById('icon-ragu');
        if (q.doubt) {
            btnRagu.classList.add('text-secondary', 'bg-secondary/10');
            btnRagu.classList.remove('text-outline');
            iconRagu.style.fontVariationSettings = "'FILL' 1";
        } else {
            btnRagu.classList.remove('text-secondary', 'bg-secondary/10');
            btnRagu.classList.add('text-outline');
            iconRagu.style.fontVariationSettings = "'FILL' 0";
        }

        // Prev/Next visibility
        document.getElementById('btn-prev').style.visibility = index === 0 ? 'hidden' : 'visible';
        document.getElementById('btn-next').style.visibility = index === totalQuestions - 1 ? 'hidden' : 'visible';

        renderGrid();
    }

    function selectAnswer(optIndex) {
        questions[currentIndex].answer = optIndex;
        loadQuestion(currentIndex);
    }

    function saveEssayAnswer() {
        const val = document.getElementById('essay-answer').value;
        questions[currentIndex].answer = val.trim() === '' ? null : val;
        renderGrid();
    }

    function toggleDoubt() {
        questions[currentIndex].doubt = !questions[currentIndex].doubt;
        loadQuestion(currentIndex);
    }

    function changeQuestion(step) {
        const newIndex = currentIndex + step;
        if (newIndex >= 0 && newIndex < totalQuestions) {
            loadQuestion(newIndex);
        }
    }

    function renderGrid() {
        const grid = document.getElementById('grid-container');
        if(!grid) return;
        grid.innerHTML = '';
        questions.forEach((q, idx) => {
            let classes = 'aspect-square rounded flex items-center justify-center text-xs font-bold transition-all relative cursor-pointer ';
            
            if (idx === currentIndex) {
                classes += 'ring-2 ring-primary border-2 border-primary bg-primary/10 text-primary scale-105 ';
            } else if (q.doubt) {
                classes += 'bg-secondary-container text-on-secondary-container border border-secondary hover:opacity-90 ';
            } else if (q.answer !== null) {
                classes += 'bg-primary text-on-primary border border-primary hover:opacity-90 ';
            } else {
                classes += 'bg-surface text-on-surface-variant border border-outline-variant hover:bg-surface-container ';
            }

            let doubtDot = '';
            if (q.doubt) {
                doubtDot = '<div class="absolute -top-1 -right-1 w-2 h-2 bg-secondary rounded-full"></div>';
            }

            grid.innerHTML += `
                <button onclick="loadQuestion(${idx})" class="${classes}">
                    ${q.id}
                    ${doubtDot}
                </button>
            `;
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        initExam();

        // Timer Logic
        const timerEl = document.getElementById('exam-timer');
        let totalSeconds = {{ ($ujian->durasi_menit ?? 120) * 60 }};
        
        function updateExamTimer() {
            if (totalSeconds <= 0) {
                timerEl.innerText = "00:00:00";
                // Auto submit when time is up
                document.getElementById('modal-confirm-simpan').classList.remove('hidden');
                return;
            }
            
            const h = Math.floor(totalSeconds / 3600);
            const m = Math.floor((totalSeconds % 3600) / 60);
            const s = totalSeconds % 60;
            
            const format = (val) => val.toString().padStart(2, '0');
            timerEl.innerText = `${format(h)}:${format(m)}:${format(s)}`;
            
            totalSeconds--;
            setTimeout(updateExamTimer, 1000);
        }
        
        updateExamTimer();

        const btnTriggerSimpan = document.getElementById('btn-trigger-simpan');
        const modalSimpan = document.getElementById('modal-confirm-simpan');
        const btnCancelSimpan = document.getElementById('btn-cancel-simpan');
        const btnConfirmSimpan = document.getElementById('btn-confirm-simpan');
        const toastSuccess = document.getElementById('toast-success');
        
        const modalWarning = document.getElementById('modal-warning');
        const warningText = document.getElementById('warning-text');

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
            const unanswered = questions.filter(q => q.answer === null).length;
            const doubtful = questions.filter(q => q.doubt).length;
            
            if (unanswered > 0 || doubtful > 0) {
                let msg = [];
                if (unanswered > 0) msg.push(`${unanswered} soal belum dijawab`);
                if (doubtful > 0) msg.push(`${doubtful} soal ragu-ragu`);
                
                warningText.innerText = `Masih ada ${msg.join(' dan ')}. Silakan periksa kembali jawaban Anda.`;
                modalWarning.classList.remove('hidden');
                return;
            }
            modalSimpan.classList.remove('hidden');
        });
        
        btnCancelSimpan.addEventListener('click', () => modalSimpan.classList.add('hidden'));
        btnConfirmSimpan.addEventListener('click', () => {
            modalSimpan.classList.add('hidden');
            
            // Siapkan data submission
            const answers = questions.map(q => ({
                soal_id: q.soal_id,
                jawaban: q.answer
            }));
            
            // Format form dynamically
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = ''; // Submit back to controller
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            const answersInput = document.createElement('input');
            answersInput.type = 'hidden';
            answersInput.name = 'answers';
            answersInput.value = JSON.stringify(answers);
            form.appendChild(answersInput);

            document.body.appendChild(form);
            form.submit();
        });
    });
</script>
@endpush
@endsection
