@extends('layouts.app')
@section('title', 'Pengajuan PO')
@section('content')
<div class="content-wrapper">
    
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Pengajuan PO</h2>
        <p class="text-muted mb-0">
            Daftar pengajuan pembelian barang.
        </p>
    </div>

    {{-- @if(Auth::user()->role == 'admin') --}}
        <a href="{{ route('pengajuan-po.create') }}"
        class="btn btn-primary">
            <i class="bi bi-plus-circle"></i>
            Buat PO
        </a>
    {{-- @endif --}}
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
                    <h3 class="card-title">Daftar Pengajuan PO</h3>
                </div>
                <div class="card-body">
                    <form method="GET" class="mb-3">
                        <select name="status" class="form-control form-control-sm d-inline-block w-auto" onchange="this.form.submit()">
                            <option value="">Semua Status</option>
<option value="pending">Menunggu Approval</option>
<option value="disetujui">Disetujui</option>
<option value="disetujui_sebagian">Disetujui Sebagian</option>
<option value="ditolak">Ditolak</option>
                        </select>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No</th><th>Tanggal PO</th><th>Sumber/Supplier</th>
                                    <th>Metode</th><th>Diajukan Oleh</th>
                                    <th class="text-right">Total Estimasi</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($po as $item)
                                <tr>
                                    <td>{{ $po->firstItem() + $loop->index }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal_po)->translatedFormat('d M Y') }}</td>
                                    <td>{{ $item->sumber_po ?? '-' }}</td>
                                    <td>{{ ucfirst($item->metode_pembelian) }}</td>
                                    <td>{{ $item->diajukan->name ?? '-' }}</td>
                                    <td class="text-right">
                                        Rp{{ number_format($item->detail->sum('subtotal'), 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">

    @if($item->status_po === 'pending')
        <span class="badge badge-warning">
            Menunggu Approval
        </span>

    @elseif($item->status_po === 'disetujui')
        <span class="badge badge-success">
            Disetujui
        </span>

    @elseif($item->status_po === 'disetujui_sebagian')
        <span class="badge badge-info">
            Disetujui Sebagian
        </span>

    @elseif($item->status_po === 'ditolak')
        <span class="badge badge-danger">
            Ditolak
        </span>

    @endif

</td>
                                    <td class="text-center">

                            <a href="{{ route('pengajuan-po.show', $item->id) }}"
                               class="btn btn-info btn-sm">
                                <i class="bi bi-eye-fill"></i>
                            </a>

                            {{-- @if(Auth::user()->role == 'admin') --}}
                                <form action="{{ route('pengajuan-po.destroy', $item->id) }}"
                                    method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Yakin ingin menghapus PO ini?')">

                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>

                                </form>
                            {{-- @endif --}}

                            {{-- @if(Auth::user()->role == 'keuangan' && $item->status_po == 'pending') --}}
                                <a href="{{ route('pengajuan-po.approval', $item->id) }}"
                                class="btn btn-success btn-sm"
                                title="Approval">

                                    <i class="bi bi-check-circle-fill"></i>

                                </a>
                            {{-- @endif --}}

                        </td>
                                </tr>
                                @empty
                                <tr><td colspan="8" class="text-center text-muted py-4">Belum ada pengajuan PO.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end mt-3">{{ $po->links() }}</div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection