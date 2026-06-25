<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\PengajuanPo;
use App\Models\PengajuanPoDetail;
use App\Models\PermintaanBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PengajuanPoController extends Controller
{
    public function index(Request $request)
    {
        $query = PengajuanPo::with(['diajukan', 'approver', 'detail'])->latest();

        if ($request->status) {
            $query->where('status_po', $request->status);
        }

        $po = $query->paginate(15);

        return view('pengajuan_po.index', compact('po'));
    }

    public function create(Request $request)
    {
        // create hanya bisa dilakukan admin
        // if (Auth::user()->role != 'admin') {
        //     abort(403);
        // }

        //difunction create ini bisa di buat po berdasarkan sumber po permintaan barang dan stok minimum
        $barang = Barang::orderBy('nama_barang')->get();

        $permintaan = null;
        $barangStokMinimum = null;

        if ($request->permintaan_id) {

            $permintaan = PermintaanBarang::with('detail.barang')
                ->findOrFail($request->permintaan_id);
        }

            //perintah kalo misal barang dinotifikasi stok menipis udah diklik dan dibuatin po maka
            //akan munculin pesan barang ini udah dibuatin po
        if ($request->barang_id) {

    $detailPo = PengajuanPoDetail::with('pengajuanPo')
        ->where('barang_id', $request->barang_id)
        ->whereHas('pengajuanPo', function ($q) {
            $q->whereIn('status_po', [
                'pending',
                'disetujui'
            ]);
        })
        ->first();

    if ($detailPo) {

        return redirect()
            ->route('home')
            ->with(
                'warning',
                'Barang sudah diajukan pada PO #' .
                $detailPo->pengajuan_po_id .
                ' dan masih dalam proses.'
            );
    }

    $barangStokMinimum = Barang::findOrFail(
        $request->barang_id
    );
}

        return view(
            'pengajuan_po.create',
            compact(
                'barang',
                'permintaan',
                'barangStokMinimum'
            )
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_po'                    => 'required|date',
            'sumber_po'                     => 'required|in:permintaan_barang,stok_minimum',
            'permintaan_barang_detail_id'   => 'nullable|array',
            'permintaan_barang_detail_id.*' => 'nullable|exists:permintaan_barang_detail,id',
            'kontak_pembelian'              => 'nullable|string|max:150',
            'metode_pembelian'              => 'required|in:whatsapp,online,beli_langsung',
            'barang_id'                     => 'required|array|min:1',
            'barang_id.*'                   => 'required|exists:barang,id',
            'qty'                           => 'required|array',
            'qty.*'                         => 'required|integer|min:1',
            'harga_estimasi'                => 'required|array',
            'harga_estimasi.*'              => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $po = PengajuanPo::create([
                'tanggal_po'       => $request->tanggal_po,
                'sumber_po'        => $request->sumber_po ?? 'stok_minimum',
                'kontak_pembelian' => $request->kontak_pembelian,
                'metode_pembelian' => $request->metode_pembelian,
                'status_po'        => 'pending',
                'diajukan_oleh'    => Auth::id(),
            ]);

            foreach ($request->barang_id as $i => $barangId) {
                $qty            = (int) $request->qty[$i];
                $hargaEstimasi  = (float) $request->harga_estimasi[$i];

                PengajuanPoDetail::create([
                    'pengajuan_po_id'               => $po->id,
                    'permintaan_barang_detail_id'   => $request->permintaan_barang_detail_id[$i] ?? null,
                    'barang_id'                     => $barangId,
                    'qty'                           => $qty,
                    'harga_estimasi'                => $hargaEstimasi,
                    'subtotal'                      => $qty * $hargaEstimasi,
                    'status_item'                   => 'menunggu',
                ]);
            }

            //ini untuk jika permintaan barang sudah dibuatin po maka status akan berubah menjadi diajukan po
            if ($request->permintaan_barang_id) {

                PermintaanBarang::where(
                    'id',
                    $request->permintaan_barang_id
                )->update([
                    'status_permintaan' => 'diajukan_po'
                ]);
            }
        });

        return redirect()
            ->route('pengajuan-po.index')
            ->with('success', 'PO berhasil diajukan ke keuangan.');
    }

    public function show(PengajuanPo $pengajuanPo)
    {
        $pengajuanPo->load([
            'diajukan',
            'approver',
            'detail.barang',
            'barangMasuk.detail.barang',
        ]);

        return view('pengajuan_po.show', compact('pengajuanPo'));
    }

    /**
     * Form approval — hanya untuk role keuangan.
     */
    public function approval(PengajuanPo $pengajuanPo)
    {
        // abort_if(Auth::user()->role !== 'keuangan', 403);
        abort_if($pengajuanPo->status_po !== 'pending', 403, 'PO ini sudah diproses.');

        $pengajuanPo->load('detail.barang');

        return view('pengajuan_po.approval', compact('pengajuanPo'));
    }

    /**
     * Simpan keputusan approval per item.
     * status_item: disetujui | ditolak
     */
    public function simpanApproval(Request $request, PengajuanPo $pengajuanPo)
    {
        // abort_if(Auth::user()->role !== 'keuangan', 403);

        $request->validate([
            'status_item'   => 'required|array',
            'status_item.*' => 'required|in:disetujui,ditolak',
        ]);

        DB::transaction(function () use ($request, $pengajuanPo) {
            foreach ($request->status_item as $detailId => $status) {
                PengajuanPoDetail::where('id', $detailId)
                    ->where('pengajuan_po_id', $pengajuanPo->id)
                    ->update(['status_item' => $status]);
            }

            $pengajuanPo->update([
                'disetujui_oleh' => Auth::id(),
                'status_po'      => 'disetujui',
            ]);
        });

        return redirect()
            ->route('pengajuan-po.show', $pengajuanPo->id)
            ->with('success', 'Keputusan approval berhasil disimpan.');
    }

    public function destroy(PengajuanPo $pengajuanPo)
    {
        //hapus hanya bisa dilakukan admin
        // if(Auth::user()->role != 'admin'){
        //     abort(403);
        // }

        $pengajuanPo->delete();

        return redirect()
            ->route('pengajuan-po.index')
            ->with('success', 'Pengajuan PO berhasil dihapus.');
    }
}