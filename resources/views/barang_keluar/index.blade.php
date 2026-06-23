@extends('layouts.app')
@section('title', 'Barang Keluar')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">Barang Keluar</h3>
        <p class="text-muted mb-0">Kelola data barang keluar gudang.</p>
    </div>

    {{-- @if(Auth::user()->role == 'logistik') --}}
        <a href="{{ route('barang-keluar.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Barang Keluar
        </a>
    {{-- @endif --}}
</div>
            @if(session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    {{ session('success') }}
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Barang Keluar</h3>
                    <div class="card-tools">
                        @if(Auth::user()->role === 'logistik')
                            <a href="{{ route('barang-keluar.create') }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-plus"></i> Tambah Barang Keluar
                            </a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" class="mb-3" style="display:flex;gap:8px;flex-wrap:wrap;">
                        <input type="date" name="tanggal" class="form-control form-control-sm w-auto"
                               value="{{ request('tanggal') }}">
                        <input type="text" name="divisi" class="form-control form-control-sm w-auto"
                               placeholder="Cari divisi..." value="{{ request('divisi') }}">
                        <button class="btn btn-sm btn-default">Filter</button>
                        @if(request('tanggal') || request('divisi'))
                            <a href="{{ route('barang-keluar.index') }}" class="btn btn-sm btn-default">Reset</a>
                        @endif
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Keluar</th>
                                    <th>Divisi Tujuan</th>
                                    <th class="text-center">Jumlah Item</th>
                                    <th>Keterangan</th>
                                    <th class="text-center">Status Stok</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($barangKeluar as $item)
                                <tr>
                                    <td>{{ $barangKeluar->firstItem() + $loop->index }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal_keluar)->translatedFormat('d M Y') }}</td>
                                    <td>{{ $item->divisi_tujuan }}</td>
                                    <td class="text-center">{{ $item->detail->count() }} item</td>
                                    <td>{{ $item->keterangan ?? '-' }}</td>
                                    <td class="text-center">
                                        @php
                                            $adaKurang = $item->detail->where('qty_kurang', '>', 0)->count() > 0;
                                        @endphp
                                        @if($adaKurang)
                                            <span class="badge badge-warning">
                                                <i class="fas fa-exclamation-triangle"></i> Ada kekurangan
                                            </span>
                                        @else
                                            <span class="badge badge-success">
                                                <i class="fas fa-check"></i> Terpenuhi
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">

    {{-- Detail --}}
    <a href="{{ route('barang-keluar.show', $item->id) }}"
       class="btn btn-info btn-sm"
       title="Detail">
        <i class="bi bi-eye-fill"></i>
    </a>

    {{-- Hapus --}}
    {{-- @if(Auth::user()->role == 'logistik') --}}
    <form action="{{ route('barang-keluar.destroy', $item->id) }}"
          method="POST"
          class="d-inline"
          onsubmit="return confirm('Yakin ingin menghapus data ini?')">

        @csrf
        @method('DELETE')

        <button type="submit"
                class="btn btn-danger btn-sm"
                title="Hapus">
            <i class="bi bi-trash-fill"></i>
        </button>

    </form>
    {{-- @endif --}}

</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        Belum ada transaksi barang keluar.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end mt-3">{{ $barangKeluar->links() }}</div>
                </div>
            </div>

        </div>
    </section>
</div>
@endsection



{{-- @extends('layouts.app')

@section('content')

<br><br>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3>Barang Keluar</h3>
        <p class="text-muted mb-0">
            Data transaksi barang keluar.
        </p>
    </div>

    <a href="{{ route('barang-keluar.create') }}"
       class="btn btn-primary">
        <i class="bi bi-plus-circle"></i>
        Tambah Barang Keluar
    </a>
</div>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="card shadow-sm border-0">
    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-hover">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Divisi Tujuan</th>
                        <th>Jumlah Item</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($barangKeluar as $item)

                <tr>
                    <td>{{ $loop->iteration }}</td>

                    <td>
                        {{ \Carbon\Carbon::parse($item->tanggal_keluar)->format('d-m-Y') }}
                    </td>

                    <td>{{ $item->divisi_tujuan }}</td>

                    <td>{{ $item->detail->count() }}</td>

                    <td>

                        <a href="{{ route('barang-keluar.show',$item->id) }}"
                           class="btn btn-info btn-sm">
                            <i class="bi bi-eye"></i>
                        </a>

                        <form action="{{ route('barang-keluar.destroy',$item->id) }}"
                              method="POST"
                              class="d-inline"
                              onsubmit="return confirm('Hapus data?')">

                            @csrf
                            @method('DELETE')

                            <button class="btn btn-danger btn-sm">
                                <i class="bi bi-trash"></i>
                            </button>

                        </form>

                    </td>
                </tr>

                @empty

                <tr>
                    <td colspan="5" class="text-center">
                        Belum ada data.
                    </td>
                </tr>

                @endforelse

                </tbody>

            </table>
        </div>

    </div>
</div>

@endsection --}}