@extends('management.layout')

@section('pageStyle')
    <link rel="stylesheet" href="https://datatables.net/legacy/v1/media/css/dataTables.tailwindcss.css">
@endsection

@section('pageContent')
    <div class="mt-2">
        <span class="text-2xl font-bold">Peminjaman</span>
        <div class="flex justify-between mt-4">
            <div class="flex gap-[10px] w-[calc(50%-10px)] items-center">
                <span class="font-bold min-w-[80px]">Filter by : </span>
                <select name="" id="filterStatus" class="w-full p-1 border border-gray-300 rounded-lg">
                    <option value="" selected disabled>Status</option>
                    <option value="">Semua</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                    <option value="returned">Returned</option>
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
            <h2 class="text-lg font-bold">Data Peminjaman</h2>
            <div class="mt-3">
                <table id="example" class="display my-2" style="width:100%">
                    <thead>
                        <tr>
                            <th>Nama Peminjam</th>
                            <th>NO SIM</th>
                            <th>Nama Mobil</th>
                            <th>Model</th>
                            <th>Merek</th>
                            <th>Plat Nomor</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($peminjaman as $item)
                            <tr>
                                <td>{{ $item->user->name }}</td>
                                <td>{{ $item->user->SIM }}</td>
                                <td>{{ $item->mobil->nama }}</td>
                                <td>{{ $item->mobil->model }}</td>
                                <td>{{ $item->mobil->merk }}</td>
                                <td>{{ $item->mobil->plat_nomor }}</td>
                                <td>{{ $item->total_harga }}</td>
                                <td>
                                    @php
                                        $bgColor = match ($item->status) {
                                            'pending' => 'bg-[#A9ADAA]',
                                            'approved' => 'bg-[#00dd00]',
                                            'rejected' => 'bg-[#dd0000]',
                                            default => 'bg-[#0000dd]',
                                        };
                                    @endphp
                                    <div class="p-1 rounded {{ $bgColor }} text-white">
                                        <p class="text-center">{{ $item->status }}</p>
                                    </div>
                                </td>
                                <td class="flex gap-1">
                                    <button onclick="detail('{{ $item->id }}')"
                                        class="bg-blue-500 px-4 py-2 rounded-lg text-white">Detail</button>
                                    @if ($item->status == 'pending')
                                        <button class="bg-green-500 px-4 py-2 rounded-lg text-white"
                                            onclick="setujui('{{ $item->id }}')">Setujui</button>
                                        <button class="bg-red-500 px-4 py-2 rounded-lg text-white"
                                            onclick="tolak('{{ $item->id }}')">Tolak</button>
                                    @elseif($item->status == 'approved')
                                        <button class="bg-[#0000dd] px-4 py-2 rounded-lg text-white"
                                            onclick="kembalikan('{{ $item->id }}')">kembalikan</button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-[10]" id="datailPeminjaman">
        <div class="bg-white p-6 rounded-lg w-[600px] max-h-[90vh] overflow-scroll">
            <div class="flex justify-between items-center">
                <span class="text-lg font-bold">Detail Peminjaman</span>
                <button class="text-[#121122] text-lg"
                    onclick="document.querySelector('#datailPeminjaman').classList.toggle('hidden')">X</button>
            </div>
            <div class="mt-5">
                <div class="flex justify-center mb-8">
                    <div class="w-[70px] h-[70px] bg-[#121122] rounded-full">
                        <img src="" alt="car" id="detail-foto"
                            class="w-full h-full object-cover rounded-full" />
                    </div>
                </div>
                <div class="grid grid-cols-2 my-2">
                    <div class="">
                        <p class="font-bold">Nama Peminjam</p>
                        <p id="nama-peminjam"></p>
                    </div>
                    <div class="">
                        <p class="font-bold">No Telepon</p>
                        <p id="no-tlp-peminjam"></p>
                    </div>
                </div>
                <div class="grid grid-cols-2 my-2">
                    <div class="">
                        <p class="font-bold">No SIM</p>
                        <p id="sim-peminjam"></p>
                    </div>
                    <div class="">
                        <p class="font-bold">Alamat</p>
                        <p id="alamat-peminjam"></p>
                    </div>
                </div>
                <div class="grid grid-cols-2 my-2">
                    <div class="">
                        <p class="font-bold">Nama Mobil</p>
                        <p id="nama-mobil"></p>
                    </div>
                    <div class="">
                        <p class="font-bold">Plat Nomor Mobil</p>
                        <p id="plat-mobil"></p>
                    </div>
                </div>
                <div class="grid grid-cols-2 my-2">
                    <div class="">
                        <p class="font-bold">Merek Mobil</p>
                        <p id="merk-mobil"></p>
                    </div>
                    <div class="">
                        <p class="font-bold">Model Mobil</p>
                        <p id="model-mobil"></p>
                    </div>
                </div>
                <div class="grid grid-cols-2 my-2">
                    <div class="">
                        <p class="font-bold">Warna</p>
                        <p id="warna-mobil"></p>
                    </div>
                    <div class="">
                        <p class="font-bold">Harga Sewa</p>
                        <p id="harga-mobil"></p>
                    </div>
                </div>
                <div class="grid grid-cols-2 my-2">
                    <div class="">
                        <p class="font-bold">Tahun</p>
                        <p id="tahun-mobil"></p>
                    </div>
                    <div class="">
                        <p class="font-bold">Status</p>
                        <p id="status"></p>
                    </div>
                </div>
                <div class="grid grid-cols-2 my-2">
                    <div class="">
                        <p class="font-bold">Tanggal Peminjaman</p>
                        <p id="tanggal-peminjaman"></p>
                    </div>
                    <div class="">
                        <p class="font-bold">Tanggal Pengembalian</p>
                        <p id="tanggal-pengembalian"></p>
                    </div>
                </div>
                <div class="my-2">
                    <p class="font-bold">Total Harga</p>
                    <p id="total-harga"></p>
                </div>
                <div class="mt-6 flex justify-end">
                    <button class="bg-gray-500 text-white py-2 px-4 rounded-lg mr-2"
                        onclick="document.querySelector('#datailPeminjaman').classList.toggle('hidden')">Tutup</button>
                </div>
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
            const status = document.getElementById('filterStatus');
            const merk = document.getElementById('filterMerk');
            const model = document.getElementById('filterModel');

            const updateData = () => {
                const filters = {
                    status: status.value || '',
                    merk: merk.value || '',
                    model: model.value || '',
                };

                fetch('/management/peminjaman/filter', {
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
                            let bgColor = ''
                            let condButton = ''
                            switch(item.status){
                                case 'pending' : bgColor = 'bg-[#A9ADAA]'; break;
                                case 'approved' : bgColor = 'bg-[#00dd00]'; break;
                                case 'rejected' : bgColor = 'bg-[#dd0000]'; break;
                                default : bgColor = 'bg-[#0000dd]'; break;
                            }
                            if(item.status == 'pending'){
                                condButton = `
                                    <button class="bg-green-500 px-4 py-2 rounded-lg text-white"
                                        onclick="setujui('${item.id}')">Setujui</button>
                                    <button class="bg-red-500 px-4 py-2 rounded-lg text-white"
                                        onclick="tolak('${item.id}')">Tolak</button>
                                `
                            }
                            if(item.status == 'approved'){
                                condButton = `
                                    <button class="bg-[#0000dd] px-4 py-2 rounded-lg text-white"
                                            onclick="kembalikan('${item.id}')">kembalikan</button>
                                `
                            }
                            const row = `
                            <tr>
                                <td class='p-3'>${item.user.name}</td>
                                <td class='p-3'>${item.user.SIM}</td>
                                <td class='p-3'>${item.mobil.nama}</td>
                                <td class='p-3'>${item.mobil.model}</td>
                                <td class='p-3'>${item.mobil.merk}</td>
                                <td class='p-3'>${item.mobil.plat_nomor}</td>
                                <td class='p-3'>${item.total_harga}</td>
                                <td class='p-3'>
                                    <div class="p-1 rounded ${bgColor} text-white">
                                        <p class="text-center">${item.status}</p>
                                    </div>    
                                </td>
                                <td class="flex gap-1 p-3">
                                    <button onclick="detail('${item.id}')"
                                        class="bg-blue-500 px-4 py-2 rounded-lg text-white">Detail</button>
                                    ${condButton}
                                </td>
                            </tr>
                        `;
                            tableBody.innerHTML += row;
                        });
                        if (data.length <= 0) {
                            listPeminjaman.innerHTML = `<p>Tidak ada riwayat pemesanan</p>`;
                        }
                    })
                    .catch(error => console.error('Error:', error));
            };

            status.addEventListener('change', updateData);
            merk.addEventListener('change', updateData);
            model.addEventListener('change', updateData);
        });
        const detail = id => {
            fetch(`/detail/peminjaman/${id}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                })
                .then(response => response.json())
                .then(data => {
                    document.querySelector('#detail-foto').src = "{{ asset('storage') }}/" + data.data.mobil.foto
                    document.querySelector('#nama-peminjam').textContent = data.data.user.name
                    document.querySelector('#no-tlp-peminjam').textContent = data.data.user.no_telp
                    document.querySelector('#sim-peminjam').textContent = data.data.user.SIM
                    document.querySelector('#alamat-peminjam').textContent = data.data.user.alamat
                    document.querySelector('#nama-mobil').textContent = data.data.mobil.nama
                    document.querySelector('#merk-mobil').textContent = data.data.mobil.merk
                    document.querySelector('#plat-mobil').textContent = data.data.mobil.plat_nomor
                    document.querySelector('#warna-mobil').textContent = data.data.mobil.warna
                    document.querySelector('#tahun-mobil').textContent = data.data.mobil.tahun
                    document.querySelector('#model-mobil').textContent = data.data.mobil.model
                    document.querySelector('#harga-mobil').textContent = data.data.mobil.harga_sewa
                    document.querySelector('#tanggal-peminjaman').textContent = data.data.tanggal_peminjaman
                    document.querySelector('#tanggal-pengembalian').textContent = data.data.tanggal_pengembalian
                    document.querySelector('#status').textContent = data.data.status
                    document.querySelector('#total-harga').textContent = 'RP ' + data.data.total_harga
                    document.querySelector('#datailPeminjaman').classList.remove('hidden')
                })
                .catch(error => console.error('Error fetching available cars:', error));
        }

        const ubahStatusPermohonan = (id, status) => {
            Swal.fire({
                title: 'Anda yakin?',
                text: `Anda ${status == 'rejected' ? 'menolak' : (status == 'approved' ? 'menyetujui' : 'menyelesaikan')} permohonan ini?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/management/peminjaman/status/${id}/${status}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire(
                                    'Berhasil!',
                                    '',
                                    'success'
                                )
                                window.location.reload()
                            } else {
                                Swal.fire(
                                    'Gagal!',
                                    '',
                                    'error'
                                )
                            }

                        })
                        .catch(error => console.error('Error:', error));
                }
            });
        }

        const tolak = id => {
            ubahStatusPermohonan(id, 'rejected')
        }

        const setujui = id => {
            ubahStatusPermohonan(id, 'approved')
        }

        const kembalikan = id => {
            ubahStatusPermohonan(id, 'returned')
        }
    </script>
@endsection
