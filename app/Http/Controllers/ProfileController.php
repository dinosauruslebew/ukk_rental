<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('frontend.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'nullable|min:6',
            'photo' => 'nullable|image|max:2048'
        ]);

        // update name & email
        $user->name = $request->name;
        $user->email = $request->email;

        // update password jika diisi
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        // update foto
        if ($request->hasFile('photo')) {

            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            $path = $request->file('photo')->store('profile_photos', 'public');
            $user->profile_photo = $path;
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
    
    // ========================================================
    // == METODE BARU UNTUK PENGISIAN DATA RENTAL/VERIFIKASI ==
    // ========================================================

    /**
     * Menampilkan form pengisian data diri rental.
     */
    public function editRentalData()
    {
        return redirect()->route('profile.edit', ['tab' => 'rental'])->with('activeTab', 'rental');
    }

    /**
     * Memproses pembaruan data diri rental (Dipanggil oleh route POST profile.rental.update).
     */
    public function updateRentalData(Request $request)
    {
        $user = Auth::user();

        // 1. Validasi input (NIK dan KTP Dihapus)
        $rules = [
            'phone_number' => ['required', 'string', 'max:20', 'min:8'], 
            'address' => ['required', 'string', 'max:255'],
            
            // Opsional
            'birth_place' => ['nullable', 'string', 'max:100'],
            'birth_date' => ['nullable', 'date'],
            'gender' => ['nullable', 'in:Laki-laki,Perempuan'],
        ];

        $request->validate($rules, [
            'phone_number.required' => 'Nomor Telepon wajib diisi.',
            'address.required' => 'Alamat lengkap wajib diisi untuk keperluan rental.',
        ]);
        
        // 2. Update data user (Hapus ktp_photo dan nik_ktp)
        $user->phone_number = $request->phone_number;
        $user->address = $request->address;
        $user->birth_place = $request->birth_place;
        $user->birth_date = $request->birth_date;
        $user->gender = $request->gender;
        
        // Catatan: Jika kolom nik_ktp dan ktp_photo tetap di database, 
        // kita bisa reset nilainya menjadi null/kosong di sini jika diperlukan
        // $user->nik_ktp = null; 
        // $user->ktp_photo = null; 

        $user->save(); // Simpan semua perubahan

        // 3. Redirect kembali ke halaman profile (Data Rental tab aktif)
        $message = 'Data diri rental berhasil disimpan! Silakan lanjutkan proses rental Anda.';
        
        // Jika form ini dipanggil setelah checkout yang tertunda, redirect ke checkout
        if ($request->session()->has('pending_paket_id')) {
            return redirect()->route('order.create')->with('success', $message);
        }
        
        // Redirect ke halaman edit profil dan aktifkan tab 'rental'
        return redirect()->route('profile.edit', ['tab' => 'rental'])->with('success', $message)->with('activeTab', 'rental');
    }
}
