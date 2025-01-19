<?php

namespace App\Http\Controllers;

use App\Models\Mobil;
use Illuminate\Http\Request;

class MobilController extends Controller
{
    public function index()
    {
        return view('management.mobil.add');
    }
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'merek' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'model' => 'required|string|max:255',
            'tahun' => 'required|numeric|digits:4',
            'harga_sewa' => 'required|numeric',
            'unit' => 'required|integer|min:1',
            'plat_nomor' => 'required|string|max:50|unique:mobils,plat_nomor',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:4096',
            'warna' => 'required|string|max:255',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('mobils', 'public');
        }

        Mobil::create([
            'nama' => $request->name,
            'merk' => $request->merek,
            'deskripsi' => $request->deskripsi,
            'model' => $request->model,
            'tahun' => $request->tahun,
            'harga_sewa' => $request->harga_sewa,
            'unit' => $request->unit,
            'plat_nomor' => $request->plat_nomor,
            'warna' => $request->warna,
            'foto' => $fotoPath,
        ]);

        return redirect('/management/mobil')->with('success', 'Mobil successfully added.');
    }

    public function show($id)
    {
        $mobil = Mobil::findOrFail($id);
        return response()->json($mobil);
    }

    public function edit($id)
    {
        $mobil = Mobil::findOrFail($id);
        return view('management.mobil.edit', compact('mobil'));
    }

    public function update(Request $request, $id)
    {
        $mobil = Mobil::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'merk' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'model' => 'required|string|max:255',
            'tahun' => 'required|numeric|digits:4',
            'harga_sewa' => 'required|numeric',
            'unit' => 'required|integer|min:1',
            'plat_nomor' => 'required|string|max:50|unique:mobils,plat_nomor,' . $id,
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
            'warna' => 'required|string|max:255',
        ]);

        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('mobils', 'public');
            $mobil->foto = $fotoPath;
        }

        $mobil->update($request->except('foto'));

        return redirect('/management/mobil')->with('success', 'Mobil updated successfully.');
    }

    public function destroy($id)
    {
        $mobil = Mobil::findOrFail($id);
        $mobil->delete();
        return redirect('/management/mobil')->with('success', 'Mobil deleted successfully.');
    }
    public function filter(Request $request)
    {
        $query = Mobil::query();

        if (!empty($request->availability)) {
            if ($request->availability === 'available') {
                $query->where('unit', '>', 0);
            } elseif ($request->availability === 'unavailable') {
                $query->where('unit', '=', 0);
            }
        }

        if (!empty($request->merk)) {
            $query->where('merk', $request->merk);
        }

        if (!empty($request->model)) {
            $query->where('model', $request->model);
        }

        $mobil = $query->get();

        return response()->json($mobil);
    }
    public function getAvailableCars(Request $request)
    {
        $tanggalPeminjaman = $request->tanggal_peminjaman;
        $tanggalPengembalian = $request->tanggal_pengembalian;

        $mobilTersedia = Mobil::with(['peminjaman' => function ($query) use ($tanggalPeminjaman, $tanggalPengembalian) {
            if ($tanggalPeminjaman && $tanggalPengembalian) {
                $query->where(function ($q) use ($tanggalPeminjaman, $tanggalPengembalian) {
                    $q->whereBetween('tanggal_peminjaman', [$tanggalPeminjaman, $tanggalPengembalian])
                        ->orWhereBetween('tanggal_pengembalian', [$tanggalPeminjaman, $tanggalPengembalian])
                        ->orWhereRaw('? BETWEEN tanggal_peminjaman AND tanggal_pengembalian', [$tanggalPeminjaman])
                        ->orWhereRaw('? BETWEEN tanggal_peminjaman AND tanggal_pengembalian', [$tanggalPengembalian]);
                });
            }
        }])->get();

        $mobilTersedia = $mobilTersedia->filter(function ($mobil) {
            $rentedUnits = $mobil->peminjaman->sum('jumlah_unit');
            return $mobil->unit > $rentedUnits;
        });

        return response()->json($mobilTersedia->values());
    }
}
