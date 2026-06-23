@extends('layouts.app')

@section('title', 'Edit Kategori Barang')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Kategori Barang</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('kategori-barang.index') }}">Kategori Barang</a></li>
                        <li class="breadcrumb-item active">Edit</li>
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

            <form action="{{ route('kategori-barang.update', $kategori->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Form Kategori Barang</h3>
                    </div>

                    <div class="card-body">
                        <div class="form-group">
                            <label>No Akun</label>
                            <input type="text" name="no_akun"
                                   class="form-control @error('no_akun') is-invalid @enderror"
                                   value="{{ old('no_akun', $kategori->no_akun) }}">
                            @error('no_akun')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Nama Akun</label>
                            <input type="text" name="nama_akun"
                                   class="form-control @error('nama_akun') is-invalid @enderror"
                                   value="{{ old('nama_akun', $kategori->nama_akun) }}">
                            @error('nama_akun')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update
                        </button>
                        <a href="{{ route('kategori-barang.index') }}" class="btn btn-default">
                            Batal
                        </a>
                    </div>
                </div>
            </form>

        </div>
    </section>
</div>
@endsection