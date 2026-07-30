
    function showSimpanModal() {
        document.getElementById('modal-confirm-simpan').classList.remove('hidden');
    }
    
    function showBatalModal() {
        document.getElementById('modal-confirm-batal').classList.remove('hidden');
    }

    function previewFile(input) {
        const previewContainer = input.nextElementSibling;
        
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const fileType = file.type;
            const fileName = file.name;
            const fileUrl = URL.createObjectURL(file);
            
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

    // Event listener dipindahkan ke atribut onclick di HTML

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
    const tipeTugasError = document.getElementById('tipe-tugas-error');
    const formatPengumpulanError = document.getElementById('format-pengumpulan-error');

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

        if (!isValid) {
            alert('Data Belum Lengkap!\n\nMohon periksa kembali form dan isi semua data wajib yang ditandai dengan teks merah.');
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

    // Trigger simpan & batal dipindahkan ke atribut onclick html

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
            window.location.href = "{{ route('guru.tugas') }}";
        }, 1500);
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
    // btnConfirmSimpan.addEventListener('click', () => {
    //     if (!validateForm()) {
    //         modalConfirmSimpan.classList.add('hidden');
    //         return;
    //     }

    //     // Sembunyikan modal konfirmasi
    //     modalConfirmSimpan.classList.add('hidden');
        
    //     // Tampilkan Popup Toast Hijau 
    //     toastSuccess.classList.remove('invisible', 'opacity-0', '-translate-y-4');
    //     toastSuccess.classList.add('opacity-100', 'translate-y-0');
        
    //     // Buat jeda waktu 1.5 detik agar pengguna bisa baca popup, lalu pindah halaman
    //     setTimeout(() => {
    //         // Bisa pakai form submit betulan jika backend dirutekan ke POST:
    //         document.getElementById('form-buat-tugas').submit();
    //     }, 1500);
    // });

        } catch (error) {
            console.error('JS Error on Load:', error);
            alert('Terjadi kesalahan pada sistem saat memuat halaman: ' + error.message);
        }
});
