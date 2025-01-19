@extends('../layoutWrapper')

@section('style')
    @yield('pageStyle')
@endsection

@section('content')
    <div class="flex min-h-screen">
        <div class="w-[220px] bg-[#121122] fixed h-screen z-[5]">
            @include('management.component.sidebar')
        </div>
        <div class="flex-1">
            <section class="flex justify-end items-center bg-[#121122] px-3 py-6 fixed w-full">
                <span class="font-bold text-white mr-4 h-fit">Management</span>
                <div class="w-[40px] h-[40px] bg-white rounded-full cursor-pointer"></div>
            </section>
            <main class="p-6 pt-[110px] pl-[250px]">
                <h1 class="text-2xl font-bold">@yield('title')</h1>
                @yield('pageContent')
            </main>
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.getElementById('logout-btn').addEventListener('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Anda yakin?',
                text: "Anda ingin keluar dari aplikasi ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        });
    </script>
    @yield('pageScript')
@endsection
