@extends('layouts.app')
@section('title', 'Buat PO Baru')
@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6"><h1>Buat PO Baru</h1></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('pengajuan-po.index') }}">Pengajuan PO</a></li>
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

            {{-- Info kalau PO dari permintaan --}}
            @if($permintaan)
                <div class="alert alert-info">
                    <i class="fas fa-link"></i>
                    PO ini dibuat dari <strong>permintaan pengadaan {{ $permintaan->divisi }}</strong>
                    tanggal {{ \Carbon\Carbon::parse($permintaan->tanggal_permintaan)->translatedFormat('d F Y') }}.
                    Daftar barang sudah terisi otomatis — kamu bisa ubah qty dan estimasi harga sebelum mengajukan.
                </div>
            @endif

            <form action="{{ route('pengajuan-po.store') }}" method="POST">
                @csrf

                {{--input hidden (menyimpan data ke db tanpa perlu ditampilkan ke pengguna) untuk permintaan barang--}}
                @if($permintaan)
                    <input type="hidden"
                        name="sumber_po"
                        value="permintaan_barang">
                {{--input hidden untuk stok minimum--}}
                @else
                    <input type="hidden"
                        name="sumber_po"
                        value="stok_minimum">
                @endif

                {{-- Simpan referensi permintaan jika ada --}}
                @if($permintaan)
                    <input type="hidden" name="permintaan_barang_id" value="{{ $permintaan->id }}">
                @endif

                <div class="card">
                    <div class="card-header"><h3 class="card-title">Form Pengajuan PO</h3></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Tanggal PO</label>
                                    <input type="date" name="tanggal_po"
                                           class="form-control @error('tanggal_po') is-invalid @enderror"
                                           value="{{ old('tanggal_po', date('Y-m-d')) }}">
                                    @error('tanggal_po')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Metode Pembelian</label>
                                    <select name="metode_pembelian"
                                            class="form-control @error('metode_pembelian') is-invalid @enderror">
                                        <option value="">-- Pilih --</option>
                                        <option value="whatsapp" {{ old('metode_pembelian')=='whatsapp'?'selected':'' }}>Whatsapp</option>
                                        <option value="online"   {{ old('metode_pembelian')=='online'?'selected':'' }}>Online</option>
                                        <option value="beli_langsung"   {{ old('metode_pembelian')=='beli_langsung'?'selected':'' }}>Beli ke Toko</option>
                                    </select>
                                    @error('metode_pembelian')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            {{-- <div class="col-md-3">
                                <div class="form-group">
                                    <label>Sumber / Supplier <small class="text-muted">(opsional)</small></label>
                                    <input type="text" name="sumber_po" class="form-control"
                                           value="{{ old('sumber_po') }}" placeholder="Nama supplier...">
                                </div>
                            </div> --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Kontak Pembelian <small class="text-muted">(opsional)</small></label>
                                    <input type="text" name="kontak_pembelian" class="form-control"
                                           value="{{ old('kontak_pembelian') }}" placeholder="No. HP / email...">
                                </div>
                            </div>
                        </div>

                        <hr>
                        <h5>Daftar Barang yang Diajukan</h5>

                        <div class="table-responsive">
                            <table class="table table-bordered" id="tabel-po">
                                <thead>
                                    <tr>
                                        <th>Barang</th>
                                        <th style="width:80px;">Qty</th>
                                        <th style="width:150px;">Harga Estimasi (Rp)</th>
                                        <th style="width:130px;">Subtotal</th>
                                        <th style="width:40px;"></th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-po">
                                    @if($permintaan && $permintaan->detail->isNotEmpty())
                                        {{-- Auto-fill dari permintaan --}}
                                        @foreach($permintaan->detail as $d)
                                        <tr class="baris-po">
                                            <td>
                                                <input type="hidden"
                                                    name="permintaan_barang_detail_id[]"
                                                    value="{{ $d->id }}">
                                                    
                                                <select name="barang_id[]" class="form-control" required>
                                                    <option value="">-- Pilih Barang --</option>
                                                    @foreach($barang as $b)
                                                        <option value="{{ $b->id }}"
                                                            {{ $b->id == $d->barang_id ? 'selected' : '' }}>
                                                            {{ $b->nama_barang }} ({{ $b->satuan }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <small class="text-muted">
                                                    <i class="fas fa-info-circle"></i>
                                                    Qty kurang: {{ $d->qty }} {{ $d->barang->satuan }}
                                                </small>
                                            </td>
                                            <td>
                                                <input type="number" name="qty[]"
                                                       class="form-control po-qty" min="1"
                                                       value="{{ $d->qty }}" required
                                                       oninput="hitungSubtotal(this)">
                                            </td>
                                            <td>
                                                <input type="number" name="harga_estimasi[]"
                                                       class="form-control po-harga" min="0"
                                                       value="{{ $d->barang->harga_terakhir }}" required
                                                       oninput="hitungSubtotal(this)">
                                            </td>
                                            <td class="po-sub align-middle font-weight-bold">
                                                Rp{{ number_format($d->qty * $d->barang->harga_terakhir, 0, ',', '.') }}
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-danger btn-hapus-po">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach

                                    {{--untuk auto isi nama barang dll dari barang dinotifikasi stok menipis 
                                        di form pengajuan po jadi ngga perlu input nama barang dll lagi--}}
                                    @elseif($barangStokMinimum)

                                        <tr class="baris-po">
                                            <td>
                                                <select name="barang_id[]" class="form-control" required>
                                                    <option value="{{ $barangStokMinimum->id }}" selected>
                                                        {{ $barangStokMinimum->nama_barang }}
                                                        ({{ $barangStokMinimum->satuan }})
                                                    </option>
                                                </select>

                                                <small class="text-danger">
                                                    Stok: {{ $barangStokMinimum->stok }}
                                                    |
                                                    Minimum: {{ $barangStokMinimum->stok_minimum }}
                                                </small>
                                            </td>

                                            <td>
                                                <input type="number"
                                                    name="qty[]"
                                                    class="form-control po-qty"
                                                    value="{{ $barangStokMinimum->stok_minimum }}"
                                                    min="1"
                                                    {{--untuk hitung subtotal ketika qty dirubah--}}
                                                    oninput="hitungSubtotal(this)">
                                            </td>

                                            <td>
                                                <input type="number"
                                                    name="harga_estimasi[]"
                                                    class="form-control po-harga"
                                                    value="{{ $barangStokMinimum->harga_terakhir }}"
                                                    {{--untuk hitung subtotal ketika qty dirubah--}}
                                                    oninput="hitungSubtotal(this)">
                                            </td>

                                            <td class="po-sub">
                                                Rp0
                                            </td>

                                            <td></td>
                                        </tr>

                                    @else
                                        {{-- Form kosong biasa --}}
                                        <tr class="baris-po">
                                            <td>
                                                <select name="barang_id[]" class="form-control" required>
                                                    <option value="">-- Pilih Barang --</option>
                                                    @foreach($barang as $b)
                                                        <option value="{{ $b->id }}">
                                                            {{ $b->nama_barang }} ({{ $b->satuan }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" name="qty[]"
                                                       class="form-control po-qty" min="1" value="1" required
                                                       oninput="hitungSubtotal(this)">
                                            </td>
                                            <td>
                                                <input type="number" name="harga_estimasi[]"
                                                       class="form-control po-harga" min="0" value="0" required
                                                       oninput="hitungSubtotal(this)">
                                            </td>
                                            <td class="po-sub align-middle font-weight-bold">Rp0</td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-danger btn-hapus-po">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-right font-weight-bold">Total Estimasi</td>
                                        <td class="font-weight-bold" id="po-grand-total">Rp0</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <button type="button" id="btn-tambah-po" class="btn btn-sm btn-secondary">
                            <i class="fas fa-plus"></i> Tambah Barang
                        </button>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('pengajuan-po.index') }}" class="btn btn-default">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Ajukan ke Keuangan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>

<script>
const barangOpts = `<option value="">-- Pilih Barang --</option>` + [
    @foreach($barang as $b)
    `<option value="{{ $b->id }}">{{ $b->nama_barang }} ({{ $b->satuan }})</option>`,
    @endforeach
].join('');

function hitungSubtotal(input) {
    const row    = input.closest('tr');
    const qty    = Number(row.querySelector('.po-qty').value) || 0;
    const harga  = Number(row.querySelector('.po-harga').value) || 0;
    row.querySelector('.po-sub').textContent = 'Rp' + (qty * harga).toLocaleString('id-ID');
    hitungGrandTotal();
}

function hitungGrandTotal() {
    let total = 0;
    document.querySelectorAll('.po-sub').forEach(el => {
        total += parseInt(el.textContent.replace(/[^0-9]/g, '')) || 0;
    });
    document.getElementById('po-grand-total').textContent = 'Rp' + total.toLocaleString('id-ID');
}

document.getElementById('btn-tambah-po').addEventListener('click', function () {
    const baris = document.querySelector('.baris-po').cloneNode(true);
    baris.querySelector('select').innerHTML = barangOpts;
    baris.querySelector('.po-qty').value   = 1;
    baris.querySelector('.po-harga').value = 0;
    baris.querySelector('.po-sub').textContent = 'Rp0';
    // hapus hint qty kurang kalau ada
    const hint = baris.querySelector('small');
    if (hint) hint.remove();
    baris.querySelectorAll('input').forEach(i => {
        i.oninput = function () { hitungSubtotal(this); };
    });
    document.getElementById('tbody-po').appendChild(baris);
    bindHapusPo();
});

function bindHapusPo() {
    document.querySelectorAll('.btn-hapus-po').forEach(btn => {
        btn.onclick = function () {
            if (document.querySelectorAll('.baris-po').length > 1) {
                btn.closest('tr').remove();
                hitungGrandTotal();
            }
        };
    });
}

// Hitung total awal saat halaman dimuat
hitungGrandTotal();
bindHapusPo();

document.querySelectorAll('.po-qty').forEach(function(el){
    hitungSubtotal(el);
});
</script>
@endsection