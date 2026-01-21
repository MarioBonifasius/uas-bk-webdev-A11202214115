<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Process dummy payment for order
     */
    public function process(Request $request, Order $order)
    {
        // Verify the order belongs to the authenticated user
        if ($order->user_id !== auth()->id()) {
            return response()->json(['ok' => false, 'message' => 'Unauthorized'], 403);
        }

        // Check if order is already paid
        if ($order->payment_status === 'paid') {
            return response()->json(['ok' => false, 'message' => 'Pesanan sudah dibayar'], 400);
        }

        try {
            // Dummy payment processing - always succeeds
            $order->update([
                'payment_status' => 'paid',
                'payment_method' => 'dummy'
            ]);

            return response()->json([
                'ok' => true,
                'message' => 'Pembayaran berhasil diproses',
                'order_id' => $order->id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Gagal memproses pembayaran: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Show payment page
     */
    public function show(Order $order)
    {
        // Verify the order belongs to the authenticated user
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load('detailOrders.tiket', 'event');
        return view('payments.show', compact('order'));
    }
}
