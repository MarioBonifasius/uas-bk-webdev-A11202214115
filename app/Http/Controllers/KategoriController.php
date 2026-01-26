<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $categories = Kategori::whereNull('deleted_at')->get();
        $deletedCategories = Kategori::onlyTrashed()->get();
        return view('pages-admin.kategori', compact('categories', 'deletedCategories'));
    }

    public function store(Request $request)
    {
        $payload = $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        if (!isset($payload['nama'])) {
            return redirect()->route('categories.index')->with('error', 'Nama kategori wajib diisi.');
        }

        Kategori::create([
            'nama' => $payload['nama'],
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, string $id)
    {
        $payload = $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        if (!isset($payload['nama'])) {
            return redirect()->route('categories.index')->with('error', 'Nama kategori wajib diisi.');
        }

        $category = Kategori::findOrFail($id);
        $category->nama = $payload['nama'];
        $category->save();

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $category = Kategori::findOrFail($id);
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus.');
    }

    public function restore(string $id)
    {
        $category = Kategori::onlyTrashed()->findOrFail($id);
        $category->restore();
        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dipulihkan.');
    }
}
