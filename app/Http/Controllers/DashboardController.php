<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Kategori;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $categories = Kategori::all();

        $eventsQuery = Event::withMin('tikets', 'harga')
            ->orderBy('tanggal', 'asc');

        if ($request->has('kategori') && $request->kategori) {
            $eventsQuery->where('kategori_id', $request->kategori);
        }

        $events = $eventsQuery->get();

        return view('home', compact('events', 'categories'));
    }
}
