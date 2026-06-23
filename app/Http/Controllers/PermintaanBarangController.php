<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\BarangKeluarDetail;
use App\Models\PermintaanBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PermintaanBarangController extends Controller
{
    /**
     * Permintaan barang dibuat OTOMATIS oleh sistem dari BarangKeluarController
     * saat ada item yang qty_kurang > 0.
     * Controller ini hanya untuk admin melihat daftar dan detail permintaan masuk.
     */
    public function index(Request $request)
    {
        $query = PermintaanBarang::with(['detail.barang'])->latest();

        if ($request->status) {
            $query->where('status_permintaan', $request->status);
        }

        $permintaan = $query->paginate(15);

        return view('permintaan_barang.index', compact('permintaan'));
    }

    public function show(PermintaanBarang $permintaanBarang)
    {
        $permintaanBarang->load([
            'detail.barang',
        ]);

        return view('permintaan_barang.show', compact('permintaanBarang'));
    }

    public function destroy(PermintaanBarang $permintaanBarang)
    {
        //hapus hanya bisa dilakukan oleh logistik
        // if(Auth::user()->role != 'logistik'){
        //     abort(403);
        // }

        $permintaanBarang->delete();

        return redirect()->route('permintaan-barang.index')
            ->with('success', 'Data permintaan barang berhasil dihapus.');
    }

    public function penuhi(PermintaanBarang $permintaan)
{
    DB::transaction(function () use ($permintaan) {

        $barangKeluar = BarangKeluar::create([
            'tanggal_keluar' => now(),
            'divisi_tujuan'  => $permintaan->divisi,
            'keterangan'     => 'Pemenuhan permintaan barang #' . $permintaan->id,
        ]);

        foreach ($permintaan->detail as $detail) {

            $barang = Barang::findOrFail($detail->barang_id);

            if ($barang->stok < $detail->qty) {
                throw new \Exception(
                    'Stok '.$barang->nama_barang.' belum mencukupi.'
                );
            }

            BarangKeluarDetail::create([
                'barang_keluar_id' => $barangKeluar->id,
                'barang_id'        => $barang->id,
                'qty'              => $detail->qty,
                'qty_keluar'       => $detail->qty,
                'qty_kurang'       => 0,
            ]);

            $barang->decrement('stok', $detail->qty);
        }

        $permintaan->update([
            'status_permintaan' => 'terpenuhi'
        ]);
    });

    return redirect()
        ->route('permintaan-barang.index')
        ->with(
            'success',
            'Permintaan berhasil dipenuhi dan barang telah dikirim ke divisi.'
        );
}
}