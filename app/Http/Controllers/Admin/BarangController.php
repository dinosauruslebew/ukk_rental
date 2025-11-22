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
            // Pastikan ini nullable di validasi
            'harga_2_malam'  => 'nullable|numeric|min:0', 
            'harga_3_malam'  => 'nullable|numeric|min:0',
            'deskripsi'      => 'nullable|string',
            'gambar'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status'         => 'nullable|in:tersedia,tidak tersedia',
        ]);

        $path = null;
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('barang', 'public');
        }

        // --- Perbaikan Logic Harga Opsional ---
        // Jika input dikirim sebagai string kosong ('') dari form, set menjadi null
        $harga2Malam = empty($validated['harga_2_malam']) ? null : $validated['harga_2_malam'];
        $harga3Malam = empty($validated['harga_3_malam']) ? null : $validated['harga_3_malam'];

        Barang::create([
            'nama_barang'    => $validated['nama_barang'],
            'stok'           => $validated['stok'],
            'harga_sewa'     => $validated['harga_sewa'],
            'harga_2_malam'  => $harga2Malam, // Menggunakan nilai yang sudah diolah
            'harga_3_malam'  => $harga3Malam, // Menggunakan nilai yang sudah diolah
            'deskripsi'      => $validated['deskripsi'] ?? null,
            'gambar'         => $path,
            // Status akan otomatis di-set oleh Eloquent Model hook (static::saving)
            'status'         => $validated['status'] ?? null,
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
  public function update(Request $request, $id_barang)
{
    $barang = Barang::findOrFail($id_barang);

    $validated = $request->validate([
        'nama_barang'    => 'required|string|max:255',
        'stok'           => 'required|integer|min:0',
        'harga_sewa'     => 'required|numeric|min:0',
        'harga_2_malam'  => 'nullable|numeric|min:0',
        'harga_3_malam'  => 'nullable|numeric|min:0',
        'deskripsi'      => 'nullable|string',
        'gambar'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'status'         => 'required|in:tersedia,tidak tersedia',
    ]);

    // Gambar
    $gambarPath = $barang->gambar;

    if ($request->hasFile('gambar')) {
        if ($gambarPath && Storage::disk('public')->exists($gambarPath)) {
            Storage::disk('public')->delete($gambarPath);
        }
        $gambarPath = $request->file('gambar')->store('barang', 'public');
    }

    // Harga opsional → kosong = null
    $harga2 = $validated['harga_2_malam'] ?? null;
    $harga3 = $validated['harga_3_malam'] ?? null;

    $barang->update([
        'nama_barang'    => $validated['nama_barang'],
        'stok'           => $validated['stok'],
        'harga_sewa'     => $validated['harga_sewa'],
        'harga_2_malam'  => $harga2,
        'harga_3_malam'  => $harga3,
        'deskripsi'      => $validated['deskripsi'] ?? null,
        'gambar'         => $gambarPath,
        'status'         => $validated['status'],
    ]);

    return redirect()->route('admin.barang.index')
        ->with('success', 'Data barang berhasil diperbarui!');
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
