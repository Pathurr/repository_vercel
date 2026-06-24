<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🛠️ Dev Panel - LMS SMK Mandalahayu 1</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background: #0f172a;
            color: #e2e8f0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .container { max-width: 700px; width: 100%; }
        .header { text-align: center; margin-bottom: 2.5rem; }
        .badge {
            display: inline-block;
            background: #ef4444;
            color: white;
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            text-transform: uppercase;
            margin-bottom: 1rem;
        }
        h1 { font-size: 2rem; font-weight: 700; color: #f8fafc; margin-bottom: 0.5rem; }
        .subtitle { color: #94a3b8; font-size: 0.9rem; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.25rem; margin-bottom: 1.5rem; }
        .card {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 1rem;
            padding: 1.75rem;
            text-decoration: none;
            color: inherit;
            transition: all 0.2s;
            display: block;
        }
        .card:hover { transform: translateY(-3px); border-color: #64748b; background: #273549; }
        .card-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            display: block;
        }
        .card-title { font-size: 1.2rem; font-weight: 700; color: #f1f5f9; margin-bottom: 0.4rem; }
        .card-desc { font-size: 0.8rem; color: #64748b; line-height: 1.5; margin-bottom: 1rem; }
        .card-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.8rem;
            font-weight: 700;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: opacity 0.2s;
        }
        .card-btn:hover { opacity: 0.85; }
        .btn-guru { background: #7c3aed; color: white; }
        .btn-siswa { background: #0ea5e9; color: white; }
        .btn-admin { background: #10b981; color: white; }
        .divider { border: none; border-top: 1px solid #1e293b; margin: 1.5rem 0; }
        .links-section { background: #1e293b; border: 1px solid #334155; border-radius: 1rem; padding: 1.5rem; }
        .links-title { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: #64748b; margin-bottom: 1rem; }
        .links-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem; }
        .link-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
            text-decoration: none;
            font-size: 0.8rem;
            color: #94a3b8;
            border: 1px solid #334155;
            transition: all 0.15s;
        }
        .link-item:hover { background: #273549; color: #e2e8f0; border-color: #475569; }
        .link-dot-guru { width: 7px; height: 7px; border-radius: 50%; background: #7c3aed; flex-shrink: 0; }
        .link-dot-siswa { width: 7px; height: 7px; border-radius: 50%; background: #0ea5e9; flex-shrink: 0; }
        .link-dot-admin { width: 7px; height: 7px; border-radius: 50%; background: #10b981; flex-shrink: 0; }
        .link-dot-auth { width: 7px; height: 7px; border-radius: 50%; background: #f59e0b; flex-shrink: 0; }
        .warning {
            background: #431407;
            border: 1px solid #9a3412;
            border-radius: 0.75rem;
            padding: 0.875rem 1.25rem;
            font-size: 0.78rem;
            color: #fca5a5;
            margin-top: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .url { font-family: monospace; font-size: 0.75rem; color: #4ade80; background: #052e16; padding: 0.15rem 0.4rem; border-radius: 0.25rem; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <span class="badge">🛠️ Development Only</span>
            <h1>LMS Dev Panel</h1>
            <p class="subtitle">SMK Mandalahayu 1 Bekasi — Akses cepat ke semua halaman</p>
        </div>

        {{-- Quick Access Cards --}}
        <div class="grid">
            <div class="card">
                <span class="card-icon">👨‍🏫</span>
                <div class="card-title">Portal Guru</div>
                <div class="card-desc">Login otomatis sebagai guru dan langsung masuk ke dashboard guru.</div>
                <a href="{{ route('dev.guru') }}" class="card-btn btn-guru">→ Masuk sebagai Guru</a>
            </div>
            <div class="card">
                <span class="card-icon">🧑‍🎓</span>
                <div class="card-title">Portal Siswa</div>
                <div class="card-desc">Login otomatis sebagai siswa dan langsung masuk ke dashboard siswa.</div>
                <a href="{{ route('dev.siswa') }}" class="card-btn btn-siswa">→ Masuk sebagai Siswa</a>
            </div>
            <div class="card">
                <span class="card-icon">🛡️</span>
                <div class="card-title">Portal Admin</div>
                <div class="card-desc">Login otomatis sebagai admin dan langsung masuk ke admin panel.</div>
                <a href="{{ route('dev.admin') }}" class="card-btn btn-admin">→ Masuk sebagai Admin</a>
            </div>
        </div>

        {{-- All Page Links --}}
        <div class="links-section">
            <div class="links-title">🔗 Semua Halaman Tersedia</div>
            <div class="links-grid">
                {{-- Auth --}}
                <a href="{{ route('login') }}" class="link-item"><span class="link-dot-auth"></span>Login</a>
                <a href="{{ route('register') }}" class="link-item"><span class="link-dot-auth"></span>Register</a>
                <a href="{{ route('password.request') }}" class="link-item"><span class="link-dot-auth"></span>Lupa Password</a>
                <a href="{{ route('dev.verify-email') }}" class="link-item"><span class="link-dot-auth"></span>Verifikasi Email (Dev)</a>

                {{-- Admin --}}
                <a href="{{ route('dev.admin') }}" class="link-item"><span class="link-dot-admin"></span>Admin: Dashboard</a>
                <a href="{{ route('admin.akun') }}" class="link-item"><span class="link-dot-admin"></span>Admin: Manajemen Akun</a>
                <a href="{{ route('admin.aktivitas') }}" class="link-item"><span class="link-dot-admin"></span>Admin: Laporan Aktivitas</a>

                {{-- Guru --}}
                <a href="{{ route('dev.guru') }}" class="link-item"><span class="link-dot-guru"></span>Guru: Dashboard</a>
                <a href="{{ route('guru.kelas') }}" class="link-item"><span class="link-dot-guru"></span>Guru: Kelas</a>
                <a href="{{ route('guru.materi') }}" class="link-item"><span class="link-dot-guru"></span>Guru: Materi</a>
                <a href="{{ route('guru.materi.tambah') }}" class="link-item"><span class="link-dot-guru"></span>Guru: Tambah Materi</a>
                <a href="{{ route('guru.tugas') }}" class="link-item"><span class="link-dot-guru"></span>Guru: Tugas</a>
                <a href="{{ route('guru.kuis') }}" class="link-item"><span class="link-dot-guru"></span>Guru: Kuis</a>
                <a href="{{ route('guru.ujian') }}" class="link-item"><span class="link-dot-guru"></span>Guru: Ujian</a>
                <a href="{{ route('guru.nilai') }}" class="link-item"><span class="link-dot-guru"></span>Guru: Nilai</a>
                <a href="{{ route('guru.nilai.rekap') }}" class="link-item"><span class="link-dot-guru"></span>Guru: Rekap Nilai</a>
                <a href="{{ route('guru.monitor') }}" class="link-item"><span class="link-dot-guru"></span>Guru: Monitor Tugas</a>
                <a href="{{ route('guru.penilaian.tugas') }}" class="link-item"><span class="link-dot-guru"></span>Guru: Penilaian Tugas</a>
                <a href="{{ route('guru.penilaian.ujian') }}" class="link-item"><span class="link-dot-guru"></span>Guru: Penilaian Ujian</a>
                <a href="{{ route('guru.notifikasi') }}" class="link-item"><span class="link-dot-guru"></span>Guru: Notifikasi</a>

                {{-- Siswa --}}
                <a href="{{ route('dev.siswa') }}" class="link-item"><span class="link-dot-siswa"></span>Siswa: Dashboard</a>
                <a href="{{ route('siswa.mapel') }}" class="link-item"><span class="link-dot-siswa"></span>Siswa: Mata Pelajaran</a>
                <a href="{{ route('siswa.materi') }}" class="link-item"><span class="link-dot-siswa"></span>Siswa: Materi</a>
                <a href="{{ route('siswa.lihat-materi', 1) }}" class="link-item"><span class="link-dot-siswa"></span>Siswa: Lihat Materi</a>
                <a href="{{ route('siswa.tugas') }}" class="link-item"><span class="link-dot-siswa"></span>Siswa: Tugas</a>
                <a href="{{ route('siswa.pengerjaan-tugas', 1) }}" class="link-item"><span class="link-dot-siswa"></span>Siswa: Pengerjaan Tugas</a>
                <a href="{{ route('siswa.ujian') }}" class="link-item"><span class="link-dot-siswa"></span>Siswa: Ujian</a>
                <a href="{{ route('siswa.pengerjaan-ujian', 1) }}" class="link-item"><span class="link-dot-siswa"></span>Siswa: Kerjakan Ujian</a>
                <a href="{{ route('siswa.pengerjaan-kuis', 1) }}" class="link-item"><span class="link-dot-siswa"></span>Siswa: Kerjakan Kuis</a>
                <a href="{{ route('siswa.nilai') }}" class="link-item"><span class="link-dot-siswa"></span>Siswa: Nilai</a>
                <a href="{{ route('siswa.notifikasi') }}" class="link-item"><span class="link-dot-siswa"></span>Siswa: Notifikasi</a>
            </div>
        </div>

        <div class="warning">
            ⚠️ Halaman ini hanya muncul di environment <span class="url">APP_ENV=local</span>.
            Akan otomatis hilang saat deploy ke production.
        </div>
    </div>
</body>
</html>
