<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mobil;
use App\Models\Peminjaman;

class ManagementController extends Controller
{
    public function dashboard()
    {
        $totalUnit = Mobil::sum('unit');
        $totalPeminjaman = Peminjaman::count();
        $latestPeminjaman = Peminjaman::with(['user', 'mobil'])->orderBy('created_at', 'desc')->limit(5)->get();
        return view('management.dashboard', [
            'totalUnit' => $totalUnit,
            'totalPeminjaman' => $totalPeminjaman,
            'latestPeminjaman' => $latestPeminjaman,
        ]);
    }

    public function mobil()
    {
        $mobil = Mobil::all();
        $mereks = Mobil::select('merk')->distinct()->pluck('merk');
        $models = Mobil::select('model')->distinct()->pluck('model');

        return view('management.mobil', [
            'mobil' => $mobil,
            'merks' => $mereks,
            'models' => $models,
        ]);
    }

    public function peminjaman()
    {
        $peminjaman = Peminjaman::with(['user', 'mobil'])->get();
        $mereks = Mobil::select('merk')->distinct()->pluck('merk');
        $models = Mobil::select('model')->distinct()->pluck('model');

        return view('management.peminjaman', [
            'peminjaman' => $peminjaman,
            'merks' => $mereks,
            'models' => $models,
        ]);
    }

    public function ubahStatus($id, $status)
    {
        $peminjaman = Peminjaman::find($id);
        if (!$peminjaman) {
            return response()->json([
                'success' => false,
                'status' => 404,
                'message' => 'Peminjaman tidak ditemukan',
            ]);
        }

        if($status == 'rejected' || $status == 'returned'){
            $mobil = Mobil::find($peminjaman->mobil_id);
            $mobil->unit += 1;
            $mobil->save();
        }
        
        $peminjaman->status = $status;
        $peminjaman->save();

        return response()->json([
            'success' => true,
            'status' => 200,
            'message' => 'Status peminjaman berhasil diubah',
        ]);
    }
}
