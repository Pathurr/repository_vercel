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
        $kuis = Kuis::with(['kelas.siswa', 'soal', 'jawaban'])->latest()->get();
        return view('guru.kuis', compact('kuis'));
    }

    public function create(Request $request)
    {
        $kelases = \App\Models\Kelas::where('guru_id', Auth::id())->get();
        $kuis = null;
        if ($request->mode === 'edit' && $request->id) {
            $kuis = Kuis::with('soal')->where('guru_id', Auth::id())->findOrFail($request->id);
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
                'guru_id'   => Auth::id(),
                'kelas_id'  => $kelasId,
                'judul'     => $request->judul,
                'deskripsi' => $request->deskripsi,
                'durasi_menit' => $request->durasi ?? 60,
            ]);

            if ($request->questions_data) {
                $questions = json_decode($request->questions_data, true);
                if (is_array($questions)) {
                    foreach ($questions as $q) {
                        \App\Models\SoalKuis::create([
                            'kuis_id' => $kuis->id,
                            'pertanyaan' => $q['pertanyaan'],
                            'tipe' => $q['tipe'] ?? 'pilihan_ganda',
                            'pilihan' => $q['pilihan'],
                            'jawaban_benar' => (string) $q['jawaban_benar'],
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
        ]);

        if ($request->questions_data) {
            $questions = json_decode($request->questions_data, true);
            if (is_array($questions)) {
                // Hapus soal lama
                \App\Models\SoalKuis::where('kuis_id', $kuis->id)->delete();
                
                // Tambahkan soal baru
                foreach ($questions as $q) {
                    \App\Models\SoalKuis::create([
                        'kuis_id' => $kuis->id,
                        'pertanyaan' => $q['pertanyaan'],
                        'tipe' => $q['tipe'] ?? 'pilihan_ganda',
                        'pilihan' => $q['pilihan'],
                        'jawaban_benar' => (string) $q['jawaban_benar'],
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