<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Mobil;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(){
        $totalPeminjaman = Peminjaman::where('user_id', Auth::user()->id)->count();
        $totalPeminjamanDikembalikan = Peminjaman::where('user_id', Auth::user()->id)->where('status', 'returned')->count();
        $latestPeminjaman = Peminjaman::with(['mobil'])->where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->limit(5)->get();

        return view('peminjam.dashboard', [
            'totalPeminjaman' => $totalPeminjaman,
            'totalPeminjamanDikembalikan' => $totalPeminjamanDikembalikan,
            'latestPeminjaman' => $latestPeminjaman,
        ]);
    }

    public function peminjaman(){
        $peminjaman = Peminjaman::with(['mobil'])->where('user_id', Auth::user()->id)->get();
        $mereks = Mobil::select('merk')->distinct()->pluck('merk');
        $models = Mobil::select('model')->distinct()->pluck('model');
        
        return view('peminjam.peminjaman', [
            'peminjaman' => $peminjaman,
            'merks' => $mereks,
            'models' => $models,
        ]);
    }
}
