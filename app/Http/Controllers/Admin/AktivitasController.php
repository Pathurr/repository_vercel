<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class AktivitasController extends Controller
{
    public function index(Request $request)
    {
        $logs = ActivityLog::with('user')->latest()->take(1000)->get();

        return view('admin.laporan-aktivitas', compact('logs'));
    }
}
