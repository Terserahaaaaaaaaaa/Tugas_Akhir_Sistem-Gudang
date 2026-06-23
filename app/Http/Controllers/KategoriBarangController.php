<?php

namespace App\Http\Controllers;

use App\Models\KategoriBarang;
use Illuminate\Http\Request;

class KategoriBarangController extends Controller
{
    public function index()
    {
        $kategori = KategoriBarang::all();

        return view('kategori_barang.index', compact('kategori'));
    }

    public function create()
    {
        return view('kategori_barang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_akun' => 'required|unique:kategori_barang',
            'nama_akun' => 'required'
        ]);

        KategoriBarang::create([
            'no_akun' => $request->no_akun,
            'nama_akun' => $request->nama_akun
        ]);

        return redirect()
            ->route('kategori-barang.index')
            ->with('success', 'Kategori berhasil ditambahkan');
    }

    public function edit($id)
    {
        $kategori = KategoriBarang::findOrFail($id);

        return view('kategori_barang.edit', compact('kategori'));
    }

    public function update(Request $request, $id)
    {
        $kategori = KategoriBarang::findOrFail($id);

        $request->validate([
            'no_akun' => 'required|unique:kategori_barang,no_akun,' . $id,
            'nama_akun' => 'required'
        ]);

        $kategori->update([
            'no_akun' => $request->no_akun,
            'nama_akun' => $request->nama_akun
        ]);

        return redirect()
            ->route('kategori-barang.index')
            ->with('success', 'Kategori berhasil diubah');
    }

    public function destroy($id)
    {
        KategoriBarang::findOrFail($id)->delete();

        return redirect()
            ->route('kategori-barang.index')
            ->with('success', 'Kategori berhasil dihapus');
    }
}