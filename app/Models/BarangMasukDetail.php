<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BarangMasukDetail extends Model
{
    use HasFactory;

    protected $table = 'barang_masuk_detail';

    protected $fillable = [
        'barang_masuk_id',
        'pengajuan_po_detail_id',
        'barang_id',
        'qty',
        'harga_beli',
        'subtotal'
    ];

    //relasi
    public function barangMasuk()
    {
        //belongto untuk menghubungkan, tapi model ini yg punya forenkeynya, model ini ambil primary key ke tabel lain
        return $this->belongsTo(
            BarangMasuk::class,
            'barang_masuk_id'
        );
    }

    public function barang()
    {
        //belongto untuk menghubungkan, tapi model ini yg punya forenkeynya, model ini ambil primary key ke tabel lain
        return $this->belongsTo(
            Barang::class,
            'barang_id'
        );
    }

    public function riwayatHarga()
    {
        //hasmany untuk model ini dapat memiliki banyak dari model yang terkait
        return $this->hasMany(
            RiwayatHarga::class,
            'barang_masuk_detail_id'
        );
    }

    public function pengajuanPoDetail()
    {
        //belongto untuk menghubungkan, tapi model ini yg punya forenkeynya, model ini ambil primary key ke tabel lain
        return $this->belongsTo(
            PengajuanPoDetail::class,
            'pengajuan_po_detail_id'
        );
    }
}