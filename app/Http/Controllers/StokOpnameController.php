<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\StokOpname;
use App\Models\StokOpnameDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StokOpnameController extends Controller
{
    public function index()
    {
        $opname = StokOpname::with(['user', 'detail'])
            ->latest()
            ->paginate(15);

        return view('stok_opname.index', compact('opname'));
    }

    public function create()
    {
        $barang = Barang::with('kategori')->orderBy('nama_barang')->get();

        return view('stok_opname.create', compact('barang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_opname' => 'required|date',
            'keterangan'     => 'nullable|string|max:255',
            'barang_id'      => 'required|array|min:1',
            'barang_id.*'    => 'required|exists:barang,id',
            'stok_fisik'     => 'required|array',
            'stok_fisik.*'   => 'required|integer|min:0',
            'keterangan_item'=> 'nullable|array',
            'keterangan_item.*' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request) {
            $opname = StokOpname::create([
                'user_id'        => Auth::id(),
                'tanggal_opname' => $request->tanggal_opname,
                'keterangan'     => $request->keterangan,
            ]);

            foreach ($request->barang_id as $i => $barangId) {
                $barang     = Barang::find($barangId);
                $stokSistem = $barang->stok;
                $stokFisik  = (int) $request->stok_fisik[$i];
                $selisih    = $stokFisik - $stokSistem;

                StokOpnameDetail::create([
                    'stok_opname_id' => $opname->id,
                    'barang_id'      => $barangId,
                    'stok_sistem'    => $stokSistem,
                    'stok_fisik'     => $stokFisik,
                    'selisih'        => $selisih,
                    'keterangan'     => $request->keterangan_item[$i] ?? null,
                ]);
            }
        });

        return redirect()
            ->route('stok-opname.index')
            ->with('success', 'Stok opname berhasil disimpan.');
    }

    public function show(StokOpname $stokOpname)
    {
        $stokOpname->load(['user', 'detail.barang']);

        return view('stok_opname.show', compact('stokOpname'));
    }
}