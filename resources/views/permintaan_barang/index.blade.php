@extends('layouts.app')
@section('title', 'Permintaan Barang Masuk')
@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6"><h1>Permintaan Pengadaan Barang</h1></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item active">Permintaan Barang</li>
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

            {{-- Penjelasan untuk admin --}}
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                Daftar permintaan di bawah dibuat <strong>otomatis oleh sistem</strong>
                saat logistik mencatat barang keluar yang stoknya tidak mencukupi.
                Tindak lanjuti dengan membuat PO ke keuangan.
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Permintaan Pengadaan</h3>
                </div>
                <div class="card-body">
                    <form method="GET" class="mb-3">
                        <select name="status" class="form-control form-control-sm d-inline-block w-auto"
                                onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="baru" {{ request('status')=='baru'?'selected':'' }}>Baru</option>
                            <option value="diajukan_po" {{ request('status')=='diajukan_po'?'selected':'' }}>Diajukan PO</option>
                            <option value="terpenuhi"  {{ request('status')=='terpenuhi'?'selected':'' }}>Terpenuhi</option>
                            <option value="tidak_terpenuhi"  {{ request('status')=='tidak_terpenuhi'?'selected':'' }}>Tidal Terpenuhi</option>
                        </select>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Divisi</th>
                                    <th class="text-center">Jumlah Item Kurang</th>
                                    <th>Keterangan</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($permintaan as $item)
                                <tr>
                                    <td>{{ $permintaan->firstItem() + $loop->index }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal_permintaan)->translatedFormat('d M Y') }}</td>
                                    <td>{{ $item->divisi }}</td>
                                    <td class="text-center">
                                        <span class="badge badge-danger">{{ $item->detail->count() }} item</span>
                                    </td>
                                    <td>{{ $item->keterangan ?? '-' }}</td>
                                    <td class="text-center">
                                        {{--untuk status permintaan--}}
                                        @if($item->status_permintaan === 'baru')
                                            <span class="badge badge-warning text-dark">
                                                Baru
                                            </span>

                                        @elseif($item->status_permintaan === 'diajukan_po')
                                            <span class="badge badge-primary text-dark">
                                                Diajukan PO
                                            </span>

                                        @elseif($item->status_permintaan === 'barang_tersedia')
                                            <span class="badge badge-success text-dark">
                                                Barang Sudah Masuk Gudang
                                            </span>

                                        @elseif($item->status_permintaan === 'terpenuhi')
                                            <span class="badge badge-success text-dark">
                                                Terpenuhi
                                            </span>

                                        @else
                                            <span class="badge badge-danger text-dark">
                                                Tidak Terpenuhi
                                            </span>
                                        @endif

                                    </td>

                                    <td>
                                        <a href="{{ route('permintaan-barang.show', $item->id) }}"
                                        class="btn btn-info btn-sm">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                        {{--untuk tombol buat po hanya muncul dan dilakukan oleh admin--}}
                                        @if(Auth::user()->role == 'admin' && $item->status_permintaan != 'terpenuhi')
                                            <a href="{{ route('pengajuan-po.create', ['permintaan_id' => $item->id]) }}"
                                        class="btn btn-primary btn-sm"
                                        title="Buat PO">
                                            <i class="bi bi-file-earmark-plus"></i>
                                        </a>
                                        @endif

                                        {{-- Penuhi Permintaan --}}
                                        {{--untuk tombol ini untuk jika barang udah tersedia digudang 
                                            maka akan diklik dan barang yang kekurangan statusnya akan terpenuhi 
                                            dan hanya muncul dan dilakukan oleh logistik--}}
                                        @if(Auth::user()->role ==  'logistik' 
                                        &&$item->status_permintaan == 'barang_tersedia')
                                        
                                            <form action="{{ route('permintaan-barang.penuhi', $item->id) }}"
                                                method="POST"
                                                class="d-inline">
                                                @csrf

                                                <button class="btn btn-success btn-sm"
                                                        title="Penuhi Permintaan">
                                                    <i class="bi bi-box-arrow-up"></i>
                                                </button>
                                            </form>
                                        @endif

                                        {{--hapus hanya muncul dan dilakukan oleh logistik--}}
                                        @if(Auth::user()->role == 'logistik')
                                            <form action="{{ route('permintaan-barang.destroy', $item->id) }}"
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
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="fas fa-check-circle text-success"></i>
                                        Tidak ada permintaan pengadaan. Semua stok terpenuhi.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end mt-3">{{ $permintaan->links() }}</div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection