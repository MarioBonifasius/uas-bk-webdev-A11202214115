<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    //
    public function index()
    {
        $histories = Order::with(['user', 'event'])->latest()->get();
        return view('histories.histories', compact('histories'));
    }

    public function show(string $history)
    {
        $order = Order::findOrFail($history);
        return view('histories.show_histories', compact('order'));
    }
}
