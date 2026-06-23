<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();

        return view('user.index', compact('users'));
    }

    public function create()
    {
        return view('user.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role' => 'required|in:pimpinan'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'status_akun' => 'disetujui',
        ]);

        return redirect()
            ->route('user.index')
            ->with('success', 'Akun berhasil dibuat.');
    }

    public function setujui(User $user)
    {
        $user->update([
            'status_akun' => 'disetujui'
        ]);

        return back()->with(
            'success',
            'Akun disetujui'
        );
    }

    public function tolak(User $user)
    {
        $user->update([
            'status_akun' => 'ditolak'
        ]);

        return back()->with(
            'success',
            'Akun ditolak'
        );
    }

    public function show(User $user)
    {
        return view('user.show', compact('user'));
    }

    public function destroy(User $user)
    {
        if ($user->role == 'admin') {

            return back()->with(
                'error',
                'Admin tidak dapat dihapus.'
            );
        }

        $user->delete();

        return back()->with(
            'success',
            'User berhasil dihapus.'
        );
    }
}