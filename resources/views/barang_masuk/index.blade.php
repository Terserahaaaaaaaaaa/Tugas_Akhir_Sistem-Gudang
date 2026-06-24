@extends('layouts.app')
@section('title', 'Barang Masuk')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">Barang Masuk</h3>
        <p class="text-muted mb-0">
            Kelola data barang masuk gudang.
        </p>
    </div>

    @if(Auth::user()->role == 'logistik')
        <a href="{{ route('barang-masuk.create') }}"
        class="btn btn-primary">

            <i class="bi bi-plus-circle"></i>
            Tambah Barang Masuk
        </a>
    @endif
</div>
<div class="content-wrapper">
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
                    <h3 class="card-title">Daftar Barang Masuk</h3>
                    <div class="card-tools">
                        @if(Auth::user()->role === 'logistik')
                            <a href="{{ route('barang-masuk.create') }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-plus"></i> Konfirmasi Barang Masuk
                            </a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" class="mb-3">
                        <input type="date" name="tanggal" class="form-control form-control-sm d-inline-block w-auto"
                               value="{{ request('tanggal') }}" onchange="this.form.submit()">
                    </form>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No</th><th>Tanggal Masuk</th><th>PO</th>
                                    <th>Dikonfirmasi Oleh</th><th class="text-center">Jumlah Item</th>
                                    <th>Keterangan</th><th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($barangMasuk as $item)
                                <tr>
                                    <td>{{ $barangMasuk->firstItem() + $loop->index }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal_masuk)->translatedFormat('d M Y') }}</td>
                                    <td>
                                        <a href="{{ route('pengajuan-po.show', $item->pengajuan_po_id) }}">
                                            PO #{{ $item->pengajuan_po_id }}
                                        </a>
                                    </td>
                                    <td>{{ $item->user->name ?? '-' }}</td>
                                    <td class="text-center">{{ $item->detail->count() }} item</td>
                                    <td>{{ $item->keterangan ?? '-' }}</td>
                                    <td>

                            <a href="{{ route('barang-masuk.show', $item->id) }}"
                               class="btn btn-info btn-sm">

                                <i class="bi bi-eye-fill"></i>
                            </a>

                            {{--yang bisa hapus cuma logistik--}}
                            @if(Auth::user()->role == 'logistik')
                                <form action="{{ route('barang-masuk.destroy', $item->id) }}"
                                    method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Yakin ingin menghapus data ini?')">

                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            @endif

                        </td>
                                </tr>
                                @empty
                                <tr><td colspan="7" class="text-center text-muted py-4">Belum ada konfirmasi barang masuk.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end mt-3">{{ $barangMasuk->links() }}</div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection