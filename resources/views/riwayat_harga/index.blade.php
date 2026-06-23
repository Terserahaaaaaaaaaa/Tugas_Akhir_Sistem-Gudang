@extends('layouts.app')
@section('title', 'Riwayat Harga')
@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6"><h1>Riwayat Harga Barang</h1></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item active">Riwayat Harga</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header"><h3 class="card-title">Riwayat Perubahan Harga</h3></div>
                <div class="card-body">
                    <form method="GET" class="mb-3 d-flex flex-wrap" style="gap:8px;">
                        <select name="barang_id" class="form-control form-control-sm w-auto">
                            <option value="">Semua Barang</option>
                            @foreach($barang as $b)
                                <option value="{{ $b->id }}" {{ request('barang_id')==$b->id?'selected':'' }}>
                                    {{ $b->nama_barang }}
                                </option>
                            @endforeach
                        </select>
                        <button class="btn btn-sm btn-default">Filter</button>
                        @if(request('barang_id'))
                            <a href="{{ route('riwayat-harga.index') }}" class="btn btn-sm btn-default">Reset</a>
                        @endif
                    </form>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No</th><th>Barang</th><th>Tanggal</th>
                                    <th class="text-right">Harga Lama</th>
                                    <th class="text-right">Harga Baru</th>
                                    <th class="text-center">Perubahan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($riwayat as $item)
                                @php
                                    $persen = $item->harga_lama > 0
                                        ? (($item->harga_baru - $item->harga_lama) / $item->harga_lama * 100)
                                        : 0;
                                @endphp
                                <tr>
                                    <td>{{ $riwayat->firstItem() + $loop->index }}</td>
                                    <td>{{ $item->barang->nama_barang }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal_perubahan)->translatedFormat('d M Y') }}</td>
                                    <td class="text-right">Rp{{ number_format($item->harga_lama, 0, ',', '.') }}</td>
                                    <td class="text-right">Rp{{ number_format($item->harga_baru, 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        @if($persen > 10)
                                            <span class="badge badge-danger">
                                                <i class="fas fa-arrow-up"></i> Naik {{ number_format($persen, 1) }}% ⚠
                                            </span>
                                        @elseif($persen > 0)
                                            <span class="badge badge-warning">
                                                <i class="fas fa-arrow-up"></i> Naik {{ number_format($persen, 1) }}%
                                            </span>
                                        @elseif($persen < 0)
                                            <span class="badge badge-success">
                                                <i class="fas fa-arrow-down"></i> Turun {{ number_format(abs($persen), 1) }}%
                                            </span>
                                        @else
                                            <span class="badge badge-secondary">Sama</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="6" class="text-center text-muted py-4">Belum ada riwayat perubahan harga.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end mt-3">{{ $riwayat->links() }}</div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection