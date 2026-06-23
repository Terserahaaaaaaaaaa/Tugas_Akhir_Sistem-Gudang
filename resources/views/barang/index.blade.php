@extends('layouts.app')

@section('title', 'Data Barang')

@section('content')
<div class="content-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">Data Barang</h3>
        <p class="text-muted mb-0">Kelola data barang perlengkapan produksi.</p>
    </div>

    {{-- @if(Auth::user()->role == 'admin') --}}
        <a href="{{ route('barang.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Barang
        </a>
    {{-- @endif --}}
</div>

    <section class="content">
        <div class="container-fluid">

            @if (session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    {{ session('error') }}
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Barang</h3>
                </div>

                <div class="card-body">
                    <form action="{{ route('barang.index') }}" method="GET" class="mb-3">
                        <div class="input-group input-group-sm" style="max-width: 320px;">
                            <input type="text" name="search" class="form-control"
                                   placeholder="Cari kode atau nama barang..."
                                   value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                                @if (request('search'))
                                    <a href="{{ route('barang.index') }}" class="btn btn-default">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 40px;">No</th>
                                    <th style="width: 70px;">Foto</th>
                                    <th>Kode</th>
                                    <th>Nama Barang</th>
                                    <th>Kategori</th>
                                    <th>Satuan</th>
                                    {{-- <th class="text-center">Stok</th>
                                    <th class="text-center">Stok Min.</th>
                                    <th class="text-right">Harga Terakhir</th> --}}
                                    <th style="width: 130px;" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($barang as $item)
                                    <tr>
                                        <td>{{ $barang->firstItem() + $loop->index }}</td>
                                        <td>
                                            @if ($item->foto)
                                                <img src="{{ asset('storage/' . $item->foto) }}"
                                                     alt="{{ $item->nama_barang }}"
                                                     class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center"
                                                     style="width: 50px; height: 50px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>{{ $item->kode_barang }}</td>
                                        <td>{{ $item->nama_barang }}</td>
                                        <td>{{ $item->kategori->nama_akun ?? '-' }}</td>
                                        <td>{{ $item->satuan ?? '-' }}</td>
                                        {{-- <td class="text-center">{{ $item->stok }}</td>
                                        <td class="text-center">{{ $item->stok_minimum }}</td>
                                        <td class="text-right">Rp{{ number_format($item->harga_terakhir, 0, ',', '.') }}</td> --}}
                                        {{-- aksi --}}
                                        <td>
                                            {{-- detail --}}
                                            <a href="{{ route('barang.show', $item->id) }}"
                                            class="btn btn-info btn-sm"
                                            title="Detail">

                                                <i class="bi bi-eye-fill"></i>
                                            </a>

                                            {{-- edit --}}
                                            @if(Auth::user()->role == 'admin')
                                                <a href="{{ route('barang.edit', $item->id) }}"
                                                class="btn btn-warning btn-sm"
                                                title="Edit">

                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                            @endif

                                            {{-- hapus --}}
                                            @if(Auth::user()->role == 'admin')
                                                <form action="{{ route('barang.destroy', $item->id) }}"
                                                    method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Yakin ingin menghapus barang ini?')">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button class="btn btn-danger btn-sm"
                                                            title="Hapus">

                                                        <i class="bi bi-trash-fill"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                    {{-- @if ($item->stok < $item->stok_minimum)
                                        <tr>
                                            <td></td>
                                            <td colspan="9" class="py-1">
                                                <span class="badge badge-danger">
                                                    <i class="fas fa-exclamation-triangle"></i> Stok kritis, di bawah stok minimum
                                                </span>
                                            </td>
                                        </tr>
                                    @endif --}}
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center text-muted py-4">
                                            @if (request('search'))
                                                Tidak ada barang yang cocok dengan pencarian "{{ request('search') }}".
                                            @else
                                                Belum ada data barang.
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        {{ $barang->appends(['search' => request('search')])->links() }}
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>
@endsection