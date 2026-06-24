
@extends('layouts.app')

@section('content')

{{--munculin pesan yang notif stok menipis udah dibuatin po--}}
@if(session('warning'))
<div class="alert alert-warning">
    {{ session('warning') }}
</div>
@endif
<div class="container-fluid px-3 px-lg-4 py-4">
          <div class="page-heading">
            <div class="page-heading-copy">
              <div>
                <h1 class="h3 mb-1">Dashboard Admin</h1>
                <p class="text-muted mb-0">
                    Hallo, Selamat Datang {{ Auth::user()->name }}
                </p>
              </div>
            </div>
          </div>

          <section class="row g-3 mt-1" aria-label="Dashboard metrics">
           <div class="col-12 col-sm-6 col-xl-3">
                <article class="metric-card metric-primary">

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
                <article class="metric-card metric-success">

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

            <div class="col-12 col-sm-6 col-xl-3">
                <article class="metric-card metric-warning">

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
                <article class="metric-card metric-danger">

                    <div class="metric-top">
                        <span class="metric-label">
                            Permintaan Barang
                        </span>

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
                        Jumlah transaksi barang masuk dan barang keluar setiap bulan.
                    </p>
                  </div>
                  <a class="btn btn-light btn-sm" href="{{ route('laporan.menu') }}">View Details</a>
                </div>

                <!--untuk grafik perbandingan barang masuk dan keluar-->
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
                        <i class="bi bi-exclamation-triangle"></i>
                        <span>Notifikasi Stok Menipis</span>
                    </h2>

                    <p class="text-muted mb-0">
                        Daftar barang yang perlu segera dilakukan pengadaan.
                    </p>
                  </div>
                </div>

                <div class="activity-list">

                    @forelse($stokMenipis as $barang)

                        <div class="activity-item">
                            <span class="activity-dot bg-danger"></span>

                            <div>
                                {{--ini nptifikasi stok menipis jika diklik barangnya 
                                    maka akan mengarahkan ke pembuatan po dgn sumber po stok minimum--}}
                                <a href="{{ route('pengajuan-po.create', [
                                        'barang_id' => $barang->id,
                                        'sumber_po' => 'stok_minimum'
                                    ]) }}"
                                class="fw-semibold text-danger">

                                    {{ $barang->nama_barang }}

                                </a>

                                <p class="text-muted small mb-0">
                                    Stok tersisa {{ $barang->stok }} unit
                                </p>
                            </div>
                        </div>

                    @empty

                        <div class="text-muted">
                            Tidak ada stok barang yang menipis.
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
                          Riwayat aktivitas terbaru pada sistem gudang.
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
                              <td>
                                  @if($item['jenis'] == 'Barang Masuk')
                                      Barang Masuk
                                  @elseif($item['jenis'] == 'Barang Keluar')
                                      Barang Keluar
                                  @elseif($item['jenis'] == 'Pengajuan PO')
                                      Pengajuan PO
                                  @else
                                      Permintaan Barang
                                  @endif
                              </td>

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
                                  Belum ada aktivitas
                              </td>
                          </tr>
                      @endforelse
                  </tbody>
              </table>
          </div>
          </section>
        

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
