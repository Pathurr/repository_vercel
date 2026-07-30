<?php
namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Ujian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UjianController extends Controller
{
    public function index()
    {
        $ujian = Ujian::with(['kelas.siswa', 'soal', 'jawaban'])->where('guru_id', Auth::id())->latest()->get();
        return view('guru.ujian', compact('ujian'));
    }

    public function create(Request $request)
    {
        $kelases = \App\Models\Kelas::where('guru_id', Auth::id())->get();
        $ujian = null;
        if ($request->mode === 'edit' && $request->id) {
            $ujian = Ujian::with('soal')->where('guru_id', Auth::id())->findOrFail($request->id);
            $ujian->soal->transform(function($s) {
                if ($s->file_path) {
                    $s->file_url = \Illuminate\Support\Facades\Storage::disk(env('FILESYSTEM_DISK', 'public'))->url($s->file_path);
                }
                return $s;
            });
        }
        return view('guru.buat-ujian', compact('kelases', 'ujian'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'kelas_id' => 'required|array',
            'kelas_id.*'=> 'exists:kelas,id',
            'deskripsi' => 'nullable|string',
        ]);

        foreach ($request->kelas_id as $kelasId) {
            $ujian = Ujian::create([
                'guru_id'   => Auth::id(),
                'kelas_id'  => $kelasId,
                'judul'     => $request->judul,
                'deskripsi' => $request->deskripsi,
                'status'    => $request->status ?? 'published',
                'mulai_at'  => $request->waktu_mulai ?? now(),
                'selesai_at'=> $request->waktu_berakhir ?? now()->addHours(2),
                'durasi_menit' => $request->durasi ?? 120,
                'batas_percobaan' => $request->batas ?? 1,
                'acak_soal' => $request->has('acak_soal') ? 'true' : 'false',
            ]);

            if ($request->questions_data) {
                $questions = json_decode($request->questions_data, true);
                if (is_array($questions)) {
                    foreach ($questions as $idx => $q) {
                        $filePath = null;
                        $originalFileName = null;
                        if ($request->hasFile('gambar_soal_' . $q['urutan'])) {
                            $file = $request->file('gambar_soal_' . $q['urutan']);
                            $originalFileName = $file->getClientOriginalName();
                            $filename = \Illuminate\Support\Str::random(40) . '.' . $file->getClientOriginalExtension();
                            $filePath = $file->storeAs('soal_ujian', $filename, env('FILESYSTEM_DISK', 'public'));
                        }

                        \App\Models\SoalUjian::create([
                            'ujian_id' => $ujian->id,
                            'pertanyaan' => $q['pertanyaan'],
                            'file_path' => $filePath,
                            'original_file_name' => $originalFileName,
                            'tipe' => $q['tipe'] ?? 'pilihan_ganda',
                            'pilihan' => $q['pilihan'],
                            'jawaban_benar' => is_array($q['jawaban_benar']) ? json_encode($q['jawaban_benar']) : (string) $q['jawaban_benar'],
                            'bobot' => $q['bobot'],
                            'urutan' => $q['urutan']
                        ]);
                    }
                }
            }
        }

        return redirect()->route('guru.ujian')->with('success', 'Ujian berhasil disimpan!');
    }

    public function update(Request $request, $id)
    {
        $ujian = Ujian::where('guru_id', Auth::id())->findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'kelas_id' => 'required|array',
            'kelas_id.*'=> 'exists:kelas,id',
        ]);

        $ujian->update([
            'kelas_id'  => $request->kelas_id[0],
            'judul'     => $request->judul,
            'deskripsi' => $request->deskripsi,
            'status'    => $request->status ?? 'published',
            'mulai_at'  => $request->waktu_mulai ?? null,
            'selesai_at'=> $request->waktu_berakhir ?? null,
            'durasi_menit' => $request->durasi ?? 120,
            'batas_percobaan' => $request->batas ?? 1,
            'acak_soal' => $request->has('acak_soal') ? 'true' : 'false',
        ]);

        if ($request->questions_data) {
            $questions = json_decode($request->questions_data, true);
            if (is_array($questions)) {
                // Get old questions to retain file_path if not updated
                $oldQuestions = \App\Models\SoalUjian::where('ujian_id', $ujian->id)->get()->keyBy('urutan');
                
                // Hapus soal lama
                \App\Models\SoalUjian::where('ujian_id', $ujian->id)->delete();
                
                // Tambahkan soal baru
                foreach ($questions as $idx => $q) {
                    $filePath = null;
                    $originalFileName = null;
                    if ($request->hasFile('gambar_soal_' . $q['urutan'])) {
                        $file = $request->file('gambar_soal_' . $q['urutan']);
                        $originalFileName = $file->getClientOriginalName();
                        $filename = \Illuminate\Support\Str::random(40) . '.' . $file->getClientOriginalExtension();
                        $filePath = $file->storeAs('soal_ujian', $filename, env('FILESYSTEM_DISK', 'public'));
                    } else {
                        // Attempt to retain old image if it exists for this urutan
                        if (isset($oldQuestions[$q['urutan']])) {
                            $filePath = $oldQuestions[$q['urutan']]->file_path;
                            $originalFileName = $oldQuestions[$q['urutan']]->original_file_name;
                        }
                    }

                    \App\Models\SoalUjian::create([
                        'ujian_id' => $ujian->id,
                        'pertanyaan' => $q['pertanyaan'],
                        'file_path' => $filePath,
                        'original_file_name' => $originalFileName,
                        'tipe' => $q['tipe'] ?? 'pilihan_ganda',
                        'pilihan' => $q['pilihan'],
                        'jawaban_benar' => is_array($q['jawaban_benar']) ? json_encode($q['jawaban_benar']) : (string) $q['jawaban_benar'],
                        'bobot' => $q['bobot'],
                        'urutan' => $q['urutan']
                    ]);
                }
            }
        }

        return redirect()->route('guru.ujian')->with('success', 'Ujian berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $ujian = Ujian::findOrFail($id);
        $ujian->delete();
        return back()->with('success', 'Ujian berhasil dihapus!');
    }
}