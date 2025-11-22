<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Paket;
use App\Models\Barang;
use Illuminate\Support\Facades\Storage;

class PaketController extends Controller
{
    public function index()
    {
        // PERBAIKAN DI SINI:
        // Menggunakan withCount('items') untuk menghitung jumlah item 
        // yang terkait dengan setiap paket. Laravel akan menambahkan kolom 'items_count'.
        $paket = Paket::withCount('items')->latest()->get(); 
        
        return view('admin.paket.index', compact('paket'));
    }

    public function create()
    {
        $barang = Barang::orderBy('nama_barang')->get();
        return view('admin.paket.create', compact('barang'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_paket' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga_paket' => 'nullable|numeric|min:0',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'barang_id' => 'required|array',
            'qty' => 'required|array'
        ]);

        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('paket', 'public');
        }

        $paket = Paket::create([
            'nama_paket' => $validated['nama_paket'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'harga_paket' => $validated['harga_paket'] ?? null,
            'gambar' => $gambarPath
        ]);

        foreach ($validated['barang_id'] as $barangId) {
            $qty = $validated['qty'][$barangId] ?? 1;

            if ($qty > 0) {
                // Relasi yang digunakan adalah items()
                $paket->items()->attach($barangId, ['qty' => $qty]);
            }
        }

        return redirect()->route('admin.paket.index')->with('success', 'Paket berhasil dibuat!');
    }

    public function edit($id)
    {
        $paket = Paket::findOrFail($id);
        $barang = Barang::all();
        return view('admin.paket.edit', compact('paket', 'barang'));
    }

    public function update(Request $request, $id)
    {
        $paket = Paket::findOrFail($id);

        $validated = $request->validate([
            'nama_paket' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga_paket' => 'nullable|numeric|min:0',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'barang_id' => 'required|array',
            'qty' => 'required|array'
        ]);

        $gambarPath = $paket->gambar;

        if ($request->hasFile('gambar')) {
            if ($gambarPath && Storage::disk('public')->exists($gambarPath)) {
                Storage::disk('public')->delete($gambarPath);
            }

            $gambarPath = $request->file('gambar')->store('paket', 'public');
        }

        $paket->update([
            'nama_paket' => $validated['nama_paket'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'harga_paket' => $validated['harga_paket'] ?? null,
            'gambar' => $gambarPath
        ]);

        $paket->items()->detach();

        foreach ($validated['barang_id'] as $barangId) {
            $qty = $validated['qty'][$barangId] ?? 1;

            if ($qty > 0) {
                $paket->items()->attach($barangId, ['qty' => $qty]);
            }
        }

        return redirect()->route('admin.paket.index')->with('success', 'Paket berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $paket = Paket::findOrFail($id);

        if ($paket->gambar && Storage::disk('public')->exists($paket->gambar)) {
            Storage::disk('public')->delete($paket->gambar);
        }

        $paket->delete();

        return redirect()->route('admin.paket.index')->with('success', 'Paket berhasil dihapus!');
    }
}