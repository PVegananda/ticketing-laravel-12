<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Menampilkan daftar semua user (Hanya Superadmin)
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
        }

        $users = $query->orderBy('role')->latest()->paginate(15);

        return view('pages.admin.users.index', compact('users'));
    }

    /**
     * Memperbarui Role pengguna
     */
    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:user,admin,superadmin'
        ]);

        $user = User::findOrFail($id);

        // Jangan biarkan superadmin mengubah role dirinya sendiri menjadi yang lain agar tidak kehilangan akses
        if ($user->id === auth()->id() && $request->role !== 'superadmin') {
            return redirect()->back()->with('error', 'Anda tidak dapat mengubah role Anda sendiri.');
        }

        $user->update([
            'role' => $request->role
        ]);

        return redirect()->back()->with('success', "Role {$user->name} berhasil diperbarui menjadi {$request->role}.");
    }
}
