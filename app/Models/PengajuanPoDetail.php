<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PengajuanPoDetail extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_po_detail';

    protected $fillable = [
        'pengajuan_po_id',
        'permintaan_barang_detail_id',
        'barang_id',
        'qty',
        'harga_estimasi',
        'subtotal',
        'status_item'
    ];

    //relasi
    public function pengajuanPo()
    {
        //belongto untuk menghubungkan, tapi model ini yg punya forenkeynya, model ini ambil primary key ke tabel lain
        return $this->belongsTo(PengajuanPo::class,'pengajuan_po_id');
    }

    public function barang()
    {
        //belongto untuk menghubungkan, tapi model ini yg punya forenkeynya, model ini ambil primary key ke tabel lain
        return $this->belongsTo(Barang::class,'barang_id');
    }

    public function permintaanDetail()
    {
        //belongto untuk menghubungkan, tapi model ini yg punya forenkeynya, model ini ambil primary key ke tabel lain
        return $this->belongsTo(
            PermintaanBarangDetail::class,
            'permintaan_barang_detail_id'
        );
    }

    public function barangMasukDetail()
    {
        //hasmany untuk model ini dapat memiliki banyak dari model yang terkait
        return $this->hasMany(
            BarangMasukDetail::class,
            'pengajuan_po_detail_id'
        );
    }
}