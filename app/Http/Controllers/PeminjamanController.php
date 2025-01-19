<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Mobil;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'mobil_id' => 'required',
                'tanggal_peminjaman' => 'required|date',
                'tanggal_pengembalian' => 'required|date|after_or_equal:tanggal_peminjaman',
            ], [
                'tanggal_pengembalian.after_or_equal' => 'Tanggal pengembalian harus setelah tanggal peminjaman',
                'tanggal_peminjaman.required' => 'Tanggal peminjaman harus diisi',
                'tanggal_pengembalian.required' => 'Tanggal pengembalian harus diisi',
                'tanggal_peminjaman.date' => 'Tanggal peminjaman harus berupa tanggal',
                'tanggal_pengembalian.date' => 'Tanggal pengembalian harus berupa tanggal',
                'mobil_id.required' => 'Pilih salah satu mobil untuk dipinjam',
            ]);

            $mobil = Mobil::find($request->mobil_id);

            $diff = date_diff(date_create($request->tanggal_peminjaman), date_create($request->tanggal_pengembalian));
            $total_harga = $mobil->harga_sewa * $diff->days;

            $peminjaman = new Peminjaman();
            $peminjaman->user_id = auth()->user()->id;
            $peminjaman->mobil_id = $request->mobil_id;
            $peminjaman->tanggal_peminjaman = $request->tanggal_peminjaman;
            $peminjaman->tanggal_pengembalian = $request->tanggal_pengembalian;
            $peminjaman->status = 'pending';
            $peminjaman->total_harga = $total_harga;
            $peminjaman->save();

            $mobil->unit -= 1;
            $mobil->save();

            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Peminjaman berhasil dibuat',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'success' => false,
                'status' => 200,
                'errors' => $e->errors(),
            ], 422);
        }
    }

    public function kembalikan(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required'
            ]);

            $peminjaman = Peminjaman::find($request->id);
            $peminjaman->status = 'returned';
            $peminjaman->save();

            $mobil = Mobil::find($peminjaman->mobil_id);
            $mobil->unit += 1;
            $mobil->save();

            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Peminjaman berhasil dikembalikan',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'success' => false,
                'status' => 200,
                'errors' => $e->errors(),
            ], 422);
        }
    }

    public function show($id)
    {
        $peminjaman = Peminjaman::with(['mobil', 'user'])->find($id);

        return response()->json([
            'success' => true,
            'status' => 200,
            'data' => $peminjaman,
        ]);
    }

    public function filter(Request $request)
    {
        $query = Peminjaman::query();

        if (!empty($request->status)) {
            $query->where('status', '=', $request->status);
        }

        if (!empty($request->merk) || !empty($request->model)) {
            $query->whereHas('mobil', function ($mobilQuery) use ($request) {
                if (!empty($request->merk)) {
                    $mobilQuery->where('merk', $request->merk);
                }

                if (!empty($request->model)) {
                    $mobilQuery->where('model', $request->model);
                }
            });
        }

        $peminjaman = $query->with(['mobil', 'user'])->get();

        return response()->json($peminjaman);
    }
}
