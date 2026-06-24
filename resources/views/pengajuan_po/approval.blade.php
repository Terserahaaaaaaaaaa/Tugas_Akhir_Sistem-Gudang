@extends('layouts.app')
@section('title', 'Approval PO')
@section('content')
<div class="content-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h3 class="mb-1">Detail Approval PO</h3>
        <p class="text-muted mb-0">
            Informasi detail pengajuan purchase order.
        </p>
    </div>

    <a href="{{ route('pengajuan-po.index') }}"
       class="btn btn-secondary">

        <i class="bi bi-arrow-left"></i> Kembali

    </a>

</div>

    <section class="content">
        <div class="container-fluid">
            <form action="{{ route('pengajuan-po.simpan-approval', $pengajuanPo->id) }}" method="POST">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Tinjau PO</h3>
                        <div class="card-tools">
                            <span class="text-muted small">
                                {{ \Carbon\Carbon::parse($pengajuanPo->tanggal_po)->translatedFormat('d F Y') }} &middot;
                                {{ ucfirst($pengajuanPo->metode_pembelian) }} &middot;
                                {{ $pengajuanPo->sumber_po ?? '-' }} &middot;
                                Diajukan oleh: {{ $pengajuanPo->diajukan->name ?? '-' }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th><th>Nama Barang</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-right">Harga Estimasi</th>
                                        <th class="text-right">Subtotal</th>
                                        <th>Keputusan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pengajuanPo->detail as $i => $detail)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $detail->barang->nama_barang }}</td>
                                        <td class="text-center">{{ $detail->qty }} {{ $detail->barang->satuan }}</td>
                                        <td class="text-right">Rp{{ number_format($detail->harga_estimasi, 0, ',', '.') }}</td>
                                        <td class="text-right subtotal-ap" data-subtotal="{{ $detail->subtotal }}">
                                            Rp{{ number_format($detail->subtotal, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            <div class="d-flex" style="gap:4px;">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <input type="radio" name="status_item[{{ $detail->id }}]" value="disetujui"
                                                           id="ap_setuju_{{ $detail->id }}"
                                                           class="d-none ap-radio"
                                                           data-subtotal="{{ $detail->subtotal }}"
                                                           onchange="hitungApTotal()" required>
                                                    <label for="ap_setuju_{{ $detail->id }}" class="btn btn-outline-success btn-sm ap-label">Setujui</label>

                                                    <input type="radio" name="status_item[{{ $detail->id }}]" value="ditolak"
                                                           id="ap_tolak_{{ $detail->id }}"
                                                           class="d-none ap-radio"
                                                           data-subtotal="0"
                                                           onchange="hitungApTotal()">
                                                    <label for="ap_tolak_{{ $detail->id }}" class="btn btn-outline-danger btn-sm ap-label">Tolak</label>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4" class="text-right">Total yang Disetujui</th>
                                        <th class="text-right" id="ap-grand-total">Rp0</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('pengajuan-po.show', $pengajuanPo->id) }}" class="btn btn-default">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Keputusan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>
<script>
function hitungApTotal() {
    let total = 0;
    document.querySelectorAll('.ap-radio:checked').forEach(radio => {
        if (radio.value === 'disetujui') {
            total += Number(radio.dataset.subtotal) || 0;
        }
    });
    document.getElementById('ap-grand-total').textContent = 'Rp' + total.toLocaleString('id-ID');
}
// Style label aktif
document.querySelectorAll('.ap-radio').forEach(radio => {
    radio.addEventListener('change', function () {
        const group = this.closest('.btn-group');
        group.querySelectorAll('.ap-label').forEach(l => l.classList.remove('active'));
        this.nextElementSibling.classList.add('active');
    });
});
</script>
@endsection