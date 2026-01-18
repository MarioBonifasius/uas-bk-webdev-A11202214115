<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Kategori;
use Illuminate\Http\Request;

class EventsController extends Controller
{
    public function index()
    {
        $events = Event::with('kategori')->get();
        return view('events.events', compact('events'));
    }

    public function create()
    {
        $categories = Kategori::all();
        return view('events.create_events', compact('categories'));
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal' => 'required|date',
            'lokasi' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Handle file upload
        if ($request->hasFile('gambar')) {
            $imageName = time() . '.' . $request->gambar->extension();
            $request->gambar->move(public_path('./images/'), $imageName);
            $validatedData['gambar'] = $imageName;
        }

        $validatedData['user_id'] = auth()->id();
        Event::create($validatedData);

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil ditambahkan.');
    }


    public function show(string $id)
    {
        $event = Event::findOrFail($id);
        $categories = Kategori::all();
        $tickets = $event->tikets;

        return view('events.eventshow', compact('event', 'categories', 'tickets'));
    }


    public function edit(string $id)
    {
        $event = Event::findOrFail($id);
        $categories = Kategori::all();
        return view('events.update_events', compact('event', 'categories'));
    }


    public function update(Request $request, string $id)
    {
        try {
            $event = Event::findOrFail($id);

            $validatedData = $request->validate([
                'judul' => 'required|string|max:255',
                'deskripsi' => 'required|string',
                'tanggal' => 'required|date',
                'lokasi' => 'required|string|max:255',
                'kategori_id' => 'required|exists:kategoris,id',
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            // Handle file upload
            if ($request->hasFile('gambar')) {
                $imageName = time() . '.' . $request->gambar->extension();
                $request->gambar->move(public_path('./images/'), $imageName);
                $validatedData['gambar'] = $imageName;
            }

            $event->update($validatedData);

            return redirect()->route('admin.events.index')->with('success', 'Event berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui event: ' . $e->getMessage()]);
        }
    }
    public function destroy(string $id)
    {
        Event::destroy($id);
        return redirect()->route('admin.events.index')->with('success', 'Kategori berhasil dihapus.');
    }

      public function lihat(Event $event)
    {
        $event->load(['tikets', 'kategori', 'user']);

        return view('events.show', compact('event'));
    }
}
