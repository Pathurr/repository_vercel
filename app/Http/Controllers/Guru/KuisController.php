<?php
namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Kuis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KuisController extends Controller
{
    public function index()
    {
        $kuis = Kuis::with(['kelas.siswa', 'soal', 'jawaban'])->where('guru_id', Auth::id())->latest()->get();
        return view('guru.kuis', compact('kuis'));
    }

    public function create(Request $request)
    {
        $kelases = \App\Models\Kelas::where('guru_id', Auth::id())->get();
        $kuis = null;
        if ($request->mode === 'edit' && $request->id) {
            $kuis = Kuis::with('soal')->where('guru_id', Auth::id())->findOrFail($request->id);
            $kuis->soal->transform(function($s) {
                if ($s->file_path) {
                    $s->file_url = \Illuminate\Support\Facades\Storage::disk(env('FILESYSTEM_DISK', 'public'))->url($s->file_path);
                }
                return $s;
            });
        }
        return view('guru.buat-kuis', compact('kelases', 'kuis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'kelas_id' => 'required|array',
            'kelas_id.*'=> 'exists:kelas,id',
        ]);

        foreach ($request->kelas_id as $kelasId) {
            $kuis = Kuis::create([
                'kelas_id'  => $kelasId,
                'guru_id'   => Auth::id(),
                'judul'     => $request->judul,
                'deskripsi' => $request->deskripsi,
                'durasi_menit' => $request->durasi ?? 60,
                'status'    => $request->status ?? 'published',
                'mulai_at'  => $request->waktu_mulai ?? null,
                'selesai_at' => $request->waktu_berakhir ?? null,
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
                            $filePath = $file->storeAs('soal_kuis', $filename, env('FILESYSTEM_DISK', 'public'));
                        }

                        \App\Models\SoalKuis::create([
                            'kuis_id' => $kuis->id,
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

        return redirect()->route('guru.kuis')->with('success', 'Kuis berhasil disimpan!');
    }

    public function update(Request $request, $id)
    {
        $kuis = Kuis::where('guru_id', Auth::id())->findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'kelas_id' => 'required|array',
            'kelas_id.*'=> 'exists:kelas,id',
        ]);

        $kuis->update([
            'kelas_id'  => $request->kelas_id[0],
            'judul'     => $request->judul,
            'deskripsi' => $request->deskripsi,
            'durasi_menit' => $request->durasi ?? 60,
            'status'    => $request->status ?? 'published',
            'mulai_at'  => $request->waktu_mulai ?? null,
            'selesai_at' => $request->waktu_berakhir ?? null,
        ]);

        if ($request->questions_data) {
            $questions = json_decode($request->questions_data, true);
            if (is_array($questions)) {
                // Get old questions to retain file_path if not updated
                $oldQuestions = \App\Models\SoalKuis::where('kuis_id', $kuis->id)->get()->keyBy('urutan');
                
                // Hapus soal lama
                \App\Models\SoalKuis::where('kuis_id', $kuis->id)->delete();
                
                // Tambahkan soal baru
                foreach ($questions as $idx => $q) {
                    $filePath = null;
                    $originalFileName = null;
                    if ($request->hasFile('gambar_soal_' . $q['urutan'])) {
                        $file = $request->file('gambar_soal_' . $q['urutan']);
                        $originalFileName = $file->getClientOriginalName();
                        $filename = \Illuminate\Support\Str::random(40) . '.' . $file->getClientOriginalExtension();
                        $filePath = $file->storeAs('soal_kuis', $filename, env('FILESYSTEM_DISK', 'public'));
                    } else {
                        // Attempt to retain old image if it exists for this urutan
                        if (isset($oldQuestions[$q['urutan']])) {
                            $filePath = $oldQuestions[$q['urutan']]->file_path;
                            $originalFileName = $oldQuestions[$q['urutan']]->original_file_name;
                        }
                    }

                    \App\Models\SoalKuis::create([
                        'kuis_id' => $kuis->id,
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

        return redirect()->route('guru.kuis')->with('success', 'Kuis berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $kuis = Kuis::findOrFail($id);
        $kuis->delete();
        return back()->with('success', 'Kuis berhasil dihapus!');
    }
}