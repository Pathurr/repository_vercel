<?php
namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Tugas;
use App\Models\PengumpulanTugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TugasController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $kelasIds = $user->kelas()->pluck('kelas.id');

        $tugas = Tugas::with(['pengumpulan' => function ($query) use ($user) {
            $query->where('siswa_id', $user->id);
        }])->whereIn('kelas_id', $kelasIds)->latest()->get();

        return view('siswa.tugas', compact('tugas'));
    }

    public function show($id)
    {
        $user = Auth::user();
        $kelasIds = $user->kelas()->pluck('kelas.id');

        $tugas = Tugas::with(['pengumpulan' => function ($query) use ($user) {
            $query->where('siswa_id', $user->id);
        }])->whereIn('kelas_id', $kelasIds)->findOrFail($id);
        
        return view('siswa.pengerjaan-tugas', compact('tugas'));
    }

    public function store(Request $request, $id)
    {
        $tugas = Tugas::findOrFail($id);
        $formats = $tugas->format_pengumpulan ?? [];
        
        $rules = ['catatan' => 'nullable|string'];
        
        $isLinkAllowed = in_array('link', $formats);
        $isFileAllowed = count(array_diff($formats, ['link'])) > 0 || empty($formats);

        if ($isLinkAllowed && !$isFileAllowed) {
            $rules['link'] = 'required|url';
        } elseif (!$isLinkAllowed && $isFileAllowed) {
            $rules['file'] = 'required|file|mimes:pdf,zip,rar,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png|max:51200';
        } elseif ($isLinkAllowed && $isFileAllowed) {
            $rules['file'] = 'required_without:link|file|mimes:pdf,zip,rar,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png|max:51200';
            $rules['link'] = 'required_without:file|url';
        }

        $request->validate($rules);

        $filePath = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $disk = env('FILESYSTEM_DISK', 'public');
            $filePath = $file->storeAs('pengumpulan_tugas', $filename, $disk);
        }

        $now = now();
        $status = 'tepat_waktu';
        if ($tugas->deadline && $now->greaterThan($tugas->deadline)) {
            $status = 'terlambat';
        }

        PengumpulanTugas::updateOrCreate(
            ['tugas_id' => $tugas->id, 'siswa_id' => Auth::id()],
            [
                'file_path' => $filePath,
                'link' => $request->link,
                'catatan' => $request->catatan,
                'dikumpulkan_at' => $now,
                'status' => $status,
            ]
        );
        return redirect()->route('siswa.mapel')->with('success', 'Tugas berhasil dikumpulkan!');
    }
}