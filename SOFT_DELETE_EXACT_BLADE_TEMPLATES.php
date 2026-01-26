<?php
/**
 * EXACT BLADE TEMPLATE EXAMPLES FOR SOFT DELETE
 * 
 * Copy-paste ini langsung ke view files masing-masing
 */
?>

<!-- ====================================================================== -->
<!-- EXAMPLE 1: KATEGORI VIEW (Tab-based) -->
<!-- File: resources/views/pages-admin/kategori.blade.php -->
<!-- ====================================================================== -->

@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-4xl font-bold mb-6">Manajemen Kategori</h1>
    
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            ✅ {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            ❌ {{ session('error') }}
        </div>
    @endif

    <!-- TAB BUTTONS -->
    <div class="flex gap-2 mb-4 border-b">
        <button onclick="switchTab('active')" id="activeBtn" 
                class="tab-button px-4 py-2 border-b-2 border-blue-500 font-semibold">
            📋 Kategori Aktif ({{ count($categories) }})
        </button>
        <button onclick="switchTab('deleted')" id="deletedBtn" 
                class="tab-button px-4 py-2 border-b-2 border-gray-300">
            🗑️ Kategori Dihapus ({{ count($deletedCategories) }})
        </button>
    </div>

    <!-- TAB: KATEGORI AKTIF -->
    <div id="active" class="tab-panel">
        <a href="#" class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block hover:bg-blue-700">
            ➕ Tambah Kategori
        </a>

        @if(count($categories) > 0)
            <table class="w-full border-collapse border border-gray-300 mt-4">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="border border-gray-300 px-4 py-2 text-left">ID</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Nama Kategori</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Dibuat</th>
                        <th class="border border-gray-300 px-4 py-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                        <tr class="hover:bg-gray-50">
                            <td class="border border-gray-300 px-4 py-2">{{ $category->id }}</td>
                            <td class="border border-gray-300 px-4 py-2 font-semibold">{{ $category->nama }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-sm text-gray-600">
                                {{ $category->created_at->format('d-m-Y H:i') }}
                            </td>
                            <td class="border border-gray-300 px-4 py-2 text-center">
                                <!-- EDIT BUTTON -->
                                <button class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 mr-2">
                                    ✏️ Edit
                                </button>
                                
                                <!-- DELETE BUTTON (SOFT DELETE) -->
                                <form method="POST" action="{{ route('admin.categories.destroy', $category->id) }}" 
                                      style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600"
                                            onclick="return confirm('Yakin ingin menghapus kategori ini?')">
                                        🗑️ Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="bg-blue-50 border border-blue-200 rounded p-4 text-center text-gray-600">
                📭 Tidak ada kategori aktif
            </div>
        @endif
    </div>

    <!-- TAB: KATEGORI DIHAPUS (TRASH) -->
    <div id="deleted" class="tab-panel hidden">
        @if(count($deletedCategories) > 0)
            <table class="w-full border-collapse border border-gray-300">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="border border-gray-300 px-4 py-2 text-left">ID</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Nama Kategori</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Dihapus Pada</th>
                        <th class="border border-gray-300 px-4 py-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($deletedCategories as $category)
                        <tr class="bg-gray-100 opacity-75 hover:opacity-100">
                            <td class="border border-gray-300 px-4 py-2">{{ $category->id }}</td>
                            <td class="border border-gray-300 px-4 py-2 line-through text-gray-600">
                                {{ $category->nama }}
                            </td>
                            <td class="border border-gray-300 px-4 py-2 text-sm text-gray-600">
                                {{ $category->deleted_at->format('d-m-Y H:i') }}
                            </td>
                            <td class="border border-gray-300 px-4 py-2 text-center">
                                <!-- RESTORE BUTTON -->
                                <form method="POST" action="{{ route('admin.categories.restore', $category->id) }}" 
                                      style="display:inline;">
                                    @csrf
                                    <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                                        ↩️ Pulihkan
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="bg-blue-50 border border-blue-200 rounded p-4 text-center text-gray-600">
                ✅ Tidak ada kategori yang dihapus
            </div>
        @endif
    </div>
</div>

<script>
    function switchTab(tabName) {
        // Hide all panels
        document.querySelectorAll('.tab-panel').forEach(el => el.classList.add('hidden'));
        
        // Show selected panel
        document.getElementById(tabName).classList.remove('hidden');
        
        // Update button styles
        document.querySelectorAll('.tab-button').forEach(btn => {
            btn.classList.remove('border-blue-500', 'font-semibold');
            btn.classList.add('border-gray-300');
        });
        
        if (tabName === 'active') {
            document.getElementById('activeBtn').classList.add('border-blue-500', 'font-semibold');
            document.getElementById('activeBtn').classList.remove('border-gray-300');
        } else {
            document.getElementById('deletedBtn').classList.add('border-blue-500', 'font-semibold');
            document.getElementById('deletedBtn').classList.remove('border-gray-300');
        }
    }
</script>
@endsection


<!-- ====================================================================== -->
<!-- EXAMPLE 2: EVENTS VIEW (Card-based) -->
<!-- File: resources/views/events/events.blade.php -->
<!-- ====================================================================== -->

@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-4xl font-bold">Manajemen Event</h1>
        <a href="{{ route('admin.events.create') }}" 
           class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
            ➕ Tambah Event Baru
        </a>
    </div>
    
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            ✅ {{ session('success') }}
        </div>
    @endif

    <!-- TAB BUTTONS -->
    <div class="flex gap-2 mb-6 border-b">
        <button onclick="switchTab('active')" id="activeBtn" 
                class="tab-button px-4 py-2 border-b-2 border-blue-500 font-semibold">
            📋 Event Aktif ({{ count($events) }})
        </button>
        <button onclick="switchTab('deleted')" id="deletedBtn" 
                class="tab-button px-4 py-2 border-b-2 border-gray-300">
            🗑️ Event Dihapus ({{ count($deletedEvents) }})
        </button>
    </div>

    <!-- TAB: EVENT AKTIF (Card Grid) -->
    <div id="active" class="tab-panel">
        @if(count($events) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($events as $event)
                    <div class="border rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-shadow">
                        <!-- Event Image -->
                        <img src="/images/{{ $event->gambar }}" alt="{{ $event->judul }}" 
                             class="w-full h-40 object-cover">
                        
                        <!-- Event Info -->
                        <div class="p-4">
                            <h3 class="font-bold text-lg mb-2">{{ $event->judul }}</h3>
                            
                            <div class="text-sm text-gray-600 mb-3">
                                <p>📌 <strong>{{ $event->kategori->nama }}</strong></p>
                                <p>📅 {{ $event->tanggal->format('d-m-Y') }}</p>
                                <p>📍 {{ $event->lokasi }}</p>
                            </div>
                            
                            <p class="text-sm text-gray-500 mb-4">
                                {{ Str::limit($event->deskripsi, 60) }}
                            </p>
                            
                            <!-- Action Buttons -->
                            <div class="flex gap-2">
                                <a href="{{ route('admin.events.edit', $event->id) }}" 
                                   class="flex-1 bg-yellow-500 text-white px-3 py-2 rounded text-center hover:bg-yellow-600">
                                    ✏️ Edit
                                </a>
                                
                                <form method="POST" action="{{ route('admin.events.destroy', $event->id) }}" 
                                      style="display:inline; flex:1;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full bg-red-500 text-white px-3 py-2 rounded hover:bg-red-600"
                                            onclick="return confirm('Yakin hapus event ini?')">
                                        🗑️ Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-blue-50 border border-blue-200 rounded p-8 text-center text-gray-600">
                📭 Tidak ada event aktif
            </div>
        @endif
    </div>

    <!-- TAB: EVENT DIHAPUS (TRASH) -->
    <div id="deleted" class="tab-panel hidden">
        @if(count($deletedEvents) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($deletedEvents as $event)
                    <div class="border rounded-lg overflow-hidden shadow-lg opacity-60 bg-gray-100">
                        <!-- Event Image (Dimmed) -->
                        <img src="/images/{{ $event->gambar }}" alt="{{ $event->judul }}" 
                             class="w-full h-40 object-cover opacity-50">
                        
                        <!-- Event Info -->
                        <div class="p-4">
                            <h3 class="font-bold text-lg mb-2 line-through text-gray-600">{{ $event->judul }}</h3>
                            
                            <div class="text-sm text-gray-600 mb-3">
                                <p>🗑️ Dihapus: {{ $event->deleted_at->format('d-m-Y H:i') }}</p>
                            </div>
                            
                            <!-- Restore Button -->
                            <form method="POST" action="{{ route('admin.events.restore', $event->id) }}">
                                @csrf
                                <button type="submit" class="w-full bg-green-500 text-white px-3 py-2 rounded hover:bg-green-600">
                                    ↩️ Pulihkan
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-blue-50 border border-blue-200 rounded p-8 text-center text-gray-600">
                ✅ Tidak ada event yang dihapus
            </div>
        @endif
    </div>
</div>

<script>
    function switchTab(tabName) {
        document.querySelectorAll('.tab-panel').forEach(el => el.classList.add('hidden'));
        document.getElementById(tabName).classList.remove('hidden');
        
        document.querySelectorAll('.tab-button').forEach(btn => {
            btn.classList.remove('border-blue-500', 'font-semibold');
            btn.classList.add('border-gray-300');
        });
        
        const btnId = tabName === 'active' ? 'activeBtn' : 'deletedBtn';
        document.getElementById(btnId).classList.add('border-blue-500', 'font-semibold');
        document.getElementById(btnId).classList.remove('border-gray-300');
    }
</script>
@endsection


<!-- ====================================================================== -->
<!-- EXAMPLE 3: HISTORIES/ORDERS VIEW (Table-based) -->
<!-- File: resources/views/histories/histories.blade.php -->
<!-- ====================================================================== -->

@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-4xl font-bold mb-6">Riwayat Pesanan</h1>
    
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            ✅ {{ session('success') }}
        </div>
    @endif

    <!-- TAB BUTTONS -->
    <div class="flex gap-2 mb-4 border-b">
        <button onclick="switchTab('active')" id="activeBtn" 
                class="tab-button px-4 py-2 border-b-2 border-blue-500 font-semibold">
            📋 Pesanan Aktif ({{ count($histories) }})
        </button>
        <button onclick="switchTab('deleted')" id="deletedBtn" 
                class="tab-button px-4 py-2 border-b-2 border-gray-300">
            🗑️ Pesanan Dihapus ({{ count($deletedHistories) }})
        </button>
    </div>

    <!-- TAB: PESANAN AKTIF -->
    <div id="active" class="tab-panel">
        @if(count($histories) > 0)
            <table class="w-full border-collapse border border-gray-300">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="border border-gray-300 px-4 py-2 text-left">ID</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Pembeli</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Event</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Total Harga</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Status</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Tanggal Pesan</th>
                        <th class="border border-gray-300 px-4 py-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($histories as $history)
                        <tr class="hover:bg-gray-50">
                            <td class="border border-gray-300 px-4 py-2">#{{ $history->id }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $history->user->nama }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $history->event->judul }}</td>
                            <td class="border border-gray-300 px-4 py-2 font-semibold">
                                Rp{{ number_format($history->total_harga, 0, ',', '.') }}
                            </td>
                            <td class="border border-gray-300 px-4 py-2">
                                @if($history->payment_status === 'paid')
                                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">
                                        ✅ Lunas
                                    </span>
                                @elseif($history->payment_status === 'pending')
                                    <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm">
                                        ⏳ Pending
                                    </span>
                                @else
                                    <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm">
                                        ❌ Gagal
                                    </span>
                                @endif
                            </td>
                            <td class="border border-gray-300 px-4 py-2 text-sm">
                                {{ $history->order_date->format('d-m-Y H:i') }}
                            </td>
                            <td class="border border-gray-300 px-4 py-2 text-center">
                                <a href="{{ route('admin.histories.show', $history->id) }}" 
                                   class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 mr-2">
                                    👁️ Lihat
                                </a>
                                
                                <form method="POST" action="{{ route('admin.histories.destroy', $history->id) }}" 
                                      style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600"
                                            onclick="return confirm('Yakin hapus pesanan ini? Stok tiket akan dipulihkan.')">
                                        🗑️ Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="bg-blue-50 border border-blue-200 rounded p-4 text-center text-gray-600">
                📭 Tidak ada pesanan aktif
            </div>
        @endif
    </div>

    <!-- TAB: PESANAN DIHAPUS (TRASH) -->
    <div id="deleted" class="tab-panel hidden">
        @if(count($deletedHistories) > 0)
            <table class="w-full border-collapse border border-gray-300">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="border border-gray-300 px-4 py-2 text-left">ID</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Pembeli</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Event</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Total Harga</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Dihapus Pada</th>
                        <th class="border border-gray-300 px-4 py-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($deletedHistories as $history)
                        <tr class="bg-gray-100 opacity-75">
                            <td class="border border-gray-300 px-4 py-2">#{{ $history->id }}</td>
                            <td class="border border-gray-300 px-4 py-2 line-through">{{ $history->user->nama }}</td>
                            <td class="border border-gray-300 px-4 py-2 line-through">{{ $history->event->judul }}</td>
                            <td class="border border-gray-300 px-4 py-2 line-through">
                                Rp{{ number_format($history->total_harga, 0, ',', '.') }}
                            </td>
                            <td class="border border-gray-300 px-4 py-2 text-sm text-gray-600">
                                {{ $history->deleted_at->format('d-m-Y H:i') }}
                            </td>
                            <td class="border border-gray-300 px-4 py-2 text-center">
                                <form method="POST" action="{{ route('admin.histories.restore', $history->id) }}" 
                                      style="display:inline;">
                                    @csrf
                                    <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                                        ↩️ Pulihkan
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="bg-blue-50 border border-blue-200 rounded p-4 text-center text-gray-600">
                ✅ Tidak ada pesanan yang dihapus
            </div>
        @endif
    </div>
</div>

<script>
    function switchTab(tabName) {
        document.querySelectorAll('.tab-panel').forEach(el => el.classList.add('hidden'));
        document.getElementById(tabName).classList.remove('hidden');
        
        document.querySelectorAll('.tab-button').forEach(btn => {
            btn.classList.remove('border-blue-500', 'font-semibold');
            btn.classList.add('border-gray-300');
        });
        
        const btnId = tabName === 'active' ? 'activeBtn' : 'deletedBtn';
        document.getElementById(btnId).classList.add('border-blue-500', 'font-semibold');
        document.getElementById(btnId).classList.remove('border-gray-300');
    }
</script>
@endsection


<!-- ====================================================================== -->
<!-- NOTES -->
<!-- ====================================================================== -->

<!--
CARA MENGGUNAKAN FILE INI:

1. KATEGORI VIEW:
   Copy bagian "EXAMPLE 1" ke resources/views/pages-admin/kategori.blade.php
   Sesuaikan form action dan route sesuai kebutuhan

2. EVENTS VIEW:
   Copy bagian "EXAMPLE 2" ke resources/views/events/events.blade.php
   Sesuaikan form action dan route

3. HISTORIES VIEW:
   Copy bagian "EXAMPLE 3" ke resources/views/histories/histories.blade.php
   Sesuaikan form action dan route

4. UNTUK TIKETS VIEW (eventshow.blade.php):
   Gunakan pattern dari EXAMPLE 1 atau 2
   Sesuaikan dengan struktur tikets data

KEY POINTS:
- Setiap contoh punya 2 tab: Aktif dan Dihapus
- Delete form POST ke destroy() method
- Restore form POST ke restore() method
- Gunakan @csrf untuk CSRF protection
- @method('DELETE') untuk HTTP method override

CUSTOMIZATION:
- Ubah warna, styling sesuai preference
- Ubah emoji/icon sesuai theme
- Tambah confirm dialog untuk delete
- Tambah loading state jika perlu
-->
