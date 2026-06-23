<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use App\Models\PengajuanPo;
use App\Models\RiwayatHarga;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function barangKeluar(Request $request)
    {
        $query = BarangKeluar::with(['permintaanBarang', 'detail.barang'])
            ->latest('tanggal_keluar');

        if ($request->dari) {
            $query->whereDate('tanggal_keluar', '>=', $request->dari);
        }
        if ($request->sampai) {
            $query->whereDate('tanggal_keluar', '<=', $request->sampai);
        }
        if ($request->divisi) {
            $query->where('divisi_tujuan', 'like', '%' . $request->divisi . '%');
        }

        $data = $query->paginate(20)->withQueryString();

        return view('laporan.barang_keluar', compact('data'));
    }

    public function barangMasuk(Request $request)
    {
        $query = BarangMasuk::with(['detail.barang', 'user', 'pengajuanPo'])
            ->latest('tanggal_masuk');

        if ($request->dari) {
            $query->whereDate('tanggal_masuk', '>=', $request->dari);
        }
        if ($request->sampai) {
            $query->whereDate('tanggal_masuk', '<=', $request->sampai);
        }

        $data = $query->paginate(20)->withQueryString();

        return view('laporan.barang_masuk', compact('data'));
    }

    public function stokBarang(Request $request)
    {
        $query = Barang::with('kategori')->orderBy('nama_barang');

        if ($request->kritis) {
            $query->whereColumn('stok', '<', 'stok_minimum');
        }
        if ($request->search) {
            $query->where('nama_barang', 'like', '%' . $request->search . '%');
        }

        $data = $query->paginate(20)->withQueryString();

        return view('laporan.stok_barang', compact('data'));
    }

    public function pengajuanPo(Request $request)
    {
        $query = PengajuanPo::with(['diajukan', 'approver', 'detail'])
            ->latest('tanggal_po');

        if ($request->status) {
            $query->where('status_po', $request->status);
        }
        if ($request->dari) {
            $query->whereDate('tanggal_po', '>=', $request->dari);
        }
        if ($request->sampai) {
            $query->whereDate('tanggal_po', '<=', $request->sampai);
        }

        $data = $query->paginate(20)->withQueryString();

        return view('laporan.pengajuan_po', compact('data'));
    }

    public function riwayatHarga(Request $request)
    {
        $query = RiwayatHarga::with(['barang', 'barangMasukDetail'])
            ->latest('tanggal_perubahan');

        if ($request->barang_id) {
            $query->where('barang_id', $request->barang_id);
        }

        $riwayatHarga   = $query->paginate(20)->withQueryString();
        $barang = Barang::orderBy('nama_barang')->get();

        return view('laporan.riwayat_harga', compact('riwayatHarga', 'barang'));
    }
}