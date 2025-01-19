<div class="flex flex-col h-full">
    <div class="flex flex-col items-center justify-center h-[100px] bg-[#121122]">
        <span class="text-2xl text-white font-bold">Logo</span>
    </div>
    <div class="flex flex-col h-[calc(100%-180px)] bg-[#121122]">
        <a href="/user" class="flex items-center w-full pl-[20px] h-[50px] text-white hover:bg-[#1f1f33]">
            <i class="fas fa-tachometer-alt"></i>
            <span class="ml-3">Dashboard</span>
        </a>
        <a href="/user/peminjaman" class="flex items-center w-full pl-[20px] h-[50px] text-white hover:bg-[#1f1f33]">
            <i class="fas fa-calendar-alt mr-3"></i>
            <span>Peminjaman</span>
        </a>
    </div>
    <form id="logout-form" action="{{ route('logoutAction') }}" method="post" class="hidden">
        @csrf
    </form>
    <a href="#" 
       id="logout-btn" 
       class="flex items-center w-full pl-[20px] h-[50px] text-white hover:bg-[#1f1f33]">
        <i class="fas fa-power-off mr-3"></i>
        <span>Logout</span>
    </a>
</div>