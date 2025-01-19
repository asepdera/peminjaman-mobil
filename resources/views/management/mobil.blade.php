@extends('management.layout')

@section('pageStyle')
    <link rel="stylesheet" href="https://datatables.net/legacy/v1/media/css/dataTables.tailwindcss.css">
@endsection

@section('pageContent')
    <div class="mt-2">
        <div class="flex justify-between items-center">
            <span class="text-2xl font-bold">Mobil</span>
            <a class="bg-blue-500 px-4 py-2 rounded-lg text-white" href="/management/mobil/add">Tambah Mobil</a>
        </div>
        <div class="flex justify-between mt-4">
            <div class="flex gap-[10px] w-[calc(50%-10px)] items-center">
                <span class="font-bold min-w-[80px]">Filter by : </span>
                <select name="" id="availability" class="w-full p-1 border border-gray-300 rounded-lg">
                    <option value="" selected disabled>Ketersediaan</option>
                    <option value="">Semua</option>
                    <option value="available">Tersedia</option>
                    <option value="unavailable">Tidak Tersedia</option>
                </select>
                <select name="" id="filterMerk" class="w-full p-1 border border-gray-300 rounded-lg">
                    <option value="" selected disabled>Merek</option>
                    <option value="">Semua</option>
                    @forelse ($merks as $merk)
                        <option value="{{ $merk }}">{{ $merk }}</option>
                    @empty
                        <option value="">Tidak ada merek</option>
                    @endforelse
                </select>
                <select name="" id="filterModel" class="w-full p-1 border border-gray-300 rounded-lg">
                    <option value="" selected disabled>Model</option>
                    <option value="">Semua</option>
                    @forelse ($models as $model)
                        <option value="{{ $model }}">{{ $model }}</option>
                    @empty
                        <option value="">Tidak ada merek</option>
                    @endforelse
                </select>
            </div>
            <div class="w-[calc(50%-10px)]">
            </div>
        </div>
        <div class="mt-6">
            <h2 class="text-lg font-bold">Data Mobil</h2>
            <div class="mt-3">
                <table id="example" class="display my-2" style="width:100%">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Merek</th>
                            <th>Model</th>
                            <th>Harga sewa / hari</th>
                            <th>Tahun</th>
                            <th>Warna</th>
                            <th>Unit</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($mobil as $item)
                            <tr>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->merk }}</td>
                                <td>{{ $item->model }}</td>
                                <td>{{ $item->harga_sewa }}</td>
                                <td>{{ $item->tahun }}</td>
                                <td>{{ $item->warna }}</td>
                                <td>{{ $item->unit }}</td>
                                <td class="flex gap-1">
                                    <a href="/management/mobil/edit/{{ $item->id }}"
                                        class="bg-blue-500 px-4 py-2 rounded-lg text-white">Edit</a>
                                    <form action="{{ route('deleteMobil', ['id' => $item->id]) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-red-500 px-4 py-2 rounded-lg text-white">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('pageScript')
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://datatables.net/legacy/v1/media/js/jquery.dataTables.js"></script>
    <script src="https://datatables.net/legacy/v1/media/js/dataTables.tailwindcss.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>

    <script>
        const table = new DataTable('#example', {
            info: false,
            lengthChange: false
        });
        document.addEventListener('DOMContentLoaded', function() {
            const filterForm = document.getElementById('filterForm');
            const availability = document.getElementById('availability');
            const merk = document.getElementById('filterMerk');
            const model = document.getElementById('filterModel');

            const updateTable = () => {
                const filters = {
                    availability: availability.value || '',
                    merk: merk.value || '',
                    model: model.value || '',
                };

                fetch('/management/mobil/filter', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify(filters),
                    })
                    .then(response => response.json())
                    .then(data => {
                        const tableBody = document.querySelector('#example tbody');
                        tableBody.innerHTML = '';

                        data.forEach(item => {
                            const row = `
                            <tr>
                                <td class='p-3'>${item.nama}</td>
                                <td class='p-3'>${item.merk}</td>
                                <td class='p-3'>${item.model}</td>
                                <td class='p-3'>${item.harga_sewa}</td>
                                <td class='p-3'>${item.tahun}</td>
                                <td class='p-3'>${item.warna}</td>
                                <td class='p-3'>${item.unit}</td>
                                <td class="flex gap-1 p-3">
                                    <a href="/management/mobil/edit/${item.id}" class="bg-blue-500 px-4 py-2 rounded-lg text-white">Edit</a>
                                    <form action="/management/mobil/delete/${item.id}" method="post">
                                        <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="bg-red-500 px-4 py-2 rounded-lg text-white">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        `;
                            tableBody.innerHTML += row;
                        });
                    })
                    .catch(error => console.error('Error:', error));
            };

            availability.addEventListener('change', updateTable);
            merk.addEventListener('change', updateTable);
            model.addEventListener('change', updateTable);
        });
    </script>
@endsection
