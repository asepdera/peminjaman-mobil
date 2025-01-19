@extends('management.layout')

@section('pageContent')
    <div class="mt-2">
        <span class="text-2xl font-bold">Dashboard</span>
        <div class="flex justify-between mt-3">
            <div class="w-[calc(50%-10px)] bg-white p-6 rounded-lg">
                <h2 class="text-lg font-bold">Total Peminjam</h2>
                <p class="text-3xl font-bold">{{ $totalPeminjaman }}</p>
            </div>
            <div class="w-[calc(50%-10px)] bg-white p-6 rounded-lg">
                <h2 class="text-lg font-bold">Total Mobil</h2>
                <p class="text-3xl font-bold">{{ $totalUnit }} <span class="text-xl font-bold">Unit</span></p>
            </div>
        </div>
        <div class="mt-6">
            <h2 class="text-lg font-bold">Peminjam Terbaru</h2>
            <div class="mt-3">
                @forelse ($latestPeminjaman as $item)
                    <div class="flex justify-between items-center bg-white p-3 rounded-lg my-1">
                        <div class="flex items-center">
                            <div class="ml-3">
                                <p class="font-bold">{{$item->user->name}} - {{$item->status}}</p>
                                <p class="text-sm">{{$item->mobil->nama}} - {{$item->mobil->model}} - {{$item->mobil->plat_nomor}}</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm">{{$item->tanggal_peminjaman}} - {{$item->tanggal_pengembalian}}</p>
                        </div>
                    </div>
                @empty
                    <span>Belum ada peminjaman</span>
                @endforelse
            </div>
        </div>
    </div>
@endsection
