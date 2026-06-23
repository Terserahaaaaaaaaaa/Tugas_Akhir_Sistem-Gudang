<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\KategoriBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BarangController extends Controller
{
   public function index(Request $request)
    {
        $search = $request->search;

        $query = Barang::with('kategori');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('kode_barang', 'like', "%{$search}%")
                ->orWhere('nama_barang', 'like', "%{$search}%");
            });
        }

        $barang = $query->latest()->paginate(15);

        return view('barang.index', compact('barang'));
    }

    public function create()
    {
        //create hanya bisa dilakukan admin
        // if(Auth::user()->role != 'admin'){
        //     abort(403);
        // }
        
        $lastBarang = Barang::latest()->first();

        if (!$lastBarang) {

            $kodeBarang = 'BRG0001';

        } else {

            $lastNumber = (int) substr($lastBarang->kode_barang, 3);

            $kodeBarang = 'BRG' .
                str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        }

        $kategori = KategoriBarang::all();

        return view(
            'barang.create',
            compact(
                'kodeBarang',
                'kategori'
            )
        );
    }

    public function store(Request $request)
    {
        //store hanya bisa dilakukan admin
        // if(Auth::user()->role != 'admin'){
        //     abort(403);
        // }

        $request->validate([
            'kode_barang' => 'required|unique:barang,kode_barang',
            'nama_barang' => 'required',
            'kategori_barang_id' => 'required|exists:kategori_barang,id',
            'harga_terakhir' => 'required|numeric|min:0',
            'satuan' => 'nullable',
            'stok' => 'required|integer|min:0',
            'stok_minimum' => 'required|integer|min:1',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $foto = null;

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto')->store('barang', 'public');
        }

        Barang::create([
            'kategori_barang_id' => $request->kategori_barang_id,
            'kode_barang' => $request->kode_barang,
            'nama_barang' => $request->nama_barang,
            'satuan' => $request->satuan,
            'stok' => $request->stok,
            'stok_minimum' => $request->stok_minimum,
            'harga_terakhir' => $request->harga_terakhir,
            'foto' => $foto,
        ]);

        return redirect()->route('barang.index')
            ->with('success', 'Data barang berhasil ditambahkan.');
    }

    public function show(Barang $barang)
    {
        $barang->load('kategori');

        return view('barang.show', compact('barang'));
    }

    public function edit(Barang $barang)
    {
        // if(Auth::user()->role != 'admin'){
        //     abort(403);
        // }

        $kategori = KategoriBarang::all();

        return view(
            'barang.edit',
            compact('barang', 'kategori')
        );
    }

    public function update(Request $request, Barang $barang)
    {
        // if(Auth::user()->role != 'admin'){
        //     abort(403);
        // }
        $request->validate([
            'kode_barang' => 'required|unique:barang,kode_barang,' . $barang->id,
            'nama_barang' => 'required',
            'kategori_barang_id' => 'required|exists:kategori_barang,id',
            'satuan' => 'nullable',
            'stok_minimum' => 'required|integer|min:1',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $foto = $barang->foto;

        // upload foto baru
        if ($request->hasFile('foto')) {

            // hapus foto lama
            if ($barang->foto && Storage::disk('public')->exists($barang->foto)) {
                Storage::disk('public')->delete($barang->foto);
            }

            // simpan foto baru
            $foto = $request->file('foto')->store('barang', 'public');
        }

        $barang->update([
            'kategori_barang_id' => $request->kategori_barang_id,
            'kode_barang' => $request->kode_barang,
            'nama_barang' => $request->nama_barang,
            'satuan' => $request->satuan,
            'stok_minimum' => $request->stok_minimum,
            'foto' => $foto,
        ]);

        return redirect()->route('barang.index')
            ->with('success', 'Data barang berhasil diperbarui.');
    }

    public function destroy(Barang $barang)
    {
        // if(Auth::user()->role != 'admin'){
        //     abort(403);
        // }

        try {

            if ($barang->foto &&
                Storage::disk('public')->exists($barang->foto)) {

                Storage::disk('public')->delete($barang->foto);
            }

            $barang->delete();

            return redirect()
                ->route('barang.index')
                ->with('success', 'Data barang berhasil dihapus.');

        } catch (\Exception $e) {

            return redirect()
                ->route('barang.index')
                ->with(
                    'error',
                    'Data barang tidak dapat dihapus karena sudah digunakan pada transaksi lain.'
                );
        }
    }

    public function stokBarang()
    {
        $barang = Barang::orderBy('nama_barang')->get();

        return view('stok_brg.index', compact('barang'));
    }
}