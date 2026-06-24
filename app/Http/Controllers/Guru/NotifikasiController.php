<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifications = Notifikasi::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('guru.notifikasi', compact('notifications'));
    }
}
