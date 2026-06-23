@extends('layouts.app')
@section('title', 'Stok Opname')
@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6"><h1>Stok Opname</h1></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item active">Stok Opname</li>
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
                    <h3 class="card-title">Daftar Stok Opname</h3>
                    <div class="card-tools">
                        @if(Auth::user()->role === 'logistik')
                            <a href="{{ route('stok-opname.create') }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-plus"></i> Buat Opname Baru
                            </a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No</th><th>Tanggal Opname</th><th>Dilakukan Oleh</th>
                                    <th class="text-center">Jumlah Item</th><th>Keterangan</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($opname as $item)
                                <tr>
                                    <td>{{ $opname->firstItem() + $loop->index }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal_opname)->translatedFormat('d M Y') }}</td>
                                    <td>{{ $item->user->name ?? '-' }}</td>
                                    <td class="text-center">{{ $item->detail->count() }} item</td>
                                    <td>{{ $item->keterangan ?? '-' }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('stok-opname.show', $item->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="6" class="text-center text-muted py-4">Belum ada stok opname.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end mt-3">{{ $opname->links() }}</div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection