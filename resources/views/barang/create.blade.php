@extends('layouts.app')

@section('title', 'Tambah Barang')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Tambah Barang</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('barang.index') }}">Data Barang</a></li>
                        <li class="breadcrumb-item active">Tambah</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Periksa kembali isian berikut:</strong>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('barang.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Form Data Barang</h3>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group text-center">
                                    <label>Foto Barang</label>
                                    <div>
                                        <img id="preview-foto"
                                             src="https://via.placeholder.com/150x150?text=No+Image"
                                             class="img-thumbnail mb-2"
                                             style="width: 150px; height: 150px; object-fit: cover;">
                                    </div>
                                    <input type="file" name="foto" id="foto"
                                           class="form-control-file @error('foto') is-invalid @enderror"
                                           accept="image/png, image/jpeg, image/jpg"
                                           onchange="previewFoto(event)">
                                    @error('foto')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Format JPG/PNG, maks 2MB.</small>
                                </div>
                            </div>

                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Kode Barang</label>
                                            <input type="text" name="kode_barang"
                                                   class="form-control @error('kode_barang') is-invalid @enderror"
                                                   value="{{ old('kode_barang', $kodeBarang) }}" readonly>
                                            @error('kode_barang')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Kategori</label>
                                            <select name="kategori_barang_id"
                                                    class="form-control @error('kategori_barang_id') is-invalid @enderror">
                                                <option value="">-- Pilih Kategori --</option>
                                                @foreach ($kategori as $k)
                                                    <option value="{{ $k->id }}"
                                                        {{ old('kategori_barang_id') == $k->id ? 'selected' : '' }}>
                                                        {{ $k->nama_akun }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('kategori_barang_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Nama Barang</label>
                                    <input type="text" name="nama_barang"
                                           class="form-control @error('nama_barang') is-invalid @enderror"
                                           value="{{ old('nama_barang') }}"
                                           placeholder="Contoh: Sarung tangan kerja">
                                    @error('nama_barang')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Satuan</label>
                                            <input type="text" name="satuan"
                                                   class="form-control @error('satuan') is-invalid @enderror"
                                                   value="{{ old('satuan') }}"
                                                   placeholder="pcs, box, dll">
                                            @error('satuan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Stok Awal</label>
                                            <input type="number" name="stok" min="0"
                                                   class="form-control @error('stok') is-invalid @enderror"
                                                   value="{{ old('stok', 0) }}">
                                            @error('stok')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Stok Minimum</label>
                                            <input type="number" name="stok_minimum" min="1"
                                                   class="form-control @error('stok_minimum') is-invalid @enderror"
                                                   value="{{ old('stok_minimum', 1) }}">
                                            @error('stok_minimum')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Harga Terakhir</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="number" name="harga_terakhir" min="0" step="100"
                                               class="form-control @error('harga_terakhir') is-invalid @enderror"
                                               value="{{ old('harga_terakhir') }}">
                                        @error('harga_terakhir')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                        <a href="{{ route('barang.index') }}" class="btn btn-default">
                            Batal
                        </a>
                    </div>
                </div>
            </form>

        </div>
    </section>
</div>

<script>
    function previewFoto(event) {
        const reader = new FileReader();
        reader.onload = function () {
            document.getElementById('preview-foto').src = reader.result;
        };
        if (event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    }
</script>
@endsection