<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Kategori;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $categories = Kategori::all();

        return view('pages-admin.dashboard', compact('categories'));
    }
    public function home()
    {
        $totalEvents = Event::count();
        $totalCategories = Kategori::count();
        $totalOrders = Order::count();
        return view('pages-admin.dashboard', compact('totalEvents', 'totalCategories', 'totalOrders'));
    }
}
