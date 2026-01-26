<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Kategori;
use App\Models\Tiket;
use Illuminate\Http\Request;

class TiketController extends Controller
{
    public function index()
    {
        $events = Event::whereNull('deleted_at')->get();
        $categories = Kategori::whereNull('deleted_at')->get();
        $tickets = Tiket::whereNull('deleted_at')->get();
        $deletedTickets = Tiket::onlyTrashed()->get();
        return view('eventshow', compact('events', 'categories', 'tickets', 'deletedTickets'));
    }
    public function store(Request $request)
    {
        $validatedData = request()->validate([
            'event_id' => 'required|exists:events,id',
            'tipe' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
        ]);

        // Create the ticket
        Tiket::create($validatedData);

        return redirect()->route('admin.events.show', $validatedData['event_id'])->with('success', 'Ticket berhasil ditambahkan.');
    }

    public function update(Request $request, string $id)
    {
        $ticket = Tiket::findOrFail($id);

        $validatedData = $request->validate([
            'tipe' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
        ]);

        $ticket->update($validatedData);

        return redirect()->route('admin.events.show', $ticket->event_id)->with('success', 'Ticket berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $ticket = Tiket::findOrFail($id);
        $eventId = $ticket->event_id;
        $ticket->delete();

        return redirect()->route('admin.events.show', $eventId)->with('success', 'Tiket berhasil dihapus.');
    }

    public function restore(string $id)
    {
        $ticket = Tiket::onlyTrashed()->findOrFail($id);
        $eventId = $ticket->event_id;
        $ticket->restore();

        return redirect()->route('admin.events.show', $eventId)->with('success', 'Tiket berhasil dipulihkan.');
    }
}
