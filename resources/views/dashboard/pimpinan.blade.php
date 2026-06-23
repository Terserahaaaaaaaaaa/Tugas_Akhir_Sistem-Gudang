@extends('layouts.app')

@section('content')

<div class="container-fluid px-3 px-lg-4 py-4">

    <div class="page-heading">
        <div class="page-heading-copy">
            <div>
                <h1 class="h3 mb-1">Dashboard Pimpinan</h1>
                <p class="text-muted mb-0">
                    Ringkasan kondisi dan aktivitas gudang perusahaan.
                </p>
            </div>
        </div>
    </div>

    <section class="row g-3 mt-1">

        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-primary">

                <div class="metric-top">
                    <span class="metric-label">
                        Total Barang
                    </span>

                    <span class="metric-icon">
                        <i class="bi bi-box-seam"></i>
                    </span>
                </div>

                <div class="metric-value">
                    {{ $totalBarang }}
                </div>

                <div class="metric-meta">
                    <span>Data Barang Gudang</span>
                </div>

            </article>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-success">

                <div class="metric-top">
                    <span class="metric-label">
                        Pengajuan PO
                    </span>

                    <span class="metric-icon">
                        <i class="bi bi-file-earmark-text"></i>
                    </span>
                </div>

                <div class="metric-value">
                    {{ $totalPo }}
                </div>

                <div class="metric-meta">
                    <span>Total Pengajuan PO</span>
                </div>

            </article>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-warning">

                <div class="metric-top">
                    <span class="metric-label">
                        Barang Masuk
                    </span>

                    <span class="metric-icon">
                        <i class="bi bi-box-arrow-in-down"></i>
                    </span>
                </div>

                <div class="metric-value">
                    {{ $totalBarangMasuk }}
                </div>

                <div class="metric-meta">
                    <span>Total Transaksi Barang Masuk</span>
                </div>

            </article>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-danger">

                <div class="metric-top">
                    <span class="metric-label">
                        Barang Keluar
                    </span>

                    <span class="metric-icon">
                        <i class="bi bi-box-arrow-right"></i>
                    </span>
                </div>

                <div class="metric-value">
                    {{ $totalBarangKeluar }}
                </div>

                <div class="metric-meta">
                    <span>Total Transaksi Barang Keluar</span>
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
                            Ringkasan aktivitas gudang per bulan.
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
                            <i class="bi bi-clipboard-data"></i>
                            <span>Ringkasan Gudang</span>
                        </h2>

                        <p class="text-muted mb-0">
                            Informasi singkat kondisi gudang.
                        </p>
                    </div>

                </div>

                <div class="p-3">

                    <div class="mb-3">
                        <strong>Total Barang :</strong>
                        {{ $totalBarang }}
                    </div>

                    <div class="mb-3">
                        <strong>Total Permintaan :</strong>
                        {{ $totalPermintaan }}
                    </div>

                    <div class="mb-3">
                        <strong>Total Pengajuan PO :</strong>
                        {{ $totalPo }}
                    </div>

                    <div class="mb-3">
                        <strong>Total Barang Masuk :</strong>
                        {{ $totalBarangMasuk }}
                    </div>

                    <div>
                        <strong>Total Barang Keluar :</strong>
                        {{ $totalBarangKeluar }}
                    </div>

                </div>

            </div>

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