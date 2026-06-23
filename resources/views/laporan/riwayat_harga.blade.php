@extends('layouts.app')

@section('title','Laporan Riwayat Harga')

@section('content')
<div class="content-wrapper">

<div class="content-header">
    <div class="container-fluid">
        <h1>Laporan Riwayat Harga</h1>
    </div>
</div>

<section class="content">
<div class="container-fluid">

<div class="card">
<div class="card-body">

<table class="table table-bordered">

<thead>
<tr>
    <th>No</th>
    <th>Barang</th>
    <th>Harga Lama</th>
    <th>Harga Baru</th>
    <th>Tanggal Perubahan</th>
</tr>
</thead>

<tbody>

@foreach($riwayatHarga as $item)
<tr>
    <td>{{ $loop->iteration }}</td>
    <td>{{ $item->barang->nama_barang }}</td>
    <td>Rp {{ number_format($item->harga_lama,0,',','.') }}</td>
    <td>Rp {{ number_format($item->harga_baru,0,',','.') }}</td>
    <td>{{ $item->tanggal_perubahan }}</td>
</tr>
@endforeach

</tbody>

</table>

</div>
</div>

</div>
</section>

</div>
@endsection