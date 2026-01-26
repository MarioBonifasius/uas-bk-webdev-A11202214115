<?php
/**
 * SOFT DELETE VIEW EXAMPLE GUIDE
 * 
 * File ini menunjukkan cara mengupdate Blade templates untuk support soft delete
 * dengan menampilkan tab untuk data aktif dan data dihapus
 */
?>

<!-- CONTOH 1: KATEGORI VIEW -->
<!-- File: resources/views/pages-admin/kategori.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold mb-4">Manajemen Kategori</h1>
    
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- TAB NAVIGATION -->
    <div class="flex border-b mb-4">
        <button class="tab-btn active px-4 py-2 border-b-2 border-blue-500" 
                onclick="showTab('active')">
            Kategori Aktif
        </button>
        <button class="tab-btn px-4 py-2 border-b-2 border-gray-300" 
                onclick="showTab('deleted')">
            Kategori Dihapus ({{ count($deletedCategories) }})
        </button>
    </div>

    <!-- TAB: KATEGORI AKTIF -->
    <div id="active" class="tab-content">
        <button class="bg-blue-500 text-white px-4 py-2 rounded mb-4">
            + Tambah Kategori
        </button>

        <table class="w-full border">
            <thead class="bg-gray-200">
                <tr>
                    <th class="border px-4 py-2">ID</th>
                    <th class="border px-4 py-2">Nama</th>
                    <th class="border px-4 py-2">Dibuat</th>
                    <th class="border px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td class="border px-4 py-2">{{ $category->id }}</td>
                        <td class="border px-4 py-2">{{ $category->nama }}</td>
                        <td class="border px-4 py-2">{{ $category->created_at->format('d-m-Y') }}</td>
                        <td class="border px-4 py-2">
                            <!-- EDIT -->
                            <button class="bg-yellow-500 text-white px-3 py-1 rounded">Edit</button>
                            
                            <!-- DELETE (Soft Delete) -->
                            <form method="DELETE" action="{{ route('admin.categories.destroy', $category->id) }}" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded"
                                        onclick="return confirm('Yakin ingin hapus?')">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="border px-4 py-2 text-center text-gray-500">
                            Tidak ada kategori
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- TAB: KATEGORI DIHAPUS (TRASH) -->
    <div id="deleted" class="tab-content hidden">
        @if(count($deletedCategories) > 0)
            <table class="w-full border">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="border px-4 py-2">ID</th>
                        <th class="border px-4 py-2">Nama</th>
                        <th class="border px-4 py-2">Dihapus Pada</th>
                        <th class="border px-4 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($deletedCategories as $category)
                        <tr class="bg-gray-100">
                            <td class="border px-4 py-2">{{ $category->id }}</td>
                            <td class="border px-4 py-2 line-through">{{ $category->nama }}</td>
                            <td class="border px-4 py-2">{{ $category->deleted_at->format('d-m-Y H:i') }}</td>
                            <td class="border px-4 py-2">
                                <!-- RESTORE -->
                                <form method="POST" 
                                      action="{{ route('admin.categories.restore', $category->id) }}" 
                                      style="display:inline;">
                                    @csrf
                                    <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded">
                                        Pulihkan
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="text-center text-gray-500 py-4">
                Tidak ada kategori yang dihapus
            </div>
        @endif
    </div>
</div>

<script>
    function showTab(tabName) {
        // Sembunyikan semua tab
        document.querySelectorAll('.tab-content').forEach(el => {
            el.classList.add('hidden');
        });
        
        // Tampilkan tab yang dipilih
        document.getElementById(tabName).classList.remove('hidden');
        
        // Update button style
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('border-blue-500');
            btn.classList.add('border-gray-300');
        });
        event.target.classList.remove('border-gray-300');
        event.target.classList.add('border-blue-500');
    }
</script>
@endsection


<!-- ========================================================================== -->

<!-- CONTOH 2: EVENTS VIEW (Lebih kompleks karena ada relasi) -->
<!-- File: resources/views/events/events.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold mb-4">Manajemen Event</h1>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- TAB -->
    <div class="flex border-b mb-4">
        <button class="tab-btn active px-4 py-2 border-b-2 border-blue-500" 
                onclick="showTab('active', event)">
            Event Aktif ({{ count($events) }})
        </button>
        <button class="tab-btn px-4 py-2 border-b-2 border-gray-300" 
                onclick="showTab('deleted', event)">
            Event Dihapus ({{ count($deletedEvents) }})
        </button>
    </div>

    <!-- ACTIVE EVENTS TAB -->
    <div id="active" class="tab-content">
        <a href="{{ route('admin.events.create') }}" 
           class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">
            + Tambah Event
        </a>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @forelse($events as $event)
                <div class="border rounded-lg p-4 shadow-md">
                    <img src="/images/{{ $event->gambar }}" alt="{{ $event->judul }}" 
                         class="w-full h-48 object-cover rounded mb-2">
                    
                    <h3 class="text-lg font-bold">{{ $event->judul }}</h3>
                    <p class="text-sm text-gray-600">
                        Kategori: <strong>{{ $event->kategori->nama }}</strong>
                    </p>
                    <p class="text-sm text-gray-600">
                        Tanggal: <strong>{{ $event->tanggal->format('d-m-Y') }}</strong>
                    </p>
                    <p class="text-sm text-gray-600 mb-3">
                        Lokasi: <strong>{{ $event->lokasi }}</strong>
                    </p>

                    <div class="flex gap-2">
                        <a href="{{ route('admin.events.edit', $event->id) }}" 
                           class="bg-yellow-500 text-white px-3 py-1 rounded text-sm flex-1 text-center">
                            Edit
                        </a>
                        
                        <form method="DELETE" 
                              action="{{ route('admin.events.destroy', $event->id) }}" 
                              style="display:inline; flex:1;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-sm w-full"
                                    onclick="return confirm('Yakin ingin hapus event ini?')">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center text-gray-500 py-4">
                    Tidak ada event aktif
                </div>
            @endforelse
        </div>
    </div>

    <!-- DELETED EVENTS TAB (TRASH) -->
    <div id="deleted" class="tab-content hidden">
        @if(count($deletedEvents) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($deletedEvents as $event)
                    <div class="border rounded-lg p-4 shadow-md bg-gray-100 opacity-75">
                        <img src="/images/{{ $event->gambar }}" alt="{{ $event->judul }}" 
                             class="w-full h-48 object-cover rounded mb-2 opacity-50">
                        
                        <h3 class="text-lg font-bold line-through">{{ $event->judul }}</h3>
                        <p class="text-sm text-gray-600">
                            Dihapus: <strong>{{ $event->deleted_at->format('d-m-Y H:i') }}</strong>
                        </p>

                        <div class="flex gap-2 mt-3">
                            <form method="POST" 
                                  action="{{ route('admin.events.restore', $event->id) }}" 
                                  style="display:inline; flex:1;">
                                @csrf
                                <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded text-sm w-full">
                                    Pulihkan
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center text-gray-500 py-4">
                Tidak ada event yang dihapus
            </div>
        @endif
    </div>
</div>

<script>
    function showTab(tabName, event) {
        // Prevent default link behavior
        if (event) event.preventDefault();
        
        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(el => {
            el.classList.add('hidden');
        });
        
        // Show selected tab
        document.getElementById(tabName).classList.remove('hidden');
        
        // Update button styles
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('border-blue-500');
            btn.classList.add('border-gray-300');
        });
        if (event && event.target) {
            event.target.classList.remove('border-gray-300');
            event.target.classList.add('border-blue-500');
        }
    }
</script>
@endsection


<!-- ========================================================================== -->

<!-- PENJELASAN TAB FUNCTIONALITY -->
<!--
FITUR TAB DI ATAS MENJELASKAN:

1. TERDAPAT 2 TAB:
   - Tab "Aktif": Menampilkan data yang belum dihapus (deleted_at IS NULL)
   - Tab "Dihapus": Menampilkan data yang sudah soft-delete (deleted_at IS NOT NULL)

2. BUTTON DELETE:
   - Ketika diklik, kirim DELETE request ke controller destroy()
   - Controller soft-delete: UPDATE tabel SET deleted_at = NOW()
   - Data masih ada di DB, tapi tidak muncul di tab Aktif

3. BUTTON PULIHKAN:
   - Ketika diklik, kirim POST request ke controller restore()
   - Controller restore: UPDATE tabel SET deleted_at = NULL
   - Data kembali ke tab Aktif

4. STYLING DELETED DATA:
   - Warna abu-abu (bg-gray-100)
   - Text bergarisbawah (line-through)
   - Opacity reduced untuk visual indication

5. COUNTER PADA TAB:
   - ({{ count($events) }}) → jumlah data aktif
   - ({{ count($deletedEvents) }}) → jumlah data dihapus

CATATAN:
- Ubah nama tabel, field, dan route sesuai dengan view masing-masing
- Pastikan controller sudah mengirim variabel $deletedXXX ke view
- Validate form action dengan route yang benar
-->
