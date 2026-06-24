<?php

namespace App\Http\Controllers;

use App\Models\BarangMasuk;
use App\Models\BarangMasukDetail;
use App\Models\PengajuanPo;
use App\Models\PermintaanBarang;
use App\Models\RiwayatHarga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BarangMasukController extends Controller
{
    public function index(Request $request)
    {
        $query = BarangMasuk::with(['pengajuanPo', 'user', 'detail.barang'])->latest();

        if ($request->tanggal) {
            $query->whereDate('tanggal_masuk', $request->tanggal);
        }

        $barangMasuk = $query->paginate(15);

        return view('barang_masuk.index', compact('barangMasuk'));
    }

    /**
     * Tampilkan PO yang sudah ada item disetujui dan belum punya barang masuk.
     */
    public function create()
    {
        $po = PengajuanPo::with(['detail' => function ($q) {
                $q->where('status_item', 'disetujui')->with('barang');
            }])
            ->where('status_po', 'disetujui')
            ->whereDoesntHave('barangMasuk')
            ->get()
            ->filter(fn($p) => $p->detail->isNotEmpty());

        return view('barang_masuk.create', compact('po'));
    }

    /**
     * Simpan konfirmasi barang masuk.
     * Update stok barang & catat riwayat harga jika harga berubah.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pengajuan_po_id'           => 'required|exists:pengajuan_po,id',
            'tanggal_masuk'             => 'required|date',
            'keterangan'                => 'nullable|string|max:255',
            'pengajuan_po_detail_id'    => 'required|array|min:1',
            'pengajuan_po_detail_id.*'  => 'required|exists:pengajuan_po_detail,id',
            'barang_id'                 => 'required|array',
            'barang_id.*'               => 'required|exists:barang,id',
            'qty'                       => 'required|array',
            'qty.*'                     => 'required|integer|min:1',
            'harga_beli'                => 'required|array',
            'harga_beli.*'              => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $masuk = BarangMasuk::create([
                'pengajuan_po_id' => $request->pengajuan_po_id,
                'dilakukan_oleh'  => Auth::id(),
                'tanggal_masuk'   => $request->tanggal_masuk,
                'keterangan'      => $request->keterangan,
            ]);

            foreach ($request->pengajuan_po_detail_id as $i => $poDetailId) {
                $qty        = (int) $request->qty[$i];
                $hargaBeli  = (float) $request->harga_beli[$i];
                $barangId   = $request->barang_id[$i];

                $detail = BarangMasukDetail::create([
                    'barang_masuk_id'        => $masuk->id,
                    'pengajuan_po_detail_id' => $poDetailId,
                    'barang_id'              => $barangId,
                    'qty'                    => $qty,
                    'harga_beli'             => $hargaBeli,
                    'subtotal'               => $qty * $hargaBeli,
                ]);

                // Tambah stok
                $detail->barang->increment('stok', $qty);

                //kalo misal barang udah masuk, distatus item barang di pengajuan po akan berubah 
                //dari disetujui menjadi diterima
                $detail->pengajuanPoDetail()->update([
                    'status_item' => 'diterima'
                ]);
                
                // Catat riwayat harga jika berubah
                $hargaLama = (float) $detail->barang->harga_terakhir;
                if ($hargaLama !== $hargaBeli) {
                    RiwayatHarga::create([
                        'barang_id'              => $barangId,
                        'barang_masuk_detail_id' => $detail->id,
                        'harga_lama'             => $hargaLama,
                        'harga_baru'             => $hargaBeli,
                        'tanggal_perubahan'      => $request->tanggal_masuk,
                    ]);

                    $detail->barang->update(['harga_terakhir' => $hargaBeli]);
                }
            }

            // Tutup PO jika semua item disetujui sudah diterima
            $po             = PengajuanPo::find($request->pengajuan_po_id);
            $totalDisetujui = $po->detail()->where('status_item', 'disetujui')->count();
            $totalDiterima  = BarangMasukDetail::whereHas(
                'barangMasuk',
                fn($q) => $q->where('pengajuan_po_id', $po->id)
            )->count();

            if ($totalDiterima >= $totalDisetujui) {
                $po->update(['status_po' => 'disetujui']);
            }

            PermintaanBarang::whereHas('detail', function ($q) use ($po) {
                $q->whereIn(
                    'id',
                    $po->detail()
                        ->whereNotNull('permintaan_barang_detail_id')
                        ->pluck('permintaan_barang_detail_id')
                );
            })->update([
                'status_permintaan' => 'barang_tersedia'
            ]);
        });

        return redirect()
            ->route('barang-masuk.index')
            ->with('success', 'Barang masuk berhasil dikonfirmasi dan stok telah diperbarui.');
    }

    public function show(BarangMasuk $barangMasuk)
    {
        $barangMasuk->load([
            'pengajuanPo',
            'user',
            'detail.barang',
            'detail.riwayatHarga',
        ]);

        return view('barang_masuk.show', compact('barangMasuk'));
    }

    public function destroy(BarangMasuk $barangMasuk)
    {
        //hapus hanya bisa dilakukan oleh logistik
        // if(Auth::user()->role != 'logistik'){
        //     abort(403);
        // }

        // Kalau suatu saat Barang Masuk dihapus, stoknya tidak kembali berkurang. makanya pake code dibawah biar ngga gitu
        DB::transaction(function () use ($barangMasuk) {

            foreach ($barangMasuk->detail as $detail) {

                $barang = $detail->barang;

                $barang->stok -= $detail->qty;

                $barang->save();
            }

            $barangMasuk->delete();
        });

        return redirect()
            ->route('barang-masuk.index')
            ->with('success', 'Data barang masuk berhasil dihapus.');
    }
}