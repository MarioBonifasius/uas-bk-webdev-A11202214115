<x-layouts.admin title="History Pembelian">
    <div class="container mx-auto p-10">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-semibold">History Pembelian</h1>
        </div>

        @if(session('success'))
            <div class="alert alert-success mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="overflow-x-auto rounded-box bg-white p-5 shadow-xs">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pembeli</th>
                        <th>Event</th>
                        <th>Tanggal Pembelian</th>
                        <th>Total Harga</th>
                        <th>Status Pembayaran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($histories as $index => $history)
                    <tr>
                        <th>{{ $index + 1 }}</th>
                        <td>{{ $history->user->nama }}</td>
                        <td>{{ $history->event->judul }}</td>
                        <td>{{ $history->created_at->format('d M Y') }}</td>
                        <td>Rp {{ number_format($history->total_harga, 0, ',', '.') }}</td>
                        <td>
                            @if($history->payment_status === 'paid')
                                <span class="badge badge-success">Sudah Dibayar</span>
                            @elseif($history->payment_status === 'pending')
                                <span class="badge badge-warning">Menunggu Pembayaran</span>
                            @else
                                <span class="badge badge-error">Gagal</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex gap-2">
                                <a href="{{ route('admin.histories.show', $history->id) }}" class="btn btn-sm btn-info text-white">Detail</a>
                                <a href="{{ route('admin.histories.edit', $history->id) }}" class="btn btn-sm btn-warning text-white">Edit</a>
                                <form action="{{ route('admin.histories.destroy', $history->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-error text-white" onclick="return confirm('Yakin ingin menghapus pesanan ini?')">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada history pembelian tersedia.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.admin>