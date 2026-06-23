@extends('layouts.app')

@section('content')

<br><br>

<h3>Daftar Laporan</h3>

<div class="list-group">

    <a href="{{ route('laporan.barang-masuk') }}"
       class="list-group-item list-group-item-action">
        Laporan Barang Masuk
    </a>

    <a href="{{ route('laporan.barang-keluar') }}"
       class="list-group-item list-group-item-action">
        Laporan Barang Keluar
    </a>

    <a href="{{ route('laporan.stok-barang') }}"
       class="list-group-item list-group-item-action">
        Laporan Stok Barang
    </a>

    <a href="{{ route('laporan.pengajuan-po') }}"
       class="list-group-item list-group-item-action">
        Laporan Pengajuan PO
    </a>

    <a href="{{ route('laporan.riwayat-harga') }}"
       class="list-group-item list-group-item-action">
        Laporan Riwayat Harga
    </a>

</div>

@endsection