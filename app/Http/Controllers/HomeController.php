<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use App\Models\PengajuanPo;
use App\Models\PermintaanBarang;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    
    public function index()
    {
        if(Auth::user()->role == 'admin'){
            return $this->dashboardAdmin();
        }

        if(Auth::user()->role == 'logistik'){
            // return view('dashboard.logistik');
            return $this->dashboardLogistik();
        }

        if(Auth::user()->role == 'keuangan'){
            // return view('dashboard.keuangan');
            return $this->dashboardKeuangan();
            
        }

        if(Auth::user()->role == 'pimpinan'){
            // return view('dashboard.pimpinan');
            return $this->dashboardPimpinan();
        }
    }


    public function dashboardAdmin()
    {
        $totalPo = PengajuanPo::count();

        $totalPermintaan = PermintaanBarang::count();

        $totalBarangMasuk = BarangMasuk::count();

        $totalBarangKeluar = BarangKeluar::count();

        //untuk menghitung stok menipis
        $stokMenipis = Barang::whereColumn(
            'stok',
            '<=',
            'stok_minimum'
        )
        ->orderBy('stok')
        ->take(5)
        ->get();

        // Barang Masuk per bulan
        $barangMasukBulanan = BarangMasuk::selectRaw('MONTH(tanggal_masuk) as bulan, COUNT(*) as total')
            ->groupBy('bulan')
            ->pluck('total', 'bulan');

        // Barang Keluar per bulan
        $barangKeluarBulanan = BarangKeluar::selectRaw('MONTH(tanggal_keluar) as bulan, COUNT(*) as total')
            ->groupBy('bulan')
            ->pluck('total', 'bulan');

        $dataMasuk = [];
        $dataKeluar = [];

        for ($i = 1; $i <= 12; $i++) {
            $dataMasuk[] = $barangMasukBulanan[$i] ?? 0;
            $dataKeluar[] = $barangKeluarBulanan[$i] ?? 0;
        }

        //untuk aktifitas bar masuk baru 3 hari terakhir
        $aktivitasMasuk = BarangMasuk::where('created_at', '>=', Carbon::now()->subDays(3))
            ->latest()
            ->get()
            ->map(function ($item) {
                return [
                    'jenis' => 'Barang Masuk',
                    'detail' => 'Barang Masuk #' . $item->id,
                    'tanggal' => $item->created_at
                ];
            });

        //untuk aktifitas bar keluar baru 3 hari terakhir
        $aktivitasKeluar = BarangKeluar::where('created_at', '>=', Carbon::now()->subDays(3))
            ->latest()
            ->get()
            ->map(function ($item) {
                return [
                    'jenis' => 'Barang Keluar',
                    'detail' => 'Barang Keluar #' . $item->id,
                    'tanggal' => $item->created_at
                ];
            });

        //ini untuk activitas po terbaru 3 hari terakhir
        $aktivitasPO = PengajuanPo::where('created_at', '>=', Carbon::now()->subDays(3))
            ->latest()
            ->get()
            ->map(function ($item) {
                return [
                    'jenis' => 'Pengajuan PO',
                    'detail' => 'PO #' . $item->id,
                    'tanggal' => $item->created_at
                ];
            });

        //untuk aktifitas permintaan barang baru 3 hari terakhir
        $aktivitasPermintaan = PermintaanBarang::where('created_at', '>=', Carbon::now()->subDays(3))
            ->latest()
            ->get()
            ->map(function ($item) {
                return [
                    'jenis' => 'Permintaan Barang',
                    'detail' => 'Permintaan #' . $item->id,
                    'tanggal' => $item->created_at
                ];
            });

        $aktivitas = $aktivitasMasuk
            ->concat($aktivitasKeluar)
            ->concat($aktivitasPO)
            ->concat($aktivitasPermintaan)
            ->sortByDesc('tanggal')
            ->take(10);

        return view('home', compact(
            'totalPo',
            'totalPermintaan',
            'totalBarangMasuk',
            'totalBarangKeluar',
            'dataMasuk',
            'dataKeluar',
            'stokMenipis',
            'aktivitas'
        ));
    }

    public function dashboardLogistik()
    {
        $totalPermintaan = PermintaanBarang::count();

        $totalBarangMasuk = BarangMasuk::count();

        $totalBarangKeluar = BarangKeluar::count();

        $permintaanBelumTerpenuhi = PermintaanBarang::whereIn(
            'status_permintaan',
            ['baru', 'diajukan_po']
        )->count();

        $permintaanTerbaru = PermintaanBarang::latest()
            ->take(5)
            ->get();

        // Grafik Barang Masuk
        $barangMasukBulanan = BarangMasuk::selectRaw('MONTH(tanggal_masuk) as bulan, COUNT(*) as total')
            ->groupBy('bulan')
            ->pluck('total', 'bulan');

        // Grafik Barang Keluar
        $barangKeluarBulanan = BarangKeluar::selectRaw('MONTH(tanggal_keluar) as bulan, COUNT(*) as total')
            ->groupBy('bulan')
            ->pluck('total', 'bulan');

        $dataMasuk = [];
        $dataKeluar = [];

        for ($i = 1; $i <= 12; $i++) {
            $dataMasuk[] = $barangMasukBulanan[$i] ?? 0;
            $dataKeluar[] = $barangKeluarBulanan[$i] ?? 0;
        }

        $aktivitasMasuk = BarangMasuk::latest()->take(5)->get()
            ->map(function ($item) {
                return [
                    'jenis' => 'Barang Masuk',
                    'detail' => 'Barang Masuk #' . $item->id,
                    'tanggal' => $item->created_at
                ];
            });

        $aktivitasKeluar = BarangKeluar::latest()->take(5)->get()
            ->map(function ($item) {
                return [
                    'jenis' => 'Barang Keluar',
                    'detail' => 'Barang Keluar #' . $item->id,
                    'tanggal' => $item->created_at
                ];
            });

        $aktivitasPermintaan = PermintaanBarang::latest()->take(5)->get()
            ->map(function ($item) {
                return [
                    'jenis' => 'Permintaan Barang',
                    'detail' => 'Permintaan #' . $item->id,
                    'tanggal' => $item->created_at
                ];
            });

        $aktivitas = $aktivitasMasuk
            ->concat($aktivitasKeluar)
            ->concat($aktivitasPermintaan)
            ->sortByDesc('tanggal')
            ->take(10);

        return view('dashboard.logistik', compact(
            'totalPermintaan',
            'totalBarangMasuk',
            'totalBarangKeluar',
            'permintaanBelumTerpenuhi',
            'permintaanTerbaru',
            'dataMasuk',
            'dataKeluar',
            'aktivitas'
        ));
    }

    public function dashboardKeuangan()
    {
        $poPending = PengajuanPo::where('status_po', 'pending')->count();

        $poDisetujui = PengajuanPo::where('status_po', 'disetujui')->count();

        $poDitolak = PengajuanPo::where('status_po', 'ditolak')->count();

        $totalPo = PengajuanPo::count();

        $poPendingList = PengajuanPo::where('status_po', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $aktivitas = PengajuanPo::latest()
            ->take(10)
            ->get()
            ->map(function ($item) {
                return [
                    'jenis' => 'Pengajuan PO',
                    'detail' => 'PO #' . $item->id,
                    'tanggal' => $item->created_at
                ];
            });

        return view('dashboard.keuangan', compact(
            'poPending',
            'poDisetujui',
            'poDitolak',
            'totalPo',
            'poPendingList',
            'aktivitas'
        ));
    }

    public function dashboardPimpinan()
    {
        $totalBarang = Barang::count();

        $totalPermintaan = PermintaanBarang::count();

        $totalBarangMasuk = BarangMasuk::count();

        $totalBarangKeluar = BarangKeluar::count();

        $totalPo = PengajuanPo::count();

        // Grafik
        $barangMasukBulanan = BarangMasuk::selectRaw('MONTH(tanggal_masuk) as bulan, COUNT(*) as total')
            ->groupBy('bulan')
            ->pluck('total', 'bulan');

        $barangKeluarBulanan = BarangKeluar::selectRaw('MONTH(tanggal_keluar) as bulan, COUNT(*) as total')
            ->groupBy('bulan')
            ->pluck('total', 'bulan');

        $dataMasuk = [];
        $dataKeluar = [];

        for ($i = 1; $i <= 12; $i++) {
            $dataMasuk[] = $barangMasukBulanan[$i] ?? 0;
            $dataKeluar[] = $barangKeluarBulanan[$i] ?? 0;
        }

        return view('dashboard.pimpinan', compact(
            'totalBarang',
            'totalPermintaan',
            'totalBarangMasuk',
            'totalBarangKeluar',
            'totalPo',
            'dataMasuk',
            'dataKeluar'
        ));
    }
}
