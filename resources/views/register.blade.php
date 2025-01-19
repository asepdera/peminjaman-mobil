@extends('layoutWrapper')

@section('content')
    <div class="flex justify-center items-center min-h-screen bg-gray-200 p-9">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/2">
            <h2 class="text-2xl font-semibold text-center mb-2">Register</h2>
            <form action="{{ route('registerAction') }}" method="post">
                @csrf
                <div class="columns-2">
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 font-bold">Nama</label>
                        <input type="text" name="name" id="name" placeholder="Masukan Nama" class="w-full p-2 border border-gray-300 rounded-lg">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="no_telp" class="block text-gray-700 font-bold">No Telepon</label>
                        <input type="text" name="no_telp" id="no_telp" placeholder="Masukan No Telepon" class="w-full p-2 border border-gray-300 rounded-lg">
                        @error('no_telp')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="mb-4">
                    <label for="alamat" class="block text-gray-700 font-bold">Alamat</label>
                    <textarea name="alamat" id="alamat" cols="10" rows="2" class="w-full p-2 border border-gray-300 rounded-lg"></textarea>
                    @error('alamat')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="no_SIM" class="block text-gray-700 font-bold">No SIM</label>
                    <input type="number" name="no_SIM" id="no_SIM" placeholder="Masukan No SIM" class="w-full p-2 border border-gray-300 rounded-lg">
                    @error('no_SIM')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="columns-2">
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 font-bold">Email</label>
                        <input type="email" name="email" id="email" placeholder="Masukan email" class="w-full p-2 border border-gray-300 rounded-lg">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block text-gray-700 font-bold">Password</label>
                        <input type="password" name="password" id="password" placeholder="Masukan password" class="w-full p-2 border border-gray-300 rounded-lg">
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <span class="mb-4">Sudah punya akun? <a href="/" class="text-blue-500">Login</a></span>
                <button type="submit" class="w-full mt-2 bg-[#121122] text-white p-2 rounded-lg">Register</button>
            </form>
        </div>
    </div>
    
@endsection