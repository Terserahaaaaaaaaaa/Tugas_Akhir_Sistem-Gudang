@extends('layouts.app')
@section('title', 'Detail Permintaan Pengadaan')
@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6"><h1>Detail Permintaan Pengadaan</h1></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('permintaan-barang.index') }}">Permintaan Barang</a></li>
                        <li class="breadcrumb-item active">Detail</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Permintaan Pengadaan</h3>
                    <div class="card-tools">
                        @if($permintaanBarang->status_permintaan === 'selesai')
                            <span class="badge badge-success">Selesai</span>
                        @elseif($permintaanBarang->status_permintaan === 'diajukan_po')
                            <span class="badge badge-info">Diajukan PO</span>
                        @else
                            <span class="badge badge-warning">Menunggu Tindak Lanjut</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-3 text-muted">Tanggal</div>
                        <div class="col-md-9">: <strong>{{ \Carbon\Carbon::parse($permintaanBarang->tanggal_permintaan)->translatedFormat('d F Y') }}</strong></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 text-muted">Divisi</div>
                        <div class="col-md-9">: <strong>{{ $permintaanBarang->divisi }}</strong></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 text-muted">Keterangan</div>
                        <div class="col-md-9">: {{ $permintaanBarang->keterangan ?? '-' }}</div>
                    </div>
                    @if(isset($permintaanBarang->barang_keluar_id))
                        <div class="row mb-2">
                            <div class="col-md-3 text-muted">Dari Barang Keluar</div>
                            <div class="col-md-9">:
                                <a href="{{ route('barang-keluar.show', $permintaanBarang->barang_keluar_id) }}">
                                    Lihat transaksi barang keluar
                                </a>
                            </div>
                        </div>
                    @endif

                    <h5 class="mt-4">Barang yang Perlu Diadakan</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Barang</th>
                                    <th class="text-center">Qty Kurang</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($permintaanBarang->detail as $i => $d)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $d->barang->nama_barang }}</td>
                                    <td class="text-center">
                                        <span class="badge badge-danger">
                                            {{ $d->qty }} {{ $d->barang->satuan }}
                                        </span>
                                    </td>
                                    <td>{{ $d->keterangan ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('permintaan-barang.index') }}" class="btn btn-default">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    @if($permintaanBarang->status_permintaan === 'diajukan' && Auth::user()->role === 'admin')
                        <a href="{{ route('pengajuan-po.create', $permintaanBarang->id) }}" class="btn btn-primary">
                            <i class="fas fa-file-plus"></i> Buat PO untuk Permintaan Ini
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>
@endsection