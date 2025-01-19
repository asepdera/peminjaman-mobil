@extends('peminjam.layout')

@section('pageContent')
    <div class="mt-2">
        <div class="flex justify-between items-center">
            <span class="text-2xl font-bold">Peminjaman</span>
            <button class="bg-blue-500 px-4 py-2 rounded-lg text-white"
                onclick="document.querySelector('#addPeminjaman').classList.remove('hidden')">Buat Peminjaman</button>
        </div>
        <div class="flex justify-between mt-3">
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
        </div>
        <div class="mt-6">
            <h2 class="text-lg font-bold">Riwayat Peminjaman</h2>
            <div class="mt-3" id="peminjaman-list">
                @forelse ($peminjaman as $item)
                    <div class="flex justify-between items-center bg-white p-3 rounded-lg my-1">
                        <div class="flex items-center">
                            <div class="w-[40px] h-[40px] bg-[#121122] rounded-full">
                                <img src="{{ asset('storage/' . $item->mobil->foto) }}" alt="car"
                                    class="w-full h-full object-cover rounded-full" />
                            </div>
                            <div class="ml-3">
                                <p class="font-bold">{{ $item->mobil->nama }} - {{ $item->mobil->model }}</p>
                                <p
                                    class="text-sm {{ $item->status == 'rejected' ? 'text-[#f00]' : ($item->status != 'pending' ? 'text-[#0f0]' : '') }}">
                                    {{ $item->status }}</p>
                            </div>
                        </div>
                        <div class="flex gap-[10px] items-center">
                            <p class="text-sm">{{ $item->tanggal_peminjaman }} - {{ $item->tanggal_pengembalian }}</p>
                            <div class="flex gap-[10px]">
                                <button class="bg-[#121122] text-white py-2 px-3 text-sm rounded-lg"
                                    onclick="detail('{{ $item->id }}')">Detail</button>
                                @if ($item->status == 'approved')
                                    <button class="bg-[#121122] text-white py-2 px-3 text-sm rounded-lg"
                                        onclick="kembalikan('{{ $item->id }}')">Kembalikan</button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <p>Belum ada pemesanan</p>
                @endforelse
            </div>
        </div>
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-[10]" id="addPeminjaman">
            <div class="bg-white p-6 rounded-lg w-[600px]">
                <div class="flex justify-between items-center">
                    <span class="text-lg font-bold">Buat Pesanan Peminjaman</span>
                    <button class="text-[#121122] text-lg"
                        onclick="document.querySelector('#addPeminjaman').classList.toggle('hidden')">X</button>
                </div>
                <div class="mt-5">
                    {{-- <form action="{{ route('createPeminjaman') }}" method="post"> --}}
                    <div id="alert"
                        class="hidden bg-red-500 text-white px-4 py-2 rounded mb-4 flex justify-between items-center">
                        <span>Pilih salah satu mobil untuk dipinjam</span>
                        <button class="text-white ml-4 focus:outline-none"
                            onclick="document.getElementById('alert').remove()">
                            &times;
                        </button>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="hidden" id="selected-mobil-id" name="mobil_id">
                        <div class="flex flex-col">
                            <label for="tanggal" class="font-bold min-w-[80px] mb-2">Tanggal Peminjaman:</label>
                            <input type="date" id="tanggal_peminjaman" name="tanggal"
                                class="w-full p-1 border border-gray-300 rounded-lg">
                            {{-- @error('tanggal_peminjaman') --}}
                            <p class="text-red-500 text-sm mt-1 hidden" id="error-tanggal-peminjaman"></p>
                            {{-- @enderror --}}
                        </div>
                        <div class="flex flex-col">
                            <label for="tanggal" class="font-bold min-w-[80px] mb-2">Tanggal Pengembalian:</label>
                            <input type="date" id="tanggal_pengembalian" name="tanggal"
                                class="w-full p-1 border border-gray-300 rounded-lg">
                            {{-- @error('tanggal_pengembalian') --}}
                            <p class="text-red-500 text-sm mt-1 hidden" id="error-tanggal-pengembalian"></p>
                            {{-- @enderror --}}
                        </div>
                    </div>
                    <div class="flex flex-col mt-3">
                        <label for="tanggal" class="font-bold min-w-[80px] mb-2">Mobil Tersedia:</label>
                        <div id="available-car" class="flex flex-col"></div>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <button class="bg-blue-500 text-white py-2 px-4 rounded-lg mr-2" id="btn-pesan">Pesan</button>
                    </div>
                    {{-- </form> --}}
                </div>
            </div>
        </div>
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-[10]"
            id="datailPeminjaman">
            <div class="bg-white p-6 rounded-lg w-[600px]">
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
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-[10]" id="pengembalian">
            <div class="bg-white p-6 rounded-lg w-[600px]">
                <div class="flex justify-between items-center">
                    <span class="text-lg font-bold">Pengembalian</span>
                    <button class="text-[#121122] text-lg"
                        onclick="document.querySelector('#pengembalian').classList.toggle('hidden')">X</button>
                </div>
                <div class="mt-5">
                    <div id="alert-plat"
                        class="hidden bg-red-500 text-white px-4 py-2 rounded mb-4 flex justify-between items-center">
                        <span>Tolong masukan plat nomor mobil untuk proses pengembalian</span>
                        <button class="text-white ml-4 focus:outline-none"
                            onclick="document.getElementById('alert').remove()">
                            &times;
                        </button>
                    </div>
                    <div class="grid grid-cols-2 my-2">
                        <div class="">
                            <p class="font-bold">Tanggal Peminjaman</p>
                            <p id="k-tanggal-peminjaman"></p>
                        </div>
                        <div class="">
                            <p class="font-bold">Tanggal Pengembalian</p>
                            <p id="k-tanggal-pengembalian"></p>
                        </div>
                    </div>
                    <div class="my-2">
                        <p class="font-bold">Total Harga</p>
                        <p id="k-total-harga"></p>
                    </div>
                    <div class="flex flex-col">
                        <input type="hidden" name="pinjam_id" id="pinjam_id">
                        <label for="tanggal" class="font-bold min-w-[80px] mb-2">Masukan Plat Nomor:</label>
                        <input type="text" id="plat_nomor" name="plat_nomor"
                            class="w-full p-1 border border-gray-300 rounded-lg">
                        <p class="text-red-500 text-sm mt-1 hidden" id="error-plat-nomor"></p>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <button class="bg-blue-500 text-white py-2 px-4 rounded-lg mr-2" id="btn-kembalikan">Kembalikan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pageScript')
    <script>
        document.getElementById('tanggal_peminjaman').addEventListener('change', fetchAvailableCars);
        document.getElementById('tanggal_pengembalian').addEventListener('change', fetchAvailableCars);

        function doFetch(pinjamDate, kembaliDate) {
            fetch('/management/mobil/available', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        tanggal_peminjaman: pinjamDate || null,
                        tanggal_pengembalian: kembaliDate || null
                    })
                })
                .then(response => response.json())
                .then(data => {
                    const mobilContainer = document.getElementById('available-car');
                    data.forEach(mobil => {
                        const carDiv = document.createElement('div');
                        carDiv.className =
                            'flex items-center cursor-pointer p-2 border rounded-lg mb-2 hover:bg-gray-100';
                        carDiv.setAttribute('data-id', mobil.id);

                        carDiv.innerHTML = `
                                <div class="w-[40px] h-[40px] bg-[#121122] rounded-full">
                                    <img src="{{ asset('storage') }}/${mobil.foto}" alt="car" class="w-full h-full object-cover rounded-full"/>
                                </div>
                                <div class="ml-3">
                                    <p class="font-bold">${mobil.nama} - ${mobil.model}</p>
                                    <p class="text-sm">Tersedia: ${mobil.unit} unit - RP ${mobil.harga_sewa} / hari</p>
                                </div>
                            `;

                        carDiv.addEventListener('click', () => {
                            document.querySelectorAll('#available-car .selected').forEach((el) => {
                                el.classList.remove('selected');
                            });
                            carDiv.classList.add('selected');

                            const selectedMobilId = carDiv.getAttribute('data-id');
                            console.log('Selected Mobil ID:', selectedMobilId);

                            document.getElementById('selected-mobil-id').value = selectedMobilId;
                        });

                        mobilContainer.appendChild(carDiv);
                    });
                })
                .catch(error => console.error('Error fetching available cars:', error));
        }

        (() => {
            const tanggalPeminjaman = document.getElementById('tanggal_peminjaman').value;
            const tanggalPengembalian = document.getElementById('tanggal_pengembalian').value;
            doFetch(tanggalPeminjaman, tanggalPengembalian);
        })()

        function fetchAvailableCars() {
            const tanggalPeminjaman = document.getElementById('tanggal_peminjaman').value;
            const tanggalPengembalian = document.getElementById('tanggal_pengembalian').value;

            if (tanggalPeminjaman && tanggalPengembalian) {
                const availableCarContainer = document.getElementById('available-car');
                availableCarContainer.innerHTML = '';
                doFetch(tanggalPeminjaman, tanggalPengembalian);
            }
        }

        document.getElementById('btn-pesan').addEventListener('click', () => {
            const tanggalPeminjaman = document.getElementById('tanggal_peminjaman').value;
            const tanggalPengembalian = document.getElementById('tanggal_pengembalian').value;
            const mobil_id = document.getElementById('selected-mobil-id').value;

            if (!mobil_id) {
                document.getElementById('alert').classList.remove('hidden');
            } else {
                document.getElementById('alert')?.classList.add('hidden');
            }

            fetch("{{ route('createPeminjaman') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        tanggal_peminjaman: tanggalPeminjaman,
                        tanggal_pengembalian: tanggalPengembalian,
                        mobil_id
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Peminjaman berhasil dibuat'
                        });
                        window.location.reload();
                    } else {
                        const handleError = (field, errorKey) => {
                            const errorElement = document.getElementById(`error-${field}`);
                            if (errorKey in data.errors) {
                                errorElement.classList.remove('hidden');
                                errorElement.textContent = data.errors[errorKey][0];
                            } else {
                                errorElement.classList.add('hidden');
                            }
                        };

                        handleError('tanggal-peminjaman', 'tanggal_peminjaman');
                        handleError('tanggal-pengembalian', 'tanggal_pengembalian');
                    }
                })
                .catch(error => console.error('Error fetching available cars:', error));
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
                        const listPeminjaman = document.getElementById('peminjaman-list');
                        listPeminjaman.innerHTML = '';
                        data.forEach(peminjaman => {
                            const carDiv = document.createElement('div');
                            carDiv.className =
                                'flex justify-between items-center bg-white p-3 rounded-lg my-1';

                            carDiv.innerHTML = `
                                <div class="flex items-center">
                                    <div class="w-[40px] h-[40px] bg-[#121122] rounded-full">
                                        <img src="{{ asset('storage') }}/${peminjaman.mobil.foto}" alt="car"
                                            class="w-full h-full object-cover rounded-full" />
                                    </div>
                                    <div class="ml-3">
                                        <p class="font-bold">${peminjaman.mobil.nama} - ${peminjaman.mobil.model}</p>
                                        <p
                                            class="text-sm ${peminjaman.status == 'rejected' ? 'text-[#f00]' : (peminjaman.status != 'pending' ? 'text-[#0f0]' : '')}">
                                            ${peminjaman.status}</p>
                                    </div>
                                </div>
                                <div class="flex gap-[10px] items-center">
                                    <p class="text-sm">${peminjaman.tanggal_peminjaman} - ${peminjaman.tanggal_pengembalian}</p>
                                    <div class="flex gap-[10px]">
                                        <button class="bg-[#121122] text-white py-2 px-3 text-sm rounded-lg"
                                            onclick="detail('${peminjaman.id}')">Detail</button>
                                            <button class="bg-[#121122] text-white py-2 px-3 text-sm rounded-lg ${peminjaman.status == 'approved' ? '' : 'hidden'}"
                                                onclick="kembalikan('${peminjaman.id}')">Kembalikan</button>
                                    </div>
                                </div>
                            `;

                            listPeminjaman.appendChild(carDiv);
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

        const kembalikan = id => {
            document.querySelector('#pengembalian').classList.remove('hidden')
            fetch(`/detail/peminjaman/${id}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                })
                .then(response => response.json())
                .then(data => {
                    document.querySelector('#pinjam_id').value = data.data.id
                    document.querySelector('#k-tanggal-peminjaman').textContent = data.data.tanggal_peminjaman
                    document.querySelector('#k-tanggal-pengembalian').textContent = data.data.tanggal_pengembalian
                    document.querySelector('#k-total-harga').textContent = 'RP ' + data.data.total_harga
                })
                .catch(error => console.error('Error fetching available cars:', error));
        }
        document.getElementById('btn-kembalikan').addEventListener('click', () => {
            const pinjam_id = document.getElementById('pinjam_id').value;
            const plat_nomor = document.getElementById('plat_nomor').value;

            if (!plat_nomor) {
                document.getElementById('alert-plat').classList.remove('hidden');
                return
            } else {
                document.getElementById('alert-plat')?.classList.add('hidden');
            }

            fetch("{{ route('createPengembalian') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        id: pinjam_id
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Pengembalian berhasil'
                        });
                        window.location.reload();
                    } else {
                        console.log(data)
                    }
                })
                .catch(error => console.error('Error fetching available cars:', error));
        });
    </script>
@endsection
