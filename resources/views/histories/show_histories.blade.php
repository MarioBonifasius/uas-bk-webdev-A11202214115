<x-layouts.admin title="Detail Pemesanan">
    <section class="max-w-4xl mx-auto py-12 px-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Detail Pemesanan</h1>
            <div class="text-sm text-gray-500">Order #{{ $order->id }} •
                {{ $order->order_date->format('d M Y H:i') }}
            </div>
        </div>

        <div class="card bg-base-100 shadow-md">
            <div class="lg:flex ">
                <div class="lg:w-1/3 p-4">
                    <img
                        src="{{ $order->event?->gambar
        ? asset('storage/' . $order->event->gambar)
        : 'https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp' }}"
                        alt="{{ $order->event?->judul ?? 'Event' }}"
                        class="w-full object-cover mb-2" />

                    <h2 class="font-semibold text-lg">{{ $order->event->judul ?? 'Event' }}</h2>
                    <p class="text-sm text-gray-500 mt-1">{{ $order->event?->lokasi ?? '' }}</p>
                </div>
                <div class="card-body lg:w-2/3">


                    <div class="space-y-3">
                        @foreach($order->detailOrders as $d)
                        <div class="flex justify-between items-center">
                            <div>
                                <div class="font-bold">{{ $d->tiket->tipe }}</div>
                                <div class="text-sm text-gray-500">Qty: {{ $d->jumlah }}</div>
                            </div>
                            <div class="text-right">
                                <div class="font-bold">Rp {{ number_format($d->subtotal_harga, 0, ',', '.') }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="divider"></div>

                    <div class="space-y-3 mb-4">
                        <div class="flex justify-between items-center">
                            <span class="font-bold">Total</span>
                            <span class="font-bold text-lg">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-bold">Status Pembayaran:</span>
                            <span>
                                @if($order->payment_status === 'paid')
                                    <span class="badge badge-success">Sudah Dibayar</span>
                                @elseif($order->payment_status === 'pending')
                                    <span class="badge badge-warning">Menunggu Pembayaran</span>
                                @else
                                    <span class="badge badge-error">Gagal</span>
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-bold">Metode Pembayaran:</span>
                            <span class="text-sm">{{ $order->payment_method ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="sm:ml-auto sm:mt-auto sm:mr-0 mx-auto mt-3 flex gap-2">
                        <a href="{{ route('admin.histories.index') }}" class="btn btn-ghost">Kembali ke Riwayat</a>
                        <a href="{{ route('admin.histories.edit', $order->id) }}" class="btn btn-warning text-white">Edit Status</a>
                        <form action="{{ route('admin.histories.destroy', $order->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-error text-white" onclick="return confirm('Yakin ingin menghapus pesanan ini? Stok tiket akan dipulihkan.')">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </section>
</x-layouts.admin>