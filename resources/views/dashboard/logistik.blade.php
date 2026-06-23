@extends('layouts.app')

@section('content')

<div class="container-fluid px-3 px-lg-4 py-4">

    <div class="page-heading">
        <div class="page-heading-copy">
            <div>
                <h1 class="h3 mb-1">Dashboard Logistik</h1>
                <p class="text-muted mb-0">
                    Monitoring aktivitas permintaan barang dan pergerakan stok gudang.
                </p>
            </div>
        </div>
    </div>

    <section class="row g-3 mt-1">

        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-primary">
                <div class="metric-top">
                    <span class="metric-label">Permintaan Barang</span>
                    <span class="metric-icon">
                        <i class="bi bi-clipboard-check"></i>
                    </span>
                </div>

                <div class="metric-value">
                    {{ $totalPermintaan }}
                </div>

                <div class="metric-meta">
                    <span>Total Permintaan Barang</span>
                </div>
            </article>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-success">
                <div class="metric-top">
                    <span class="metric-label">Barang Masuk</span>
                    <span class="metric-icon">
                        <i class="bi bi-box-arrow-in-down"></i>
                    </span>
                </div>

                <div class="metric-value">
                    {{ $totalBarangMasuk }}
                </div>

                <div class="metric-meta">
                    <span>Total Barang Masuk</span>
                </div>
            </article>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-warning">
                <div class="metric-top">
                    <span class="metric-label">Barang Keluar</span>
                    <span class="metric-icon">
                        <i class="bi bi-box-arrow-right"></i>
                    </span>
                </div>

                <div class="metric-value">
                    {{ $totalBarangKeluar }}
                </div>

                <div class="metric-meta">
                    <span>Total Barang Keluar</span>
                </div>
            </article>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-danger">
                <div class="metric-top">
                    <span class="metric-label">Belum Terpenuhi</span>
                    <span class="metric-icon">
                        <i class="bi bi-hourglass-split"></i>
                    </span>
                </div>

                <div class="metric-value">
                    {{ $permintaanBelumTerpenuhi }}
                </div>

                <div class="metric-meta">
                    <span>Permintaan Belum Terpenuhi</span>
                </div>
            </article>
        </div>

    </section>

    <section class="row g-3 mt-1">

        <div class="col-12 col-xl-8">

            <div class="panel">

                <div class="panel-header">

                    <div>
                        <h2 class="h5 mb-1 section-title">
                            <i class="bi bi-bar-chart-line"></i>
                            <span>Perbandingan Barang Masuk & Barang Keluar</span>
                        </h2>

                        <p class="text-muted mb-0">
                            Jumlah transaksi barang masuk dan keluar setiap bulan.
                        </p>
                    </div>

                </div>

                <div style="height:350px;">
                    <canvas id="grafikGudang"></canvas>
                </div>

            </div>

        </div>

        <div class="col-12 col-xl-4">

            <div class="panel h-100">

                <div class="panel-header">

                    <div>
                        <h2 class="h5 mb-1 section-title">
                            <i class="bi bi-clipboard-check"></i>
                            <span>Permintaan Terbaru</span>
                        </h2>

                        <p class="text-muted mb-0">
                            Permintaan barang terbaru dari divisi.
                        </p>
                    </div>

                </div>

                <div class="activity-list">

                    @forelse($permintaanTerbaru as $item)

                        <div class="activity-item">

                            <span class="activity-dot bg-primary"></span>

                            <div>

                                <p class="mb-1 fw-semibold">
                                    {{ $item->no_permintaan }}
                                </p>

                                <p class="text-muted small mb-0">
                                    {{ $item->divisi }}
                                </p>

                            </div>

                        </div>

                    @empty

                        <div class="text-muted">
                            Belum ada permintaan barang.
                        </div>

                    @endforelse

                </div>

            </div>

        </div>

    </section>

    <section class="panel mt-3">

        <div class="panel-header">

            <div>
                <h2 class="h5 mb-1 section-title">
                    <i class="bi bi-clock-history"></i>
                    <span>Aktivitas Terbaru</span>
                </h2>

                <p class="text-muted mb-0">
                    Riwayat aktivitas logistik terbaru.
                </p>
            </div>

        </div>

        <div class="table-responsive">

            <table class="table table-sm align-middle mb-0">

                <thead>
                    <tr>
                        <th>Aktivitas</th>
                        <th>Detail</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($aktivitas as $item)

                    <tr>

                        <td>{{ $item['jenis'] }}</td>

                        <td>{{ $item['detail'] }}</td>

                        <td>
                            {{ \Carbon\Carbon::parse($item['tanggal'])->format('d-m-Y') }}
                        </td>

                        <td>
                            {{ \Carbon\Carbon::parse($item['tanggal'])->format('H:i') }}
                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="4" class="text-center text-muted">
                            Belum ada aktivitas.
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </section>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const ctx = document.getElementById('grafikGudang');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: [
            'Jan','Feb','Mar','Apr','Mei','Jun',
            'Jul','Agu','Sep','Okt','Nov','Des'
        ],
        datasets: [
            {
                label: 'Barang Masuk',
                data: @json($dataMasuk)
            },
            {
                label: 'Barang Keluar',
                data: @json($dataKeluar)
            }
        ]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>

@endsection