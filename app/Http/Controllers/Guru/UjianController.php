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
        $ujian = Ujian::with(['kelas.siswa', 'soal', 'jawaban'])->latest()->get();
        return view('guru.ujian', compact('ujian'));
    }

    public function create(Request $request)
    {
        $kelases = \App\Models\Kelas::where('guru_id', Auth::id())->get();
        $ujian = null;
        if ($request->mode === 'edit' && $request->id) {
            $ujian = Ujian::with('soal')->where('guru_id', Auth::id())->findOrFail($request->id);
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
                'mulai_at'  => now(),
                'selesai_at'=> now()->addHours(2),
                'durasi_menit' => $request->durasi ?? 90,
            ]);

            if ($request->questions_data) {
                $questions = json_decode($request->questions_data, true);
                if (is_array($questions)) {
                    foreach ($questions as $q) {
                        \App\Models\SoalUjian::create([
                            'ujian_id' => $ujian->id,
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

        return redirect()->route('guru.ujian')->with('success', 'Ujian berhasil disimpan!');
    }

    public function update(Request $request, $id)
    {
        $ujian = Ujian::where('guru_id', Auth::id())->findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'kelas_id' => 'required|array',
            'kelas_id.*'=> 'exists:kelas,id',
            'deskripsi' => 'nullable|string',
        ]);

        $ujian->update([
            'kelas_id'  => $request->kelas_id[0],
            'judul'     => $request->judul,
            'deskripsi' => $request->deskripsi,
            'durasi_menit' => $request->durasi ?? 90,
        ]);

        if ($request->questions_data) {
            $questions = json_decode($request->questions_data, true);
            if (is_array($questions)) {
                // Hapus soal lama
                \App\Models\SoalUjian::where('ujian_id', $ujian->id)->delete();
                
                // Tambahkan soal baru
                foreach ($questions as $q) {
                    \App\Models\SoalUjian::create([
                        'ujian_id' => $ujian->id,
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

        return redirect()->route('guru.ujian')->with('success', 'Ujian berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $ujian = Ujian::findOrFail($id);
        $ujian->delete();
        return back()->with('success', 'Ujian berhasil dihapus!');
    }
}