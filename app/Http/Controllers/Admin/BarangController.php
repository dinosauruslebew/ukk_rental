<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use Illuminate\Support\Facades\Storage;

class BarangController extends Controller
{
    /**
     * Menampilkan semua data barang
     */
    public function index()
    {
        $barang = Barang::orderBy('created_at', 'desc')->get();
        return view('admin.barang.index', compact('barang'));
    }

    /**
     * Menampilkan form tambah barang baru
     */
    public function create()
    {
        return view('admin.barang.create');
    }

    /**
     * Menyimpan barang baru ke database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_barang'    => 'required|string|max:255',
            'stok'           => 'required|integer|min:0',
            'harga_sewa'     => 'required|numeric|min:0',
            'harga_2_malam'  => 'required|numeric|min:0',
            'harga_3_malam'  => 'required|numeric|min:0',
            'deskripsi'      => 'nullable|string',
            'gambar'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status'         => 'required|in:tersedia,tidak tersedia',
        ]);

        $path = null;
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('barang', 'public');
        }

        Barang::create([
            'nama_barang'    => $validated['nama_barang'],
            'stok'           => $validated['stok'],
            'harga_sewa' => $validated['harga_sewa'],
            'harga_2_malam' => $validated['harga_2_malam'],
            'harga_3_malam' => $validated['harga_3_malam'],
            'deskripsi'      => $validated['deskripsi'] ?? null,
            'gambar'         => $path,
            'status'         => $validated['status'],
        ]);

        return redirect()->route('admin.barang.index')->with('success', 'Barang berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit barang
     */
    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        return view('admin.barang.edit', compact('barang'));
    }

    /**
     * Mengupdate data barang
     */
    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);

        $validated = $request->validate([
            'nama_barang'    => 'required|string|max:255',
            'stok'           => 'required|integer|min:0',
            'harga_sewa'     => 'required|numeric|min:0',
            'harga_2_malam'  => 'required|numeric|min:0',
            'harga_3_malam'  => 'required|numeric|min:0',
            'deskripsi'      => 'nullable|string',
            'gambar'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status'         => 'required|in:tersedia,tidak tersedia',
        ]);

        $gambarPath = $barang->gambar;

        if ($request->hasFile('gambar')) {
            if ($gambarPath && Storage::disk('public')->exists($gambarPath)) {
                Storage::disk('public')->delete($gambarPath);
            }
            $gambarPath = $request->file('gambar')->store('barang', 'public');
        }

        $barang->update([
            'nama_barang'    => $validated['nama_barang'],
            'stok'           => $validated['stok'],
            'harga_sewa' => $validated['harga_sewa'],
            'harga_2_malam' => $validated['harga_2_malam'],
            'harga_3_malam' => $validated['harga_3_malam'],
            'deskripsi'      => $validated['deskripsi'] ?? null,
            'gambar'         => $gambarPath,
            'status'         => $validated['status'],
        ]);

        return redirect()->route('admin.barang.index')->with('success', 'Data barang berhasil diperbarui!');
    }

    /**
     * Menghapus barang
     */
    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);

        if ($barang->gambar && Storage::disk('public')->exists($barang->gambar)) {
            Storage::disk('public')->delete($barang->gambar);
        }

        $barang->delete();

        return redirect()->route('admin.barang.index')->with('success', 'Barang berhasil dihapus!');
    }
}
