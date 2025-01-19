@extends('peminjam.layout')

@section('pageContent')
    <div class="mt-2">
        <span class="text-2xl font-bold">Dashboard</span>
        <div class="flex justify-between mt-3">
            <div class="w-[calc(50%-10px)] bg-white p-6 rounded-lg">
                <h2 class="text-lg font-bold">Total Peminjaman</h2>
                <p class="text-3xl font-bold">{{ $totalPeminjaman }} <span class="text-xl font-bold">Unit</span></p>
            </div>
            <div class="w-[calc(50%-10px)] bg-white p-6 rounded-lg">
                <h2 class="text-lg font-bold">Total Dikembalikan</h2>
                <p class="text-3xl font-bold">{{ $totalPeminjamanDikembalikan }} <span class="text-xl font-bold">Unit</span>
                </p>
            </div>
        </div>
        <div class="mt-6">
            <h2 class="text-lg font-bold">Peminjaman Mobil Terbaru</h2>
            <div class="mt-3">
                @forelse ($latestPeminjaman as $item)
                    <div class="flex justify-between items-center bg-white p-3 rounded-lg my-1">
                        <div class="flex items-center">
                            <div class="w-[40px] h-[40px] bg-[#121122] rounded-full">
                                <img src="{{ asset('storage/'.$item->mobil->foto) }}" alt="car" class="w-full h-full object-cover rounded-full"/>
                            </div>
                            <div class="ml-3">
                                <p class="font-bold">{{ $item->mobil->nama }} - {{ $item->mobil->model }}</p>
                                <p
                                    class="text-sm {{ $item->status == 'rejected' ? 'text-[#f00]' : ($item->status != 'pending' ? 'text-[#0f0]' : '') }}">
                                    {{ $item->status }}</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm">{{ $item->tanggal_peminjaman }} - {{ $item->tanggal_pengembalian }}</p>
                        </div>
                    </div>
                @empty
                    <span>Belum ada peminjaman</span>
                @endforelse
            </div>
        </div>
    </div>
@endsection
