@extends('layouts.app')

@section('title', 'Detail Barang')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Detail Barang</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('barang.index') }}">Data Barang</a></li>
                        <li class="breadcrumb-item active">Detail</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">

            @if (session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    {{ session('success') }}
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $barang->nama_barang }}</h3>
                    @if ($barang->stok < $barang->stok_minimum)
                        <div class="card-tools">
                            <span class="badge badge-danger">
                                <i class="fas fa-exclamation-triangle"></i> Stok kritis
                            </span>
                        </div>
                    @endif
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            @if ($barang->foto)
                                <img src="{{ asset('storage/' . $barang->foto) }}"
                                     alt="{{ $barang->nama_barang }}"
                                     class="img-thumbnail"
                                     style="width: 180px; height: 180px; object-fit: cover;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center mx-auto"
                                     style="width: 180px; height: 180px;">
                                    <i class="fas fa-image fa-2x text-muted"></i>
                                </div>
                            @endif
                        </div>

                        <div class="col-md-9">
                            <div class="row mb-2">
                                <div class="col-md-4 text-muted">Kode Barang</div>
                                <div class="col-md-8">: <strong>{{ $barang->kode_barang }}</strong></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4 text-muted">Nama Barang</div>
                                <div class="col-md-8">: <strong>{{ $barang->nama_barang }}</strong></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4 text-muted">Kategori</div>
                                <div class="col-md-8">: <strong>{{ $barang->kategori->nama_akun ?? '-' }}</strong></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4 text-muted">Satuan</div>
                                <div class="col-md-8">: <strong>{{ $barang->satuan ?? '-' }}</strong></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4 text-muted">Stok Saat Ini</div>
                                <div class="col-md-8">:
                                    <strong>{{ $barang->stok }}</strong>
                                    @if ($barang->stok < $barang->stok_minimum)
                                        <span class="badge badge-danger ml-1">Di bawah stok minimum</span>
                                    @else
                                        <span class="badge badge-success ml-1">Aman</span>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4 text-muted">Stok Minimum</div>
                                <div class="col-md-8">: <strong>{{ $barang->stok_minimum }}</strong></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4 text-muted">Harga Terakhir</div>
                                <div class="col-md-8">: <strong>Rp{{ number_format($barang->harga_terakhir, 0, ',', '.') }}</strong></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4 text-muted">Ditambahkan</div>
                                <div class="col-md-8">: {{ $barang->created_at?->translatedFormat('d F Y, H:i') ?? '-' }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4 text-muted">Terakhir Diperbarui</div>
                                <div class="col-md-8">: {{ $barang->updated_at?->translatedFormat('d F Y, H:i') ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <a href="{{ route('barang.edit', $barang->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form action="{{ route('barang.destroy', $barang->id) }}" method="POST" class="d-inline"
                          onsubmit="return confirm('Yakin ingin menghapus barang ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </form>
                    <a href="{{ route('barang.index') }}" class="btn btn-default">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>

        </div>
    </section>
</div>
@endsection