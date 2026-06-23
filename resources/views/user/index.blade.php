@extends('layouts.app')

@section('content')

<br><br>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">Data User</h3>
        <p class="text-muted mb-0">Kelola data pengguna sistem.</p>
    </div>

    <a href="{{ route('user.create') }}"
       class="btn btn-primary">
        <i class="bi bi-plus-circle"></i>
        Tambah User
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">

            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th width="220">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($users as $user)
                    <tr>

                        <td>{{ $loop->iteration }}</td>

                        <td>{{ $user->name }}</td>

                        <td>{{ $user->email }}</td>

                        <td>
                            <span class="badge bg-primary">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>

                        <td>
                            @if($user->status_akun == 'disetujui')
                                <span class="badge bg-success">
                                    Disetujui
                                </span>

                            @elseif($user->status_akun == 'pending')
                                <span class="badge bg-warning text-dark">
                                    Pending
                                </span>

                            @else
                                <span class="badge bg-danger">
                                    Ditolak
                                </span>
                            @endif
                        </td>

                        <td>

                            {{-- Detail --}}
                            <a href="{{ route('user.show', $user->id) }}"
                            class="btn btn-info btn-sm"
                            title="Detail">
                                <i class="bi bi-eye-fill"></i>
                            </a>

                            {{-- Tombol Approval --}}
                            @if($user->status_akun == 'pending')

                            <form action="{{ route('user.setujui', $user->id) }}"
                                method="POST"
                                class="d-inline">
                                @csrf
                                @method('PATCH')

                                <button class="btn btn-success btn-sm"
                                        title="Setujui">
                                    <i class="bi bi-check-circle-fill"></i>
                                </button>
                            </form>

                            <form action="{{ route('user.tolak', $user->id) }}"
                                method="POST"
                                class="d-inline">
                                @csrf
                                @method('PATCH')

                                <button class="btn btn-warning btn-sm"
                                        title="Tolak">
                                    <i class="bi bi-x-circle-fill"></i>
                                </button>
                            </form>

                            @endif

                            {{-- Hapus --}}
                            <form action="{{ route('user.destroy', $user->id) }}"
                                method="POST"
                                class="d-inline"
                                onsubmit="return confirm('Yakin ingin menghapus user ini?')">

                                @csrf
                                @method('DELETE')

                                <button class="btn btn-danger btn-sm"
                                        title="Hapus">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            Data user belum ada.
                        </td>
                    </tr>
                    @endforelse

                </tbody>

            </table>

        </div>
    </div>
</div>

@endsection