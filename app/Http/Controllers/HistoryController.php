<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    /**
     * Display a listing of orders (history pembelian)
     */
    public function index()
    {
        $histories = Order::whereNull('deleted_at')->with(['user', 'event'])->latest()->get();
        $deletedHistories = Order::onlyTrashed()->with(['user', 'event'])->latest()->get();
        return view('histories.histories', compact('histories', 'deletedHistories'));
    }

    /**
     * Show the form for creating a new order
     */
    public function create()
    {
        // Note: In real scenario, this would be for admin to create orders
        // For now, orders are created by users during checkout
        return abort(403);
    }

    /**
     * Store a newly created order
     */
    public function store(Request $request)
    {
        return abort(403);
    }

    /**
     * Display the specified order
     */
    public function show(string $history)
    {
        $order = Order::with(['detailOrders.tiket', 'user', 'event'])->findOrFail($history);
        return view('histories.show_histories', compact('order'));
    }

    /**
     * Show the form for editing the order status
     */
    public function edit(string $history)
    {
        $order = Order::findOrFail($history);
        return view('histories.edit_histories', compact('order'));
    }

    /**
     * Update the specified order (payment status, etc)
     */
    public function update(Request $request, string $history)
    {
        $order = Order::findOrFail($history);

        $validated = $request->validate([
            'payment_status' => 'required|in:pending,paid,failed',
            'payment_method' => 'nullable|string|max:50',
        ]);

        $order->update($validated);

        return redirect()->route('admin.histories.show', $order->id)
            ->with('success', 'Status pembayaran berhasil diperbarui.');
    }

    /**
     * Remove the specified order
     */
    public function destroy(string $history)
    {
        $order = Order::findOrFail($history);
        
        try {
            // Restore ticket stock before deleting
            foreach ($order->detailOrders as $detail) {
                $detail->tiket->increment('stok', $detail->jumlah);
            }

            // Soft delete order (detail orders akan terhapus juga via cascade)
            $order->delete();

            return redirect()->route('admin.histories.index')
                ->with('success', 'Pesanan berhasil dihapus dan stok tiket dipulihkan.');
        } catch (\Exception $e) {
            return redirect()->route('admin.histories.index')
                ->with('error', 'Gagal menghapus pesanan: ' . $e->getMessage());
        }
    }

    /**
     * Restore the specified order
     */
    public function restore(string $history)
    {
        $order = Order::onlyTrashed()->findOrFail($history);

        try {
            $order->restore();

            return redirect()->route('admin.histories.index')
                ->with('success', 'Pesanan berhasil dipulihkan.');
        } catch (\Exception $e) {
            return redirect()->route('admin.histories.index')
                ->with('error', 'Gagal memulihkan pesanan: ' . $e->getMessage());
        }
    }
}
