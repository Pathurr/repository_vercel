@extends('layouts.guru')
@section('title', 'Rekap Nilai - SMK Mandalahayu 1')
@section('content')
<div class="mb-8 flex justify-between items-center flex-wrap gap-4">
    <div>
        <h2 class="font-bold text-4xl text-primary" style="font-family: var(--font-serif)">Rekap Nilai Siswa</h2>
        <p class="text-on-surface-variant mt-1">Ringkasan lengkap nilai seluruh siswa per semester.</p>
    </div>
    <div class="flex gap-3">
        <a href="#" class="border border-secondary text-secondary font-bold py-2 px-4 rounded-xl flex items-center gap-2 text-sm hover:bg-secondary/5 transition-soft">
            <span class="material-symbols-outlined text-base">print</span> Cetak
        </a>
        <a href="#" class="bg-secondary text-on-secondary font-bold py-2 px-4 rounded-xl flex items-center gap-2 text-sm hover:brightness-110 transition-soft">
            <span class="material-symbols-outlined text-base">download</span> Export Excel
        </a>
    </div>
</div>

{{-- Summary Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-surface rounded-xl border border-outline-variant/30 p-5 text-center">
        <p class="text-3xl font-bold text-primary">{{ $summary['total'] }}</p>
        <p class="text-xs text-on-surface-variant mt-1">Total Siswa</p>
    </div>
    <div class="bg-surface rounded-xl border border-outline-variant/30 p-5 text-center">
        <p class="text-3xl font-bold text-green-600">{{ $summary['pass'] }}</p>
        <p class="text-xs text-on-surface-variant mt-1">Lulus KKM</p>
    </div>
    <div class="bg-surface rounded-xl border border-outline-variant/30 p-5 text-center">
        <p class="text-3xl font-bold text-red-500">{{ $summary['fail'] }}</p>
        <p class="text-xs text-on-surface-variant mt-1">Tidak Lulus KKM</p>
    </div>
    <div class="bg-surface rounded-xl border border-outline-variant/30 p-5 text-center">
        <p class="text-3xl font-bold text-secondary">{{ $summary['average'] }}</p>
        <p class="text-xs text-on-surface-variant mt-1">Rata-rata Kelas</p>
    </div>
</div>

<div class="bg-surface rounded-xl border border-outline-variant/30 shadow-sm overflow-hidden">
    <div class="p-4 bg-surface-container-low border-b border-surface-variant flex flex-wrap gap-4">
        <select class="bg-surface border border-outline-variant rounded-lg px-4 py-2 text-sm flex-1 focus:outline-none">
            <option>Semua Kelas</option>
            @foreach($students->pluck('kelas.nama_kelas')->unique() as $kelasName)
                <option>{{ $kelasName }}</option>
            @endforeach
        </select>
        <select class="bg-surface border border-outline-variant rounded-lg px-4 py-2 text-sm flex-1 focus:outline-none">
            <option>Semua Predikat</option>
            <option>A</option>
            <option>B+</option>
            <option>B</option>
            <option>C</option>
            <option>D</option>
        </select>
    </div>
    <table class="w-full text-left">
        <thead class="bg-surface-container-low border-b border-surface-variant">
            <tr>
                <th class="p-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">No</th>
                <th class="p-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Nama Siswa</th>
                <th class="p-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">NIS</th>
                <th class="p-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Kelas</th>
                <th class="p-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider text-center">Tugas</th>
                <th class="p-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider text-center">Kuis</th>
                <th class="p-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider text-center">Ujian</th>
                <th class="p-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider text-center">Rata-rata</th>
                <th class="p-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider text-center">Predikat</th>
                <th class="p-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider text-center">Keterangan</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-surface-variant">
            @forelse($students as $i => $item)
            <tr class="hover:bg-surface-container transition-soft {{ $item['average'] < 75 ? 'bg-red-50/30' : '' }}">
                <td class="p-4 text-on-surface-variant text-sm">{{ $i + 1 }}</td>
                <td class="p-4 font-bold text-primary">{{ $item['siswa']->name }}</td>
                <td class="p-4 text-sm text-on-surface-variant">{{ $item['siswa']->id }}</td>
                <td class="p-4 text-sm text-on-surface-variant">{{ $item['kelas']->nama_kelas }}</td>
                <td class="p-4 text-center font-bold">{{ $item['tugas'] }}</td>
                <td class="p-4 text-center font-bold">{{ $item['kuis'] }}</td>
                <td class="p-4 text-center font-bold">{{ $item['ujian'] }}</td>
                <td class="p-4 text-center"><span class="font-bold text-lg text-primary">{{ $item['average'] }}</span></td>
                <td class="p-4 text-center"><span class="px-3 py-1 rounded-full text-xs font-bold {{ $item['predikat'] === 'A' ? 'bg-green-100 text-green-700' : ($item['predikat'] === 'B+' ? 'bg-secondary-container/40 text-secondary' : ($item['predikat'] === 'B' ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700')) }}">{{ $item['predikat'] }}</span></td>
                <td class="p-4 text-center">
                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $item['status'] === 'Lulus' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $item['status'] }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="p-6 text-center text-on-surface-variant">Belum ada data nilai untuk ditampilkan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
