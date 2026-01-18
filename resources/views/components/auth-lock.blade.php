<div class="relative">

    <div class="@guest blur-sm pointer-events-none select-none @endguest transition">
        {{ $slot }}
    </div>

    @guest
    <div class="absolute inset-0 flex items-center justify-center bg-black/30">
        <div class="bg-white p-6 rounded-xl shadow-lg text-center max-w-sm">
            <h2 class="text-xl font-bold mb-2">Login Diperlukan</h2>
            <p class="text-gray-600 mb-4">
                Silakan login terlebih dahulu untuk mengakses fitur ini.
            </p>
            <a href="{{ route('login') }}" class="btn btn-primary w-full">
                Login Sekarang
            </a>
        </div>
    </div>
    @endguest

</div>