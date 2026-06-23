@extends('layouts.app')

@section('content')

<div class="container-fluid px-3 px-lg-4 py-4">

    <div class="page-heading">
        <div class="page-heading-copy">
            <div>
                <h1 class="h3 mb-1">Dashboard Keuangan</h1>
                <p class="text-muted mb-0">
                    Monitoring approval pengajuan purchase order.
                </p>
            </div>
        </div>
    </div>

    <section class="row g-3 mt-1">

        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-warning">
                <div class="metric-top">
                    <span class="metric-label">PO Pending</span>
                    <span class="metric-icon">
                        <i class="bi bi-hourglass-split"></i>
                    </span>
                </div>

                <div class="metric-value">
                    {{ $poPending }}
                </div>

                <div class="metric-meta">
                    <span>Menunggu Approval</span>
                </div>
            </article>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-success">
                <div class="metric-top">
                    <span class="metric-label">PO Disetujui</span>
                    <span class="metric-icon">
                        <i class="bi bi-check-circle"></i>
                    </span>
                </div>

                <div class="metric-value">
                    {{ $poDisetujui }}
                </div>

                <div class="metric-meta">
                    <span>PO Disetujui</span>
                </div>
            </article>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-danger">
                <div class="metric-top">
                    <span class="metric-label">PO Ditolak</span>
                    <span class="metric-icon">
                        <i class="bi bi-x-circle"></i>
                    </span>
                </div>

                <div class="metric-value">
                    {{ $poDitolak }}
                </div>

                <div class="metric-meta">
                    <span>PO Ditolak</span>
                </div>
            </article>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-primary">
                <div class="metric-top">
                    <span class="metric-label">Total PO</span>
                    <span class="metric-icon">
                        <i class="bi bi-file-earmark-text"></i>
                    </span>
                </div>

                <div class="metric-value">
                    {{ $totalPo }}
                </div>

                <div class="metric-meta">
                    <span>Seluruh Pengajuan PO</span>
                </div>
            </article>
        </div>

    </section>

    <section class="row g-3 mt-1">

        <!--untuk tabel Aktivitas PO Terbaru-->
        <div class="col-12 col-xl-8">

            <div class="panel">

                <div class="panel-header">

                    <div>
                        <h2 class="h5 mb-1 section-title">
                            <i class="bi bi-clock-history"></i>
                            <span>Aktivitas PO Terbaru</span>
                        </h2>
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

            </div>

        </div>

        <!--untuk tabel PO Pending Approval-->
        <div class="col-12 col-xl-4">

            <div class="panel h-100">

                <div class="panel-header">

                    <div>
                        <h2 class="h5 mb-1 section-title">
                            <i class="bi bi-hourglass-split"></i>
                            <span>PO Pending Approval</span>
                        </h2>

                        <p class="text-muted mb-0">
                            Menunggu persetujuan keuangan.
                        </p>
                    </div>

                </div>

                <div class="activity-list">

                    @forelse($poPendingList as $item)

                        <div class="activity-item">

                            <span class="activity-dot bg-warning"></span>

                            <div>

                                <p class="mb-1 fw-semibold">
                                    {{ $item->no_po }}
                                </p>

                                <p class="text-muted small mb-0">
                                    {{ \Carbon\Carbon::parse($item->tanggal_po)->format('d-m-Y') }}
                                </p>

                            </div>

                        </div>

                    @empty

                        <div class="text-muted">
                            Tidak ada PO pending.
                        </div>

                    @endforelse

                </div>

            </div>

        </div>

    </section>

</div>

@endsection