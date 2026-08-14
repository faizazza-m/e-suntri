<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        // Ambil data Santri
        $santris = \App\Models\Santri::with(['kelas', 'halaqoh'])->get();
        // Ambil data User (selain wali santri)
        $users = \App\Models\User::where('role_id', '!=', 3)->orderBy('role_id')->get();
        
        $semuaKelas = \App\Models\Kelas::orderBy('nama')->get();
        $semuaHalaqoh = \App\Models\Halaqoh::orderBy('nama')->get();

        return view('admin.pengguna.index', compact('santris', 'users', 'semuaKelas', 'semuaHalaqoh'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:150',
            'email'    => 'required|string|email|max:150|unique:users',
            'password' => 'required|string|min:6',
            'role_id'  => 'required|in:1,2,5,6', // 1: Admin, 2: Musyrif, 5: Ustadz, 6: Mudir
            'phone'    => 'nullable|string|max:20',
        ]);

        User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role_id'   => $request->role_id,
            'phone'     => $request->phone,
            'is_active' => 1,
        ]);

        return redirect()->route('pengguna')->with('success', 'Staff/Pengurus berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'     => 'required|string|max:150',
            'email'    => 'required|string|email|max:150|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6',
            'role_id'  => 'required|in:1,2,5,6',
            'phone'    => 'nullable|string|max:20',
        ]);

        $user->name    = $validated['name'];
        $user->email   = $validated['email'];
        $user->role_id = $validated['role_id'];
        $user->phone   = $validated['phone'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('pengguna')->with('success', 'Data staff berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Mencegah admin menghapus dirinya sendiri
        if ($user->id == auth()->id()) {
            return redirect()->route('pengguna')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
        }

        $user->delete();

        return redirect()->route('pengguna')->with('success', 'Data staff berhasil dihapus!');
    }
}
