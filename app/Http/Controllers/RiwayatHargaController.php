<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\RiwayatHarga;
use Illuminate\Http\Request;

class RiwayatHargaController extends Controller
{
    public function index(Request $request)
    {
        $query = RiwayatHarga::with(['barang', 'barangMasukDetail.barangMasuk'])
            ->latest('tanggal_perubahan');

        if ($request->barang_id) {
            $query->where('barang_id', $request->barang_id);
        }

        $riwayat = $query->paginate(20);
        $barang  = Barang::orderBy('nama_barang')->get();

        return view('riwayat_harga.index', compact('riwayat', 'barang'));
    }
}