<x-layouts.app>
    <section class="max-w-2xl mx-auto py-12 px-6">
        <div class="card bg-base-100 shadow-lg">
            <div class="card-body">
                <h1 class="card-title text-2xl mb-6">Pembayaran Pesanan</h1>

                <div class="space-y-4 mb-6">
                    <div class="flex justify-between border-b pb-2">
                        <span>Nomor Pesanan:</span>
                        <span class="font-bold">#{{ $order->id }}</span>
                    </div>

                    <div class="flex justify-between border-b pb-2">
                        <span>Event:</span>
                        <span class="font-bold">{{ $order->event->judul }}</span>
                    </div>

                    <div class="flex justify-between border-b pb-2">
                        <span>Status Pembayaran:</span>
                        <span class="font-bold">
                            @if($order->payment_status === 'paid')
                                <span class="badge badge-success">Sudah Dibayar</span>
                            @elseif($order->payment_status === 'pending')
                                <span class="badge badge-warning">Menunggu Pembayaran</span>
                            @else
                                <span class="badge badge-error">Gagal</span>
                            @endif
                        </span>
                    </div>

                    <div class="mt-6 bg-blue-50 p-4 rounded-lg">
                        <h3 class="font-bold mb-3">Detail Tiket:</h3>
                        <div class="space-y-2">
                            @forelse($order->detailOrders as $detail)
                                <div class="flex justify-between">
                                    <span>{{ $detail->tiket->tipe }} x{{ $detail->jumlah }}</span>
                                    <span>Rp {{ number_format($detail->subtotal_harga, 0, ',', '.') }}</span>
                                </div>
                            @empty
                                <p class="text-gray-500">Tidak ada detail tiket</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="flex justify-between text-lg font-bold border-t pt-4 mt-4">
                        <span>Total Pembayaran:</span>
                        <span class="text-primary">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="card-actions justify-end">
                    <a href="{{ route('orders.index') }}" class="btn btn-ghost">Kembali</a>
                    @if($order->payment_status !== 'paid')
                        <button id="payButton" class="btn btn-primary text-white">Proses Pembayaran (Dummy)</button>
                    @else
                        <button class="btn btn-success text-white" disabled>✓ Pembayaran Selesai</button>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <script>
        document.getElementById('payButton')?.addEventListener('click', async () => {
            const btn = document.getElementById('payButton');
            btn.disabled = true;
            btn.textContent = 'Memproses...';

            try {
                const response = await fetch("{{ route('payments.process', $order) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                if (data.ok) {
                    alert('Pembayaran berhasil diproses!');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                    btn.disabled = false;
                    btn.textContent = 'Proses Pembayaran (Dummy)';
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan: ' + error.message);
                btn.disabled = false;
                btn.textContent = 'Proses Pembayaran (Dummy)';
            }
        });
    </script>
</x-layouts.app>
