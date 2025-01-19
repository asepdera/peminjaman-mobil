@extends('management.layout')

@section('pageContent')
    <div class="mt-2">
        <div class="flex justify-between items-center">
            <span class="text-2xl font-bold">Tambah Mobil</span>
        </div>
        <div class="flex justify-center items-center mt-6 min-w-[80vw]">
            <div class="bg-white p-4 rounded-lg w-full">
                <form action="{{route('createMobil')}}" method="post" class="mt-3" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-2 gap-2">
                        <div class="flex flex-col gap-3">
                            <label for="name" class="font-bold">Nama Mobil</label>
                            <input type="text" name="name" id="name"
                                class="p-2 border border-gray-300 rounded-lg" value="{{ old('name') }}">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex flex-col gap-3">
                            <label for="merek" class="font-bold">Merek</label>
                            <input type="text" name="merek" id="merek"
                                class="p-2 border border-gray-300 rounded-lg" value="{{ old('merek') }}">
                            @error('merek')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="flex flex-col gap-3 mt-3">
                        <label for="deskripsi" class="font-bold">Deskripsi</label>
                        <textarea type="text" name="deskripsi" id="deskripsi"
                            class="p-2 border border-gray-300 rounded-lg" value="{{ old('deskripsi') }}"></textarea>
                        @error('deskripsi')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-2 mt-3">
                        <div class="flex flex-col gap-3">
                            <label for="model" class="font-bold">Model</label>
                            <input type="text" name="model" id="model"
                                class="p-2 border border-gray-300 rounded-lg" value="{{ old('model') }}">
                            @error('model')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex flex-col gap-3">
                            <label for="tahun" class="font-bold">Tahun</label>
                            <input type="text" name="tahun" id="tahun"
                                class="p-2 border border-gray-300 rounded-lg" value="{{ old('tahun') }}">
                            @error('tahun')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2 mt-3">
                        <div class="flex flex-col gap-3">
                            <label for="harga_sewa" class="font-bold">Harga Sewa</label>
                            <input type="text" name="harga_sewa" id="harga_sewa"
                                class="p-2 border border-gray-300 rounded-lg" value="{{ old('harga_sewa') }}">
                            @error('harga_sewa')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex flex-col gap-3">
                            <label for="unit" class="font-bold">Unit</label>
                            <input type="text" name="unit" id="unit"
                                class="p-2 border border-gray-300 rounded-lg" value="{{ old('unit') }}">
                            @error('unit')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2 mt-3">
                        <div class="flex flex-col gap-3">
                            <label for="plat_nomor" class="font-bold">Plat Nomor</label>
                            <input type="text" name="plat_nomor" id="plat_nomor"
                                class="p-2 border border-gray-300 rounded-lg" value="{{ old('plat_nomor') }}">
                            @error('plat_nomor')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex flex-col gap-3">
                            <label for="warna" class="font-bold">Warna</label>
                            <input type="text" name="warna" id="warna"
                                class="p-2 border border-gray-300 rounded-lg" value="{{ old('warna') }}">
                            @error('warna')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="flex flex-col gap-3 mt-3">
                        <label for="foto" class="font-bold">Foto(max 4mb)</label>
                        <img id="imagePreview" 
                             src="" 
                             alt="Preview Foto" 
                             class="w-40 h-40 object-cover border border-gray-300 hidden rounded-lg mb-2">
                        <input type="file" name="foto" id="foto" onchange="previewImage(event)"
                            class="p-2 border border-gray-300 rounded-lg">
                        @error('foto')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex justify-end mt-3 gap-[10px]">
                        <a href="/management/mobil" type="button" class="bg-gray-500 px-4 py-2 rounded-lg text-white">Kembali</a>
                        <button type="submit" class="bg-blue-500 px-4 py-2 rounded-lg text-white">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('pageScript')
<script>
    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('imagePreview');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.classList.remove('hidden');
                preview.src = e.target.result;
            };
            
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection