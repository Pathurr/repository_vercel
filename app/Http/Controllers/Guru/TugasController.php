<?php
namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Tugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TugasController extends Controller
{
    public function index()
    {
        $tugas = Tugas::with(['kelas', 'pengumpulan'])->where('guru_id', Auth::id())->latest()->get();
        return view('guru.tugas', compact('tugas'));
    }

    public function create(Request $request)
    {
        $kelases = \App\Models\Kelas::where('guru_id', Auth::id())->get();
        $tugas = null;
        if ($request->mode === 'edit' && $request->id) {
            $tugas = Tugas::where('guru_id', Auth::id())->findOrFail($request->id);
        }
        return view('guru.buat-tugas', compact('kelases', 'tugas'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'judul'    => 'required|string|max:255',
            'kelas_id' => 'required|array',
            'kelas_id.*'=> 'exists:kelas,id',
            'deadline' => 'required|date',
        ]);

        $filePath = null;
        $originalFileName = null;
        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $originalFileName = $file->getClientOriginalName();
            $filename = \Illuminate\Support\Str::random(40) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('tugas_attachments', $filename, 'public');
        }

        foreach ($request->kelas_id as $kelasId) {
            Tugas::create([
                'guru_id'        => Auth::id(),
                'kelas_id'       => $kelasId,
                'judul'          => $request->judul,
                'deskripsi'      => $request->deskripsi,
                'deadline'       => $request->deadline,
                'nilai_maksimal' => $request->nilai_maksimal ?? 100,
                'format_pengumpulan' => $request->format_pengumpulan,
                'file_path'      => $filePath,
                'original_file_name' => $originalFileName,
                'status'         => $request->status ?? 'publish',
                'scheduled_at'   => $request->scheduled_at ?? null,
            ]);
        }

        return redirect()->route('guru.tugas')->with('success', 'Tugas berhasil disimpan!');
    }

    public function update(Request $request, $id)
    {
        $tugas = Tugas::where('guru_id', Auth::id())->findOrFail($id);

        $request->validate([
            'judul'    => 'required|string|max:255',
            'kelas_id' => 'required|array',
            'kelas_id.*'=> 'exists:kelas,id',
            'deadline' => 'required|date',
        ]);

        $dataToUpdate = [
            'kelas_id'       => $request->kelas_id[0],
            'judul'          => $request->judul,
            'deskripsi'      => $request->deskripsi,
            'deadline'       => $request->deadline,
            'nilai_maksimal' => $request->nilai_maksimal ?? 100,
            'format_pengumpulan' => $request->format_pengumpulan,
            'status'         => $request->status ?? 'publish',
            'scheduled_at'   => $request->scheduled_at ?? null,
        ];

        if ($request->hasFile('file_path')) {
            if ($tugas->file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($tugas->file_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($tugas->file_path);
            }
            $file = $request->file('file_path');
            $dataToUpdate['original_file_name'] = $file->getClientOriginalName();
            $filename = \Illuminate\Support\Str::random(40) . '.' . $file->getClientOriginalExtension();
            $dataToUpdate['file_path'] = $file->storeAs('tugas_attachments', $filename, 'public');
        }

        $tugas->update($dataToUpdate);

        return redirect()->route('guru.tugas')->with('success', 'Tugas berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $tugas = Tugas::findOrFail($id);
        $tugas->delete();
        return back()->with('success', 'Tugas berhasil dihapus!');
    }
}