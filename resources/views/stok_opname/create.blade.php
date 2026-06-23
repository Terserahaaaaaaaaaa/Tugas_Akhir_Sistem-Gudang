@extends('layouts.app')
@section('title', 'Buat Stok Opname')
@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6"><h1>Buat Stok Opname</h1></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('stok-opname.index') }}">Stok Opname</a></li>
                        <li class="breadcrumb-item active">Buat</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif
            <form action="{{ route('stok-opname.store') }}" method="POST">
                @csrf
                <div class="card">
                    <div class="card-header"><h3 class="card-title">Form Stok Opname</h3></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tanggal Opname</label>
                                    <input type="date" name="tanggal_opname"
                                           class="form-control @error('tanggal_opname') is-invalid @enderror"
                                           value="{{ old('tanggal_opname', date('Y-m-d')) }}" required>
                                    @error('tanggal_opname')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Keterangan <small class="text-muted">(opsional)</small></label>
                                    <input type="text" name="keterangan" class="form-control"
                                           value="{{ old('keterangan') }}" placeholder="Catatan opname...">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <h5>Daftar Stok Barang</h5>
                        <p class="text-muted small">Isi kolom "Stok Fisik" berdasarkan hasil hitung fisik di gudang. Selisih akan dihitung otomatis.</p>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th>No</th><th>Kode</th><th>Nama Barang</th><th>Kategori</th>
                                        <th class="text-center">Stok Sistem</th>
                                        <th style="width:120px;">Stok Fisik</th>
                                        <th class="text-center">Selisih</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($barang as $i => $b)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $b->kode_barang }}</td>
                                        <td>{{ $b->nama_barang }}<input type="hidden" name="barang_id[]" value="{{ $b->id }}"></td>
                                        <td>{{ $b->kategori->nama_akun ?? '-' }}</td>
                                        <td class="text-center">{{ $b->stok }} {{ $b->satuan }}</td>
                                        <td>
                                            <input type="number" name="stok_fisik[]"
                                                   class="form-control form-control-sm stok-fisik"
                                                   min="0" value="{{ $b->stok }}" required
                                                   data-sistem="{{ $b->stok }}"
                                                   oninput="hitungSelisih(this)">
                                        </td>
                                        <td class="text-center selisih-col" id="selisih-{{ $i }}">0</td>
                                        <td><input type="text" name="keterangan_item[]" class="form-control form-control-sm" placeholder="Opsional..."></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Opname</button>
                        <a href="{{ route('stok-opname.index') }}" class="btn btn-default">Batal</a>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>
<script>
function hitungSelisih(input) {
    const sistem = parseInt(input.dataset.sistem) || 0;
    const fisik  = parseInt(input.value) || 0;
    const selisih = fisik - sistem;
    const row = input.closest('tr');
    const idx = Array.from(row.parentNode.children).indexOf(row);
    const el = document.getElementById('selisih-' + idx);
    if (!el) return;
    el.textContent = (selisih >= 0 ? '+' : '') + selisih;
    el.style.color = selisih === 0 ? 'inherit' : selisih > 0 ? 'var(--warning)' : 'var(--danger)';
}
document.querySelectorAll('.stok-fisik').forEach(i => hitungSelisih(i));
</script>
@endsection
BLADE

cat > /mnt/user-data/outputs/resources/views/stok_opname/show.blade.php << 'BLADE'
@extends('layouts.app')
@section('title', 'Detail Stok Opname')
@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6"><h1>Detail Stok Opname</h1></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('stok-opname.index') }}">Stok Opname</a></li>
                        <li class="breadcrumb-item active">Detail</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header"><h3 class="card-title">Hasil Stok Opname</h3></div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-3 text-muted">Tanggal Opname</div>
                        <div class="col-md-9">: <strong>{{ \Carbon\Carbon::parse($stokOpname->tanggal_opname)->translatedFormat('d F Y') }}</strong></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 text-muted">Dilakukan Oleh</div>
                        <div class="col-md-9">: <strong>{{ $stokOpname->user->name ?? '-' }}</strong></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 text-muted">Keterangan</div>
                        <div class="col-md-9">: {{ $stokOpname->keterangan ?? '-' }}</div>
                    </div>
                    <h5 class="mt-4">Detail Hasil Opname</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th>No</th><th>Barang</th>
                                    <th class="text-center">Stok Sistem</th>
                                    <th class="text-center">Stok Fisik</th>
                                    <th class="text-center">Selisih</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stokOpname->detail as $i => $d)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $d->barang->nama_barang }}</td>
                                    <td class="text-center">{{ $d->stok_sistem }}</td>
                                    <td class="text-center">{{ $d->stok_fisik }}</td>
                                    <td class="text-center">
                                        @if($d->selisih == 0)
                                            <span class="badge badge-success">0</span>
                                        @elseif($d->selisih > 0)
                                            <span class="badge badge-warning">+{{ $d->selisih }}</span>
                                        @else
                                            <span class="badge badge-danger">{{ $d->selisih }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $d->keterangan ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('stok-opname.index') }}" class="btn btn-default">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection