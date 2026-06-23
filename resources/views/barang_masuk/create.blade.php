@extends('layouts.app')
@section('title', 'Konfirmasi Barang Masuk')
@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6"><h1>Konfirmasi Barang Masuk</h1></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('barang-masuk.index') }}">Barang Masuk</a></li>
                        <li class="breadcrumb-item active">Konfirmasi</li>
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

            @if($po->isEmpty())
                <div class="alert alert-info">
                    Belum ada PO yang siap dikonfirmasi. PO harus sudah disetujui keuangan terlebih dahulu.
                </div>
            @else
            <form action="{{ route('barang-masuk.store') }}" method="POST" id="form-masuk">
                @csrf
                <div class="card">
                    <div class="card-header"><h3 class="card-title">Form Konfirmasi Barang Masuk</h3></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Pilih PO</label>
                                    <select name="pengajuan_po_id" id="pilih-po"
                                            class="form-control @error('pengajuan_po_id') is-invalid @enderror"
                                            onchange="loadDetailPo(this.value)" required>
                                        <option value="">-- Pilih PO --</option>
                                        @foreach($po as $p)
                                            <option value="{{ $p->id }}"
                                                data-detail="{{ json_encode($p->detail->map(fn($d)=>['id'=>$d->id,'nama'=>$d->barang->nama_barang,'satuan'=>$d->barang->satuan,'barang_id'=>$d->barang_id,'qty'=>$d->qty,'harga_estimasi'=>$d->harga_estimasi])) }}">
                                                PO #{{ $p->id }} · {{ $p->sumber_po ?? 'Tanpa supplier' }} · {{ \Carbon\Carbon::parse($p->tanggal_po)->translatedFormat('d M Y') }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('pengajuan_po_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tanggal Masuk</label>
                                    <input type="date" name="tanggal_masuk"
                                           class="form-control @error('tanggal_masuk') is-invalid @enderror"
                                           value="{{ old('tanggal_masuk', date('Y-m-d')) }}" required>
                                    @error('tanggal_masuk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Keterangan <small class="text-muted">(opsional)</small></label>
                                    <input type="text" name="keterangan" class="form-control"
                                           value="{{ old('keterangan') }}" placeholder="Catatan penerimaan...">
                                </div>
                            </div>
                        </div>

                        <div id="detail-po-wrapper" style="display:none;">
                            <hr>
                            <h5>Detail Penerimaan Barang</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Barang</th>
                                            <th class="text-center">Qty di PO</th>
                                            <th style="width:90px;">Qty Diterima</th>
                                            <th style="width:130px;">Harga PO</th>
                                            <th style="width:140px;">Harga Aktual</th>
                                            <th style="width:130px;">Subtotal</th>
                                            <th>Status Harga</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody-masuk"></tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="5" class="text-right">Total</th>
                                            <th id="bm-total">Rp0</th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan & Update Stok</button>
                        <a href="{{ route('barang-masuk.index') }}" class="btn btn-default">Batal</a>
                    </div>
                </div>
            </form>
            @endif
        </div>
    </section>
</div>
<script>
function loadDetailPo(poId) {
    const select = document.getElementById('pilih-po');
    const opt = select.options[select.selectedIndex];
    if (!poId) { document.getElementById('detail-po-wrapper').style.display = 'none'; return; }
    const detail = JSON.parse(opt.dataset.detail || '[]');
    let rows = '';
    detail.forEach((d, i) => {
        rows += `<tr id="bm-row-${i}">
            <td>${d.nama}
                <input type="hidden" name="pengajuan_po_detail_id[]" value="${d.id}">
                <input type="hidden" name="barang_id[]" value="${d.barang_id}">
            </td>
            <td class="text-center">${d.qty} ${d.satuan}</td>
            <td><input type="number" name="qty[]" class="form-control bm-qty" min="1" max="${d.qty}" value="${d.qty}" oninput="hitungBm(${i},${d.harga_estimasi})" required></td>
            <td class="text-muted">Rp${Number(d.harga_estimasi).toLocaleString('id-ID')}</td>
            <td><input type="number" name="harga_beli[]" class="form-control bm-harga" min="0" value="${d.harga_estimasi}" oninput="hitungBm(${i},${d.harga_estimasi})" required></td>
            <td class="bm-sub font-weight-bold">Rp${(d.qty*d.harga_estimasi).toLocaleString('id-ID')}</td>
            <td class="bm-status-harga"></td>
        </tr>`;
    });
    document.getElementById('tbody-masuk').innerHTML = rows;
    document.getElementById('detail-po-wrapper').style.display = 'block';
    detail.forEach((d, i) => hitungBm(i, d.harga_estimasi));
}

function hitungBm(i, hargaPo) {
    const row = document.getElementById('bm-row-'+i);
    const qty = Number(row.querySelector('.bm-qty').value) || 0;
    const harga = Number(row.querySelector('.bm-harga').value) || 0;
    const subtotal = qty * harga;
    row.querySelector('.bm-sub').textContent = 'Rp' + subtotal.toLocaleString('id-ID');
    const persen = hargaPo > 0 ? ((harga - hargaPo) / hargaPo) * 100 : 0;
    const statusEl = row.querySelector('.bm-status-harga');
    if (Math.abs(persen) < 0.01) {
        statusEl.innerHTML = '<span class="badge badge-secondary">Sama</span>';
    } else if (persen > 10) {
        statusEl.innerHTML = `<span class="badge badge-danger">Naik ${persen.toFixed(1)}% ⚠</span>`;
    } else if (persen > 0) {
        statusEl.innerHTML = `<span class="badge badge-warning">Naik ${persen.toFixed(1)}%</span>`;
    } else {
        statusEl.innerHTML = `<span class="badge badge-success">Turun ${Math.abs(persen).toFixed(1)}%</span>`;
    }
    hitungBmTotal();
}

function hitungBmTotal() {
    let total = 0;
    document.querySelectorAll('.bm-sub').forEach(el => {
        total += parseInt(el.textContent.replace(/[^0-9]/g, '')) || 0;
    });
    document.getElementById('bm-total').textContent = 'Rp' + total.toLocaleString('id-ID');
}
</script>
@endsection