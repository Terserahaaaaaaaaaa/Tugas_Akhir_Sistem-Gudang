@extends('layouts.app')
@section('title', 'Detail Barang Keluar')
@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6"><h1>Detail Barang Keluar</h1></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('barang-keluar.index') }}">Barang Keluar</a></li>
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
                    <h3 class="card-title">Detail Barang Keluar</h3>
                    <div class="card-tools">
                        @if($barangKeluar->permintaanBarang)
                            <span class="badge badge-warning">
                                <i class="fas fa-exclamation-triangle"></i> Ada kekurangan stok
                            </span>
                        @else
                            <span class="badge badge-success">
                                <i class="fas fa-check"></i> Stok terpenuhi semua
                            </span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-3 text-muted">Tanggal Keluar</div>
                        <div class="col-md-9">: <strong>{{ \Carbon\Carbon::parse($barangKeluar->tanggal_keluar)->translatedFormat('d F Y') }}</strong></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 text-muted">Divisi Tujuan</div>
                        <div class="col-md-9">: <strong>{{ $barangKeluar->divisi_tujuan }}</strong></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 text-muted">Keterangan</div>
                        <div class="col-md-9">: {{ $barangKeluar->keterangan ?? '-' }}</div>
                    </div>

                    <h5 class="mt-4">Detail Barang</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Barang</th>
                                    <th class="text-center">Qty Diminta</th>
                                    <th class="text-center">Qty Dikeluarkan</th>
                                    <th class="text-center">Qty Kurang</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($barangKeluar->detail as $i => $d)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $d->barang->nama_barang }}</td>
                                    <td class="text-center">{{ $d->qty }} {{ $d->barang->satuan }}</td>
                                    <td class="text-center text-success font-weight-bold">{{ $d->qty_keluar }} {{ $d->barang->satuan }}</td>
                                    <td class="text-center">
                                        @if($d->qty_kurang > 0)
                                            <span class="text-danger font-weight-bold">{{ $d->qty_kurang }} {{ $d->barang->satuan }}</span>
                                        @else
                                            <span class="text-success">–</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($d->qty_kurang > 0)
                                            <span class="badge badge-warning">Kurang</span>
                                        @else
                                            <span class="badge badge-success">Terpenuhi</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Jika ada kekurangan, tampilkan info permintaan yang dikirim ke admin --}}
                    @if($barangKeluar->permintaanBarang)
                        <div class="alert alert-warning mt-4">
                            <h6><i class="fas fa-paper-plane"></i> Permintaan pengadaan otomatis dikirim ke admin</h6>
                            <p class="mb-1">Barang berikut tidak mencukupi dan sudah diajukan ke admin untuk pengadaan:</p>
                            <ul class="mb-0">
                                @foreach($barangKeluar->permintaanBarang->detail as $d)
                                    <li>{{ $d->barang->nama_barang }} — {{ $d->qty }} {{ $d->barang->satuan }}</li>
                                @endforeach
                            </ul>
                            <a href="{{ route('permintaan-barang.show', $barangKeluar->permintaanBarang->id) }}"
                               class="btn btn-sm btn-warning mt-2">
                                <i class="fas fa-eye"></i> Lihat permintaan ke admin
                            </a>
                        </div>
                    @endif
                </div>
                <div class="card-footer">
                    <a href="{{ route('barang-keluar.index') }}" class="btn btn-default">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection




{{-- @extends('layouts.app')

@section('content')

<br><br>

<h3>Detail Barang Keluar</h3>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">

        <p>
            <strong>Tanggal :</strong>
            {{ \Carbon\Carbon::parse($barangKeluar->tanggal_keluar)->format('d-m-Y') }}
        </p>

        <p>
            <strong>Divisi :</strong>
            {{ $barangKeluar->divisi_tujuan }}
        </p>

        <p>
            <strong>Keterangan :</strong>
            {{ $barangKeluar->keterangan }}
        </p>

    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">

        <table class="table table-bordered">

            <thead>
                <tr>
                    <th>No</th>
                    <th>Barang</th>
                    <th>Qty</th>
                </tr>
            </thead>

            <tbody>

                @foreach($barangKeluar->detail as $detail)

                <tr>
                    <td>{{ $loop->iteration }}</td>

                    <td>
                        {{ $detail->barang->nama_barang }}
                    </td>

                    <td>
                        {{ $detail->qty }}
                    </td>
                </tr>

                @endforeach

            </tbody>

        </table>

    </div>
</div>

@endsection --}}