@extends('layoutWrapper')

@section('content')
    <div class="flex justify-center flex-col gap-[10px] items-center min-h-screen bg-gray-200">
        @if (session()->has('error'))
            @foreach ((array) session('error') as $error)
                <div id="alert-{{ $loop->index }}"
                    class="bg-red-500 text-white px-4 py-2 rounded mb-4 flex justify-between items-center">
                    <span>{{ $error }}</span>
                    <button class="text-white ml-4 focus:outline-none"
                        onclick="document.getElementById('alert-{{ $loop->index }}').remove()">
                        &times;
                    </button>
                </div>
            @endforeach
        @endif
        <div class="bg-white p-6 rounded-lg shadow-lg w-[30%]">
            <h2 class="text-2xl font-semibold text-center mb-2">Login</h2>
            <form action="{{ route('loginAction') }}" method="post">
                @csrf
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-bold">Email</label>
                    <input type="email" name="email" id="email" placeholder="Masukan email"
                        class="w-full p-2 border border-gray-300 rounded-lg" value="{{ old('email') }}">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-gray-700 font-bold">Password</label>
                    <input type="password" name="password" id="password" placeholder="Masukan password"
                        class="w-full p-2 border border-gray-300 rounded-lg">
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <span class="mb-4">Belum punya akun? <a href="/register" class="text-blue-500">Register</a></span>
                <button type="submit" class="w-full mt-2 bg-[#121122] text-white p-2 rounded-lg">Login</button>
            </form>
        </div>
    </div>
@endsection
