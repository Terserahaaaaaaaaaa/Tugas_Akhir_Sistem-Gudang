@extends('layouts.app')
@section('title', 'Catat Barang Keluar')
@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6"><h1>Catat Barang Keluar</h1></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('barang-keluar.index') }}">Barang Keluar</a></li>
                        <li class="breadcrumb-item active">Catat</li>
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

            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                Input <strong>qty yang diminta divisi</strong>. Sistem akan otomatis mengeluarkan sebanyak stok yang tersedia.
                Jika ada kekurangan, permintaan pengadaan akan otomatis dikirim ke admin.
            </div>

            <form action="{{ route('barang-keluar.store') }}" method="POST">
                @csrf
                <div class="card">
                    <div class="card-header"><h3 class="card-title">Form Barang Keluar</h3></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Divisi Peminta</label>
                                    <input type="text" name="divisi_tujuan"
                                           class="form-control @error('divisi_tujuan') is-invalid @enderror"
                                           value="{{ old('divisi_tujuan') }}"
                                           placeholder="Contoh: Produksi A">
                                    @error('divisi_tujuan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tanggal</label>
                                    <input type="date" name="tanggal_keluar"
                                           class="form-control @error('tanggal_keluar') is-invalid @enderror"
                                           value="{{ old('tanggal_keluar', date('Y-m-d')) }}">
                                    @error('tanggal_keluar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Keterangan <small class="text-muted">(opsional)</small></label>
                                    <input type="text" name="keterangan" class="form-control"
                                           value="{{ old('keterangan') }}" placeholder="Catatan...">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <h5>Daftar Barang</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Barang</th>
                                        <th style="width:110px;">Qty Diminta</th>
                                        <th style="width:130px;">Stok Tersedia</th>
                                        <th style="width:130px;">Qty Keluar</th>
                                        <th style="width:130px;">Qty Kurang</th>
                                        <th style="width:40px;"></th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-keluar">
                                    <tr class="baris-keluar">
                                        <td>
                                            <select name="barang_id[]" class="form-control select-barang" required
                                                    onchange="updateInfo(this)">
                                                <option value="">-- Pilih Barang --</option>
                                                @foreach($barang as $b)
                                                    <option value="{{ $b->id }}"
                                                            data-stok="{{ $b->stok }}"
                                                            data-satuan="{{ $b->satuan }}">
                                                        {{ $b->nama_barang }}
                                                        (Stok: {{ $b->stok }} {{ $b->satuan }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="qty[]"
                                                   class="form-control qty-input"
                                                   min="1" value="1" required
                                                   oninput="hitungBaris(this)">
                                        </td>
                                        <td class="stok-tersedia text-center align-middle">
                                            <span class="badge badge-secondary">–</span>
                                        </td>
                                        <td class="qty-keluar text-center align-middle font-weight-bold text-success">–</td>
                                        <td class="qty-kurang text-center align-middle font-weight-bold">–</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-danger btn-hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <button type="button" id="btn-tambah" class="btn btn-sm btn-secondary">
                            <i class="fas fa-plus"></i> Tambah Barang
                        </button>

                        {{-- Ringkasan --}}
                        <div id="ringkasan" class="mt-4" style="display:none;">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="info-box bg-success">
                                        <span class="info-box-icon"><i class="fas fa-check"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Total Keluar</span>
                                            <span class="info-box-number" id="total-keluar">0 item</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-box bg-warning">
                                        <span class="info-box-icon"><i class="fas fa-exclamation"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Total Kurang (dikirim ke admin)</span>
                                            <span class="info-box-number" id="total-kurang">0 item</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan & Proses
                        </button>
                        <a href="{{ route('barang-keluar.index') }}" class="btn btn-default">Batal</a>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>

<script>
const dataBarang = @json($barang);

function getStok(select) {
    const opt = select.options[select.selectedIndex];
    return { stok: parseInt(opt.dataset.stok) || 0, satuan: opt.dataset.satuan || '' };
}

function updateInfo(select) {
    const row   = select.closest('tr');
    const { stok, satuan } = getStok(select);
    const stokEl = row.querySelector('.stok-tersedia');

    if (!select.value) {
        stokEl.innerHTML = '<span class="badge badge-secondary">–</span>';
        row.querySelector('.qty-keluar').textContent = '–';
        row.querySelector('.qty-kurang').textContent = '–';
        return;
    }

    if (stok > 0) {
        stokEl.innerHTML = `<span class="badge badge-success">${stok} ${satuan}</span>`;
    } else {
        stokEl.innerHTML = `<span class="badge badge-danger">Habis</span>`;
    }

    hitungBaris(row.querySelector('.qty-input'));
}

function hitungBaris(input) {
    const row    = input.closest('tr');
    const select = row.querySelector('select');
    if (!select.value) return;

    const { stok, satuan } = getStok(select);
    const qty      = parseInt(input.value) || 0;
    const keluar   = Math.min(qty, stok);
    const kurang   = qty - keluar;

    const keluarEl = row.querySelector('.qty-keluar');
    const kurangEl = row.querySelector('.qty-kurang');

    keluarEl.textContent = keluar + ' ' + satuan;
    keluarEl.style.color = keluar > 0 ? 'green' : 'inherit';

    if (kurang > 0) {
        kurangEl.innerHTML = `<span class="text-danger font-weight-bold">${kurang} ${satuan}</span>`;
    } else {
        kurangEl.innerHTML = `<span class="text-success">–</span>`;
    }

    updateRingkasan();
}

function updateRingkasan() {
    let totalKeluar = 0, totalKurang = 0;
    document.querySelectorAll('.baris-keluar').forEach(row => {
        const select = row.querySelector('select');
        if (!select.value) return;
        const { stok } = getStok(select);
        const qty = parseInt(row.querySelector('.qty-input').value) || 0;
        totalKeluar += Math.min(qty, stok);
        totalKurang += Math.max(0, qty - stok);
    });

    document.getElementById('ringkasan').style.display = 'block';
    document.getElementById('total-keluar').textContent = totalKeluar + ' item';
    document.getElementById('total-kurang').textContent = totalKurang + ' item';
}

function buildOptions() {
    return `<option value="">-- Pilih Barang --</option>` +
        dataBarang.map(b => `
            <option value="${b.id}"
                    data-stok="${b.stok}"
                    data-satuan="${b.satuan ?? ''}">
                ${b.nama_barang}
            </option>
        `).join('');
}

document.getElementById('btn-tambah').addEventListener('click', function () {
    const baris = document.querySelector('.baris-keluar').cloneNode(true);
    baris.querySelector('select').innerHTML = buildOptions();
    baris.querySelector('select').onchange = function () { updateInfo(this); };
    baris.querySelector('.qty-input').value = 1;
    baris.querySelector('.qty-input').oninput = function () { hitungBaris(this); };
    baris.querySelector('.stok-tersedia').innerHTML = '<span class="badge badge-secondary">–</span>';
    baris.querySelector('.qty-keluar').textContent = '–';
    baris.querySelector('.qty-kurang').textContent = '–';
    document.getElementById('tbody-keluar').appendChild(baris);
    bindHapus();
});

function bindHapus() {
    document.querySelectorAll('.btn-hapus').forEach(btn => {
        btn.onclick = function () {
            if (document.querySelectorAll('.baris-keluar').length > 1) {
                btn.closest('tr').remove();
                updateRingkasan();
            }
        };
    });
}
bindHapus();
</script>
@endsection




{{-- @extends('layouts.app')

@section('content')

<br><br>

<h3>Tambah Barang Keluar</h3>

<div class="card border-0 shadow-sm">
    <div class="card-body">

        <form action="{{ route('barang-keluar.store') }}"
              method="POST">

            @csrf

            <div class="mb-3">
                <label>Divisi Tujuan</label>

                <input type="text"
                       name="divisi_tujuan"
                       class="form-control"
                       required>
            </div>

            <div class="mb-3">
                <label>Keterangan</label>

                <textarea name="keterangan"
                          class="form-control"></textarea>
            </div>

            <hr>

            <h5>Daftar Barang</h5>

            <table class="table" id="tableBarang">

                <thead>
                    <tr>
                        <th>Barang</th>
                        <th>Qty</th>
                    </tr>
                </thead>

                <tbody>

                    <tr>

                        <td>
                            <select name="barang_id[]"
                                    class="form-control"
                                    required>

                                <option value="">
                                    Pilih Barang
                                </option>

                                @foreach($barang as $item)

                                <option value="{{ $item->id }}">
                                    {{ $item->nama_barang }}
                                    (stok : {{ $item->stok }})
                                </option>

                                @endforeach

                            </select>
                        </td>

                        <td>
                            <input type="number"
                                   name="qty[]"
                                   min="1"
                                   class="form-control"
                                   required>
                        </td>

                    </tr>

                </tbody>

            </table>

            <button type="button"
                    class="btn btn-secondary mb-3"
                    onclick="tambahBaris()">
                Tambah Barang
            </button>

            <br>

            <button type="submit"
                    class="btn btn-primary">
                Simpan
            </button>

        </form>

    </div>
</div>

<script>

function tambahBaris()
{
    let row = document.querySelector('#tableBarang tbody tr').cloneNode(true);

    row.querySelectorAll('input').forEach(i => i.value = '');

    row.querySelector('select').selectedIndex = 0;

    document.querySelector('#tableBarang tbody').appendChild(row);
}

</script>

@endsection --}}