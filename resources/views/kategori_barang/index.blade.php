@extends('layouts.app')

@section('title', 'Kategori Barang')

@section('content')

<div class="content-wrapper">
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">Kategori Barang</h3>
        <p class="text-muted mb-0">Kelola Kategori Barang</p>
    </div>

    {{-- @if(Auth::user()->role == 'logistik') --}}
        <a href="{{ route('kategori-barang.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Kategori Barang
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

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Kategori Barang</h3>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">No</th>
                                    <th>No Akun</th>
                                    <th>Nama Akun</th>
                                    <th style="width: 130px;" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($kategori as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->no_akun }}</td>
                                        <td>{{ $item->nama_akun }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('kategori-barang.edit', $item->id) }}"
                                               class="btn btn-sm btn-warning" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <form action="{{ route('kategori-barang.destroy', $item->id) }}" method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Yakin ingin menghapus kategori ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            Belum ada kategori barang.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>
@endsection