<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register Sistem Gudang</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f3f4f6;
            font-family: Arial, sans-serif;
        }

        .login-wrapper {
            min-height: 100vh;
        }

        .login-left {
            width: 40%;
            background: linear-gradient(180deg, #1B4332, #2D6A4F);
            color: white;
            padding: 50px;
        }

        .login-right {
            width: 60%;
            background: #ffffff;
        }

        .logo-box img {
            width: 140px;
            height: 140px;
            object-fit: contain;
            background: white;
            border-radius: 20px;
            padding: 15px;
        }

        .company-title {
            font-size: 34px;
            font-weight: 700;
            line-height: 1.4;
        }

        .company-subtitle {
            color: #d8f3dc;
            font-size: 18px;
            font-weight: 600;
        }

        .login-card {
            width: 430px;
        }

        .login-title {
            font-size: 36px;
            font-weight: 700;
            color: #1B4332;
        }

        .form-control {
            height: 52px;
            border-radius: 12px;
        }

        .form-label {
            font-weight: 600;
            color: #1B4332;
        }

        .btn-login {
            height: 52px;
            border: none;
            border-radius: 12px;
            background: #2D6A4F;
            color: white;
            font-weight: 700;
        }

        .btn-login:hover {
            background: #1B4332;
            color: white;
        }

        .register-link a {
            color: #2D6A4F;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .login-wrapper {
                flex-direction: column;
            }

            .login-left,
            .login-right {
                width: 100%;
            }

            .login-left {
                min-height: 280px;
            }

            .login-right {
                padding: 40px 20px;
            }
        }
    </style>
</head>

<body>

<div class="d-flex login-wrapper">

    <div class="login-left d-flex flex-column justify-content-center align-items-center text-center">

        <h1 class="company-title">
            SISTEM INFORMASI<br>
            PENGELOLAAN LOGISTIK<br>
            GUDANG
        </h1><br>

        <div class="logo-box mb-4">
            <img src="{{ asset('template/assets/img/logo-perusahaan.png') }}" alt="Logo Perusahaan">
        </div>

        <p class="company-subtitle mt-4">
            PT Muara Kayu Sengon<br>
            Jatilawang
        </p>
    </div>

    <div class="login-right d-flex justify-content-center align-items-center">
        <div class="login-card">

            <h2 class="login-title mb-2">Register</h2>
            <p class="text-muted mb-4">
                Daftarkan akun untuk mengakses sistem
            </p>

            @if ($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text"
                           name="name"
                           value="{{ old('name') }}"
                           class="form-control"
                           placeholder="Masukkan Nama"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email"
                           name="email"
                           value="{{ old('email') }}"
                           class="form-control"
                           placeholder="Masukkan Email"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select name="role"
                            class="form-control"
                            required>

                        <option value="">-- Pilih Role --</option>
                        <option value="logistik">Logistik</option>
                        <option value="keuangan">Keuangan</option>

                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password"
                           name="password"
                           class="form-control"
                           placeholder="Masukkan Password"
                           required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password"
                           name="password_confirmation"
                           class="form-control"
                           placeholder="Masukkan Ulang Password"
                           required>
                </div>

                <button type="submit" class="btn btn-login w-100">
                    REGISTER
                </button>

                <div class="text-center mt-4 register-link">
                    Sudah punya akun?
                    <a href="{{ route('login') }}">Login</a>
                </div>

            </form>

        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>


{{-- @extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="role" class="col-md-4 col-form-label text-md-end">
                                Role
                            </label>

                            <div class="col-md-6">
                                <select name="role"
                                        id="role"
                                        class="form-control @error('role') is-invalid @enderror"
                                        required>

                                    <option value="">-- Pilih Role --</option>
                                    <option value="logistik">Logistik</option>
                                    <option value="keuangan">Keuangan</option>

                                </select>

                                @error('role')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection --}}
