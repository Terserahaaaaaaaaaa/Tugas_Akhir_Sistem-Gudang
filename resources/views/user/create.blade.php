@extends('layouts.app')

@section('content')

<br><br>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">Tambah User</h3>
        <p class="text-muted mb-0">
            Buat akun pimpinan baru.
        </p>
    </div>

    <a href="{{ route('user.index') }}"
       class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i>
        Kembali
    </a>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body">

        <form action="{{ route('user.store') }}"
              method="POST">

            @csrf

            <div class="mb-3">
                <label class="form-label">
                    Nama Lengkap
                </label>

                <input type="text"
                       name="name"
                       class="form-control"
                       value="{{ old('name') }}"
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label">
                    Email
                </label>

                <input type="email"
                       name="email"
                       class="form-control"
                       value="{{ old('email') }}"
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label">
                    Password
                </label>

                <input type="password"
                       name="password"
                       class="form-control"
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label">
                    Konfirmasi Password
                </label>

                <input type="password"
                       name="password_confirmation"
                       class="form-control"
                       required>
            </div>

            <div class="mb-4">
                <label class="form-label">
                    Role
                </label>

                <select name="role"
                        class="form-control"
                        required>

                    <option value="pimpinan">
                        Pimpinan
                    </option>

                </select>
            </div>

            <button type="submit"
                    class="btn btn-primary">
                <i class="bi bi-save"></i>
                Simpan
            </button>

        </form>

    </div>
</div>

@endsection