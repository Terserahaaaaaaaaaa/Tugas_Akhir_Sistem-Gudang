@extends('layouts.app')
@section('title', 'Detail Barang Masuk')
@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6"><h1>Detail Barang Masuk</h1></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('barang-masuk.index') }}">Barang Masuk</a></li>
                        <li class="breadcrumb-item active">Detail</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header"><h3 class="card-title">Detail Barang Masuk</h3></div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-3 text-muted">Tanggal Masuk</div>
                        <div class="col-md-9">: <strong>{{ \Carbon\Carbon::parse($barangMasuk->tanggal_masuk)->translatedFormat('d F Y') }}</strong></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 text-muted">PO</div>
                        <div class="col-md-9">:
                            <a href="{{ route('pengajuan-po.show', $barangMasuk->pengajuan_po_id) }}">
                                PO #{{ $barangMasuk->pengajuan_po_id }}
                            </a>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 text-muted">Dikonfirmasi Oleh</div>
                        <div class="col-md-9">: <strong>{{ $barangMasuk->user->name ?? '-' }}</strong></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 text-muted">Keterangan</div>
                        <div class="col-md-9">: {{ $barangMasuk->keterangan ?? '-' }}</div>
                    </div>

                    <h5 class="mt-4">Detail Barang Diterima</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th>No</th><th>Barang</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-right">Harga Beli</th>
                                    <th class="text-right">Subtotal</th>
                                    <th>Perubahan Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($barangMasuk->detail as $i => $d)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $d->barang->nama_barang }}</td>
                                    <td class="text-center">{{ $d->qty }} {{ $d->barang->satuan }}</td>
                                    <td class="text-right">Rp{{ number_format($d->harga_beli, 0, ',', '.') }}</td>
                                    <td class="text-right">Rp{{ number_format($d->subtotal, 0, ',', '.') }}</td>
                                    <td>
                                        @if($d->riwayatHarga->isNotEmpty())
                                            @php $rh = $d->riwayatHarga->first(); @endphp
                                            @php $persen = $rh->harga_lama > 0 ? (($rh->harga_baru - $rh->harga_lama) / $rh->harga_lama * 100) : 0; @endphp
                                            @if($persen > 10)
                                                <span class="badge badge-danger">
                                                    <i class="fas fa-arrow-up"></i> Naik {{ number_format($persen, 1) }}%
                                                    (Rp{{ number_format($rh->harga_lama,0,',','.') }} → Rp{{ number_format($rh->harga_baru,0,',','.') }})
                                                </span>
                                            @elseif($persen > 0)
                                                <span class="badge badge-warning">
                                                    Naik {{ number_format($persen, 1) }}%
                                                </span>
                                            @else
                                                <span class="badge badge-secondary">Tidak berubah</span>
                                            @endif
                                        @else
                                            <span class="text-muted">–</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-right">Total</th>
                                    <th class="text-right">Rp{{ number_format($barangMasuk->detail->sum('subtotal'), 0, ',', '.') }}</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('barang-masuk.index') }}" class="btn btn-default">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection