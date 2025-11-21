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
}
