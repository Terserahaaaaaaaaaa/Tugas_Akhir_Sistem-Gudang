<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\BarangKeluarDetail;
use App\Models\PermintaanBarang;
use App\Models\PermintaanBarangDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BarangKeluarController extends Controller
{
    public function index(Request $request)
    {
        $query = BarangKeluar::with(['detail.barang'])->latest();

        if ($request->tanggal) {
            $query->whereDate('tanggal_keluar', $request->tanggal);
        }
        if ($request->divisi) {
            $query->where('divisi_tujuan', 'like', '%' . $request->divisi . '%');
        }

        $barangKeluar = $query->paginate(15);

        return view('barang_keluar.index', compact('barangKeluar'));
    }

    public function create()
    {
        $barang = Barang::with('kategori')->orderBy('nama_barang')->get();

        return view('barang_keluar.create', compact('barang'));
    }

    /**
     * Alur utama:
     * 1. Logistik input barang keluar beserta qty yang diminta divisi.
     * 2. Sistem cek stok tiap item:
     *    - qty_keluar = min(qty_diminta, stok_tersedia)
     *    - qty_kurang = qty_diminta - qty_keluar
     *    - Stok dikurangi sebesar qty_keluar.
     * 3. Jika ada item yang qty_kurang > 0 → otomatis buat
     *    satu PermintaanBarang beserta detailnya untuk admin.
     */
    public function store(Request $request)
    {
        $request->validate([
            'divisi_tujuan'  => 'required|string|max:100',
            'tanggal_keluar' => 'required|date',
            'keterangan'     => 'nullable|string|max:255',
            'barang_id'      => 'required|array|min:1',
            'barang_id.*'    => 'required|exists:barang,id',
            'qty'            => 'required|array',
            'qty.*'          => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request) {
            // 1. Buat header barang keluar
            $keluar = BarangKeluar::create([
                'tanggal_keluar'       => $request->tanggal_keluar,
                'divisi_tujuan'        => $request->divisi_tujuan,
                //'permintaan_barang_id' => null, // diisi nanti jika ada kekurangan
                'keterangan'           => $request->keterangan,
            ]);

            $itemKurang = [];

            foreach ($request->barang_id as $i => $barangId) {
                $barang     = Barang::find($barangId);
                $qtyDiminta = (int) $request->qty[$i];
                $qtyKeluar  = min($qtyDiminta, $barang->stok);
                $qtyKurang  = $qtyDiminta - $qtyKeluar;

                BarangKeluarDetail::create([
                    'barang_keluar_id' => $keluar->id,
                    'barang_id'        => $barangId,
                    'qty'              => $qtyDiminta,
                    'qty_keluar'       => $qtyKeluar,
                    'qty_kurang'       => $qtyKurang,
                ]);

                // Kurangi stok sebesar yang benar-benar bisa dikeluarkan
                if ($qtyKeluar > 0) {
                    $barang->decrement('stok', $qtyKeluar);
                }

                if ($qtyKurang > 0) {
                    $itemKurang[] = [
                        'barang'     => $barang,
                        'qty_kurang' => $qtyKurang,
                    ];
                }
            }

            // 2. Kalau ada item yang kurang → buat satu permintaan barang ke admin
            if (!empty($itemKurang)) {
                $permintaan = PermintaanBarang::create([
                    'tanggal_permintaan' => $request->tanggal_keluar,
                    'divisi'             => $request->divisi_tujuan,
                    'keterangan'         => 'Otomatis dari barang keluar — stok tidak mencukupi',
                    'status_permintaan'  => 'baru',
                    'barang_keluar_id'   => $keluar->id,
                ]);

                foreach ($itemKurang as $item) {
                    PermintaanBarangDetail::create([
                        'permintaan_barang_id' => $permintaan->id,
                        'barang_id'            => $item['barang']->id,
                        'qty'                  => $item['qty_kurang'],
                        'status_item'          => 'tidak_tersedia',
                        'keterangan'           => 'Kekurangan dari barang keluar #' . $keluar->id,
                    ]);
                }

                // Simpan referensi permintaan ke barang keluar
                //$keluar->update(['permintaan_barang_id' => $permintaan->id]);
            }
        });

        return redirect()
            ->route('barang-keluar.index')
            ->with('success', 'Barang keluar berhasil dicatat. Kekurangan stok otomatis dikirim ke admin.');
    }

    public function show(BarangKeluar $barangKeluar)
    {
        $barangKeluar->load([
            'detail.barang',
            'permintaanBarang.detail.barang',
        ]);

        return view('barang_keluar.show', compact('barangKeluar'));
    }

    public function destroy(BarangKeluar $barangKeluar)
    {
        //hapus hanya bisa dilakukan oleh logistik
        if(Auth::user()->role != 'logistik'){
            abort(403);
        }

        $barangKeluar->delete();

        return redirect()->route('barang-keluar.index')
            ->with('success', 'Data barang keluar berhasil dihapus.');
    }
}