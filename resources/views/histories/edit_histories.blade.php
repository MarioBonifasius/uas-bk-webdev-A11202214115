<x-layouts.admin title="Edit Status Pembayaran">
    <section class="max-w-2xl mx-auto py-12 px-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Edit Status Pembayaran</h1>
            <div class="text-sm text-gray-500">Order #{{ $order->id }}</div>
        </div>

        <div class="card bg-base-100 shadow-lg">
            <div class="card-body">
                <div class="space-y-4 mb-6 pb-6 border-b">
                    <div class="flex justify-between">
                        <span class="font-semibold">Pembeli:</span>
                        <span>{{ $order->user->nama }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-semibold">Event:</span>
                        <span>{{ $order->event->judul }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-semibold">Total Pembelian:</span>
                        <span>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-semibold">Tanggal Pembelian:</span>
                        <span>{{ $order->order_date->format('d M Y H:i') }}</span>
                    </div>
                </div>

                <form action="{{ route('admin.histories.update', $order->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-control mb-6">
                        <label class="label">
                            <span class="label-text font-semibold">Status Pembayaran</span>
                        </label>
                        <select name="payment_status" class="select select-bordered @error('payment_status') select-error @enderror" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="pending" @selected($order->payment_status === 'pending')>Menunggu Pembayaran</option>
                            <option value="paid" @selected($order->payment_status === 'paid')>Sudah Dibayar</option>
                            <option value="failed" @selected($order->payment_status === 'failed')>Pembayaran Gagal</option>
                        </select>
                        @error('payment_status')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <div class="form-control mb-6">
                        <label class="label">
                            <span class="label-text font-semibold">Metode Pembayaran</span>
                        </label>
                        <input type="text" name="payment_method" value="{{ old('payment_method', $order->payment_method) }}" 
                               class="input input-bordered @error('payment_method') input-error @enderror"
                               placeholder="Contoh: dummy, transfer bank, e-wallet, dll">
                        @error('payment_method')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <div class="card-actions justify-between mt-8">
                        <a href="{{ route('admin.histories.show', $order->id) }}" class="btn btn-ghost">Batal</a>
                        <button type="submit" class="btn btn-primary text-white">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</x-layouts.admin>
