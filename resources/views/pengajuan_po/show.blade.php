@extends('layouts.app')
@section('title', 'Detail PO')
@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6"><h1>Detail Pengajuan PO</h1></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('pengajuan-po.index') }}">Pengajuan PO</a></li>
                        <li class="breadcrumb-item active">Detail</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    {{ session('success') }}
                </div>
            @endif
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail PO</h3>
                    <div class="card-tools">
                        @if($pengajuanPo->status_po === 'selesai')
                            <span class="badge badge-success">Selesai</span>
                        @elseif($pengajuanPo->status_po === 'diproses')
                            <span class="badge badge-info">Diproses</span>
                        @else
                            <span class="badge badge-warning">Menunggu Approval</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row mb-2">
                                <div class="col-5 text-muted">Tanggal PO</div>
                                <div class="col-7">: <strong>{{ \Carbon\Carbon::parse($pengajuanPo->tanggal_po)->translatedFormat('d F Y') }}</strong></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-5 text-muted">Metode Pembelian</div>
                                <div class="col-7">: <strong>{{ ucfirst($pengajuanPo->metode_pembelian) }}</strong></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-5 text-muted">Sumber / Supplier</div>
                                <div class="col-7">: {{ $pengajuanPo->sumber_po ?? '-' }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-5 text-muted">Kontak</div>
                                <div class="col-7">: {{ $pengajuanPo->kontak_pembelian ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row mb-2">
                                <div class="col-5 text-muted">Diajukan Oleh</div>
                                <div class="col-7">: <strong>{{ $pengajuanPo->diajukan->name ?? '-' }}</strong></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-5 text-muted">Disetujui Oleh</div>
                                <div class="col-7">: {{ $pengajuanPo->approver->name ?? '-' }}</div>
                            </div>
                        </div>
                    </div>

                    <h5 class="mt-4">Detail Item</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th>No</th><th>Nama Barang</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-right">Harga Estimasi</th>
                                    <th class="text-right">Subtotal</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pengajuanPo->detail as $i => $detail)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $detail->barang->nama_barang }}</td>
                                    <td class="text-center">{{ $detail->qty }} {{ $detail->barang->satuan }}</td>
                                    <td class="text-right">Rp{{ number_format($detail->harga_estimasi, 0, ',', '.') }}</td>
                                    <td class="text-right">Rp{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        @if($detail->status_item === 'disetujui')
                                            <span class="badge badge-success">Disetujui</span>
                                        @elseif($detail->status_item === 'ditahan')
                                            <span class="badge badge-warning">Ditahan</span>
                                        @elseif($detail->status_item === 'ditolak')
                                            <span class="badge badge-danger">Ditolak</span>
                                        @else
                                            <span class="badge badge-secondary">Menunggu</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-right">Total Estimasi</th>
                                    <th class="text-right">Rp{{ number_format($pengajuanPo->detail->sum('subtotal'), 0, ',', '.') }}</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('pengajuan-po.index') }}" class="btn btn-default">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <div>
                        @if($pengajuanPo->status_po === 'diajukan' && Auth::user()->role === 'keuangan')
                            <a href="{{ route('pengajuan-po.approval', $pengajuanPo->id) }}" class="btn btn-warning">
                                <i class="fas fa-check"></i> Proses Approval
                            </a>
                        @endif
                        @if($pengajuanPo->status_po === 'diproses' && Auth::user()->role === 'logistik')
                            <a href="{{ route('barang-masuk.create') }}" class="btn btn-primary">
                                <i class="fas fa-truck"></i> Konfirmasi Barang Masuk
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection